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

namespace de\codenamephp\deployer\base\test\taskMatcher;

use de\codenamephp\deployer\base\taskMatcher\ByExactTaskName;
use Deployer\Task\Task;
use PHPUnit\Framework\TestCase;

final class ByExactTaskNameTest extends TestCase {

  private ByExactTaskName $sut;

  protected function setUp() : void {
    parent::setUp();

    $this->sut = new ByExactTaskName('');
  }

  public function testMatches() : void {
    $this->sut->taskName = 'some task';

    $task = $this->createMock(Task::class);
    $task->expects(self::once())->method('getName')->willReturn('some task');

    self::assertTrue($this->sut->matches($task));
  }

  public function testMatches_canReturnFalse_whenTaskNameDoesNotMatch() : void {
    $this->sut->taskName = 'some task';

    $task = $this->createMock(Task::class);
    $task->expects(self::once())->method('getName')->willReturn('some other task');

    self::assertFalse($this->sut->matches($task));
  }

  public function test__construct() : void {
    $taskName = 'some task';

    $this->sut = new ByExactTaskName($taskName);

    self::assertSame($taskName, $this->sut->taskName);
  }
}
