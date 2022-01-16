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

namespace de\codenamephp\deployer\base\transferable;

/**
 * Implementation that takes the local and remote path and some config values as constructor parameters and returns them accordingly in the interface methods.
 */
final class Simple implements iTransferable {

  public function __construct(
    private string $localPath,
    private string $remotePath,
    private array  $excludes = [],
    private bool   $delete = true,
    private bool   $deleteExcluded = true
  ) {}

  public function getLocalPath() : string {
    return $this->localPath;
  }

  public function getRemotePath() : string {
    return $this->remotePath;
  }

  public function getConfig() : array {
    $options = array_map(static fn(string $exclude) : string => '--exclude=' . $exclude, $this->excludes);
    if($this->delete) $options[] = '--delete';
    if($this->deleteExcluded) $options[] = '--delete-excluded';

    return [
      'options' => $options,
    ];
  }
}
