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

namespace de\codenamephp\deployer\base\test\task\media;

use de\codenamephp\deployer\base\functions\All;
use de\codenamephp\deployer\base\functions\iDownload;
use de\codenamephp\deployer\base\task\media\Pull;
use de\codenamephp\deployer\base\transferable\iTransferable;
use PHPUnit\Framework\TestCase;

final class PullTest extends TestCase {

  private Pull $sut;

  protected function setUp() : void {
    parent::setUp();

    $this->sut = new Pull();
  }

  public function test__construct() : void {
    $transferable1 = $this->createMock(iTransferable::class);
    $transferable2 = $this->createMock(iTransferable::class);
    $this->sut = new Pull($transferable1, $transferable2);

    self::assertSame([$transferable1, $transferable2], $this->sut->getTransferables());
    self::assertInstanceOf(All::class, $this->sut->deployerFunctions);
  }

  public function test__invoke() : void {
    $this->sut->setTransferables(
      $this->createConfiguredMock(iTransferable::class, ['getLocalPath' => 'local1', 'getRemotePath' => 'remote1', 'getConfig' => ['config1']]),
      $this->createConfiguredMock(iTransferable::class, ['getLocalPath' => 'local2', 'getRemotePath' => 'remote2', 'getConfig' => ['config2']]),
      $this->createConfiguredMock(iTransferable::class, ['getLocalPath' => 'local3', 'getRemotePath' => 'remote3', 'getConfig' => ['config3']]),
    );

    $this->sut->deployerFunctions = $this->createMock(iDownload::class);
    $this->sut->deployerFunctions
      ->expects(self::exactly(3))
      ->method('download')
      ->withConsecutive(
        ['local1', 'remote1', ['config1']],
        ['local2', 'remote2', ['config2']],
        ['local3', 'remote3', ['config3']],
      );

    $this->sut->__invoke();
  }
}
