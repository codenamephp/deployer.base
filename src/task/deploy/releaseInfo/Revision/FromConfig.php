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

namespace de\codenamephp\deployer\base\task\deploy\releaseInfo\Revision;

use de\codenamephp\deployer\base\functions\All;
use de\codenamephp\deployer\base\functions\iRun;
use de\codenamephp\deployer\base\task\iTask;
use de\codenamephp\deployer\base\task\iTaskWithDescription;
use de\codenamephp\deployer\base\task\iTaskWithName;

/**
 * Creates a revision file from a config value that can be used to identify the release, e.g. in a sentry client.
 *
 * The config key is used to get the value from the config using the usual replacement syntax, so it is passed to run with {{configKey}}. This means
 * that the revision can be set with "-o releaseInfo.revision=1234" on the command line or in the config file.
 */
final class FromConfig implements iTask, iTaskWithName, iTaskWithDescription {

  public const NAME = 'deploy:release_info:revision:from_config';

  public function __construct(
    public readonly string $configKey = 'releaseInfo.revision',
    public readonly string $revisionFile = '{{release_or_current_path}}/REVISION',
    public readonly iRun $run = new All(),
  ) {}

  public function getDescription() : string {
    return "Creates a revision file from a config value that can be used to identify the release, e.g. in a sentry client. Set the value with -o {$this->configKey}='your revision' on the command line or in the config file.";
  }

  public function getName() : string {
    return self::NAME;
  }

  public function __invoke() : void {
    $this->run->run("echo '{{{$this->configKey}}}' > '$this->revisionFile'");
  }
}
