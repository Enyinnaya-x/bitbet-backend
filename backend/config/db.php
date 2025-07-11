<?php

$servername = "sql3.freesqldatabase.com";  // use the actual host shown
$username = "sql3789442";                 // your actual username
$password = "Ri2Fn4ZcCW";               // your actual password
$dbname = "sql3789442";                   // your DB name (usually same as username)

$conn = new mysqli($servername, $username, $password, $dbname, 3306);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
