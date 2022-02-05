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

namespace de\codenamephp\deployer\base\functions;

use Deployer\Exception\RunException;

/**
 * Interface for the Deployer\download function
 */
interface iDownload {

  /**
   * Download file or directory from host
   *
   * @param string $source The path on the remote host to download
   * @param string $destination The path on the local host to download to
   * @param array{flags?: string, options?: array, timeout?: int|null, progress_bar?: bool, display_stats?: bool} $config Config for the transfer
   *
   * @throws RunException
   */
  public function download(string $source, string $destination, array $config = []) : void;
}