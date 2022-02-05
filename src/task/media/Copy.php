<?php declare(strict_types=1);
/*
 *   Copyright 2022 Bastian Schwarz <bastian@codename-php.de>.
 *
 *   Licensed under the Apache License, Version 2.0 (the "License");
 *   you may not use this file except in compliance with the License.
 *   You may obtain a copy of the License at
 *
 *         http://www.apache.org/licenses/LICENSE-2.0
 *
 *   Unless required by applicable law or agreed to in writing, software
 *   distributed under the License is distributed on an "AS IS" BASIS,
 *   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *   See the License for the specific language governing permissions and
 *   limitations under the License.
 */

namespace de\codenamephp\deployer\base\task\media;

use de\codenamephp\deployer\base\functions\All;
use de\codenamephp\deployer\base\functions\iAll;
use de\codenamephp\deployer\base\functions\iInput;
use de\codenamephp\deployer\base\hostCheck\DoNotRunOnProduction;
use de\codenamephp\deployer\base\hostCheck\iHostCheck;
use de\codenamephp\deployer\base\iConfigurationKeys;
use de\codenamephp\deployer\base\MissingConfigurationException;
use de\codenamephp\deployer\base\ssh\client\iClient;
use de\codenamephp\deployer\base\ssh\client\StaticProxy;
use de\codenamephp\deployer\base\task\iTaskWithDescription;
use de\codenamephp\deployer\base\task\iTaskWithName;
use de\codenamephp\deployer\base\transferable\iTransferable;
use de\codenamephp\deployer\base\UnsafeOperationException;
use Deployer\Exception\RunException;

/**
 * Task to copy media between two remotes. The copy uses rsync over a ssh tunnel with agent forwarding so the remotes do not need each others credentials but
 * the local credentials are used.
 *
 * @psalm-suppress PropertyNotSetInConstructor see https://github.com/vimeo/psalm/issues/4393
 */
final class Copy implements iTaskWithName, iTaskWithDescription {

  public const OPTION_SOURCE_HOST = 'cpd:media:copy:sourceHost';
  public const NAME = 'media:copy';

  /**
   * @var array<iTransferable>
   */
  private array $transferables;

  /**
   * @param array<iTransferable> $transferables Array of transferables to copy between two remotes
   * @param iAll $deployerFunctions Deployer function to set options and run the copy
   * @param iHostCheck $hostCheck HostCheck to prevent accidental execution
   */
  public function __construct(array             $transferables,
                              public iAll       $deployerFunctions = new All(),
                              public iHostCheck $hostCheck = new DoNotRunOnProduction(),
                              public iClient    $sshClient = new StaticProxy()) {
    $this->setTransferables(...$transferables);
    $deployerFunctions->option(
      self::OPTION_SOURCE_HOST,
      null,
      iInput::OPTION_VALUE_REQUIRED,
      'The source host to copy the media from',
      iConfigurationKeys::PRODUCTION
    );
  }

  public function getDescription() : string {
    return 'Copies the media from one remote to another. The copy is done using rsync directly between the hosts.';
  }

  /**
   * Uses the option for the source host to get the source host, the target host is the host for the current stage and uses
   * those hosts to build an ssh command that connects to the source host and an rsync command that will be executed on the source host
   * and syncs to the target host.
   *
   * The idea is that ssh agent forwarding is enabled so the hosts can talk to each other using the local key.
   *
   * The base command is then used with all transferables so all folders are synced.
   *
   * The command cannot be run on the production stage
   *
   * @return void
   * @throws UnsafeOperationException if the stage is production (more precisely: if the stage check fails)
   * @throws MissingConfigurationException if one of the target or source host could not be found
   * @throws RunException
   */
  public function __invoke() : void {
    $this->hostCheck->check();

    $sourceHost = $this->deployerFunctions->firstHost((string) $this->deployerFunctions->getOption(self::OPTION_SOURCE_HOST));
    $targetHost = $this->deployerFunctions->currentHost();

    $sshCommand = "ssh {$this->sshClient->connectionOptionsString($sourceHost)} {$sourceHost->getConnectionString()}";
    $rsyncCommand = "rsync -e 'ssh -o StrictHostKeyChecking=no {$this->sshClient->connectionOptionsString($targetHost)}' -azP %s %s {$targetHost->getConnectionString()}:%s";

    foreach($this->getTransferables() as $transferable) {
      $sourcePath = $this->deployerFunctions->parseOnHost($sourceHost, $transferable->getLocalPath());
      $targetPath = $this->deployerFunctions->parseOnHost($targetHost, $transferable->getRemotePath());

      $this->deployerFunctions->runLocally(sprintf('%s "%s"', $sshCommand, sprintf($rsyncCommand, implode(' ', $transferable->getConfig()['options'] ?? []), $sourcePath, $targetPath)));
    }
  }

  public function getName() : string {
    return self::NAME;
  }

  /**
   * @return iTransferable[]
   */
  public function getTransferables() : array {
    return $this->transferables;
  }

  public function setTransferables(iTransferable ...$transferables) : Copy {
    $this->transferables = $transferables;
    return $this;
  }
}
