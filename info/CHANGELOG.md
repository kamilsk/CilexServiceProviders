CHANGELOG for 2.x
=================

* 2.1 (2016-01-xx)

  * [git diff](https://github.com/kamilsk/CilexServiceProviders/compare/2.0...master)

* 2.0 (2015-12-24)
  * Remove all deprecated functionality
  * Classes have been moved
    * Commands
      * `OctoLab\Cilex\Doctrine\Command` renamed to `OctoLab\Cilex\Command\Doctrine`
    * Migrations
      * `OctoLab\Cilex\Doctrine` renamed to `OctoLab\Common\Doctrine\Migration`
    * Processors
      * `OctoLab\Cilex\Monolog\Processor` renamed to `OctoLab\Common\Monolog\Processor`
    * Configs
      * `OctoLab\Cilex\Config` renamed to `OctoLab\Common\Config`
    * Utils
      * `Parser` moved from `OctoLab\Cilex\Doctrine\Util` to `OctoLab\Common\Doctrine\Util`
      * `Dumper` moved from `OctoLab\Cilex\Monolog\Util` to `OctoLab\Common\Monolog\Util`
    * Service providers
      * `OctoLab\Cilex\Provider` renamed to `OctoLab\Cilex\ServiceProvider`
    * Helpers
      * `Monolog`'s `ConfigResolver` moved from `OctoLab\Cilex\Monolog` to `OctoLab\Common\Monolog\Util`
  * Classes have been abstracted
    * `OctoLab\Cilex\Command\Command`
  * Classes have been finalized
    * `OctoLab\Cilex\Command\Doctrine\CheckMigrationCommand`
    * `OctoLab\Cilex\Command\Doctrine\GenerateIndexNameCommand`
    * `OctoLab\Cilex\Command\PresetCommand`
  * `OctoLab\Cilex\Command\Command` has been optimized
    * Extends `Symfony\Component\Console\Command\Command` instead of `Cilex\Command\Command`
    * `setOutputInterface` was removed, use `initConsoleHandler` instead
    * Add `getConfig` method to return `Application` configuration
  * `Doctrine`'s configuration was changed
    * Add support `types` directive ([#71](../../issues/71))
  * `Monolog`'s configuration was changed
    * `path`, `level` and `bubble` became part of `arguments`
    * `formatter` now is not a alias, use `{ type: ... }` notation instead (see [docs](docs/MonologServiceProvider))
  * Config component has been changed
    * `$parser` is required argument for `OctoLab\Common\Config\Loader\YamlFileLoader::__construct()`
    (ex. `OctoLab\Cilex\Config\Loader\YamlFileLoader`)
    * `$app` was removed from `OctoLab\Common\Monolog\Util\ConfigResolver`
    (ex. `OctoLab\Cilex\Monolog\ConfigResolver`)
  * New features
    * Integrates with `php-school/cli-menu` by `OctoLab\Cilex\Command\PresetCommand` ([#69](../../issues/69))
    * `OctoLab\Cilex\Application::register()` prevent register service twice ([#74](../../issues/74))
  * [git diff](../../compare/1.x...2.0)

CHANGELOG for 1.x
=================

* v1.5 (2015-11-06)

 * Up to 100% test coverage and resolve Scrutinizer CI recommendations ([#61](../../issues/61))
 * Move Test in separated namespace ([#67](../../issue/67))
 * [git diff](../../compare/v1.4.3...v1.5.1)

* v1.4 (2015-06-06)

  * Add support for `.php` and `.json` config files ([#27](../../issues/27))
  * Constant supports by `OctoLab\Cilex\Config\YamlConfig` ([#42](../../issues/42))
  * Add support for [Dipper](https://github.com/secondparty/dipper) as alternative of `\Symfony\Component\Yaml\Parser`
  ([#50](../../issues/50))
  * Add `OctoLab\Cilex\Doctrine\FileBasedMigration`, `OctoLab\Cilex\Doctrine\DriverBasedMigration` and
  `OctoLab\Cilex\Doctrine\Command\CheckMigrationCommand` ([#52](../../issues/52))
  * Add `OctoLab\Cilex\Doctrine\Command\GenerateIndexNameCommand` ([#53](../../issues/53))
  * Fix bug [#56](../../issues/56)
  * [git diff](../../compare/v1.3.2...v1.4.3)

* v1.3 (2015-06-01)

  * PHP 5.4 is deprecated now ([14 Sep 2015 is end of support](http://php.net/supported-versions.php))
  * `OctoLab\Cilex\Command\Command::getLogger` now return `\Psr\Log\LoggerInterface` ([#36](../../issues/36))
  * `cilex/cilex:~1.0` is required now ([#44](../../issues/44))
  * `composer.lock` was removed ([#46](../../issues/46))
  * Isolate logic of `OctoLab\Cilex\Provider\ConfigServiceProvider` in `OctoLab\Cilex\Config\YamlConfig`
  ([#48](../../issues/48))
  * Isolate logic of `OctoLab\Cilex\Provider\MonologServiceProvider` in `OctoLab\Cilex\Monolog\ConfigResolver`
  ([#51](../../issues/51))
  * [git diff](../../compare/v1.2.3...v1.3.2)

* v1.2 (2015-04-19)

  * Add helper methods in base `Command` class ([#31](../../issues/31))
  * Update dependencies ([#33](../../issues/33))
  * Refactor `MonologServiceProvider` ([#35](../../issues/35))
  * Add `Monolog` `TimeExecutionProcessor` processor ([#39](../../issues/39))
  * Add `Monolog` `Dumper` util ([#40](../../issues/40))
  * [git diff](../../compare/v1.1.4...v1.2.3)

* v1.1 (2014-11-10)

  * You can pass a connection name for `ConnectionHelper` to `DoctrineServiceProvider` ([#20](../../issues/20))
  * Default channel for logging by `Monolog` is the application name ([#21](../../issues/21))
  * `translateLevel` ported from `Silex` ([#18](../../issues/18))
  * All handlers of logging channel placed in the registry, like `dbs` ([#17](../../issues/17))
  * Add support `ConsoleHandler` ([#16](../../issues/16))
  * [git diff](../../compare/v1.0.1...v1.1.4)

* v1.0 (2014-08-30)

  * First stable release
  * [git diff](../../compare/v0.6...v1.0.1)
