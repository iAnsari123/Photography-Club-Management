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
    $title = $_POST['title'];
    $event_date = $_POST['event_date'];
    $location = $_POST['location'];

    $insertSql = "INSERT INTO event (event_no, type, date_time, venue, club_id) 
                  VALUES (event_seq.nextval, :title, :event_date, :location, 1)";

    $stmt = oci_parse($conn, $insertSql);

    oci_bind_by_name($stmt, ':title', $title);
    oci_bind_by_name($stmt, ':event_date', $event_date);
    oci_bind_by_name($stmt, ':location', $location);

    $result = oci_execute($stmt);

    if ($result) {
        $_SESSION['successMessage'] = "Event added successfully!";
    } else {
        $_SESSION['errorMessage'] = "Error adding event: " . oci_error($stmt);
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
  <style>
    /* Add your custom CSS styles here */
  </style>
  <title>Add Event</title>
</head>
<body>
  <div class="container">
    <h2>Add Event</h2>
    <?php if (isset($_SESSION['successMessage'])): ?>
      <div class="alert alert-success"><?php echo $_SESSION['successMessage']; ?></div>
      <?php unset($_SESSION['successMessage']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['errorMessage'])): ?>
      <div class="alert alert-danger"><?php echo $_SESSION['errorMessage']; ?></div>
      <?php unset($_SESSION['errorMessage']); ?>
    <?php endif; ?>
    <form method="POST" action="add_event.php">
      <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="event_date">Event Date:</label>
        <input type="date" id="event_date" name="event_date" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="location">Location:</label>
        <input type="text" id="location" name="location" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">Add Event</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
