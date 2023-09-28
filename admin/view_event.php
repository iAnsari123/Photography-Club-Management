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

// Fetch event data
$eventsSql = "SELECT * FROM event";
$eventsResult = oci_parse($conn, $eventsSql);
oci_execute($eventsResult);
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
  <title>View Events</title>
</head>
<body>
  <div class="container">
    <h2>View Events</h2>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Event ID</th>
          <th>Type</th>
          <th>Date</th>
          <th>Location</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php
        while ($eventRow = oci_fetch_assoc($eventsResult)) {
            echo "<tr>";
            echo "<td>" . $eventRow['EVENT_NO'] . "</td>";
            echo "<td>" . $eventRow['TYPE'] . "</td>";
            echo "<td>" . $eventRow['DATE_TIME'] . "</td>";
            echo "<td>" . $eventRow['VENUE'] . "</td>";
            // echo "<td>" . $eventRow['LOCATION'] . "</td>";
            echo "<td><a href='edit_event.php?event_id=" . $eventRow['EVENT_NO'] . "' class='btn btn-primary'>Edit</a></td>";
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
