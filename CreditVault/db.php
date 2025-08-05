<?php
// Database credentials
$host = "localhost";
$user = "root";
$password = ""; // unless you set one
$database = "vault";

// Create connection
$conn = new mysqli($host, $user, $password, $database, 3307);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<?php
// db.php - Database connection file
// This file is included in other scripts to establish a database connection
