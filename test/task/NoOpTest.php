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

namespace de\codenamephp\deployer\base\test\task;

use de\codenamephp\deployer\base\functions\All;
use de\codenamephp\deployer\base\functions\iInfo;
use de\codenamephp\deployer\base\task\NoOp;
use PHPUnit\Framework\TestCase;

final class NoOpTest extends TestCase {

  public function test__invoke() : void {
    $logging = $this->createMock(iInfo::class);
    $logging->expects(self::once())->method('info')->with("No operation task 'test' was executed");

    $task = new NoOp('test', true, $logging);
    $task();
  }

  public function test__invoke_whenPrintMessageIsFalse() : void {
    $logging = $this->createMock(iInfo::class);
    $logging->expects(self::never())->method('info');

    $task = new NoOp('test', false, $logging);
    $task();
  }

  public function testGetName() : void {
    $this->assertSame('test', (new NoOp('test'))->getName());
  }

  public function testGetDescription() : void {
    $this->assertSame('This task has been disabled.', (new NoOp('test'))->getDescription());
  }

  public function test__construct() : void {
    $task = new NoOp('test');

    self::assertSame('test', $task->getName());
    self::assertFalse($task->printMessage);
    self::assertEquals(new All(), $task->logging);
    self::assertEquals(static fn(string $name) : string => "No operation task '{$name}' was executed", $task->getMessage);
  }

  public function test__construct_withOptionalArguments() : void {
    $logging = $this->createMock(iInfo::class);

    $task = new NoOp('test', true, $logging);

    self::assertSame('test', $task->getName());
    self::assertTrue($task->printMessage);
    self::assertSame($logging, $task->logging);
    self::assertEquals(static fn(string $name) : string => "No operation task '{$name}' was executed", $task->getMessage);
  }


}
