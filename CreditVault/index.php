<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Credit Card Vault</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2>Welcome, <?php echo $_SESSION['username']; ?> (<?php echo $_SESSION['role']; ?>)</h2>
    <form action="insert_card.php" method="post">
        <div class="mb-3">
            <label class="form-label">Card Number</label>
            <input type="text" name="card_number" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Expiry Date</label>
            <input type="date" name="expiry_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">CVV</label>
            <input type="text" name="cvv" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Encrypt & Save</button>
    </form>
</body>
</html>
