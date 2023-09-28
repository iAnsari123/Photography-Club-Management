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

// Handle photo deletion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $photo_number = $_POST['photo_number'];
    $sql_delete = "DELETE FROM photos WHERE photo_no = :photo_number";
    $stmt_delete = oci_parse($conn, $sql_delete);
    oci_bind_by_name($stmt_delete, ':photo_number', $photo_number);
    $result_delete = oci_execute($stmt_delete);

    if ($result_delete) {
        echo "<script>alert('Photo deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting photo.');</script>";
    }

    oci_free_statement($stmt_delete);
}

// Fetch photos from the database
$sql = "SELECT * FROM photos";
$result = oci_parse($conn, $sql);
oci_execute($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <title>Delete Photos</title>
</head>
<body>
  <div class="container">
    <h2>Delete Photos</h2>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Photo Number</th>
          <th>Photo Name</th>
          <th>Submission Date</th>
          <th>Current Photo</th>
          <th>Delete</th>
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
            echo "<td><a href='#' data-toggle='modal' data-target='#deleteModal" . $row['PHOTO_NO'] . "' class='btn btn-danger btn-sm'>Delete</a></td>";
            echo "</tr>";

            // Create delete confirmation modal for each photo
            echo '<div class="modal fade" id="deleteModal' . $row['PHOTO_NO'] . '" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">';
            echo '<div class="modal-dialog" role="document">';
            echo '<div class="modal-content">';
            echo '<div class="modal-header">';
            echo '<h5 class="modal-title" id="deleteModalLabel">Delete Photo</h5>';
            echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
            echo '<span aria-hidden="true">&times;</span>';
            echo '</button>';
            echo '</div>';
            echo '<div class="modal-body">';
            echo '<p>Are you sure you want to delete this photo?</p>';
            echo '<form method="POST">';
            echo '<input type="hidden" name="photo_number" value="' . $row['PHOTO_NO'] . '">';
            echo '<button type="submit" class="btn btn-danger">Delete</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
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
