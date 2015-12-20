CHANGELOG for 2.x
=================

* v2.0 (2015-xx-xx)
  * Remove all deprecated functionality
    * Classes have been moved
      * Commands
        * `CheckMigrationCommand` from `OctoLab\Cilex\Doctrine\Command` to `OctoLab\Cilex\Command\Doctrine`
        * `GenerateIndexNameCommand` from `OctoLab\Cilex\Doctrine\Command` to `OctoLab\Cilex\Command\Doctrine`
      * Migrations
        * `DriverBasedMigration` from `OctoLab\Cilex\Doctrine` to `OctoLab\Common\Doctrine\Migration`
        * `FileBasedMigration` from `OctoLab\Cilex\Doctrine` to `OctoLab\Common\Doctrine\Migration`
      * Processors
        * `TimeExecutionProcessor` from `OctoLab\Cilex\Monolog\Processor` to `OctoLab\Common\Monolog\Processor`
      * Configs
        * Namespace moved from `OctoLab\Cilex\Config` to `OctoLab\Common\Config`
      * Utils
        * `Parser` from `OctoLab\Cilex\Doctrine\Util` to `OctoLab\Common\Doctrine\Util`
        * `Dumper` from `OctoLab\Cilex\Monolog\Util` to `OctoLab\Common\Monolog\Util`
      * Service providers
        * `ConfigServiceProvider` from `OctoLab\Cilex\Provider` to `OctoLab\Cilex\ServiceProvider`
        * `DoctrineServiceProvider` from `OctoLab\Cilex\Provider` to `OctoLab\Cilex\ServiceProvider`
        * `MonologServiceProvider` from `OctoLab\Cilex\Provider` to `OctoLab\Cilex\ServiceProvider`
      * Helpers
        * `Monolog` `ConfigResolver` from `OctoLab\Cilex\Monolog` to `OctoLab\Common\Monolog\Util`
    * `OctoLab\Cilex\Command\Command` has been optimized
      * extends `Symfony\Component\Console\Command\Command` instead of `Cilex\Command\Command`
      * `setOutputInterface` was removed, use `initConsoleHandler` instead
    * Monolog's configuration was changed
      * `path`, `level` and `bubble` became part of `arguments`
      * `formatter` now is not a alias, use `{ type: ... }` notation instead
    * Config component changes
      * `$parser` is required argument for `OctoLab\Cilex\Config\Loader\YamlFileLoader::__construct()`
      * `$app` was removed from `OctoLab\Cilex\Monolog\ConfigResolver`
  * [git diff](../../compare/1.x...master)

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
