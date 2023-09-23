<!DOCTYPE html>
<html>
<head>
    <title>URL Shortener</title>
</head>
<body>
   <center> 
    <h1>URL Shortener project</h1>
    <?php

    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = 'Shanks0p';
    $db_name = 'url_shortener';


    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);


    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    function generateShortCode() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $short_code = '';
        $length = 6; // Change the length as needed

        for ($i = 0; $i < $length; $i++) {
            $short_code .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $short_code;
    }


    function getExistingShortURL($original_url, $conn) {
        $sql = "SELECT short_code FROM urls WHERE original_url = '$original_url'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $_SERVER['HTTP_HOST'] . '/' . $row['short_code'];
        }

        return false;
    }


    if (isset($_POST['original_url'])) {
        $original_url = $_POST['original_url'];
        $existing_short_url = getExistingShortURL($original_url, $conn);

        if ($existing_short_url) {
            echo "<p>Shortened URL: <a href='$existing_short_url'>$existing_short_url</a></p>";
        } else {
            $short_code = generateShortCode();

            $sql = "INSERT INTO urls (original_url, short_code) VALUES ('$original_url', '$short_code')";

            if ($conn->query($sql) === TRUE) {
                $shortened_url = $_SERVER['HTTP_HOST'] . '/' . $short_code;
                echo "<p>Shortened URL: <a href='$shortened_url'>$shortened_url</a></p>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }


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

    $conn->close();
    ?>

    <form method="POST" action="">
        <input type="url" name="original_url"required style="width:500px;padding:10px;font-size:1.5em;"placeholder="Enter URL to shorten" required>
        <input type="submit" name="submit" value="Shorten">
    </form>
</center>
</body>
</html>

