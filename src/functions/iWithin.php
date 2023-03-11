<?php declare(strict_types=1);
/*
 *   Copyright 2023 Bastian Schwarz <bastian@codename-php.de>.
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

/**
 * Interface for the Deployer\within function
 */
interface iWithin {

  /**
   * Execute a callback within a specific directory and revert back to the initial working directory.
   *
   * @param string $path The path to execute the callback in
   * @param callable():mixed $callback The callback to execute. No parameters and the return value is returned by this function
   * @return mixed|null Return value of the $callback function or null if callback throws an exception
   */
  public function within(string $path, callable $callback) : mixed;
}