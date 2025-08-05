<?php
session_start();

// Only allow admin or auditor to view cards
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'auditor'])) {
    echo "<script>alert('Access denied.'); window.location.href='dashboard.php';</script>";
    exit;
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'vault', 3307);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch session variables
$key = $_SESSION['aes_key'] ?? 'defaultkey';
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// SQL query with role-based filtering
if ($role === 'admin') {
    // Admin sees all records
    $sql = "SELECT c.card_id, cust.full_name, 
                AES_DECRYPT(c.card_number, ?) AS card_number, 
                c.expiry_date, 
                AES_DECRYPT(c.cvv, ?) AS cvv,
                c.inserted_by
            FROM CreditCards c
            JOIN Customers cust ON c.customer_id = cust.customer_id
            ORDER BY c.card_id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $key, $key);
} else {
    // Auditor sees only their own inserted records
    $sql = "SELECT c.card_id, cust.full_name, 
                AES_DECRYPT(c.card_number, ?) AS card_number, 
                c.expiry_date, 
                AES_DECRYPT(c.cvv, ?) AS cvv,
                c.inserted_by
            FROM CreditCards c
            JOIN Customers cust ON c.customer_id = cust.customer_id
            WHERE c.inserted_by = ?
            ORDER BY c.card_id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $key, $key, $username);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Stored Credit Cards - Credit Vault</title>
  <link rel="stylesheet" href="style.css">
  <style>
    table {
      width: 90%;
      margin: 30px auto;
      border-collapse: collapse;
      background: #fff;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 12px;
      text-align: center;
      border-bottom: 1px solid #ccc;
    }
    th {
      background-color: #667eea;
      color: white;
    }
    tr:hover {
      background-color: #f3f3f3;
    }
    h2 {
      text-align: center;
      margin-top: 20px;
    }
    .btn {
      display: block;
      margin: 20px auto;
      padding: 10px 20px;
      background: #667eea;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      text-align: center;
      width: 200px;
    }
    .btn:hover {
      background: #5a67d8;
    }
  </style>
</head>
<body>

<h2>Stored Credit Cards</h2>

<a class="btn" href="dashboard.php">← Back to Dashboard</a>

<table>
  <tr>
    <th>Customer Name</th>
    <th>Card Number</th>
    <th>Expiry Date</th>
    <th>CVV</th>
    <th>Inserted By</th>
  </tr>

<?php
if ($result && $result->num_rows > 0):
    while ($row = $result->fetch_assoc()):
?>
    <tr>
      <td><?= htmlspecialchars($row['full_name']) ?></td>
      <td><?= htmlspecialchars($row['card_number']) ?></td>
      <td><?= htmlspecialchars($row['expiry_date']) ?></td>
      <td><?= htmlspecialchars($row['cvv']) ?></td>
      <td><?= htmlspecialchars($row['inserted_by']) ?></td>
    </tr>
<?php
    endwhile;
else:
?>
    <tr><td colspan="5">No cards found.</td></tr>
<?php endif; ?>

</table>

</body>
</html>
