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
    $position = $_POST['position'];
    $reward = $_POST['reward'];
    $event_no = $_POST['event_no'];

    $insertSql = "INSERT INTO competition (c_no, position, reward, event_no) 
                  VALUES (competition_seq.nextval, :position, :reward, :event_no)";

    $stmt = oci_parse($conn, $insertSql);

    oci_bind_by_name($stmt, ':position', $position);
    oci_bind_by_name($stmt, ':reward', $reward);
    oci_bind_by_name($stmt, ':event_no', $event_no);

    $result = oci_execute($stmt);

    if ($result) {
        $_SESSION['successMessage'] = "Competition added successfully!";
    } else {
        $_SESSION['errorMessage'] = "Error adding competition: " . oci_error($stmt);
    }

    oci_free_statement($stmt);
}

// Fetch event data for dropdown
$eventSql = "SELECT event_no, type FROM event";
$eventResult = oci_parse($conn, $eventSql);
oci_execute($eventResult);

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
  <title>Add Competition</title>
</head>
<body>
  <div class="container">
    <h2>Add Competition</h2>
    <?php if (isset($_SESSION['successMessage'])): ?>
      <div class="alert alert-success"><?php echo $_SESSION['successMessage']; ?></div>
      <?php unset($_SESSION['successMessage']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['errorMessage'])): ?>
      <div class="alert alert-danger"><?php echo $_SESSION['errorMessage']; ?></div>
      <?php unset($_SESSION['errorMessage']); ?>
    <?php endif; ?>
    <form method="POST" action="add_competition.php">
      <div class="form-group">
        <label for="position">Position:</label>
        <select id="position" name="position" class="form-control" required>
          <option value="1st">1st</option>
          <option value="2nd">2nd</option>
          <option value="3rd">3rd</option>
        </select>
      </div>
      <div class="form-group">
        <label for="reward">Reward:</label>
        <input type="text" id="reward" name="reward" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="event_no">Event:</label>
        <select id="event_no" name="event_no" class="form-control" required>
          <?php while ($eventRow = oci_fetch_assoc($eventResult)): ?>
            <option value="<?php echo $eventRow['EVENT_NO']; ?>"><?php echo $eventRow['TYPE']; ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Add Competition</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
