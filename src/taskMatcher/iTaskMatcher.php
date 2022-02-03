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

namespace de\codenamephp\deployer\base\taskMatcher;

use Deployer\Task\Task;

/**
 * Interface to match tasks e.g by name
 */
interface iTaskMatcher {

  /**
   * Checks if the given task matches the criteria (e.g. matches the name to a regex) and returns true if all criteria are true
   *
   * @param Task $task The task to match
   * @return bool True if the task matches all the criteria, false otherwise
   */
  public function matches(Task $task) : bool;
}