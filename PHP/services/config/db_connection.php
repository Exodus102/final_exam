<?php
$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "db_lms";

$conn = new mysqli($localhost, $username, $password, $dbname);

if ($conn->connect_error)
    echo "Error";
