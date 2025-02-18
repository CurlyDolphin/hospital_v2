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

## Access to PgAdmin
Open in browser [http://localhost:5050](http://localhost:5050)

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
docker-compose run --rm composer install

## Взаимодействие с bin\console
docker compose run --rm console cache:clear