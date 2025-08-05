<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        die("Missing username or password.");
    }

    // Fetch user including aes_key
    $query = $conn->prepare("SELECT * FROM Users WHERE username = ? AND password_hash = SHA2(?, 256)");
    $query->bind_param("ss", $username, $password);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // ✅ Store all login session data
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $row['role'];
        $_SESSION['aes_key'] = $row['aes_key']; // 🔐 Store the user-specific AES key

        header("Location: dashboard.php"); // use .php if it has PHP logic
        exit;
    } else {
        echo "<script>alert('Invalid login'); window.location.href='login.html';</script>";
    }
}
?>
