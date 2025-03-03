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
    - **Port:** `8081`
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

Пароль и логин вводятся автоматически, ничего вводить не нужно.

## Доступ к Swagger
Open in browser [http://localhost:8081/api/doc](http://localhost:888/api/doc)

## Подключение к Postgres в .env

DATABASE_URL="postgresql://doctor:angina@postgres:5433/hospital?serverVersion=13&charset=utf8"
     
## Взаимодействие с php cs fixer
```shell
docker exec -it php-fpm php vendor/bin/php-cs-fixer fix
```

## Взаимодействие с phpstan
```shell
docker exec -it php-fpm php vendor/bin/phpstan analyse src
```

## Настройка cs fixer для РНРStorm
https://youtu.be/9vJ0vAnAcSU?si=NmgKarJOQ5-FSrz-&t=310

## История развития проекта

https://github.com/CurlyDolphin/miv_v2

После того как прееделал docker

https://github.com/CurlyDolphin/hospital_v2