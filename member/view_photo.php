<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['uni_id'])) {
    header("Location: login.php");
    exit();
}

$conn = oci_connect("ahnaf", "123", "localhost/XE");
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$uni_id = $_SESSION['uni_id'];
$sql = "SELECT * FROM photos WHERE uni_id = :uni_id";
$result = oci_parse($conn, $sql);
oci_bind_by_name($result, ':uni_id', $uni_id);
oci_execute($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <title>View Photos</title>
</head>
<body>
  <div class="container">
    <h2>View Photos</h2>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Photo Number</th>
          <th>Photo Name</th>
          <th>Submission Date</th>
          <th>Photo</th>
        </tr>
      </thead>
      <tbody>
      <?php
        while ($row = oci_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['PHOTO_NO'] . "</td>";
            echo "<td>" . $row['P_NAME'] . "</td>";
            echo "<td>" . $row['SUBMISSION_DATE'] . "</td>";
            echo "<td><img src='" . $row['PHOTO_PATH'] . "' width='100' height='100'></td>";
            echo "</tr>";
        }

        oci_close($conn);
      ?>
      </tbody>
    </table>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
