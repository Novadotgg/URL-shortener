# This is a project to shorten the URL.
## The following will guide you to get used to my URL shortener...
Welcome to the URL-shortener wiki! This is a site created in PHP that will shorten the given long URL to a URL with a fewer alphanumeric characters randomly with a fixed size and while clicked will redirect to the original site... I did this using lamp stack. So let's get started:

## Install the lamp stack
In lamp l is linux, a means Apache, m means MySql or Mongodb and p means PHP/Python/Pearl.. If you already have linux running in your PC its good to go or get linux to operate in your PC, you can do this this through other OS too but in my case I used Ubuntu.

## Install apache web server
Before that: <br>run `sudo apt update`<br>
then run `sudo apt install apache2`
During installation, Apache registers itself with UFW to provide a few application profiles that can be used to enable or disable access to Apache through the firewall. List the ufw application profiles by typing:sudo ufw app list
You will receive a list of the application profiles. It is recommended that you enable the most restrictive profile that will still allow the traffic you’ve configured. Since we haven’t configured SSL for our server yet in this guide, we will only need to allow traffic on port 80:<br>
run `sudo ufw allow 'Apache'`


## Install mysql server:
run `sudo apt install mysql-server`
then you can start the mysql services by running `sudo systemctl start mysql.service`


## Instal PHP:
run `sudo apt install php8.2`
or better do:
run `sudo apt install php libapache2-mod-php php-mysql`
This will install the following 3 packages php - installs PHP, libapache2-mod-php - Used by apache to handle PHP files, php-mysql - PHP module that allows PHP to connect to MySQL

Add PHPmyadmin for the database:
run `sudo apt install phpmyadmin`


## Database:
Set up the username and password for the phpmyadmin. Open the database and create table with id, original_url and short_code. or type `mysql -uroot -p`<br>
Enter the password and write query:
> CREATE DATABASE url_shortener; 
>
> USE url_shortener; 
>
> CREATE TABLE urls ( id INT AUTO_INCREMENT PRIMARY KEY, original_url VARCHAR(255) NOT NULL, short_code VARCHAR(10) NOT NULL );
### Connection to the database:


