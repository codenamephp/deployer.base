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
use de\codenamephp\deployer\base\functions\iCurrentHost;
use de\codenamephp\deployer\base\UnsafeOperationException;

/**
 * Contains a disallow list the current host is checked against. If the host is found in the list an UnsafeOperationException is thrown
 */
final class WithDisallowList implements iHostCheck {

  public iCurrentHost $deployerFunctions;

  /**
   * @var array<string>
   */
  private array $disallowedHosts;

  public function __construct(string ...$disallowedHosts) {
    $this->disallowedHosts = $disallowedHosts;
    $this->deployerFunctions = new All();
  }

  /**
   * @return array<string>
   */
  public function getDisallowedHosts() : array {
    return $this->disallowedHosts;
  }

  public function setDisallowedHosts(string ...$disallowedHosts) : WithDisallowList {
    $this->disallowedHosts = $disallowedHosts;
    return $this;
  }

  public function check() : void {
    if(in_array($this->deployerFunctions->currentHost()->getAlias(), $this->getDisallowedHosts(), true)) throw new UnsafeOperationException('Current host alias was found in disallow list.');
  }
}