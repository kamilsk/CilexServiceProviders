[CHANGELOG](http://keepachangelog.com)
======================================

# Version 3

## [3.0] - 2016-03-15
### Added
- services
  - `config` contains `Common\Config\SimpleConfig` with `ArrayAccess` implementation instead `array`
  - `connection` and `connections` contain `Doctrine\DBAL\Connection` and `Pimple` respectively
  - `loggers` contains `Common\Monolog\LoggerLocator`

### Changed
- configs
  - `monolog` now has a stricter format ([docs](/docs/MonologServiceProvider.md#configuration-example))
- methods
  - `Command\Command::getConfig()` has changed return value
  - `Command\Command::getDbConnection()` has changed throws
  - `Command\Command::getLogger()` has changed throws
- commands
  - `Command\PresetCommand` was moved to `Command\CliMenu\PresetCommand` and was refactored
  ([docs](/docs/docs/AppAndCommands.md#presetcommand))
- packages
  - `kamilsk/common` is up
  - `symfony/config` is up
  - `symfony/event-dispatcher` is up
  - `symfony/monolog-bridge` is up
  - `symfony/yaml` is up
- services
  - `ServiceProvider\DoctrineServiceProvider` now incompatible with `Cilex\Provider\DoctrineServiceProvider`
  - `ServiceProvider\MonologServiceProvider` now incompatible with `Cilex\Provider\MonologServiceProvider`
- [git diff](/../../compare/2.1.1...3.0)

### Removed
- config
  - `monolog:name`
- methods
  - `Command\Command::initConsoleHandler()`
  - `Command\PresetCommand::getConfig()`, use `path` option instead
  - `ServiceProvider\DoctrineServiceProvider::__construct()`
  - `ServiceProvider\MonologServiceProvider::__construct()`
- services
  - `config.raw`, use `config` instead
  - `db` and `dbs`, use `connection` and `connections` instead respectively
  - `monolog`, use `logger` instead
  - `monolog.name` now ignored, use `console.name` or channel's `name` option in configuration
  - `monolog.handlers`, is no logger supported
  - `monolog.resolver`, use `loggers` instead
- packages
  - `secondparty/dipper`

# Version 2

## [2.1] - 2016-01-07
### Added
- support multichannel for `Monolog` like [Monolog Cascade](https://github.com/theorchard/monolog-cascade)
([#54](/../../issues/54))

### Changed
- methods
  - `OctoLab\Cilex\Command\Command::getConfig` and `OctoLab\Cilex\Command\PresetCommand::getConfig`
  ([#78](/../../issues/78), [docs](/docs/AppAndCommands.md))
  - `OctoLab\Cilex\Command\Command::getDbConnection` ([#79](/../../issues/79), [docs](/docs/AppAndCommands.md))
  - `OctoLab\Cilex\Command\Command::getLogger` ([#80](/../../issues/80), [docs](/docs/AppAndCommands.md))
  - `OctoLab\Cilex\ServiceProvider\MonologServiceProvider::__construct` ([docs](/docs/MonologServiceProvider.md))
- [git diff](/../../compare/2.0...2.1.1)

### Fixed
- bug with full support of configuration in json ([#76](/../../issues/76))

## [2.0] - 2015-12-24
### Added
- integration with `php-school/cli-menu` by `OctoLab\Cilex\Command\PresetCommand` ([#69](/../../issues/69))
- `OctoLab\Cilex\Application::register()` prevent register service twice ([#74](/../../issues/74))

### Changed
- classes have been moved
  - `OctoLab\Cilex\Doctrine\Command` renamed to `OctoLab\Cilex\Command\Doctrine`
  - `OctoLab\Cilex\Doctrine` renamed to `OctoLab\Common\Doctrine\Migration`
  - `OctoLab\Cilex\Monolog\Processor` renamed to `OctoLab\Common\Monolog\Processor`
  - `OctoLab\Cilex\Config` renamed to `OctoLab\Common\Config`
  - `Parser` moved from `OctoLab\Cilex\Doctrine\Util` to `OctoLab\Common\Doctrine\Util`
  - `Dumper` moved from `OctoLab\Cilex\Monolog\Util` to `OctoLab\Common\Monolog\Util`
  - `OctoLab\Cilex\Provider` renamed to `OctoLab\Cilex\ServiceProvider`
  - `Monolog`'s `ConfigResolver` moved from `OctoLab\Cilex\Monolog` to `OctoLab\Common\Monolog\Util`
- classes have been abstracted
  - `OctoLab\Cilex\Command\Command`
- classes have been finalized
  - `OctoLab\Cilex\Command\Doctrine\CheckMigrationCommand`
  - `OctoLab\Cilex\Command\Doctrine\GenerateIndexNameCommand`
  - `OctoLab\Cilex\Command\PresetCommand`
- `OctoLab\Cilex\Command\Command` has been optimized
  - extends `Symfony\Component\Console\Command\Command` instead of `Cilex\Command\Command`
  - `setOutputInterface` was removed, use `initConsoleHandler` instead
  - add `getConfig` method to return `Application` configuration
- `Doctrine`'s configuration was changed
  - add support `types` directive ([#71](/../../issues/71))
- `Monolog`'s configuration was changed
  - `path`, `level` and `bubble` became part of `arguments`
  - `formatter` now is not a alias, use `{ type: ... }` notation instead (see [docs](/docs/MonologServiceProvider.md))
- config component has been changed
  - `$parser` is required argument for `OctoLab\Common\Config\Loader\YamlFileLoader::__construct()`
  (ex. `OctoLab\Cilex\Config\Loader\YamlFileLoader`)
  - `$app` was removed from `OctoLab\Common\Monolog\Util\ConfigResolver`
  (ex. `OctoLab\Cilex\Monolog\ConfigResolver`)
- [git diff](/../../compare/1.x...2.0)

### Removed
- all marked deprecated functionality

# Version 1

## [1.5] - 2015-11-06
### Changed
- move tests in separated namespace ([#67](/../../issues/67))
- [git diff](/../../compare/v1.4.3...v1.5.1)

## [1.4] - 2015-06-06
### Added
- support for `.php` and `.json` config files ([#27](/../../issues/27))
- constant supports by `OctoLab\Cilex\Config\YamlConfig` ([#42](/../../issues/42))
- support for [Dipper](https://github.com/secondparty/dipper) as alternative of `Symfony\Component\Yaml\Parser`
([#50](/../../issues/50))
- classes
  - `OctoLab\Cilex\Doctrine\FileBasedMigration`
  - `OctoLab\Cilex\Doctrine\DriverBasedMigration`
  - `OctoLab\Cilex\Doctrine\Command\CheckMigrationCommand` ([#52](/../../issues/52))
  - `OctoLab\Cilex\Doctrine\Command\GenerateIndexNameCommand` ([#53](/../../issues/53))

### Changed
- [git diff](/../../compare/v1.3.2...v1.4.3)

### Fixed
- bug [#56](/../../issues/56)

## [1.3] - 2015-06-01
### Changed
- `OctoLab\Cilex\Command\Command::getLogger` now return `Psr\Log\LoggerInterface` ([#36](/../../issues/36))
- `cilex/cilex:~1.0` is required now ([#44](/../../issues/44))
- isolate logic of `OctoLab\Cilex\Provider\ConfigServiceProvider` in `OctoLab\Cilex\Config\YamlConfig`
([#48](/../../issues/48))
- isolate logic of `OctoLab\Cilex\Provider\MonologServiceProvider` in `OctoLab\Cilex\Monolog\ConfigResolver`
([#51](/../../issues/51))
- [git diff](/../../compare/v1.2.3...v1.3.2)

### Deprecated
- PHP 5.4 ([14 Sep 2015 is end of support](http://php.net/supported-versions.php))

### Removed
- `composer.lock` was removed ([#46](/../../issues/46))

## [1.2] - 2015-04-19
### Added
- helper methods in base `Command` class ([#31](/../../issues/31))
- `Monolog` `TimeExecutionProcessor` processor ([#39](/../../issues/39))
- `Monolog` `Dumper` util ([#40](/../../issues/40))

### Changed
- update dependencies ([#33](/../../issues/33))
- refactor `MonologServiceProvider` ([#35](/../../issues/35))
- [git diff](/../../compare/v1.1.4...v1.2.3)

## [1.1] - 2014-11-10
### Added
- `translateLevel` ported from `Silex` ([#18](/../../issues/18))
- support `ConsoleHandler` ([#16](/../../issues/16))

### Changed
- you can pass a connection name for `ConnectionHelper` to `DoctrineServiceProvider` ([#20](/../../issues/20))
- default channel for logging by `Monolog` is the application name ([#21](/../../issues/21))
- all handlers of logging channel placed in the registry, like `dbs` ([#17](/../../issues/17))
- [git diff](/../../compare/v1.0.1...v1.1.4)

## [1.0] - 2014-08-30
### Changed
- [git diff](/../../compare/v0.6...v1.0.1)
