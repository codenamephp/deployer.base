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

namespace de\codenamephp\deployer\base\functions;

use Deployer\Exception\Exception;
use Deployer\Exception\RunException;
use Deployer\Exception\TimeoutException;

/**
 * Interface for the Deployer\run() function
 */
interface iRun {

  /**
   * Executes given command on remote host.
   *
   * Examples:
   *
   * ```php
   * run('echo hello world');
   * run('cd {{deploy_path}} && git status');
   * run('password %secret%', secret: getenv('CI_SECRET'));
   * run('curl medv.io', timeout: 5);
   * ```
   *
   * ```php
   * $path = run('readlink {{deploy_path}}/current');
   * run("echo $path");
   * ```
   *
   * @param string $command Command to run on remote host.
   * @param array|null $options Array of options will override passed named arguments.
   * @param int|null $timeout Sets the process timeout (max. runtime). The timeout in seconds (default: 300 sec; see {{default_timeout}}, `null` to disable).
   * @param int|null $idle_timeout Sets the process idle timeout (max. time since last output) in seconds.
   * @param string|null $secret Placeholder `%secret%` can be used in command. Placeholder will be replaced with this value and will not appear in any logs.
   * @param array|null $env Array of environment variables: `run('echo $KEY', env: ['key' => 'value']);`
   * @param bool|null $real_time_output Print command output in real-time.
   * @param bool|null $no_throw Don't throw an exception of non-zero exit code.
   * @return string The output of the command.
   *
   * @throws Exception|RunException|TimeoutException
   */
  public function run(string $command, ?array $options = [], ?int $timeout = null, ?int $idle_timeout = null, ?string $secret = null, ?array $env = null, ?bool $real_time_output = false, ?bool $no_throw = false) : string;
}