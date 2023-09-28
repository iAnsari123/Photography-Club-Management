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

$event_id = $_GET['event_id'];

// Fetch event data
$eventSql = "SELECT * FROM event WHERE event_no = :event_id";
$eventResult = oci_parse($conn, $eventSql);
oci_bind_by_name($eventResult, ':event_id', $event_id);
oci_execute($eventResult);
$eventRow = oci_fetch_assoc($eventResult);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $event_date = $_POST['event_date'];
    $location = $_POST['location'];

    $updateSql = "UPDATE event 
                  SET type = :title, date_time = TO_DATE(:event_date, 'YYYY-MM-DD HH24:MI:SS'), venue = :location
                  WHERE event_no = :event_id";

    $stmt = oci_parse($conn, $updateSql);

    oci_bind_by_name($stmt, ':title', $title);
    oci_bind_by_name($stmt, ':event_date', $event_date);
    oci_bind_by_name($stmt, ':location', $location);
    oci_bind_by_name($stmt, ':event_id', $event_id);

    $result = oci_execute($stmt);

    if ($result) {
        $_SESSION['successMessage'] = "Event details updated successfully!";
    } else {
        $_SESSION['errorMessage'] = "Error updating event details: " . oci_error($stmt);
    }

    oci_free_statement($stmt);
    header("Location: edit_event.php?event_id=$event_id");
    exit();
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
  <title>Edit Event</title>
</head>
<body>
  <div class="container">
    <h2>Edit Event</h2>
    <?php if (isset($_SESSION['successMessage'])): ?>
      <div class="alert alert-success"><?php echo $_SESSION['successMessage']; ?></div>
      <?php unset($_SESSION['successMessage']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['errorMessage'])): ?>
      <div class="alert alert-danger"><?php echo $_SESSION['errorMessage']; ?></div>
      <?php unset($_SESSION['errorMessage']); ?>
    <?php endif; ?>
    <form method="POST" action="edit_event.php?event_id=<?php echo $event_id; ?>">
      <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" class="form-control" value="<?php echo $eventRow['TYPE']; ?>" required>
      </div>
      <div class="form-group">
        <label for="event_date">Event Date:</label>
        <input type="date" id="event_date" name="event_date" class="form-control" value="<?php echo $eventRow['DATE_TIME']; ?>" required>
      </div>
      <div class="form-group">
        <label for="location">Location:</label>
        <input type="text" id="location" name="location" class="form-control" value="<?php echo $eventRow['VENUE']; ?>" required>
      </div>
      <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
    <hr>
    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">Delete Event</button>
  </div>

  <!-- Delete Event Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Delete Event</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this event?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <a href="delete_event.php?event_id=<?php echo $event_id; ?>" class="btn btn-danger">Yes, Delete</a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
