# ﷽

# Terawhere API Server

- Laravel 5.4
- MySQL 5.6

## Development Setup
### Docker Compose Setup
- Install Docker and Docker Compose on your local system
- Install MySQL via Docker: `docker run --name terawhere-mysql -e MYSQL_ROOT_PASSWORD=password -d -p 13306:3306 mysql:5.6`
- Create Database `terawhere`: `docker run -it --link terawhere-mysql:mysql --rm mysql:5.6 sh -c 'exec mysql -h"$MYSQL_PORT_3306_TCP_ADDR" -P"$MYSQL_PORT_3306_TCP_PORT" -uroot -p"$MYSQL_ENV_MYSQL_ROOT_PASSWORD" -e "create database terawhere"'`
- Copy `.env.dev` to `.env`
- Configure `.env` accordingly to point to correct mysql
- Run `docker-compose run --rm -w /var/www phpfpm php docker/composer.phar install`
- Run `docker-compose run --rm -w /var/www phpfpm php artisan migrate`
- Run `docker-compose run --rm -w /var/www phpfpm php artisan key:generate`
- Run `docker-compose run --rm -w /var/www node yarn install`
- Start the containers: `docker-compose up`
- Edit your system host file (`C:\Windows\System32\Drivers\etc\hosts` or `/etc/hosts`) and add `127.0.0.1 terawhere.local` (change `127.0.0.1` to reflect your Docker configuration if needed)

## Development

### Running artisan
- `docker-compose run --rm -w /var/www phpfpm php artisan`
- i.e. `tinker`: `docker-compose run --rm -w /var/www phpfpm php artisan tinker`

### Running composer
- `docker-compose run --rm -w /var/www phpfpm php docker/composer.phar`
- i.e. `require laravel/socialite`: `docker-compose run --rm -w /var/www phpfpm php docker/composer.phar require laravel/socialite`

### Running gulp
- `docker-compose run --rm -w /var/www gulp gulp`

### Running npm install
- `docker-compose run --rm -w /var/www gulp npm install`

### Running bower install
- `docker-compose run --rm -w /var/www gulp bower --allow-root install`

### Access Bash Shell inside container
- `docker exec -it terawherelaravel_phpfpm_1 bash`
- `terawherelaravel_phpfpm_1` is the name of the container and might be different.

### Committing
- Run `docker-compose run --rm -w /var/www phpfpm bash -c "chmod 777 tools/fmt && tools/fmt"` script before committing to ensure consistency in code formatting