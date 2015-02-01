`config.yml + parameters.yml.dist`

Используйте `config.yml` для хранения настроек, не зависящих от окружения, и `parameters.yml` для их переопределения
в зависимости от конкретного окружения.

1) Добавьте зависимость от [ParameterHandler](https://github.com/Incenteev/ParameterHandler):
```bash
$ composer require incenteev/composer-parameter-handler:~2.0
```
2) Настройте `composer.json`:
```json
"scripts": {
    "post-install-cmd": [
        "Incenteev\\ParameterHandler\\ScriptHandler::buildParameters"
    ],
    "post-update-cmd": [
        "Incenteev\\ParameterHandler\\ScriptHandler::buildParameters"
    ]
},
"extra": {
    "incenteev-parameters": {
        "file": "path/to/parameters.yml"
    }
}
```
3) Создайте файл `path/to/parameters.yml.dist` и пропишите там необходимые параметры:
```yaml
parameters:
    some_parameter: some_value
```
4) Исключите `path/to/parameters.yml` из vcs (например, git):
```bash
$ echo 'path/to/parameters.yml' >> .gitignore
```
5) Используйте эти параметры в `config.yml`:
```yaml
component:
    component_option: %some_parameter%
```
6) Обновите проект:
```bash
$ composer update
```
