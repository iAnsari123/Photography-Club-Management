<?php
session_start();

// Check if the user is logged in
if ($_SESSION['name'] != 'admin') {
    header("Location: login.php");
    exit();
}

$conn = oci_connect("ahnaf", "123", "localhost/XE");
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Fetch fund data
$fundSql = "SELECT f.f_no, f.month, f.amount, c.club_name
            FROM fund f
            JOIN club c ON f.club_id = c.club_id";
$fundResult = oci_parse($conn, $fundSql);
oci_execute($fundResult);

// Calculate total fund
$totalFundSql = "SELECT SUM(amount) AS total_fund FROM fund";
$totalFundResult = oci_parse($conn, $totalFundSql);
oci_execute($totalFundResult);
$totalFundRow = oci_fetch_assoc($totalFundResult);
$totalFund = $totalFundRow['TOTAL_FUND'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <title>View Funds</title>
</head>
<body>
  <div class="container">
    <h2>View Funds</h2>
    <p>Total Fund Available: $<?php echo $totalFund; ?></p>
    <table class="table">
      <thead>
        <tr>
          <th>Fund ID</th>
          <th>Month</th>
          <th>Amount</th>
          <th>Club Name</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($fundRow = oci_fetch_assoc($fundResult)): ?>
          <tr>
            <td><?php echo $fundRow['F_NO']; ?></td>
            <td><?php echo $fundRow['MONTH']; ?></td>
            <td><?php echo $fundRow['AMOUNT']; ?></td>
            <td><?php echo $fundRow['CLUB_NAME']; ?></td>
            <td>
              <a href="edit_fund.php?fund_id=<?php echo $fundRow['F_NO']; ?>" class="btn btn-primary">Edit</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

