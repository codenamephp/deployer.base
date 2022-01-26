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

use de\codenamephp\deployer\base\functions\All;
use de\codenamephp\deployer\base\functions\iCurrentHost;
use de\codenamephp\deployer\base\hostCheck\DoNotRunOnProduction;
use de\codenamephp\deployer\base\iConfigurationKeys;
use de\codenamephp\deployer\base\UnsafeOperationException;
use Deployer\Host\Host;
use PHPUnit\Framework\TestCase;

final class DoNotRunOnProductionTest extends TestCase {

  private DoNotRunOnProduction $sut;

  protected function setUp() : void {
    parent::setUp();

    $this->sut = new DoNotRunOnProduction();
    $this->sut->deployerFunctions = $this->createMock(iCurrentHost::class);
  }

  public function test__construct() : void {
    $currentHost = $this->createMock(iCurrentHost::class);

    $this->sut = new DoNotRunOnProduction('some host', $currentHost);

    self::assertEquals('some host', $this->sut->productionAlias);
    self::assertSame($currentHost, $this->sut->deployerFunctions);
  }

  public function test__construct_withoutParameters() : void {
    $this->sut = new DoNotRunOnProduction();

    self::assertEquals(iConfigurationKeys::PRODUCTION, $this->sut->productionAlias);
    self::assertInstanceOf(All::class, $this->sut->deployerFunctions);
  }

  public function testCheck() : void {
    $this->expectException(UnsafeOperationException::class);
    $this->expectExceptionMessage('Current host alias is the same as production alias.');

    $host = $this->createMock(Host::class);
    $host->expects(self::once())->method('getAlias')->willReturn('some host');

    $this->sut->deployerFunctions = $this->createMock(iCurrentHost::class);
    $this->sut->deployerFunctions->expects(self::once())->method('currentHost')->willReturn($host);

    $this->sut->productionAlias = 'some host';

    $this->sut->check();
  }

  public function testCheck_whenAliasDoesNotMatch() : void {
    $host = $this->createMock(Host::class);
    $host->expects(self::once())->method('getAlias')->willReturn('some host');

    $this->sut->deployerFunctions = $this->createMock(iCurrentHost::class);
    $this->sut->deployerFunctions->expects(self::once())->method('currentHost')->willReturn($host);

    $this->sut->productionAlias = 'some other host';

    $this->sut->check();
  }
}
