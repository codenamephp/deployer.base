<?php
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

namespace de\codenamephp\deployer\base\task\deploy\updateCode;

use de\codenamephp\deployer\base\functions\All;
use de\codenamephp\deployer\base\functions\iUpload;
use de\codenamephp\deployer\base\task\iTask;
use de\codenamephp\deployer\base\transferable\iTransferable;

/**
 * Uses a collection of transferables and the deployer upload function to update the code on remote.
 */
final class UploadTransferables implements iTask {

  public iUpload $deployerFunctions;

  /**
   * @var iTransferable[]
   */
  private array $transferables;

  public function __construct(iTransferable ...$transferables) {
    $this->deployerFunctions = new All();
    $this->transferables = $transferables;
  }

  /**
   * @return iTransferable[]
   */
  public function getTransferables() : array {
    return $this->transferables;
  }

  public function setTransferables(iTransferable ...$transferables) : UploadTransferables {
    $this->transferables = $transferables;
    return $this;
  }

  public function __invoke() : void {
    foreach($this->transferables as $transferable) {
      $this->deployerFunctions->upload($transferable->getLocalPath(), $transferable->getRemotePath(), $transferable->getConfig());
    }
  }
}
