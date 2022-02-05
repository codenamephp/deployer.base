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

namespace de\codenamephp\deployer\base\taskMatcher\collection;

use de\codenamephp\deployer\base\taskMatcher\iTaskMatcher;
use Deployer\Task\Task;

/**
 * Collection of matchers that iterates over all given matchers and returns true for the first matcher that matches and false if no matcher matches.
 */
final class AtLeastOne implements iTaskMatcher {

  /**
   * @var iTaskMatcher[]
   */
  private array $taskMatcher;

  public function __construct(iTaskMatcher ...$taskMatcher) { $this->taskMatcher = $taskMatcher; }

  /**
   * @return iTaskMatcher[]
   */
  public function getTaskMatcher() : array {
    return $this->taskMatcher;
  }

  public function setTaskMatcher(iTaskMatcher ...$taskMatcher) : AtLeastOne {
    $this->taskMatcher = $taskMatcher;
    return $this;
  }

  public function matches(Task $task) : bool {
    foreach($this->getTaskMatcher() as $taskMatcher) {
      if($taskMatcher->matches($task)) return true;
    }
    return false;
  }

}