

<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database credentials

$servername = "css1.seattleu.edu";
$username = "ll_jmaloba";
$password = "5zWmaCpPmINRzc+x";
$dbname = "ll_jmaloba";

// Establish connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Database connection successful!";
}
?>
