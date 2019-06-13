# README #

You just found the Drop App (first version of [Boxwise](www.boxwise.co) - an web-app, which makes it easy for organisations to source, store and distribute donated goods to people in need in a fair and dignified way.  
We developed it for [Drop In The Ocean](http://www.drapenihavet.no/en/) - a Norwegian NGO who is working in three refugee camps throughout Greece. Our second user is [Intervolve](https://intervolvegr.com/) who is using Drop App in the Koutsochero camp in Larissa, Greece.  
You will not find a lot of documentation. Installing, running and customizing it will be pretty much straight forward for a developer with knowledge of PHP and Mysql.

### What are our next steps? ###

We consider Drop App our MVP or proof of concept. Based on our experience, we want to start the development of our next version - a web-app where any NGO only need to register and which is running on a single central server. Hopefully, we will also solve the issue of offline usage.  
To support the development of the new version we started a [crowdfunding campaign](https://donate.boxwise.co)!  
Write or call Hans ([hans@boxwise.co](mailto:hans@boxwise.co) & +4917652181647) if you want to be part of our next step. 

### What is this repository for? ###

To service the current version which is still running in three refugee camps throughout Greece. This repository makes it easy for us to work together with a bigger team even if we're not all on the same location.

### How do I get set up? ###

1. You first need to install 'compose' (we suggest making it available globally)

       curl -s https://getcomposer.org/installer | php`
       mv composer.phar /usr/local/bin/composer`

2. You can install the required dependencies then using

       composer install

3. To configure the app, copy `/config.php.default` and remove the `.default` in the filename. The default configuration does not need to change if you are using Docker (see below).

4. To run the application, we assume you have Docker installed. You can then run:

       docker-compose up

5. To initialize the database for the first time, you should run:

       vendor/bin/phinx migrate -e development
       vendor/bin/phinx seed:run -e development
 
   The first command creates the schema, the second command seeds the database with some dummy data

### Accessing the app

Once the docker containers are running the app is accessible at http://localhost:8100/

After this you should be able to login to the app using email address: some.admin@boxwise.co with password: admin

### Database and migrations

If you want to connect to the MySQL server from your host machine, you can do this using

    docker exec -it dropapp_mysql_db_1 mysql -u root -p

If you want to reset it, you should stop docker, delete the files in `/.docker/data/mysql` and call `docker-compose up` again.

We're using (Phinx)[https://phinx.org/] and (phinx-migrations-generator)[https://github.com/odan/phinx-migrations-generator] to manage database migrations. Running

    vendor/bin/phinx-migrations generate

Will generate a new migration based on the diff of /db/migrations/schema.php.

### Contribution guidelines ###

You gotta be awesome and kind

### Who do I talk to? ###

Right now best talk to [Hans](mailto:hans@boxwise.co)!

### License ###

See the [LICENSE](LICENSE.md) file for license rights and limitations (MIT).
