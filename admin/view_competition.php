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

// Fetch competition data
$competitionSql = "SELECT c.c_no, c.position, c.reward, e.type AS event_type
                   FROM competition c
                   JOIN event e ON c.event_no = e.event_no";
$competitionResult = oci_parse($conn, $competitionSql);
oci_execute($competitionResult);


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <title>View Competitions</title>
</head>
<body>
  <div class="container">
    <h2>View Competitions</h2>
    <?php if (isset($_SESSION['successMessage'])): ?>
      <div class="alert alert-success"><?php echo $_SESSION['successMessage']; ?></div>
      <?php unset($_SESSION['successMessage']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['errorMessage'])): ?>
      <div class="alert alert-danger"><?php echo $_SESSION['errorMessage']; ?></div>
      <?php unset($_SESSION['errorMessage']); ?>
    <?php endif; ?>
    <table class="table">
      <thead>
        <tr>
          <th>Competition No</th>
          <th>Position</th>
          <th>Reward</th>
          <th>Event Type</th>
          <th>Edit</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($competitionRow = oci_fetch_assoc($competitionResult)): ?>
          <tr>
            <td><?php echo $competitionRow['C_NO']; ?></td>
            <td><?php echo $competitionRow['POSITION']; ?></td>
            <td><?php echo $competitionRow['REWARD']; ?></td>
            <td><?php echo $competitionRow['EVENT_TYPE']; ?></td>
            <td><a href="edit_competition.php?c_no=<?php echo $competitionRow['C_NO']; ?>" class="btn btn-primary">Edit</a></td>
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
