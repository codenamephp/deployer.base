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

use de\codenamephp\deployer\base\task\iTaskWithName;
use Deployer\Task\Task;

/**
 * Interface for the Deployer\task method
 */
interface iTask {

  /**
   * Define a new task and save to tasks list.
   *
   * Alternatively get a defined task.
   *
   * @param string $name Name of current task.
   * @param \de\codenamephp\deployer\base\task\iTask|callable|array|null $body Callable task, array of other tasks names or nothing to get a defined tasks
   */
  public function task(string $name, \de\codenamephp\deployer\base\task\iTask|callable|array|null $body = null) : Task;

  /**
   * Registers a task with deployer. The task itself is used as body and the name is the identifier that the task is registered with. Implementations should
   * support the iTaskWithDescription interface and add the description as well if the task has the interface.
   *
   * @param iTaskWithName $task The task to register with deployer
   * @return void
   */
  public function registerTask(iTaskWithName $task) : void;
}