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

namespace de\codenamephp\deployer\base\taskHider;

use de\codenamephp\deployer\base\taskMatcher\iTaskMatcher;
use Deployer\Deployer;

/**
 * Iterates over the task list in the deployer instance and matches them against a task matcher. All matching tasks are hidden
 */
final class ByTaskListAndMatchers implements iTaskHider {

  public Deployer $deployer;

  public function __construct(public iTaskMatcher $taskMatcher, Deployer $deployer = null) {
    $this->deployer = $deployer ?? Deployer::get();
  }

  public function hide() : void {
    foreach($this->deployer->tasks as $task) {
      if($this->taskMatcher->matches($task)) $task->hidden(true);
    }
  }

}