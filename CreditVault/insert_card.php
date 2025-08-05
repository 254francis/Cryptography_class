<?php
session_start();

// Only admin or clerk can insert cards
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'clerk'])) {
    die("Access denied.");
}

// Use the AES key from the logged-in user's session
$key = $_SESSION['aes_key'] ?? 'defaultkey';
$inserted_by = $_SESSION['username'];

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'vault', 3307);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect and sanitize form inputs
$card_number = $_POST['card_number'] ?? '';
$card_holder = $_POST['card_holder'] ?? '';
$expiry = $_POST['expiry_date'] ?? '';
$cvv = $_POST['cvv'] ?? '';

// Basic validation
if (!$card_number || !$card_holder || !$expiry || !$cvv) {
    die("All fields are required.");
}

// Step 1: Find or create the customer
$customer_id = null;
$stmt = $conn->prepare("SELECT customer_id FROM Customers WHERE full_name = ?");
$stmt->bind_param("s", $card_holder);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $customer_id = $result->fetch_assoc()['customer_id'];
} else {
    $insert_customer = $conn->prepare("INSERT INTO Customers (full_name, email) VALUES (?, '')");
    $insert_customer->bind_param("s", $card_holder);
    $insert_customer->execute();
    $customer_id = $insert_customer->insert_id;
}

// Step 2: Insert card with per-user AES encryption + inserted_by
$stmt = $conn->prepare("INSERT INTO CreditCards (customer_id, card_number, expiry_date, cvv, inserted_by)
                        VALUES (?, AES_ENCRYPT(?, ?), ?, AES_ENCRYPT(?, ?), ?)");
$stmt->bind_param("issssss", $customer_id, $card_number, $key, $expiry, $cvv, $key, $inserted_by);

if ($stmt->execute()) {
    echo "<script>alert('Card saved successfully.'); window.location.href='dashboard.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>
