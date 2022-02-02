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

use de\codenamephp\deployer\base\functions\iAll;
use de\codenamephp\deployer\base\functions\iInput;
use de\codenamephp\deployer\base\hostCheck\iHostCheck;
use de\codenamephp\deployer\base\iConfigurationKeys;
use de\codenamephp\deployer\base\ssh\client\iClient;
use de\codenamephp\deployer\base\task\media\Copy;
use de\codenamephp\deployer\base\transferable\iTransferable;
use Deployer\Host\Host;
use PHPUnit\Framework\TestCase;

final class CopyTest extends TestCase {

  private Copy $sut;

  protected function setUp() : void {
    parent::setUp();

    $deployerFunctions = $this->createMock(iAll::class);

    $this->sut = new Copy([], $deployerFunctions);

    $this->sut->hostCheck = $this->createMock(iHostCheck::class);
  }

  public function test__construct() : void {
    $deployerFunctions = $this->createMock(iAll::class);
    $deployerFunctions->expects(self::once())->method('option')->with(
      Copy::OPTION_SOURCE_HOST,
      null,
      iInput::OPTION_VALUE_REQUIRED,
      'The source host to copy the media from',
      iConfigurationKeys::PRODUCTION
    );

    $transferables = [$this->createMock(iTransferable::class), $this->createMock(iTransferable::class)];
    $hostCheck = $this->createMock(iHostCheck::class);
    $sshClient = $this->createMock(iClient::class);

    $this->sut = new Copy($transferables, $deployerFunctions, $hostCheck, $sshClient);

    self::assertSame($transferables, $this->sut->getTransferables());
    self::assertSame($deployerFunctions, $this->sut->deployerFunctions);
    self::assertSame($hostCheck, $this->sut->hostCheck);
    self::assertSame($sshClient, $this->sut->sshClient);
  }

  public function test__invoke() : void {
    $this->sut->hostCheck = $this->createMock(iHostCheck::class);
    $this->sut->hostCheck->expects(self::once())->method('check');

    $sourceHost = $this->createMock(Host::class);
    $sourceHost->expects(self::once())->method('getConnectionString')->willReturn('source_connection_string');

    $targetHost = $this->createMock(Host::class);
    $targetHost->expects(self::once())->method('getConnectionString')->willReturn('target_connection_string');

    $this->sut->sshClient = $this->createMock(iClient::class);
    $this->sut->sshClient
      ->expects(self::exactly(2))
      ->method('connectionOptionsString')
      ->withConsecutive([$sourceHost], [$targetHost])
      ->willReturn('source_ssh_arguments', 'target_ssh_arguments');

    $transferable1 = $this->createMock(iTransferable::class);
    $transferable1->expects(self::once())->method('getLocalPath')->willReturn('source_path_1');
    $transferable1->expects(self::once())->method('getRemotePath')->willReturn('target_path_1');
    $transferable1->expects(self::once())->method('getConfig')->willReturn(['options' => ['options1_1', 'options1_2']]);

    $transferable2 = $this->createMock(iTransferable::class);
    $transferable2->expects(self::once())->method('getLocalPath')->willReturn('source_path_2');
    $transferable2->expects(self::once())->method('getRemotePath')->willReturn('target_path_2');
    $transferable2->expects(self::once())->method('getConfig')->willReturn(['options' => ['options2_1', 'options2_2']]);
    $this->sut->setTransferables($transferable1, $transferable2);

    $deployerFunctions = $this->createMock(iAll::class);
    $deployerFunctions->expects(self::once())->method('getOption')->with(Copy::OPTION_SOURCE_HOST)->willReturn(1234);
    $deployerFunctions->expects(self::once())->method('firstHost')->with('1234')->willReturn($sourceHost);
    $deployerFunctions->expects(self::once())->method('currentHost')->willReturn($targetHost);
    $deployerFunctions
      ->expects(self::exactly(4))
      ->method('parseOnHost')
      ->withConsecutive(
        [$sourceHost, 'source_path_1'],
        [$targetHost, 'target_path_1'],
        [$sourceHost, 'source_path_2'],
        [$targetHost, 'target_path_2'],
      )
      ->willReturnOnConsecutiveCalls('parsed_source_path_1', 'parsed_target_path_1', 'parsed_source_path_2', 'parsed_target_path_2');
    $deployerFunctions
      ->expects(self::exactly(2))
      ->method('runLocally')
      ->withConsecutive(
        ['ssh source_ssh_arguments source_connection_string "rsync -e \'ssh -o StrictHostKeyChecking=no target_ssh_arguments\' -azP options1_1 options1_2 parsed_source_path_1 target_connection_string:parsed_target_path_1"'],
        ['ssh source_ssh_arguments source_connection_string "rsync -e \'ssh -o StrictHostKeyChecking=no target_ssh_arguments\' -azP options2_1 options2_2 parsed_source_path_2 target_connection_string:parsed_target_path_2"']
      );
    $this->sut->deployerFunctions = $deployerFunctions;

    $this->sut->__invoke();
  }
}
