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

namespace de\codenamephp\deployer\base\test\hostCheck;

use de\codenamephp\deployer\base\functions\iInput;
use de\codenamephp\deployer\base\hostCheck\iHostCheck;
use de\codenamephp\deployer\base\hostCheck\SkippableByOption;
use PHPUnit\Framework\TestCase;

final class SkippableByOptionTest extends TestCase {

  private SkippableByOption $sut;

  protected function setUp() : void {
    parent::setUp();

    $hostCheck = $this->createMock(iHostCheck::class);
    $deployerFunctions = $this->createMock(iInput::class);

    $this->sut = new SkippableByOption($hostCheck, $deployerFunctions);
  }

  public function testCheck() : void {
    $this->sut->deployerFunctions = $this->createMock(iInput::class);
    $this->sut->deployerFunctions->expects(self::once())->method('getOption')->with(SkippableByOption::OPTION_NAME, false)->willReturn(false);

    $this->sut->hostCheck = $this->createMock(iHostCheck::class);
    $this->sut->hostCheck->expects(self::once())->method('check');

    $this->sut->check();
  }

  public function testCheck_willSkipDecorated_whenOptionIsNotSet() : void {
    $this->sut->deployerFunctions = $this->createMock(iInput::class);
    $this->sut->deployerFunctions->expects(self::once())->method('getOption')->with(SkippableByOption::OPTION_NAME, false)->willReturn(true);

    $this->sut->hostCheck = $this->createMock(iHostCheck::class);
    $this->sut->hostCheck->expects(self::never())->method('check');

    $this->sut->check();
  }

  public function test__construct() : void {
    $hostCheck = $this->createMock(iHostCheck::class);
    $deployerFunctions = $this->createMock(iInput::class);

    $this->sut = new SkippableByOption($hostCheck, $deployerFunctions);

    self::assertSame($hostCheck, $this->sut->hostCheck);
    self::assertSame($deployerFunctions, $this->sut->deployerFunctions);
  }

  public function test__construct_canAddOption() : void {
    $hostCheck = $this->createMock(iHostCheck::class);

    $deployerFunctions = $this->createMock(iInput::class);
    $deployerFunctions->expects(self::once())->method('option')->with(
      SkippableByOption::OPTION_NAME,
      SkippableByOption::OPTION_SHORTCUT_NAME,
      iInput::OPTION_VALUE_NONE,
      'If the option is set the host check is skipped',
      true
    );

    $this->sut = new SkippableByOption($hostCheck, $deployerFunctions);
  }
}
