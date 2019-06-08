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

You can get MySQL running locally using the following:

    docker run -p 3306:3306 --name mysql01 -e MYSQL_ROOT_PASSWORD=XYZ -e MYSQL_ROOT_HOST=% -d mysql/mysql-server:latest

Create a new database and import market_clean.sql

Then copy .htaccess.default and /config.php.default and remove the .default in the filename. Then change the necesary usernames, folders and database settings. You can use the root password you specified above if running locally.

### Running the app ###

To start the app using the PHP Development Server, run

    php -S localhost:8080 -c php.ini

After this you should be able to login to the app using emailaddress: demo@example.com with password: demo

### Contribution guidelines ###

You gotta be awesome and kind

### Who do I talk to? ###

Right now best talk to [Hans](mailto:hans@boxwise.co)!

### License ###

See the [LICENSE](LICENSE.md) file for license rights and limitations (MIT).
