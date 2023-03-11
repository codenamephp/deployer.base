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

namespace de\codenamephp\deployer\base\test\taskHider;

use de\codenamephp\deployer\base\taskHider\ByTaskListAndMatchers;
use de\codenamephp\deployer\base\taskMatcher\iTaskMatcher;
use Deployer\Deployer;
use Deployer\Task\Task;
use PHPUnit\Framework\TestCase;

final class ByTaskListAndMatchersTest extends TestCase {

  private ByTaskListAndMatchers $sut;

  protected function setUp() : void {
    parent::setUp();

    $taskMatcher = $this->createMock(iTaskMatcher::class);
    $deployer = $this->createMock(Deployer::class);

    $this->sut = new ByTaskListAndMatchers($taskMatcher, $deployer);
  }

  public function test__construct() : void {
    $taskMatcher = $this->createMock(iTaskMatcher::class);
    $deployer = $this->createMock(Deployer::class);

    $this->sut = new ByTaskListAndMatchers($taskMatcher, $deployer);

    self::assertSame($taskMatcher, $this->sut->taskMatcher);
    self::assertSame($deployer, $this->sut->deployer);
  }

  public function testHide() : void {
    $task1 = $this->createMock(Task::class);
    $task1->expects(self::once())->method('hidden')->with(true);
    $task2 = $this->createMock(Task::class);
    $task2->expects(self::never())->method('hidden');
    $task3 = $this->createMock(Task::class);
    $task3->expects(self::never())->method('hidden');
    $task4 = $this->createMock(Task::class);
    $task4->expects(self::once())->method('hidden')->with(true);
    $task5 = $this->createMock(Task::class);
    $task5->expects(self::once())->method('hidden')->with(true);

    $this->sut->deployer = $this->createMock(Deployer::class);
    $this->sut->deployer->expects(self::once())->method('__get')->with('tasks')->willReturn([$task1, $task2, $task3, $task4, $task5]);

    $this->sut->taskMatcher = \Mockery::mock(iTaskMatcher::class);
    $this->sut->taskMatcher->allows('matches')->once()->ordered()->with($task1)->andReturn(true);
    $this->sut->taskMatcher->allows('matches')->once()->ordered()->with($task2)->andReturn(false);
    $this->sut->taskMatcher->allows('matches')->once()->ordered()->with($task3)->andReturn(false);
    $this->sut->taskMatcher->allows('matches')->once()->ordered()->with($task4)->andReturn(true);
    $this->sut->taskMatcher->allows('matches')->once()->ordered()->with($task5)->andReturn(true);

    $this->sut->hide();
  }
}
