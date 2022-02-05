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
use de\codenamephp\deployer\base\functions\iUpload;
use de\codenamephp\deployer\base\hostCheck\DoNotRunOnProduction;
use de\codenamephp\deployer\base\hostCheck\iHostCheck;
use de\codenamephp\deployer\base\hostCheck\SkippableByOption;
use de\codenamephp\deployer\base\task\iTask;
use de\codenamephp\deployer\base\transferable\iTransferable;
use de\codenamephp\deployer\base\UnsafeOperationException;
use Deployer\Exception\RunException;

/**
 * Uploads local media (images, files, ...) to remote
 *
 * @psalm-suppress PropertyNotSetInConstructor see https://github.com/vimeo/psalm/issues/4393
 */
final class Push implements iTask {

  /**
   * @var array<iTransferable>
   */
  private array $transferables;

  /**
   * @param array<iTransferable> $transferables The transferables to push
   * @param iUpload $deployerFunctions Executes the upload
   * @param iHostCheck $hostCheck Makes sure that the task is not accidentally executed on production
   */
  public function __construct(array             $transferables,
                              public iUpload    $deployerFunctions = new All(),
                              public iHostCheck $hostCheck = new SkippableByOption(new DoNotRunOnProduction())) {
    $this->setTransferables(...$transferables);
  }

  /**
   * @return iTransferable[]
   */
  public function getTransferables() : array {
    return $this->transferables;
  }

  public function setTransferables(iTransferable ...$transferables) : Push {
    $this->transferables = $transferables;
    return $this;
  }

  /**
   * Iterates over all transferables and passes the localPath, the remotePath and the config to the upload function. If the stage is production an
   * UnsafeOperationException is thrown since we don't want to push to production.
   *
   * @return void
   * @throws UnsafeOperationException when the current stage is production
   * @throws RunException
   */
  public function __invoke() : void {
    $this->hostCheck->check();

    foreach($this->getTransferables() as $transferable) {
      $this->deployerFunctions->upload($transferable->getLocalPath(), $transferable->getRemotePath(), $transferable->getConfig());
    }
  }
}
