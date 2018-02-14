# Nobelisti

## Installation for development

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
sudo chown :33 src/var -R
```

5. Check the app at [localhost:8080/app_dev.php](http://localhost:8080/app_dev.php).

6. Stop the container politely
```
docker-compose stop
```

## Running in production

We have a separate docker-composer file for production,
to install the production requirements run

```
docker-compose -f docker-compose.prod.yml up --build -d
```

If you do not want to use docker in production, the minimal requirements for this applications are

```
Database client version: libmysql - 10.1.26-MariaDB
PHP version: 7.0
```

A CRON bundle was installed to help run CRON tasks in the production enviroment. To create a cron job, run:
```
docker-compose exec php bash
php bin/console cron:create
```
The following commands for sending emails are available:
- `email:delete:obsolete-registrations` - Deletes expired unclaimed registrations from the database, emptying space for other attendees.
- `email:send:attendee-notification` - Sends reminders to all attendees that an upcoming event is nearing. Should be executed at least once a day.
- `email:send:organizer-notification` - Sends information about registered users to organizers. Should be executed once a day.
- `email:send:registration-open` - Sends notifications that a new event has become available for registration. Should be executed at least once a day.

Setting up CRON tasks has to be done manually, either using CRON externally or using the CRON package available here: https://github.com/Cron/Cron

The commands **have to** be executed from the bin folder.

To install and setup, just follow the provided instructions on the CRON github page.

## Running the tests
```
docker-compose exec php php vendor/phpunit/phpunit/phpunit
```

## Running PHP-CS-Fixer

Run `docker-compose run php ./csfix` script before each commit!

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

### How to create superuser?
1. Connect to the virtual machine `docker-compose exec php bash`
2. `php bin/console admin:create-superuser email@email.com password`
