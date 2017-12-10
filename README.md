# Nobelisti

## Instalation

0. Ensure you have installed [docker](https://docs.docker.com/engine/installation/) and [docker-compose](https://docs.docker.com/compose/install/)
```
$ docker --version
Docker version 17.06.2-ce, build cec0b72
$ docker-compose --version
docker-compose version 1.16.1, build 6d1ac21
```

1. Clone the project
```
git clone git@github.com:TIS2017/Nobelisti.git
```

2. Build and run the container as daemon
```
docker-compose up --build -d
```

3. Composer install & create database
```
docker-compose exec php bash
composer install
php bin/console doctrine:migrations:migrate
```

4. Change permissions so symfony can save cache, logs & sessions.
```
sudo chmod -R 777 src/var/cache src/var/logs src/var/sessions
```

5. Check the app at [localhost:8080/app_dev.php](http://localhost:8080/app_dev.php).

6. Stop the container politely
```
docker-compose stop
```

## Running the tests
```
docker-compose exec php php vendor/phpunit/phpunit/phpunit
```
## Running PHP-CS-Fixer

Run `csfix` script before each commit!

## FAQ

```
$ docker-compose exec php bash
ERROR: No container found for php_1
```
You have to run `docker-compose up -d` in advance or run `docker-compose up` simultaneously in other terminal.


```
RuntimeException
Unable to write in the cache directory (/var/www/symfony/var/cache/dev)
```
Do step 4 again.


```
ClassNotFoundException

```
1. Connect to the virtual machine `docker-compose exec php bash`
2. Run `composer dumpautoload` in the root directory
3. Take a look at: https://stackoverflow.com/questions/44946911/symfony3-classnotfoundexception-after-bundle-creation

### How to make migrations and migrate?
1. Connect to the virtual machine `docker-compose exec php bash`
2. Run `php bin/console doctrine:migrations:diff`
3. Run `php bin/console doctrine:migrations:migrate`
