<?php
session_start();
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'clerk') {
    die("Access denied.");
}
$conn = new mysqli('localhost', 'root', '', 'vault');
$card = $_POST['card_number'];
$cvv = $_POST['cvv'];
$expiry = $_POST['expiry_date'];
$key = 'secretkey';
$sql = "INSERT INTO CreditCards (customer_id, card_number, expiry_date, cvv)
        VALUES (1, AES_ENCRYPT('$card', '$key'), '$expiry', AES_ENCRYPT('$cvv', '$key'))";
if ($conn->query($sql) === TRUE) {
    echo "Card saved successfully.";
} else {
    echo "Error: " . $conn->error;
}
?>
