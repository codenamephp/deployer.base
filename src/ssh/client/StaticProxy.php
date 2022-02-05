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

use Deployer\Component\Ssh\Client;
use Deployer\Host\Host;

/**
 * The \Deployer\Component\Ssh\Client has some static methods I want to use but still be able to test. Just don't write static methods folks ...
 */
final class StaticProxy implements iClient {

  public function connectionOptionsString(Host $host) : string {
    return Client::connectionOptionsString($host);
  }
}