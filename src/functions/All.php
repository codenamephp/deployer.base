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

use function Deployer\add;
use function Deployer\upload;

/**
 * Implements all method interfaces so we a "all drop-in" class to easily access the methods
 */
final class All implements iAll {

  public function add(string $name, array $array) : void {
    add($name, $array);
  }

  public function upload(string $source, string $destination, array $config = []) : void {
    upload($source, $destination, $config);
  }
}
