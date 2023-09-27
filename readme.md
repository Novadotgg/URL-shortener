# This is a project to shorten the URL.
## The following will guide you to get used to my URL shortener...
Welcome to the URL-shortener wiki! This is a site created in PHP that will shorten the given long URL to a URL with a fewer alphanumeric characters randomly with a fixed size and while clicked will redirect to the original site... I did this using lamp stack. So let's get started:

## Install the lamp stack
In lamp l is linux, a means Apache, m means MySql or Mongodb and p means PHP/Python/Pearl.. If you already have linux running in your PC its good to go or get linux to operate in your PC, you can do this this through other OS too but in my case I used Ubuntu.

## Install apache web server
Before that: <br>run
~~~
sudo apt update
~~~
<br>
then run 
~~~
sudo apt install apache2
~~~
During installation, Apache registers itself with UFW to provide a few application profiles that can be used to enable or disable access to Apache through the firewall. List the ufw application profiles by typing:sudo ufw app list
You will receive a list of the application profiles. It is recommended that you enable the most restrictive profile that will still allow the traffic you’ve configured. Since we haven’t configured SSL for our server yet in this guide, we will only need to allow traffic on port 80:<br>
run 
~~~
sudo ufw allow 'Apache'
~~~


## Install mysql server:
run 
~~~
sudo apt install mysql-server
~~~
then you can start the mysql services by running `sudo systemctl start mysql.service`


## Instal PHP:
run 
~~~
sudo apt install php8.2
~~~
or better do:
run
~~~
sudo apt install php libapache2-mod-php php-mysql
~~~
This will install the following 3 packages php - installs PHP, libapache2-mod-php - Used by apache to handle PHP files, php-mysql - PHP module that allows PHP to connect to MySQL

Add PHPmyadmin for the database:
run 
~~~
sudo apt install phpmyadmin
~~~


## Database:
Set up the username and password for the phpmyadmin. Open the database and create table with id, original_url and short_code. or type `mysql -uroot -p`<br>
Enter the password and write query:
~~~
CREATE DATABASE url_shortener; 
USE url_shortener; 
 CREATE TABLE urls ( id INT AUTO_INCREMENT PRIMARY KEY, original_url VARCHAR(255) NOT NULL, short_code VARCHAR(10) NOT NULL );
~~~
### This is my basic php plus html form:
~~~
<!DOCTYPE html>
<html>
<head>
    <title>URL Shortener</title>
</head>
<body>
<center>
    <h1>URL Shortener</h1>
    <?php
~~~
> The main logic was inserted here and is discussed later on in this readme.md file...
~~~
?>
<form method="POST" action="">
        <input type="url" name="original_url"required style="width:500px;padding:10px;font-size:1.5em;"placeholder="Enter URL to shorten" required>
        <input type="submit" name="submit" value="Shorten">
</center>
</body>
</html>

~~~
### Connection to the database:
~~~
$db_host = 'localhost'; 
$db_user = 'root';
$db_pass = ''; 
$db_name = 'url_shortener';
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
~~~
<br><br>
My database name was url_shortener.
If connection is established with the database it will create a random short code of alphanumeric characters of length 6 or else it will display an error as it couldn't connect. <br>

`if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }`



    function generateShortCode() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $short_code = '';
        $length = 6; 

        for ($i = 0; $i < $length; $i++) {
            $short_code .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $short_code;
    }
### Connecting the original URL to their shortened URL:
~~~
if (isset($_POST['original_url'])) {
        $original_url = $_POST['original_url'];
        $existing_short_url = getExistingShortURL($original_url, $conn);

        if ($existing_short_url) {
            echo "<p>Shortened URL: <a href='http://$existing_short_url'>$existing_short_url</a></p>";
        } else {
            $short_code = generateShortCode();

            $sql = "INSERT INTO urls (original_url, short_code) VALUES ('$original_url', '$short_code')";

            if ($conn->query($sql) === TRUE) {
                $shortened_url = $_SERVER['HTTP_HOST'] . '/' . $short_code;
                echo "<p>Shortened URL: <a href='http://$shortened_url'>$shortened_url</a></p>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
~~~
  
### Checking if there is any short_code for the existing URL: 
~~~
function getExistingShortURL($original_url, $conn) {
        $sql = "SELECT short_code FROM urls WHERE original_url = '$original_url'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $_SERVER['HTTP_HOST'] . '/' . $row['short_code'];
        }

        return false;
    }
~~~
### Returning the short code to the original URL (Simply Redirecting):
~~~
if (isset($_GET['code'])) {
        $short_code = $_GET['code'];
        $sql = "SELECT original_url FROM urls WHERE short_code = '$short_code'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $original_url = $row['original_url'];
            header("Location: $original_url");
            exit;
        } else {
            echo "URL not found.";
        }
    }
~~~
Therefore the PHP script ends here using : 
~~~
$conn->close();
~~~
## .htaccess:
.htaccess file can be used to manipulate behaviour of the site...
There are certain rules that can be written on .htaccess file as per requirement...
For that: <br>
go to `/etc/apache2/apache2.conf` <br>
and change `AllowOverride none` to `AllowOverride All` in `<Directory /var/www/>` in the `apache2.conf` file.
~~~
<Directory /var/www/>
	Options Indexes FollowSymLinks
	AllowOverride All
	Require all granted
</Directory>
~~~
<br>
Write the rules to the .htaccess file
The .htasscess file with written rule: 

~~~
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ project.php?code=$1 [L,QSA]
~~~

Now restart the Apache server...
Now save all the files in ` /var/www/html ` 
and go to browser type `127.0.0.1/filename.php` and hit `enter`.
Enter the Long URL and click shorten <br>
You will be given a corresponding short URL on the main page. <br>
When you click the short URL, you will be redirected to the original URL link...



