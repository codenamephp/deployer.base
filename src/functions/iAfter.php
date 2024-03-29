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

use Deployer\Task\Task;

/**
 * Interface for the Deployer\after method
 */
interface iAfter {

  /**
   * Call that task after specified task runs.
   *
   * @param string $task The task after $do should be run.
   * @param \de\codenamephp\deployer\base\task\iTask|string|callable():void $do The task to be run.
   *
   * @return Task|null
   */
  public function after(string $task, \de\codenamephp\deployer\base\task\iTask|string|callable $do) : ?Task;
}