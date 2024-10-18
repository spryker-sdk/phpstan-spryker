# Spryker PHPStan Extensions
[![Build Status](https://github.com/spryker-sdk/phpstan-spryker/workflows/CI/badge.svg?branch=master)](https://github.com/spryker-sdk/phpstan-spryker/actions?query=workflow%3ACI+branch%3Amaster)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%208.2-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/spryker/code-sniffer/license.svg)](https://packagist.org/packages/spryker-sdk/phpstan-spryker)

* [PHPStan](https://github.com/phpstan/phpstan)
* [Spryker](https://spryker.com/)

This extension provides following features:

* Notifies you when you try to use `getConfig()`, `getClient()`, `getFacade()`, `getFactory()`, `getSharedConfig()`, `getQueryContainer()` methods without specifying theirs types in a class's DocBlock.


## Usage

### How to use in Spryker projects
To use this extension, require it in [Composer](https://getcomposer.org/). Make sure you include the repo as `require-dev` dependency:
```
composer require --dev spryker-sdk/phpstan-spryker
```

You can use [phpstan/extension-installer](https://github.com/phpstan/extension-installer) to automatically load the configuration:
```
composer require --dev phpstan/extension-installer
```

Or include the `extension.neon` manually in your project's PHPStan config:

```
includes:
    - vendor/spryker-sdk/phpstan-spryker/extension.neon
```
