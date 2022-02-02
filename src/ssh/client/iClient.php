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

namespace de\codenamephp\deployer\base\ssh\client;

use Deployer\Host\Host;

/**
 * Interface for proxying the Deployer ssh client
 */
interface iClient {

  /**
   * Gets the connection options for an ssh call for the given host
   *
   * @param Host $host The host to get the connection options for
   * @return string
   */
  public function connectionOptionsString(Host $host) : string;
}