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

$members = array();

// Fetch member data
$memberSql = "SELECT * FROM member";
$memberResult = oci_parse($conn, $memberSql);
oci_execute($memberResult);

while ($memberRow = oci_fetch_assoc($memberResult)) {
    $members[] = $memberRow;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <title>View Members</title>
</head>
<body>
  <div class="container">
    <h2>View Members</h2>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>University ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone Number</th>
          <th>Semester</th>
          <th>Rank</th>
        </tr>
      </thead>
      <tbody>
        <?php
          foreach ($members as $member) {
              echo "<tr>";
              echo "<td>" . $member['UNI_ID'] . "</td>";
              echo "<td>" . $member['NAME'] . "</td>";
              echo "<td>" . $member['EMAIL'] . "</td>";
              echo "<td>" . $member['PHONE_NUM'] . "</td>";
              echo "<td>" . $member['SEM_NO'] . "</td>";
              echo "<td>" . $member['RANK'] . "</td>";
              echo "</tr>";
          }
        ?>
      </tbody>
    </table>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
