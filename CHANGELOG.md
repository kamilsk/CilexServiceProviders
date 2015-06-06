CHANGELOG versions 1.x
======================

* v1.4 (2015-06-06)

 * Add support for .php and .json config files ([#27](../../issues/27))
 * Constant supports by `\OctoLab\Cilex\Config\YamlConfig` ([#42](../../issues/42))
 * Add support for [Dipper](https://github.com/secondparty/dipper) as alternative of `\Symfony\Component\Yaml\Parser`
 ([#50](../../issues/50))
 * Add `\OctoLab\Cilex\Doctrine\FileBasedMigration`, `\OctoLab\Cilex\Doctrine\DriverBasedMigration` and
 `\OctoLab\Cilex\Doctrine\Command\CheckMigrationCommand` ([#52](../../issues/52))
 * Add `\OctoLab\Cilex\Doctrine\Command\GenerateIndexNameCommand` ([#53](../../issues/53))
 * [git diff](../../compare/v1.3.2...v1.4.1)

* v1.3 (2015-06-01)

 * PHP 5.4 is deprecated now ([14 Sep 2015 is end of support](http://php.net/supported-versions.php))
 * `\OctoLab\Cilex\Command\Command::getLogger` now return `\Psr\Log\LoggerInterface` ([#36](../../issues/36))
 * `cilex/cilex:~1.0` is required now ([#44](../../issues/44))
 * `composer.lock` was removed ([#46](../../issues/46))
 * Isolate logic of `\OctoLab\Cilex\Provider\ConfigServiceProvider` in `\OctoLab\Cilex\Config\YamlConfig`
 ([#48](../../issues/48))
 * Isolate logic of `\OctoLab\Cilex\Provider\MonologServiceProvider` in `\OctoLab\Cilex\Monolog\ConfigResolver`
 ([#51](../../issues/51))
 * [git diff](../../compare/v1.2.3...v1.3.2)

* v1.2 (2015-04-19)

 * Add helper methods in base Command class ([#31](../../issues/31))
 * Update dependencies ([#33](../../issues/33))
 * Refactor MonologServiceProvider ([#35](../../issues/35))
 * Add Monolog TimeExecutionProcessor processor ([#39](../../issues/39))
 * Add Monolog Dumper util ([#40](../../issues/40))
 * [git diff](../../compare/v1.1.4...v1.2.3)

* v1.1 (2014-11-10)

 * `DoctrineServiceProvider` можно передавать имя соединения для `ConnectionHelper` ([#20](../../issues/20))
 * В качестве канала логирования для `Monolog` по умолчанию используется имя приложения ([#21](../../issues/21))
 * Портирован `translateLevel` из `Silex` ([#18](../../issues/18))
 * Все подписчики на канал логирования помещены в свой реестр, по аналогии с `dbs` ([#17](../../issues/17))
 * Добавлена поддержка `ConsoleHandler` ([#16](../../issues/16))
 * [git diff](../../compare/v1.0.1...v1.1.4)

* v1.0 (2014-08-30)

 * First stable release
 * [git diff](../../compare/v0.6...v1.0.1)
