# Monitoring database with postgres_exporter

## Docker Compose
### Start
```shell
docker-compose up -d
```

### Stop
```shell
docker-compose down
```

## Включить\Отключить Xdebug
docker/.env
INSTALL_XDEBUG=true

## Подключение Xdebug в PhpStorm

1. Откройте **File -> Settings -> PHP -> Servers**.
2. Добавьте сервер с именем `Docker`:
    - **Host:** `localhost(127.0.0.1)`
    - **Port:** `888`
    - **Debugger:** `Xdebug`
3. Установите галочку **Use path mappings** и укажите путь:

   ```
   <твой-путь-на-хосте>/project__name/ -> /var/www
   ```
## Войти в php контейнер
```shell
docker compose exec -u www-data php-fpm bash
```
## Взаимодействие с Composer
```shell
docker-compose run --rm composer install
```

## Взаимодействие с bin\console
```shell
docker compose run --rm console cache:clear
```

## Access to PgAdmin
Open in browser [http://localhost:5050](http://localhost:5050)

Пароль и логин по умолчанию вводтся автоматически ничего вводить не нужно.