# Migration

## 1.x -> 2.x

The ssh connection string is now part of `\Deployer\Host\Host`. This means the
`\de\codenamephp\deployer\base\ssh\client\iClient` interface and the `\de\codenamephp\deployer\base\ssh\client\StaticProxy` implementation
are not needed anymore and were removed. Just call `\Deployer\Host\Host::connectionOptionsString` directly
and remove any usages to the removed classes and interfaces.