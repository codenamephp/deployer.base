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

namespace de\codenamephp\deployer\base\test\transferable;

use de\codenamephp\deployer\base\transferable\Simple;
use PHPUnit\Framework\TestCase;

final class SimpleTest extends TestCase {

  private Simple $sut;

  protected function setUp() : void {
    parent::setUp();

    $this->sut = new Simple('', '');
  }

  public function test__construct() : void {
    $this->sut = new Simple('', '');

    self::assertEquals('', $this->sut->getLocalPath());
    self::assertEquals('', $this->sut->getRemotePath());
    self::assertEquals([
      'options' => [
        '--delete',
        '--delete-excluded',
      ],
    ], $this->sut->getConfig());
  }

  public function testGetLocalPath() : void {
    $this->sut = new Simple('some path', '');

    self::assertEquals('some path', $this->sut->getLocalPath());
  }

  public function testGetRemotePath() : void {
    $this->sut = new Simple('', 'some path');

    self::assertEquals('some path', $this->sut->getRemotePath());
  }

  public function testGetConfig() : void {
    $this->sut = new Simple('', '', ['exclude1', 'exclude2'], true, true);

    self::assertEquals([
      'options' => [
        '--exclude=exclude1',
        '--exclude=exclude2',
        '--delete',
        '--delete-excluded',
      ],
    ], $this->sut->getConfig());
  }
}
