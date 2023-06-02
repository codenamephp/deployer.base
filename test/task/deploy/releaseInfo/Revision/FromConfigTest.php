<?php declare(strict_types=1);
/*
 *   Copyright 2023 Bastian Schwarz <bastian@codename-php.de>.
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

namespace de\codenamephp\deployer\base\test\task\deploy\releaseInfo\Revision;

use de\codenamephp\deployer\base\functions\All;
use de\codenamephp\deployer\base\functions\iRun;
use de\codenamephp\deployer\base\task\deploy\releaseInfo\Revision\FromConfig;
use PHPUnit\Framework\TestCase;

final class FromConfigTest extends TestCase {

  public function test__invoke() : void {
    $run = $this->createMock(iRun::class);
    $run->expects(self::once())->method('run')->with("echo '{{some key}}' > 'some path'");

    $sut = new FromConfig('some key', 'some path', $run);

    $sut();
  }

  public function testGetDescription() : void {
    self::assertSame("Creates a revision file from a config value that can be used to identify the release, e.g. in a sentry client. Set the value with -o releaseInfo.revision='your revision' on the command line or in the config file.", (new FromConfig())->getDescription());
  }

  public function test__construct() : void {
    $sut = new FromConfig();

    self::assertSame('releaseInfo.revision', $sut->configKey);
    self::assertSame('{{release_or_current_path}}/REVISION', $sut->revisionFile);
    self::assertInstanceOf(All::class, $sut->run);
  }

  public function test__construct_withOptionalArguments() : void {
    $run = $this->createMock(iRun::class);

    $sut = new FromConfig('some key', 'some path', $run);

    self::assertSame('some key', $sut->configKey);
    self::assertSame('some path', $sut->revisionFile);
    self::assertSame($run, $sut->run);
  }

  public function testGetName() : void {
    self::assertSame(FromConfig::NAME, (new FromConfig())->getName());
  }
}
