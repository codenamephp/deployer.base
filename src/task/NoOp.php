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

namespace de\codenamephp\deployer\base\task;

use Closure;
use de\codenamephp\deployer\base\functions\All;
use de\codenamephp\deployer\base\functions\iInfo;

/**
 * This task does nothing. It is used to create a task that does nothing but is not null.
 */
final class NoOp implements iTaskWithName, iTaskWithDescription {

  /**
   * Builds the message that is printed when the task is executed and the printMessage flag is set to true.
   *
   * Gets the name of the task as argument and has to return the message as string.
   *
   * @var Closure(string $name) : string
   */
  public Closure $getMessage;

  public function __construct(public readonly string $name, public readonly bool $printMessage = false, public readonly iInfo $logging = new All()) {
    $this->getMessage = static fn(string $name) : string => "No operation task '{$name}' was executed";
  }

  public function __invoke() : void {
    !$this->printMessage ?: $this->logging->info(($this->getMessage)($this->name));
  }

  public function getDescription() : string {
    return 'This task has been disabled.';
  }

  public function getName() : string {
    return $this->name;
  }
}