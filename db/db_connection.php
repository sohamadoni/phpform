<?php
$servername = "localhost";
$username = "root";          // Default username for XAMPP is 'root'
$password = "";              // Use your password here if it's set
$dbname = "project"; // Replace with the actual name of your database
$port = 3307;                // Specify the port number

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
