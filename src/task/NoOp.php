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

/**
 * This task does nothing. It is used to create a task that does nothing but is not null.
 */
final class NoOp implements iTaskWithName, iTaskWithDescription {

  public Closure $getMessage;

  public function __construct(public readonly string $name, public readonly bool $printMessage = false) {
    $this->getMessage = static fn(string $name) : string => "No operation task '{$name}' was executed";
  }

  public function __invoke() : void {
    !$this->printMessage ?: '';
  }

  public function getDescription() : string {
    return 'A task that does nothing. Use to disable a task.';
  }

  public function getName() : string {
    return $this->name;
  }
}