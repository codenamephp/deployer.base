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

use de\codenamephp\deployer\base\task\iTask;
use Deployer\Task\Task;

/**
 * Interface for the Deployer\before function
 */
interface iBefore {

  /**
   * Registers a task to be executed before another task
   *
   * @param string $task The name of the task the given task should be executed before
   * @param callable():void|iTask|string $do The task to execute before the given task name
   * @return Task|null The added task or null if no task was added
   */
  public function before(string $task, iTask|string|callable $do) : ?Task;
}