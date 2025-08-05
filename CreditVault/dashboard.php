<?php
session_start();
if (!isset($_SESSION['username'])) {
    echo "<script>alert('You must be logged in to access the dashboard.'); window.location.href='login.html';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard - Credit Vault</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <h2>Insert Credit Card Info</h2>

    <form action="insert_card.php" method="post" id="cardForm">
      <input type="text" name="card_number" placeholder="Card Number" required />
      <input type="text" name="card_holder" placeholder="Card Holder Name" required />
      <input type="text" name="expiry_date" placeholder="Expiry Date (MM/YY)" required />
      <input type="text" name="cvv" placeholder="CVV" required />
      <button type="submit">Insert</button>
    </form>

    <p id="cardMessage"></p>
  
    <?php if (in_array($_SESSION['role'], ['admin', 'auditor'])): ?>
  <form action="view_cards.php" method="get">
    <button type="submit" style="margin-top: 1rem;">View Saved Cards</button>
  </form>
<?php endif; ?>


    <form action="logout.php" method="post">
      <button type="submit" style="margin-top: 1rem; background-color: #dc3545;">Logout</button>
    </form>

  </div>
</body>
</html>
