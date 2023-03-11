# Migration

## 2.x -> 3.x

### Before Method

The iAll interface was extended with the iBefore interface. If you created your own implementation of iAll you need to implement the iBefore interface as well. 
If you use the All class you don't need to do anything.

### Within Method

The iAll interface was extended with the iWithin interface. If you created your own implementation of iAll you need to implement the iWithin interface as well. 
If you use the All class you don't need to do anything.

### Info Method
The iAll interface was extended with the iInfo interface. If you created your own implementation of iAll you need to implement the iInfo interface as well.
If you use the All class you don't need to do anything.

### Warning Method
The iAll interface was extended with the iWarning interface. If you created your own implementation of iAll you need to implement the iWarning interface as well.
If you use the All class you don't need to do anything.

### Writeln Method
The iAll interface was extended with the iWriteln interface. If you created your own implementation of iAll you need to implement the iWriteln interface as well.
If you use the All class you don't need to do anything.

## 1.x -> 2.x

The ssh connection string is now part of `\Deployer\Host\Host`. This means the
`\de\codenamephp\deployer\base\ssh\client\iClient` interface and the `\de\codenamephp\deployer\base\ssh\client\StaticProxy` implementation
are not needed anymore and were removed. Just call `\Deployer\Host\Host::connectionOptionsString` directly
and remove any usages to the removed classes and interfaces.