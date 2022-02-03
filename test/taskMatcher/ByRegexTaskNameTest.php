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

use de\codenamephp\deployer\base\taskMatcher\ByRegexTaskName;
use Deployer\Task\Task;
use PHPUnit\Framework\TestCase;

final class ByRegexTaskNameTest extends TestCase {

  private ByRegexTaskName $sut;

  protected function setUp() : void {
    parent::setUp();

    $this->sut = new ByRegexTaskName('');
  }

  public function testMatches() : void {
    $this->sut->regex = '/match:.*/';

    $task1 = $this->createMock(Task::class);
    $task1->expects(self::once())->method('getName')->willReturn('match:this');
    $task2 = $this->createMock(Task::class);
    $task2->expects(self::once())->method('getName')->willReturn('match:that');
    $task3 = $this->createMock(Task::class);
    $task3->expects(self::once())->method('getName')->willReturn('not:that');
    $task4 = $this->createMock(Task::class);
    $task4->expects(self::once())->method('getName')->willReturn(null);

    self::assertTrue($this->sut->matches($task1));
    self::assertTrue($this->sut->matches($task2));
    self::assertFalse($this->sut->matches($task3));
    self::assertFalse($this->sut->matches($task4));
  }

  public function test__construct() : void {
    $regex = 'some regex';

    $this->sut = new ByRegexTaskName($regex);

    self::assertSame($regex, $this->sut->regex);
  }
}
