<?php
$conn = new mysqli('localhost', 'root', '', 'vault',3307);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "✅ MySQL connection successful!";
}
?>
