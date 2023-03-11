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

use Closure;
use de\codenamephp\deployer\base\MissingConfigurationException;
use de\codenamephp\deployer\base\task\iTask;
use de\codenamephp\deployer\base\task\iTaskWithDescription;
use de\codenamephp\deployer\base\task\iTaskWithName;
use Deployer\Deployer;
use Deployer\Host\Host;
use Deployer\Host\Localhost;
use Deployer\Support\ObjectProxy;
use Deployer\Task\Task;
use Symfony\Component\Console\Input\InputArgument;
use function Deployer\add;
use function Deployer\after;
use function Deployer\before;
use function Deployer\currentHost;
use function Deployer\download;
use function Deployer\get;
use function Deployer\input;
use function Deployer\on;
use function Deployer\option;
use function Deployer\parse;
use function Deployer\run;
use function Deployer\runLocally;
use function Deployer\set;
use function Deployer\upload;

/**
 * Implements all method interfaces so we a "all drop-in" class to easily access the methods
 */
final class All implements iAll {

  public function add(string $name, array $array) : void {
    add($name, $array);
  }

  public function after(string $task, callable|iTask|string $do) : ?Task {
    return after($task, $do);
  }

  public function before(string $task, callable|iTask|string $do) : ?Task {
    return before($task, $do);
  }

  public function currentHost() : Host {
    return currentHost();
  }

  public function download(string $source, string $destination, array $config = []) : void {
    download($source, $destination, $config);
  }

  public function get(string $name, mixed $default = null) : mixed {
    return get($name, $default);
  }

  public function host(string ...$hostname) : Host|array {
    $hostOrHosts = \Deployer\host(...$hostname);
    if($hostOrHosts instanceof ObjectProxy) {
      /**
       * We need to hack a little since ObjectProxy doesn't expose the contained objects
       *
       * @psalm-suppress PossiblyInvalidFunctionCall,InaccessibleProperty,PossiblyNullFunctionCall
       */
      $hostOrHosts = array_values(
        array_filter(
          (array) Closure::bind(static fn(ObjectProxy $objectProxy) : array => $objectProxy->objects, null, $hostOrHosts)($hostOrHosts),
          static fn(mixed $host) : bool => $host instanceof Host
        )
      );
    }
    return $hostOrHosts;
  }

  public function firstHost(string ...$hostname) : Host {
    $hostOrHosts = $this->host(...$hostname);
    if($hostOrHosts === []) throw new MissingConfigurationException(sprintf('No hosts were found for the given hostnames: [%s]', implode(',', $hostname)));

    return is_array($hostOrHosts) ? array_values($hostOrHosts)[0] : $hostOrHosts;
  }

  public function getOption(string $name, mixed $default = null) : mixed {
    return input()->getOption($name) ?? $default;
  }

  public function getArgument(string $name, mixed $default = null) : mixed {
    return input()->getArgument($name) ?? $default;
  }

  public function option(string $name, string $shortcut = null, int $mode = null, string $description = '', string|array|int|bool|null $default = null) : void {
    option($name, $shortcut, $mode, $description, $default);
  }

  public function argument(string $name, int $mode = null, string $description = '', string|array|int|bool|null $default = null) : void {
    Deployer::get()->inputDefinition->addArgument(new InputArgument($name, $mode, $description, $default));
  }

  public function localhost(string ...$hostname) : Localhost|array {
    $localhost = \Deployer\localhost(...$hostname);
    if($localhost instanceof ObjectProxy) {
      /**
       * We need to hack a little since ObjectProxy doesn't expose the contained objects
       *
       * @psalm-suppress PossiblyInvalidFunctionCall,InaccessibleProperty,PossiblyNullFunctionCall
       */
      $localhost = array_values(
        array_filter(
          (array) Closure::bind(static fn(ObjectProxy $objectProxy) : array => $objectProxy->objects, null, $localhost)($localhost),
          static fn(mixed $localhost) : bool => $localhost instanceof Localhost
        )
      );
    }
    return $localhost;
  }

  public function on(Host|array $hosts, callable $callback) : void {
    on($hosts, $callback);
  }

  public function parse(string $value) : string {
    return parse($value);
  }

  public function parseOnHost(Host $host, string $value) : string {
    $finalValue = '';
    $this->on($host, function() use (&$finalValue, $value) { $finalValue = $this->parse($value); });
    return (string) $finalValue;
  }

  public function run(string $command, ?array $options = [], ?int $timeout = null, ?int $idle_timeout = null, ?string $secret = null, ?array $env = null, ?bool $real_time_output = false, ?bool $no_throw = false) : string {
    return run($command, $options, $timeout, $idle_timeout, $secret, $env, $real_time_output, $no_throw);
  }

  public function runLocally(string $command, ?array $options = [], ?int $timeout = null, ?int $idle_timeout = null, ?string $secret = null, ?array $env = null, ?string $shell = null) : string {
    return runLocally($command, $options, $timeout, $idle_timeout, $secret, $env, $shell);
  }

  public function set(string $name, mixed $value) : void {
    set($name, $value);
  }

  public function task(string $name, callable|array|iTask|null $body = null) : Task {
    return \Deployer\task($name, $body);
  }

  public function registerTask(iTaskWithName $task) : void {
    $registeredTask = $this->task($task->getName(), $task);
    if($task instanceof iTaskWithDescription) $registeredTask->desc($task->getDescription());
  }

  public function upload(string $source, string $destination, array $config = []) : void {
    upload($source, $destination, $config);
  }
}
