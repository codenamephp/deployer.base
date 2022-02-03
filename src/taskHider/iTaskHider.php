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

namespace de\codenamephp\deployer\base\taskHider;

/**
 * Interface to hide tasks since Deployer comes with a lot of "built-in" task we don't necessarily want to clutter up our list (e.g. all the provision stuff)
 *
 * Implementations are supposed to use matcher and set tasks to hidden
 */
interface iTaskHider {

  /**
   * Hides all tasks that should be hidden
   *
   * @return void
   */
  public function hide() : void;
}