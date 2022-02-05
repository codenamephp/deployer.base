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
use de\codenamephp\deployer\base\hostCheck\WithDisallowList;
use de\codenamephp\deployer\base\UnsafeOperationException;
use Deployer\Host\Host;
use PHPUnit\Framework\TestCase;

final class WithDisallowListTest extends TestCase {

  private WithDisallowList $sut;

  protected function setUp() : void {
    parent::setUp();

    $this->sut = new WithDisallowList();
    $this->sut->deployerFunctions = $this->createMock(iCurrentHost::class);
  }

  public function test__construct() : void {
    $this->sut = new WithDisallowList('host1', 'host2', 'host3');

    self::assertEquals(['host1', 'host2', 'host3'], $this->sut->getDisallowedHosts());
    self::assertInstanceOf(All::class, $this->sut->deployerFunctions);
  }

  public function testCheck() : void {
    $this->expectException(UnsafeOperationException::class);
    $this->expectExceptionMessage('Current host alias was found in disallow list.');

    $host = $this->createMock(Host::class);
    $host->expects(self::once())->method('getAlias')->willReturn('some host');

    $this->sut->deployerFunctions = $this->createMock(iCurrentHost::class);
    $this->sut->deployerFunctions->expects(self::once())->method('currentHost')->willReturn($host);

    $this->sut->setDisallowedHosts('host1', 'some host', 'host3');

    $this->sut->check();
  }

  public function testCheck_whenHostIsNotDisallowed() : void {
    $host = $this->createMock(Host::class);
    $host->expects(self::once())->method('getAlias')->willReturn('some host');

    $this->sut->deployerFunctions = $this->createMock(iCurrentHost::class);
    $this->sut->deployerFunctions->expects(self::once())->method('currentHost')->willReturn($host);

    $this->sut->setDisallowedHosts('host1', 'host2', 'host3');

    $this->sut->check();
  }
}
