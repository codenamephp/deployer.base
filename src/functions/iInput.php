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

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Interface for input related functions. Also groups several constants
 */
interface iInput {

  public const ARGUMENT_REQUIRED = InputArgument::REQUIRED;
  public const ARGUMENT_OPTIONAL = InputArgument::OPTIONAL;
  public const ARGUMENT_IS_ARRAY = InputArgument::IS_ARRAY;

  public const OPTION_VALUE_REQUIRED = InputOption::VALUE_REQUIRED;
  public const OPTION_VALUE_OPTIONAL = InputOption::VALUE_OPTIONAL;
  public const OPTION_VALUE_IS_ARRAY = InputOption::VALUE_IS_ARRAY;
  public const OPTION_VALUE_NONE = InputOption::VALUE_NONE;

  /**
   * Gets the value of the option with an optional default if the option was not set
   *
   * @param string $name The name of the option, e.g. --myOption => myOption
   * @param string|string[]|int|bool|null $default Default to return if the option is not set
   * @return mixed
   */
  public function getOption(string $name, mixed $default = null) : mixed;

  /**
   * Gets the value of the argument with an optional default if the argument was not set
   *
   * @param string $name The name of the argument
   * @param string|string[]|int|bool|null $default Default to return if the argument is not set
   * @return mixed
   */
  public function getArgument(string $name, mixed $default = null) : mixed;

  /**
   * Adds an option (--) to the deployer run that can later be accessed using getOption
   *
   * @param string $name The name of the option without the --
   * @param string|null $shortcut A shortcut without the -, e.g. --test could be -t
   * @param int|null $mode Combination of OPTION_* constants
   * @param string $description Description to show in the list command
   * @param string|string[]|int|bool|null $default Default to set when the option is omitted
   * @return void
   */
  public function option(string $name, string $shortcut = null, int $mode = null, string $description = '', string|array|int|bool|null $default = null) : void;

  /**
   * Adds an argument to the deployer run that can later be accessed using getArgument
   *
   * @param string $name The name of the argument
   * @param int|null $mode Combination of ARGUMENT_* constants
   * @param string $description Description to show in the list command
   * @param string|string[]|int|bool|null $default Default to set when the argument is omitted
   * @return void
   */
  public function argument(string $name, int $mode = null, string $description = '', string|array|int|bool|null $default = null) : void;
}