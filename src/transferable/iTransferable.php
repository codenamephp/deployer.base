<?php
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

namespace de\codenamephp\deployer\base\transferable;

/**
 * Simple interface to provide a stable API for transferring files. This is intended to be used alongside the deployer upload function where we pass the
 * respective paths and config
 */
interface iTransferable {

  /**
   * The path on the local machine that is used as the source for the transfer. It is expected to be an absolute path (and can contain deployer placeholders).
   *
   * @return string
   */
  public function getLocalPath() : string;

  /**
   * The path on the remote machine that is used as the destination for the transfer. It is expected to be an absolute path (and can contain deployer placeholders).
   *
   * @return string
   */
  public function getRemotePath() : string;

  /**
   * An array compatible with \Deployer\upload especially the 'options' key for rsync
   *
   * @return array{
   *   timeout?: int|null,
   *   options?: string[],
   *   flags?: string,
   *   display_stats?: bool,
   *   progress_bar?: bool
   * }
   */
  public function getConfig() : array;
}
