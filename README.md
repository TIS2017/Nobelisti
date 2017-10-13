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

2. Build and run the container
```
docker-compose up --build
```

3. Get docker's IP and save it to /etc/hosts

Linux:
```
echo $(docker network inspect bridge | grep Gateway | grep -o -E '[0-9\.]+')
```

MAC/Windows:
```
docker-machine ip default
```

Open `/etc/hosts` and append `IP.ADDRESS.FROM.ECHO    symfony.dev`.


4. Change permissions so symfony can save cache, logs & sessions.
```
sudo chmod -R 777 src/var/cache src/var/logs src/var/sessions
```

5. Composer install & create database
```
docker-compose exec php bash
composer install
sf3 doctrine:schema:update --force
```

6. Check the app at [symfony.dev/app_dev.php](http://symfony.dev/app_dev.php).


## Running the tests
```
docker-compose exec php php vendor/phpunit/phpunit/phpunit
```

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
