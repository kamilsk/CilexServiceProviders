CHANGELOG версии 1.x
====================

* v1.1 (2014-11-10)

 * `DoctrineServiceProvider` можно передавать имя соединения для `ConnectionHelper` (#20)
 * В качестве канала логирования для `Monolog` по умолчанию используется имя приложения (#21)
 * Портирован `translateLevel` из `Silex` (#18)
 * Все подписчики на канал логирования помещены в свой реестр, по аналогии с `dbs` (#17)
 * Добавлена поддержка `ConsoleHandler` (#16)
 * [git diff](https://github.com/kamilsk/CilexServiceProviders/compare/v1.0...v1.1)

* v1.0 (2014-08-30)

 * первый стабильный релиз
