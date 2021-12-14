# phpstan-cakephp
CakePHP extension for PHPStan

## Installation
To use this extension, require it through [Composer](https://getcomposer.org/):

```sh
composer require --dev phungduckien/phpstan-cakephp
```

This extensions load automatically if you install [phpstan/extension-installer](https://github.com/phpstan/extension-installer)
```sh
composer require --dev phpstan/extension-installer
```

or if you don't use `phpstan/extension-installer`, include `extension.neon` in your project's PHPStan config:

```
includes:
	- vendor/phungduckien/phpstan-cakephp/extension.neon
```
