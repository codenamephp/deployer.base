# template.php.library

![Lines of code](https://img.shields.io/tokei/lines/github/codenamephp/template.php.library)
![GitHub code size in bytes](https://img.shields.io/github/languages/code-size/codenamephp/template.php.library)
![GitHub](https://img.shields.io/github/license/codenamephp/template.php.library)

Template repository to quickstart library development.

## Prerequisites
- PHP 8.0 or later (https://php.net)
- Git (https://git-scm.com) available as global `git` command
- Composer (https://getcomposer.org) available as global `composer` command
- Phive (https://phar.io) available as global `phive` command

If one of the CLI tools is not available as global command you may have to change the `.installer/install.php` or `composer.json`
to adapt to your environment.

## Usage

This template uses an installer to setup all the files that need replacing of placeholders but most files
are just kept as they are.

TODOs:
1. Create the new repository on GitHub using this repository as template
1. Add protection rules for master and release branches since they are not copied from the template :(
1. Clone the new repository to local
1. Run the installer by executing `composer run-installer`
1. Add and commit your files and push them to remote
1. Submit the new package to packagist
1. Get to work! ;)