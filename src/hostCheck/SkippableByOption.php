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

namespace de\codenamephp\deployer\base\hostCheck;

use de\codenamephp\deployer\base\functions\All;
use de\codenamephp\deployer\base\functions\iInput;

/**
 * Decorator for an existing host check that skips the check if the option was given
 */
final class SkippableByOption implements iHostCheck {

  public const OPTION_NAME = 'cpd:skip-host-check';
  public const OPTION_SHORTCUT_NAME = 'cpd:shc';

  public function __construct(
    public iHostCheck $hostCheck,
    public iInput     $deployerFunctions = new All()
  ) {
    $deployerFunctions->option(self::OPTION_NAME, self::OPTION_SHORTCUT_NAME, iInput::OPTION_VALUE_NONE, 'If the option is set the host check is skipped', true);
  }

  public function check() : void {
    $this->deployerFunctions->getOption(self::OPTION_NAME, false) || $this->hostCheck->check();
  }
}