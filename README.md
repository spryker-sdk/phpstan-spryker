# Spryker PHPStan Extensions
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%207.0-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/spryker/code-sniffer/license.svg)](https://packagist.org/packages/spryker-sdk/phpstan)


## Documentation
See https://github.com/phpstan/phpstan


## Usage

### How to use in Spryker projects
Make sure you include the repo as `require-dev` dependency:
```
composer require --dev spryker-sdk/phpstan
```

The [Development](https://github.com/spryker/Development) module provides a convenience command:
```
console code:phpstan
```

`-v` is useful for more info output.

### How to use in any project
You can also manually invoke the phpcs/phpcbf commands:
```
vendor/bin/phpstan ...
```


## Integrating into CI testing and PRs

Please see the [Spryker Demoshop](https://github.com/spryker/demoshop) repository for details. It is used there.

## Writing new extensions
Run `./setup.sh` to have the dependencies installed.

Add new TypeExtension classes to the corresponding category inside src folder.

### Runninng sniffer/fixer on your changes
```
composer cs-check
composer cs-fix
```
