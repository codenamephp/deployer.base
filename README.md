# Deployer Base

![Lines of code](https://img.shields.io/tokei/lines/github/codenamephp/deployer.base)
![GitHub code size in bytes](https://img.shields.io/github/languages/code-size/codenamephp/deployer.base)
![GitHub](https://img.shields.io/github/license/codenamephp/deployer.base)

Base package that provides the very basic task interface, function abstraction and some initial tasks useful for all projects like transferring files

## What is it?

This package is an extension to deployer that adds basic tasks and interfaces and abstracts the actual deployer API. Deployer can still be used as usual but I
would recommend implementing your tasks as classes and write unit tests for them. If you have tasks you reuse across projects you should create your own
packages. There are already several codenamephp/deployer.* packages available to use.

### But ... why?

I really like testable code and since the actual deploy.php is otherwise just a collection of callbacks that is hard to test I added basic interfaces and
classes that encapsulate the tasks and make them reusable.

Sure, you could make it work with lambdas but since PHP is not intended to be a functional programming language it's far easier to just throw some classes into
the mix.

## Install

Just add the package to composer, ideally by executing `composer require codenamephp/deployer.base` which should install the latest version with semver range.

## Usage

Just create your `deploy.php` as usual. Then just add existing tasks or implement your own and add them using the package functions:

```php
const PROJECT_ROOT = __DIR__ . '/..'; // I have deployer in a seperate folder so this makes creating paths easier

$deployerFunctions = new All(); // Abstraction of deployer function and also has some additional methods

$deployerFunctions->registerTask(new UploadTransferables( // My version of deploying code in NEOS projects
  new Simple(PROJECT_ROOT . '/Configuration', '{{release_path}}'),
  new Simple(PROJECT_ROOT . '/DistributionPackages', '{{release_path}}', ['Tests/']),
  new Simple(PROJECT_ROOT . '/Packages', '{{release_path}}'),
  new Simple(PROJECT_ROOT . '/Web', '{{release_path}}', ['_Resources/']),
  new Simple(PROJECT_ROOT . '/flow', '{{release_path}}'),
));

// additional tasks from other packages
$deployerFunctions->task('composer:install', new \de\codenamephp\deployer\composer\task\install\Production())->desc('Run composer install for production.');
$deployerFunctions->task('composer:install:development', new \de\codenamephp\deployer\composer\task\install\Development())->desc('Run composer install for production.');

(new ByTaskListAndMatchers(new AtLeastOne( // clean up the cli list
  new ByRegexTaskName('/provision:?.*/'),
  new ByRegexTaskName('/logs:caddy.*/'),
  new ByRegexTaskName('/deploy:.*/')
)))->hide();
```

### Implementing tasks

Deployer just expects a callable as task so in theory this is everything a task needs. This package contains the
`\de\codenamephp\deployer\base\task\iTask` interface that enforces this so we can just add a new instance of the task.

There are also the `\de\codenamephp\deployer\base\task\iTaskWithName` and `\de\codenamephp\deployer\base\task\iTaskWithDescription` interfaces. These can be
used to set the description and name of the task directly in the class so we don't have to set the same strings in each project and clutter up our deployment
file. At the very least the `\de\codenamephp\deployer\base\task\iTaskWithName` has to be implemented so we can pass the task to
`\de\codenamephp\deployer\base\functions\iTask::registerTask` that takes care of the rest.

### Deployer Functions

The built in deployer functions require a running Deployer instance and are global so they are very hard to mock. In order for our tasks to be testable there
are several `\de\codenamephp\deployer\base\functions\*` interfaces, one for each method or method group. There is also an
`\de\codenamephp\deployer\base\functions\iAll` interface that combines them all as convenience if we need several methods. Likewise there's an
`\de\codenamephp\deployer\base\functions\All` implementation that acts mostly as a proxy for the global methods and also does some additional type checking.

The interfaces are mostly the same as the deployer built in methods but add some additional type hints and return hints for a cleaner API.

Use these interfaces in your tasks and either use the `\de\codenamephp\deployer\base\functions\All` implementation or write your own. In your tests you can then
easily mock these interfaces.

### Host Check

Some task should not be executed by accident, for example pushing a database from local to production. This can be easily achieved by adding
the `\de\codenamephp\deployer\base\hostCheck\iHostCheck` interface as dependency to your task and calling the
`\de\codenamephp\deployer\base\hostCheck\iHostCheck::check` method in your `__invoke()` method.

#### DoNotRunOnProduction

This implementation assumes the production host actually has the alias "production" (can be changed in constructor) and checks against the current host. If the
alias matches an `\de\codenamephp\deployer\base\UnsafeOperationException` is thrown which halts your deployer run. If you have things to clean up (e.g. a
database dump) you should add cleanup tasks to the failure step.

#### WithDisallowList

Similar to DoNotRunOnProduction but with a whole list of disallowed aliases.

#### SkippableByOption

This is a decorator for the adding the `\de\codenamephp\deployer\base\hostCheck\iHostCheck` that takes an existing host check but only executes it if the
`--cpd:skip-host-check` (or `-cpd:shc` as shorthand) are not set. This way we can skip the host check if we know what we are doing ... at your own risk of
course. ;)

### Task Hider / Task Matcher

Deployer comes with a whole set of default tasks, like the whole `provision` namespace. These are usually used once (if at all) and just clutter up the CLI
list. The `\de\codenamephp\deployer\base\taskHider\iTaskHider` can be used to hide those tasks you don't want. The default implementation The default
implementation `\de\codenamephp\deployer\base\taskHider\ByTaskListAndMatchers` uses a `\de\codenamephp\deployer\base\taskMatcher\iTaskMatcher` implementation to
find tasks to hide. There is also a collection that can be used to just add multiple matchers and hide all the matches.

An example could look like this:

```php
(new ByTaskListAndMatchers(new AtLeastOne( // clean up the cli list
  new ByRegexTaskName('/provision:?.*/'),
  new ByRegexTaskName('/logs:caddy.*/'),
  new ByRegexTaskName('/deploy:.*/')
)))->hide();
```

### Transferables

There is a `\de\codenamephp\deployer\base\transferable\iTransferable` interface that makes file transfers more readable by clearly stating what is local and
remote.

### Tasks

Maybe I'm going to include an Open API generated manual at some point but for now just check the classes in the `\de\codenamephp\deployer\base\task` namespace.