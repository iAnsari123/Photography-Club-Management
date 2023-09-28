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

$competition_id = $_GET['c_no'];

// Fetch competition data
$competitionSql = "SELECT * FROM competition WHERE c_no = :competition_id";
$competitionResult = oci_parse($conn, $competitionSql);
oci_bind_by_name($competitionResult, ':competition_id', $competition_id);
oci_execute($competitionResult);
$competitionRow = oci_fetch_assoc($competitionResult);

// Fetch event data for dropdown
$eventSql = "SELECT event_no, type FROM event";
$eventResult = oci_parse($conn, $eventSql);
oci_execute($eventResult);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $position = $_POST['position'];
    $reward = $_POST['reward'];
    $event_no = $_POST['event_no'];

    $updateSql = "UPDATE competition 
                  SET position = :position, reward = :reward, event_no = :event_no
                  WHERE c_no = :competition_id";

    $stmt = oci_parse($conn, $updateSql);

    oci_bind_by_name($stmt, ':position', $position);
    oci_bind_by_name($stmt, ':reward', $reward);
    oci_bind_by_name($stmt, ':event_no', $event_no);
    oci_bind_by_name($stmt, ':competition_id', $competition_id);

    $result = oci_execute($stmt);

    if ($result) {
        $_SESSION['successMessage'] = "Competition details updated successfully!";
    } else {
        $_SESSION['errorMessage'] = "Error updating competition details: " . oci_error($stmt);
    }

    oci_free_statement($stmt);
    header("Location: edit_competition.php?c_no=$competition_id");
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
  <title>Edit Competition</title>
</head>
<body>
  <div class="container">
    <h2>Edit Competition</h2>
    <?php if (isset($_SESSION['successMessage'])): ?>
      <div class="alert alert-success"><?php echo $_SESSION['successMessage']; ?></div>
      <?php unset($_SESSION['successMessage']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['errorMessage'])): ?>
      <div class="alert alert-danger"><?php echo $_SESSION['errorMessage']; ?></div>
      <?php unset($_SESSION['errorMessage']); ?>
    <?php endif; ?>
    <form method="POST" action="edit_competition.php?c_no=<?php echo $competition_id; ?>">
      <div class="form-group">
        <label for="position">Position:</label>
        <select id="position" name="position" class="form-control" required>
          <option value="1st" <?php if ($competitionRow['POSITION'] === '1st') echo 'selected'; ?>>1st</option>
          <option value="2nd" <?php if ($competitionRow['POSITION'] === '2nd') echo 'selected'; ?>>2nd</option>
          <option value="3rd" <?php if ($competitionRow['POSITION'] === '3rd') echo 'selected'; ?>>3rd</option>
        </select>
      </div>
      <div class="form-group">
        <label for="reward">Reward:</label>
        <input type="text" id="reward" name="reward" class="form-control" value="<?php echo $competitionRow['REWARD']; ?>" required>
      </div>
      <div class="form-group">
        <label for="event_no">Event:</label>
        <select id="event_no" name="event_no" class="form-control" required>
          <?php while ($eventRow = oci_fetch_assoc($eventResult)): ?>
            <option value="<?php echo $eventRow['EVENT_NO']; ?>" <?php if ($eventRow['EVENT_NO'] === $competitionRow['EVENT_NO']) echo 'selected'; ?>><?php echo $eventRow['TYPE']; ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
