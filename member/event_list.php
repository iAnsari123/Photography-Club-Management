<?php
session_start();
$conn = oci_connect("ahnaf", "123", "localhost/XE");
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
$uni_id = strval($_SESSION['uni_id']);
$sql = "SELECT p.p_id AS participant_id, p.role AS participant_role, p.submitted_work, e.event_no AS event_number, e.venue AS event_venue, m.name AS participant_name, m.uni_id AS university_id
        FROM participants p
        JOIN event e ON p.p_id = e.event_no
        JOIN member m ON p.uni_id = m.uni_id";

$result = oci_parse($conn, $sql);
// oci_bind_by_name($result, ':uni_id', $uni_id);
oci_execute($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <title>Event List</title>
</head>
<body>
  <div class="container">
    <h2>Event List</h2>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Participant ID</th>
          <th>Participant Role</th>
          <th>Submitted Work</th>
          <th>Event Number</th>
          <th>Event Venue</th>
          <th>Participant Name</th>
          <th>University ID</th>
        </tr>
      </thead>
      <tbody>
      <?php
        while ($row = oci_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['PARTICIPANT_ID'] . "</td>";
            echo "<td>" . $row['PARTICIPANT_ROLE'] . "</td>";
            echo "<td>" . $row['SUBMITTED_WORK'] . "</td>";
            echo "<td>" . $row['EVENT_NUMBER'] . "</td>";
            echo "<td>" . $row['EVENT_VENUE'] . "</td>";
            echo "<td>" . $row['PARTICIPANT_NAME'] . "</td>";
            echo "<td>" . $row['UNIVERSITY_ID'] . "</td>";
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
