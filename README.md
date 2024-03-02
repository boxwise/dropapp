[![CircleCI](https://circleci.com/gh/boxwise/dropapp.svg?style=svg)](https://circleci.com/gh/boxwise/dropapp)
<a width="105" height="35" href="https://auth0.com/?utm_source=oss&utm_medium=gp&utm_campaign=oss" target="_blank" alt="Single Sign On & Token Based Authentication - Auth0">
       <img width="105" height="35" alt="JWT Auth for open source projects" src="https://cdn.auth0.com/oss/badges/a0-badge-dark.png"></a>

# Readme #

You just found the Drop App (first version of [Boxtribute](https://www.boxtribute.org) - an web-app, which makes it easy for organisations to source, store and distribute donated goods to people in need in a fair and dignified way). This is currently in the process of being replaced by [Boxtribute 2.0](https://github.com/boxwise/boxtribute).

Dropapp was initially developed for [Drop In The Ocean](http://www.drapenihavet.no/en/) - a Norwegian NGO who is working in three refugee camps throughout Greece. Other users include [Samos Volunteers](https://www.samosvolunteers.org/), [Europe Cares](https://www.europecares.org/), [Hermine](https://mfh.global/hermine/), [The Free Shop Lebanon](https://www.instagram.com/thefreeshoplebanon/), [Intereuropean Human Aid Association (IHA)](https://www.iha.help/) and [Movement on the Ground](https://movementontheground.com).

We have evolved the app to now be centrally hosted so we can offer the product to many more organisations, and are working constantly to improve the system and connect donor and distributor organisations with each other. 

If you are interested in being part of this project, write us at [jointheteam@boxtribute.org](mailto:jointheteam@boxtribute.org)! You can also check out our [website](https://www.boxtribute.org/#join) for more details about the kind of help we need on this project.

### Preparation for Installation

* Install [Docker](https://www.docker.com/products/docker-desktop)
* Install [PHP 8.2 or later](https://www.php.net/downloads.php).
* Ensure you have the `mbstring`, `curl`, `mysql` and `xdebug` PHP extensions installed. On Ubuntu:

       apt install php-curl php-mbstring php-mysql php-xdebug

### How do I get set up?

0. Clone this repo. If you're running Ubuntu, you may need to set write permissions to the templates folder for Docker. 

       git clone https://github.com/boxwise/dropapp
       chmod -R 777 dropapp/templates (not generally recommended - ToDo Fix bug for Ubuntu users) 

1. You first need to install 'composer' (we suggest making it available globally)

       curl -s https://getcomposer.org/installer | php
       mv composer.phar /usr/local/bin/composer

2. You can install the required dependencies then using

       composer install

3. To configure the app, copy `/library/config.php.default` and remove the `.default` in the filename. Then fill the Auth0 credentials from the Auth0 client. Please check [docs/auth0.md](docs/auth0.md) for further information regarding Auth0.

4. To run the application, we assume you have Docker installed. You can then run:

       docker-compose up

   Alternatively, you can run using the PHP development server

       php -S localhost:8000 gcloud-entry.php 

5. To initialize the database for the first time, you should run:

       vendor/bin/phinx migrate -e development
       vendor/bin/phinx seed:run -e development
 
   The first command creates the schema, the second command seeds the database with some dummy data

6. If you want to additionally want to connect the users from the seed to auth0 and populate the db table cms_usergroups_roles then open a browser and request `http://localhost:8100/cron/reseed-auth0.php`

### Accessing the app

Once the docker containers are running the app is accessible at http://localhost:8100/

After this you should be able to login to the app using the password Browser_tests and one of the following emails:

- some.admin@boxtribute.org (God User)

BoxAid (all have access to one base called Lesvos):
- dev_headofops@boxaid.org
- dev_coordinator@boxaid.org
- dev_volunteer@boxaid.org
- warehouse.volunteer@lesvos.org
- freeshop.volunteer@lesvos.org
- library.volunteer@lesvos.org

BoxCare (there are 3 bases associated - Thessaloniki, Samos, Athens):
- dev_headofops@boxcare.org
- dev_coordinator@boxcare.org (Coordinator at bases Thessaloniki and Samos)
- dev_volunteer@boxcare.org (Volunteer at bases Thessaloniki and Samos)
- coordinator@thessaloniki.org
- coordinator@samos.org
- coordinator@athens.org
- volunteer@thessaloniki.
- volunteer@samos.org
- volunteer@athens.org
- warehouse.volunteer@athens.org
- freeshop.volunteer@athens.org
- label@athens.org (only for label creation)


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

#### Phinx migrations

We're using [Phinx](https://phinx.org/) to run database migration and create database seeds.

To migrate to the current database version run

        vendor/bin/phinx migrate

To create an migration run

        vendor/bin/phinx create <NameOfMigrationInCamelCaseFormat>

It creates an file in `db/migrations`. Please use this file to write your db migration.

#### Database seeding

If you want to re-seed your database, just run

        vendor/bin/phinx seed:run -e development

The `ClearMinimalDb` phinx-seeder clears all old tables before re-inserting the seed.

If you want to re-seed the users in Auth0 at the same time, call in any Browser the following script instead of running the command above.

              http://localhost:8100/reseed-db.php





### Cypress and testing

We use [Cypress](https://www.cypress.io) for Browser-test. To run Cypress tests on your local environment, please
1. [Install Cypress via direct Download](https://docs.cypress.io/guides/getting-started/installing-cypress.html#Direct-download)
2. Set the variable `baseURL` to your local address, e.g. `localhost:8100` in cypress.json.
3. Set the env variable `auth0Domain` to the development Auth0 tenant, 
e.g. `boxtribute-dev.eu.auth0.com` in cypress.json.
4. Open Cypress and this repo in Cypress

#### Cypress Tests fail due to unsynchronized users with Auth0

If the tests 2.4 and 2.9 fail, check
- if the user with pauli@pauli.co exists in Auth0 and delete him
- if admin@admin.co has no "m" as a prefix
- if admin@admin.co has no roles assigned
- if testnewuser@ does not exist.

#### Cypress Guidelines

All tests in `cypress/integrations` should be found and can be directly executed. When writing tests, try to follow these guidelines if possible:

+ Avoid any duplication of helper functions across several files! If testing the same page in several test suites (files), there's a tendency to copy-paste the whole file and then rewrite tests. This leads to code duplication of helper functions. Instead, helper functions needed in several locations should be defined in one of `cypress/support` files - then they're available globally. Find the matching one by name or create a new one. In latter case, don't forget to import it in `cypress/integrations/index.js`. Avoid creating miscellaneuos file names as it tends to lead to chaos.
+ Local helper functions defined in test files should have functional and easy-to-understand rather than technical names. Meaning, `clickNewUserButton()` is better than `clickElementByTypeAndTestId('button','new-user-button')`.
+ More general use helpers like 'clickElementByTypeAndTestId' can be used within the local helper functions if preferred. The reason for functional naming preference lies in increased readability of tests.
+ Current codebase doesn't 100% follow everything stated above but it'd definitely help organising the test helpers accordingly from now on.

![Selection_599](https://user-images.githubusercontent.com/8964422/77221481-6a190d00-6b4a-11ea-88d7-9fc70ce1c982.png)

#### Known Cypress Issues
We experienced before that tests can fail in CircleCI, but not in the local environment. The main reason for it is that Cypress is usually executing the commands slower in a local dev environment.
Therefore, a few additional guidelines when writing test:
+ When you want to execute a redirect, e.g. example by clicking a button or tab, please add an assertion after the click, e.g. of the url `cy.url().should('include', 'people_deactivated')`. Due to this assertion cypress will definitely wait until the redirect is executed.  
+ Only if you use `cy.visit()` you can be sure that the cypress test wait until a page is fully loaded. Therefore, try to navigate as much as possible with `cy.visit()`.

### Notes for setting up an Auth0 tenant

If you are setting up a new Auth0 tenant, we require access to the Auth0 Management API. In order to do this

+ Under 'APIs' select Auth0 Management API, go to 'Machine to Machine Applications' and enable access
+ Grant scopes for read/update/delete/create users and users_app_metadata.

### Contribution guidelines ###

You gotta be awesome and kind.
For everything else, please see our [contribution guidelines](https://github.com/boxwise/dropapp/blob/master/CONTRIBUTING.md)

### Who do I talk to? ###

Drop us an email to hello@boxtribute.org!

### License ###

See the [LICENSE](./LICENSE) file for license rights and limitations (Apache 2.0).

