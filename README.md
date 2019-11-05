[![CircleCI](https://circleci.com/gh/boxwise/dropapp.svg?style=svg)](https://circleci.com/gh/boxwise/dropapp)

# Readme #

You just found the Drop App (first version of [Boxwise](https://www.boxwise.co) - an web-app, which makes it easy for organisations to source, store and distribute donated goods to people in need in a fair and dignified way.

We initially developed it for [Drop In The Ocean](http://www.drapenihavet.no/en/) - a Norwegian NGO who is working in three refugee camps throughout Greece. Other users are [Intervolve](https://intervolvegr.com/), [R4R](https://www.refugees4refugees.gr) and [IHA](iha.help.

We have evolved the app to now be centrally hosted to we can offer the product to many more organisations, and are working to improve the quality of the product. 

To support the development of the new version we started a [crowdfunding campaign](https://donate.boxwise.co)!  
Write or call Hans ([hans@boxwise.co](mailto:hans@boxwise.co) & +4917652181647) if you want to be part of our next step. 

### Preparation for Installation

* Install [Docker](https://www.docker.com/products/docker-desktop)
* Install [PHP 7.2 or later](https://www.php.net/downloads.php).
* Ensure you have the `mbstring` and `curl` PHP extensions installed. On Ubuntu:

       apt install php-curl php-mbstring

### How do I get set up?

0. Clone this repo. If you're running Ubuntu, you may need to set write permissions to the templates folder for Docker. 

       git clone https://github.com/boxwise/dropapp
       chmod -R 777 dropapp/templates (not generally recommended - ToDo Fix bug for Ubuntu users) 

1. You first need to install 'compose' (we suggest making it available globally)

       curl -s https://getcomposer.org/installer | php
       mv composer.phar /usr/local/bin/composer

2. You can install the required dependencies then using

       composer install

3. To configure the app, copy `/library/config.php.default` and remove the `.default` in the filename. The default configuration does not need to change if you are using Docker (see below).

4. To run the application, we assume you have Docker installed. You can then run:

       docker-compose up

   Alternatively, you can run using the PHP development server

       GOOGLE_CLOUD_PROJECT=xxx php -S localhost:8000 gcloud-entry.php 

5. To initialize the database for the first time, you should run:

       vendor/bin/phinx migrate -e development
       vendor/bin/phinx seed:run -e development
 
   The first command creates the schema, the second command seeds the database with some dummy data

### Accessing the app

Once the docker containers are running the app is accessible at http://localhost:8100/

After this you should be able to login to the app using email address: some.admin@boxwise.co with password: admin

### Our dev environment recommendation

Most of us use VSCode as a code editor and MySQL workbench for database access.

### Linting / Auto formatting

So we don't have to think/argue over code conventions, we're using the [php-cs-fixer](https://github.com/FriendsOfPhp/PHP-CS-Fixer) automatic code formatter.

CircleCI will *fail* your Pull-Request if there is any code requiring linting fixes. 

If you're using VSCode, the `vscode-php-cs-fixer` extension will be suggested automatically and apply 
auto format on save. 

Alternatively, you can run

     php vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix . --rules @PhpCsFixer

in the root manually. 

### Debugging

We have enabled XDebug remote debugging in the default Docker configuration, so you can step through your code. Please run `docker-compose up --build` next time you start up your server to update your docker image.

If you're using VS Code, if you install the [PHP Debug](https://marketplace.visualstudio.com/items?itemName=felixfbecker.php-debug) extension and start the 'Listen for XDebug' configuration, you can then set breakpoints in your code.

Please be aware that only breakpoints are caught which sit in a line with executable code.

#### Debugging For linux users 

Docker containers running on linux cannot resolve the address `host.docker.internal` to an ip-address. To use Xdebug on linux you have to specify the internal ip-address of the docker container in `docker-compose.yaml`.
To find out your internal docker address run 

        docker inspect -f '{{range .NetworkSettings.Networks}}{{.Gateway}}{{end}}' <NAME OF YOUR DOCKER CONTAINER>

Enter the address in `docker-compose.yaml` here:

        environment:
            XDEBUG_CONFIG: remote_host=172.19.0.1 


### Database and migrations

#### Command-line access

If you want to connect to the MySQL server from your host machine, you can do this using

    docker exec -it <name of the db docker container> mysql -u root -p

The mysql server in your docker container is also reachable on port 9906 of your localhost

    mysql --host=127.0.0.1 --port=9906 -u root -p

The password for the root-user for the db `dropapp_dev` is `dropapp_root`.

#### MySQL workbench access

Most of use use workbench to acces the MySQL database. To establish a connection you need to enter your `localhost`-address, e.g. `127.0.0.1`, for 'Hostname' and `9906` for 'Port'.

#### Phinx migrations and seeds

We're using [Phinx](https://phinx.org/) to run database migration and create database seeds.
To create an migration run

        vendor/bin/phinx create <NameOfMigrationInCamelCaseFormat>

It creates an file in `db/migrations`. Please use this file to write your db migration.

#### Re-seed your database

If you want to re-seed your database, just run

        vendor/bin/phinx seed:run -e development

The `ClearMinimalDb` phinx-seeder clears all old tables before re-inserting the seed.

### Cypress and testing

We use [Cypress](https://www.cypress.io) for Browser-test. To run Cypress tests on your local environment, please
1. [Install Cypress via direct Download](https://docs.cypress.io/guides/getting-started/installing-cypress.html#Direct-download)
2. Set the variable `baseURL` to your local address, e.g. `localhost:8100` in cypress.json.
3. Open Cypress and this repo in Cypress

All tests in `cypress/integrations` should be found and can be directly executed. 

### Contribution guidelines ###

You gotta be awesome and kind.
For everything else, please see our [contribution guidelines](https://github.com/boxwise/dropapp/blob/master/CONTRIBUTING.md)

### Who do I talk to? ###

Right now best talk to [Hans](mailto:hans@boxwise.co)!

### License ###

See the [LICENSE](LICENSE.md) file for license rights and limitations (MIT).
