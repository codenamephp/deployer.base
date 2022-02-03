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

namespace de\codenamephp\deployer\base\test\taskMatcher\collection;

use de\codenamephp\deployer\base\taskMatcher\iTaskMatcher;
use Deployer\Task\Task;
use PHPUnit\Framework\TestCase;

final class AtLeastOneTest extends TestCase {

  private AtLeastOne $sut;

  protected function setUp() : void {
    parent::setUp();

    $this->sut = new AtLeastOne();
  }

  public function test__construct() : void {
    $matcher1 = $this->createMock(iTaskMatcher::class);
    $matcher2 = $this->createMock(iTaskMatcher::class);
    $matcher3 = $this->createMock(iTaskMatcher::class);

    $this->sut = new AtLeastOne($matcher1, $matcher2, $matcher3);

    self::assertSame([$matcher1, $matcher2, $matcher3], $this->sut->getTaskMatcher());
  }

  public function testMatches() : void {
    $task = $this->createMock(Task::class);

    $matcher1 = $this->createMock(iTaskMatcher::class);
    $matcher1->expects(self::once())->method('matches')->with($task)->willReturn(false);
    $matcher2 = $this->createMock(iTaskMatcher::class);
    $matcher2->expects(self::once())->method('matches')->with($task)->willReturn(true);
    $matcher3 = $this->createMock(iTaskMatcher::class);
    $matcher3->expects(self::never())->method('matches');
    $this->sut->setTaskMatcher($matcher1, $matcher2, $matcher3);

    self::assertTrue($this->sut->matches($task));
  }

  public function testMatches_canReturnFalse_whenNoMatcherMatches() : void {
    $task = $this->createMock(Task::class);

    $matcher1 = $this->createMock(iTaskMatcher::class);
    $matcher1->expects(self::once())->method('matches')->with($task)->willReturn(false);
    $matcher2 = $this->createMock(iTaskMatcher::class);
    $matcher2->expects(self::once())->method('matches')->with($task)->willReturn(false);
    $matcher3 = $this->createMock(iTaskMatcher::class);
    $matcher3->expects(self::once())->method('matches')->with($task)->willReturn(false);

    $this->sut->setTaskMatcher($matcher1, $matcher2, $matcher3);

    self::assertFalse($this->sut->matches($task));
  }
}
