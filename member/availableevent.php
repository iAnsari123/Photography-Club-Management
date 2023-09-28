<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <title>Event List Table</title>
</head>
<body>
  <div class="container">
    <table class="table table-striped" id="event-table">
      <thead>
        <tr>
          <th>Event Number</th>
          <th>Venue</th>
          <th>Date Time</th>
          <th>Type</th>
          <th>Organizer</th>
          <th>Join</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $conn = oci_connect("ahnaf", "123", "localhost/XE");
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $sql = "SELECT e.event_no, e.venue, e.date_time, e.type, c.club_name
                FROM event e
                JOIN club c ON e.club_id = c.club_id";
        
        $result = oci_parse($conn, $sql);
        oci_execute($result);

        while ($row = oci_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['EVENT_NO'] . "</td>";
            echo "<td>" . $row['VENUE'] . "</td>";
            echo "<td>" . $row['DATE_TIME'] . "</td>";
            echo "<td>" . $row['TYPE'] . "</td>";
            echo "<td>" . $row['CLUB_NAME'] . "</td>";
            echo "<td><a href='#' data-toggle='modal' data-target='#roleModal" . $row['EVENT_NO'] . "' class='btn btn-primary btn-sm'>Join Event</a></td>";
            echo "</tr>";

            // Create modal for each event
            echo '<div class="modal fade" id="roleModal' . $row['EVENT_NO'] . '" tabindex="-1" role="dialog" aria-labelledby="roleModalLabel" aria-hidden="true">';
            echo '<div class="modal-dialog" role="document">';
            echo '<div class="modal-content">';
            echo '<div class="modal-header">';
            echo '<h5 class="modal-title" id="roleModalLabel">Select Your Role</h5>';
            echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
            echo '<span aria-hidden="true">&times;</span>';
            echo '</button>';
            echo '</div>';
            echo '<div class="modal-body">';
            echo '<form method="post" action="./join_event.php">';
            echo '<div class="form-check">';
            echo '<input class="form-check-input" type="radio" name="role" id="photographerRadio" value="Photographer">';
            echo '<label class="form-check-label" for="photographerRadio">Photographer</label>';
            echo '</div>';
            echo '<div class="form-check">';
            echo '<input class="form-check-input" type="radio" name="role" id="managerRadio" value="Event Manager">';
            echo '<label class="form-check-label" for="managerRadio">Event Manager</label>';
            echo '</div>';
            echo '<div class="form-check">';
            echo '<input class="form-check-input" type="radio" name="role" id="memberRadio" value="General Member">';
            echo '<label class="form-check-label" for="memberRadio">General Member</label>';
            echo '</div>';
            echo '<input type="hidden" name="eventId" value="' . $row['EVENT_NO'] . '">';
            echo '</div>';
            echo '<div class="modal-footer">';
            echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
            echo '<button type="submit" class="btn btn-primary">Join</button>';
            echo '</div>';
            echo '</form>';
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
