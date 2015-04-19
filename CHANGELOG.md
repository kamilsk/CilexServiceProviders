CHANGELOG versions 1.x
======================

* v1.2 (2015-04-19)

 * Add helper methods in base Command class ([#31](../../issues/31))
 * Update dependencies ([#33](../../issues/33))
 * Refactor MonologServiceProvider ([#35](../../issues/35))

* v1.1 (2014-11-10)

 * `DoctrineServiceProvider` можно передавать имя соединения для `ConnectionHelper` ([#20](../../issues/20))
 * В качестве канала логирования для `Monolog` по умолчанию используется имя приложения ([#21](../../issues/21))
 * Портирован `translateLevel` из `Silex` ([#18](../../issues/18))
 * Все подписчики на канал логирования помещены в свой реестр, по аналогии с `dbs` ([#17](../../issues/17))
 * Добавлена поддержка `ConsoleHandler` ([#16](../../issues/16))
 * [git diff](../../compare/v1.0...v1.1)

* v1.0 (2014-08-30)

 * First stable release
