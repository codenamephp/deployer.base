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

namespace de\codenamephp\deployer\base\test\task\deploy\updateCode;

use de\codenamephp\deployer\base\functions\All;
use de\codenamephp\deployer\base\functions\iAll;
use de\codenamephp\deployer\base\task\deploy\updateCode\UploadTransferables;
use de\codenamephp\deployer\base\transferable\iTransferable;
use PHPUnit\Framework\TestCase;

final class UploadTransferablesTest extends TestCase {

  private UploadTransferables $sut;

  protected function setUp() : void {
    parent::setUp();

    $this->sut = new UploadTransferables();
    $this->sut->deployerFunctions = $this->createMock(iAll::class);
  }

  public function test__invoke() : void {
    $this->sut->deployerFunctions = $this->createMock(iAll::class);
    $this->sut->deployerFunctions
      ->expects(self::exactly(3))
      ->method('upload')
      ->withConsecutive(
        ['local path 1', 'remote path 1', ['config 1']],
        ['local path 2', 'remote path 2', ['config 2']],
        ['local path 3', 'remote path 3', ['config 3']],
      );

    $transferable1 = $this->createMock(iTransferable::class);
    $transferable1->expects(self::once())->method('getLocalPath')->willReturn('local path 1');
    $transferable1->expects(self::once())->method('getRemotePath')->willReturn('remote path 1');
    $transferable1->expects(self::once())->method('getConfig')->willReturn(['config 1']);
    $transferable2 = $this->createMock(iTransferable::class);
    $transferable2->expects(self::once())->method('getLocalPath')->willReturn('local path 2');
    $transferable2->expects(self::once())->method('getRemotePath')->willReturn('remote path 2');
    $transferable2->expects(self::once())->method('getConfig')->willReturn(['config 2']);
    $transferable3 = $this->createMock(iTransferable::class);
    $transferable3->expects(self::once())->method('getLocalPath')->willReturn('local path 3');
    $transferable3->expects(self::once())->method('getRemotePath')->willReturn('remote path 3');
    $transferable3->expects(self::once())->method('getConfig')->willReturn(['config 3']);
    $this->sut->setTransferables($transferable1, $transferable2, $transferable3);

    $this->sut->__invoke();
  }

  public function test__construct() : void {
    $this->sut = new UploadTransferables();

    self::assertInstanceOf(All::class, $this->sut->deployerFunctions);
    self::assertEquals([], $this->sut->getTransferables());
  }

  public function test__construct_withTransferrables() : void {
    $transferable1 = $this->createMock(iTransferable::class);
    $transferable2 = $this->createMock(iTransferable::class);
    $transferable3 = $this->createMock(iTransferable::class);

    $this->sut = new UploadTransferables($transferable1, $transferable2, $transferable3);

    self::assertInstanceOf(All::class, $this->sut->deployerFunctions);
    self::assertEquals([$transferable1, $transferable2, $transferable3], $this->sut->getTransferables());
  }
}
