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
use de\codenamephp\deployer\base\functions\iDownload;
use de\codenamephp\deployer\base\task\iTaskWithDescription;
use de\codenamephp\deployer\base\task\iTaskWithName;
use de\codenamephp\deployer\base\transferable\iTransferable;
use Deployer\Exception\RunException;

/**
 * Pulls uploaded media (images, files, ...) from remote to local
 *
 * @psalm-suppress PropertyNotSetInConstructor see https://github.com/vimeo/psalm/issues/4393
 */
final class Pull implements iTaskWithName, iTaskWithDescription {

  public const NAME = 'media:pull';

  /**
   * @var array<iTransferable>
   */
  private array $transferables;

  /**
   * @param array<iTransferable> $transferables The transferables to pull
   * @param iDownload $deployerFunctions Executes the download
   */
  public function __construct(array $transferables, public iDownload $deployerFunctions = new All()) {
    $this->setTransferables(...$transferables);
  }

  public function getDescription() : string {
    return 'Pull the media from remote to local.';
  }

  /**
   * Iterates over the transferables and passes the localPath, remotePath and config on the download function
   *
   * @return void
   * @throws RunException
   */
  public function __invoke() : void {
    foreach($this->getTransferables() as $transferable) {
      $this->deployerFunctions->download($transferable->getRemotePath(), $transferable->getLocalPath(), $transferable->getConfig());
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

  public function setTransferables(iTransferable ...$transferables) : Pull {
    $this->transferables = $transferables;
    return $this;
  }
}
