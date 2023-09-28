<?php
session_start();
$conn = oci_connect("ahnaf", "123", "localhost/XE");
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $photo_number = $_POST['photo_number'];
    $photo_name = $_POST['photo_name'];
    $submission_date = $_POST['submission_date'];

    // Get the logged-in user's uni_id from the session
    $uni_id = $_SESSION['uni_id'];

    // Process uploaded photo file
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);

    $sql = "INSERT INTO photos (photo_no, p_name, submission_date, uni_id, photo_path)
            VALUES (:photo_number, :photo_name, :submission_date, :uni_id, :photo_path)";

    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ':photo_number', $photo_number);
    oci_bind_by_name($stmt, ':photo_name', $photo_name);
    oci_bind_by_name($stmt, ':submission_date', $submission_date);
    oci_bind_by_name($stmt, ':uni_id', $uni_id);
    oci_bind_by_name($stmt, ':photo_path', $target_file);

    $result = oci_execute($stmt);

    if ($result) {
        $success_message = "Photo uploaded successfully!";
    } else {
        $error_message = "Error uploading photo: " . oci_error($stmt);
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
  <title>Add Photo</title>
</head>
<body>
  <div class="container">
    <h2>Add Photo</h2>
    <?php
    if (isset($success_message)) {
        echo '<div class="alert alert-success">' . $success_message . '</div>';
    } elseif (isset($error_message)) {
        echo '<div class="alert alert-danger">' . $error_message . '</div>';
    }
    ?>
    <form method="POST" action="add_photo.php" enctype="multipart/form-data">

      <div class="form-group">
        <label for="photo_number">Photo Number:</label>
        <input type="text" id="photo_number" name="photo_number" class="form-control" required>
      </div>

      <div class="form-group">
        <label for="photo_name">Photo Name:</label>
        <input type="text" id="photo_name" name="photo_name" class="form-control" required>
      </div>

      <div class="form-group">
        <label for="submission_date">Submission Date:</label>
        <input type="date" id="submission_date" name="submission_date" class="form-control" required>
      </div>

      <div class="form-group">
        <label for="photo">Upload Photo:</label>
        <input type="file" id="photo" name="photo" class="form-control-file" accept="image/*" required>
      </div>

      <button type="submit" class="btn btn-primary">Upload Photo</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
