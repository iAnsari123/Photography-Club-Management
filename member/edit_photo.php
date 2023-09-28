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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $photo_number = $_POST['photo_number'];
    $photo_name = $_POST['photo_name'];
    $submission_date = $_POST['submission_date'];

    // Upload new photo if provided
    if ($_FILES['photo_file']['name'] !== "") {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["photo_file"]["name"]);
        move_uploaded_file($_FILES["photo_file"]["tmp_name"], $target_file);

        // Update photo path in database
        $sql_path = "UPDATE photos SET photo_path = :photo_path WHERE photo_no = :photo_number";
        $stmt_path = oci_parse($conn, $sql_path);
        oci_bind_by_name($stmt_path, ':photo_path', $target_file);
        oci_bind_by_name($stmt_path, ':photo_number', $photo_number);
        oci_execute($stmt_path);
        oci_free_statement($stmt_path);
    }

    $sql = "UPDATE photos SET p_name = :photo_name, submission_date = TO_DATE(:submission_date, 'YYYY-MM-DD') WHERE photo_no = :photo_number";
    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ':photo_name', $photo_name);
    oci_bind_by_name($stmt, ':submission_date', $submission_date);
    oci_bind_by_name($stmt, ':photo_number', $photo_number);

    $result = oci_execute($stmt);

    if ($result) {
        echo "<script>alert('Photo updated successfully.');</script>";
    } else {
        echo "<script>alert('Error updating photo.');</script>";
    }

    oci_free_statement($stmt);
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
  <title>Edit Photos</title>
</head>
<body>
  <div class="container">
    <h2>Edit Photos</h2>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Photo Number</th>
          <th>Photo Name</th>
          <th>Submission Date</th>
          <th>Current Photo</th>
          <th>Edit</th>
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
            echo "<td><a href='#' data-toggle='modal' data-target='#editModal" . $row['PHOTO_NO'] . "' class='btn btn-primary btn-sm'>Edit</a></td>";
            echo "</tr>";

            // Create edit modal for each photo
            echo '<div class="modal fade" id="editModal' . $row['PHOTO_NO'] . '" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">';
            echo '<div class="modal-dialog" role="document">';
            echo '<div class="modal-content">';
            echo '<div class="modal-header">';
            echo '<h5 class="modal-title" id="editModalLabel">Edit Photo</h5>';
            echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
            echo '<span aria-hidden="true">&times;</span>';
            echo '</button>';
            echo '</div>';
            echo '<div class="modal-body">';
            echo '<form method="POST" enctype="multipart/form-data">';
            echo '<div class="form-group">';
            echo '<label for="photo_name">Photo Name:</label>';
            echo '<input type="text" class="form-control" id="photo_name" name="photo_name" value="' . $row['P_NAME'] . '" required>';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label for="submission_date">Submission Date:</label>';
            echo '<input type="date" class="form-control" id="submission_date" name="submission_date" value="' . $row['SUBMISSION_DATE'] . '" required>';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label for="photo_file">Upload New Photo:</label>';
            echo '<input type="file" class="form-control-file" id="photo_file" name="photo_file">';
            echo '</div>';
            echo '<input type="hidden" name="photo_number" value="' . $row['PHOTO_NO'] . '">';
            echo '<button type="submit" class="btn btn-primary">Save Changes</button>';
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

