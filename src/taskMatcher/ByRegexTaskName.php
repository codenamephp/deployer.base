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
 * Matches the task name against a regular expression
 */
final class ByRegexTaskName implements iTaskMatcher {

  public function __construct(public string $regex) {}

  public function matches(Task $task) : bool {
    return $this->regex !== '' && preg_match($this->regex, $task->getName()) === 1;
  }
}
