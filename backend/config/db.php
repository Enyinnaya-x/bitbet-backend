<?php
$servername = "db4free.net"; // db4free host
$username = "bitbetuser"; // your db4free username
$password = "password"; // your db4free password
$dbname = "bitbetdb"; // your db4free database name

$conn = new mysqli($servername, $username, $password, $dbname, 3306); // port 3306 is required

if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
?>