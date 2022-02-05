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

use de\codenamephp\deployer\base\UnsafeOperationException;

/**
 * Interface to check the host in tasks, e.g. to make sure the task don't run on the production server
 */
interface iHostCheck {

  /**
   * Checks if the host is allowed, e.g. by checking the current host against a list of allowed names
   *
   * @return void
   * @throws UnsafeOperationException when the host check fails
   */
  public function check() : void;
}