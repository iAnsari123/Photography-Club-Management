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

$fund_id = $_GET['fund_id'];

// Fetch fund data
$fundSql = "SELECT f.f_no, f.month, f.amount, c.club_name
            FROM fund f
            JOIN club c ON f.club_id = c.club_id
            WHERE f.f_no = :fund_id";
$fundResult = oci_parse($conn, $fundSql);
oci_bind_by_name($fundResult, ':fund_id', $fund_id);
oci_execute($fundResult);
$fundRow = oci_fetch_assoc($fundResult);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = $_POST['amount'];

    $updateSql = "UPDATE fund 
                  SET amount = :amount
                  WHERE f_no = :fund_id";

    $stmt = oci_parse($conn, $updateSql);

    oci_bind_by_name($stmt, ':amount', $amount);
    oci_bind_by_name($stmt, ':fund_id', $fund_id);

    $result = oci_execute($stmt);

    if ($result) {
        $_SESSION['successMessage'] = "Fund details updated successfully!";
    } else {
        $_SESSION['errorMessage'] = "Error updating fund details: " . oci_error($stmt);
    }

    oci_free_statement($stmt);
    header("Location: edit_fund.php?fund_id=$fund_id");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <title>Edit Fund</title>
</head>
<body>
  <div class="container">
    <h2>Edit Fund</h2>
    <?php if (isset($_SESSION['successMessage'])): ?>
      <div class="alert alert-success"><?php echo $_SESSION['successMessage']; ?></div>
      <?php unset($_SESSION['successMessage']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['errorMessage'])): ?>
      <div class="alert alert-danger"><?php echo $_SESSION['errorMessage']; ?></div>
      <?php unset($_SESSION['errorMessage']); ?>
    <?php endif; ?>
    <form method="POST" action="edit_fund.php?fund_id=<?php echo $fund_id; ?>">
      <div class="form-group">
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" class="form-control" value="<?php echo $fundRow['AMOUNT']; ?>" required>
      </div>
      <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
