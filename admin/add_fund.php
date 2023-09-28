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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $month = $_POST['month'];
    $amount = $_POST['amount'];
    $club_id = $_POST['club_id'];

    $insertSql = "INSERT INTO fund (f_no, month, amount, club_id) 
                  VALUES (fund_seq.nextval, :month, :amount, :club_id)";

    $stmt = oci_parse($conn, $insertSql);

    oci_bind_by_name($stmt, ':month', $month);
    oci_bind_by_name($stmt, ':amount', $amount);
    oci_bind_by_name($stmt, ':club_id', $club_id);

    $result = oci_execute($stmt);

    if ($result) {
        $_SESSION['successMessage'] = "Fund added successfully!";
    } else {
        $_SESSION['errorMessage'] = "Error adding fund: " . oci_error($stmt);
    }

    oci_free_statement($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <title>Add Fund</title>
</head>
<body>
  <div class="container">
    <h2>Add Fund</h2>
    <?php if (isset($_SESSION['successMessage'])): ?>
      <div class="alert alert-success"><?php echo $_SESSION['successMessage']; ?></div>
      <?php unset($_SESSION['successMessage']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['errorMessage'])): ?>
      <div class="alert alert-danger"><?php echo $_SESSION['errorMessage']; ?></div>
      <?php unset($_SESSION['errorMessage']); ?>
    <?php endif; ?>
    <form method="POST" action="add_fund.php">
      <div class="form-group">
        <label for="month">Month:</label>
        <input type="date" id="month" name="month" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="club_id">Club:</label>
        <select id="club_id" name="club_id" class="form-control" required>
          <option value="1">Photography Club</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Add Fund</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
