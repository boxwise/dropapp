[![CircleCI](https://circleci.com/gh/boxwise/dropapp.svg?style=svg)](https://circleci.com/gh/boxwise/dropapp)

# Readme #

You just found the Drop App (first version of [Boxwise](https://www.boxwise.co) - an web-app, which makes it easy for organisations to source, store and distribute donated goods to people in need in a fair and dignified way.

We initially developed it for [Drop In The Ocean](http://www.drapenihavet.no/en/) - a Norwegian NGO who is working in three refugee camps throughout Greece. Our second user is [Intervolve](https://intervolvegr.com/) who is using Drop App in the Koutsochero camp in Larissa, Greece.

We have evolved the app to now be centrally hosted to we can offer the product to many more organisations, and are working to improve the quality of the product. 

To support the development of the new version we started a [crowdfunding campaign](https://donate.boxwise.co)!  
Write or call Hans ([hans@boxwise.co](mailto:hans@boxwise.co) & +4917652181647) if you want to be part of our next step. 

### Preparation for Installation

- Please have a php version higher than 7.2 installed.
- The php extensions `mbstring` and `curl` are needed.

       apt install php-curl php-mbstring

### How do I get set up?

(optional - ToDo Fix bug for Ubuntu users) 
0. Clone this repo into /var/www/html. Also make sure to allow write permissions on templates folder. 

       cd /var/www
       git clone https://github.com/boxwise/dropapp html
       chmod -R 777 html/templates

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

### Database and migrations

If you want to connect to the MySQL server from your host machine, you can do this using

    docker exec -it html_db_mysql_1 mysql -u root -p

If you want to reset it, you should stop docker, delete the files in `/.docker/data/mysql` and call `docker-compose up` again.

We're using [Phinx](https://phinx.org/) and [phinx-migrations-generator](https://github.com/odan/phinx-migrations-generator) to manage database migrations. Running

    vendor/bin/phinx-migrations generate

Will generate a new migration based on the diff of /db/migrations/schema.php.

To connect to the DB, use mysql user `root` with password `dropapp_root`.

### Linting / Auto formatting

So we don't have to think/argue over code conventions, we're using the [php-cs-fixer](https://github.com/FriendsOfPhp/PHP-CS-Fixer) automatic code formatter.

CircleCI will *fail* if there is any code requiring linting fixes. 

If you're using VSCode, the `vscode-php-cs-fixer` extension will be suggested automatically and apply 
auto format on save. 

Alternatively, you can run

     php vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix . --rules @PhpCsFixer

in the root manually. 

### Debugging

We have enabled XDebug remote debugging in the default Docker configuration, so you can step through your code. Please run `docker-compose up --build` next time you start up your server to update your docker image.

If you're using VS Code, if you install the [PHP Debug](https://marketplace.visualstudio.com/items?itemName=felixfbecker.php-debug) extension and start the 'Listen for XDebug' configuration, you can then set breakpoints in your code.

#### For linux users 

Docker containers running on linux cannot resolve the address `host.docker.internal` to an ip-address. To use Xdebug on linux you have to specify the internal ip-address of the docker container in `docker-compose.yaml`.
To find out your internal docker address run 

        docker inspect -f '{{range .NetworkSettings.Networks}}{{.Gateway}}{{end}}' <NAME OF YOUR DOCKER CONTAINER>

Enter the address in `docker-compose.yaml` here:

        environment:
            XDEBUG_CONFIG: remote_host=172.19.0.1 

### Contribution guidelines ###

You gotta be awesome and kind

### Who do I talk to? ###

Right now best talk to [Hans](mailto:hans@boxwise.co)!

### License ###

See the [LICENSE](LICENSE.md) file for license rights and limitations (MIT).
