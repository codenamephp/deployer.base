# Deployer Base

![Lines of code](https://img.shields.io/tokei/lines/github/codenamephp/deployer.base)
![GitHub code size in bytes](https://img.shields.io/github/languages/code-size/codenamephp/deployer.base)
![GitHub](https://img.shields.io/github/license/codenamephp/deployer.base)

Base package that provides the very basic task interface, function abstraction and some initial tasks useful for all projects like transferring files

## What is it?

This package is an extension to deployer that adds basic tasks and interfaces and abstracts the actual deployer API.

## But ... why?

I really like testable code and since the actual deploy.php is otherwise just a collection of callbacks that is hard to test I added basic interfaces and
classes that encapsulate the tasks and make them reusable.

Sure, you could make it work with lambdas but since PHP is not intended to be a functional programming language it's far easier to just throw some classes into
the mix.