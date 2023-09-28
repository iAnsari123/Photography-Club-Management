<?php
session_start();

// Check if the user is logged in as an admin
if ($_SESSION['name'] != 'admin') {
    header("Location: login.php");
    exit();
}

$conn = oci_connect("ahnaf", "123", "localhost/XE");
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Delete member
if (isset($_POST['delete_uni_id'])) {
    $delete_uni_id = $_POST['delete_uni_id'];

    $deleteSql = "DELETE FROM member WHERE uni_id = :delete_uni_id";
    $stmt = oci_parse($conn, $deleteSql);
    oci_bind_by_name($stmt, ':delete_uni_id', $delete_uni_id);
    
    if (oci_execute($stmt)) {
        $_SESSION['successMessage'] = "Member deleted successfully!";
    } else {
        $_SESSION['errorMessage'] = "Error deleting member: " . oci_error($stmt);
    }

    oci_free_statement($stmt);
    header("Location: delete_member.php");
    exit();
}

// Fetch member data
$membersSql = "SELECT * FROM member";
$membersResult = oci_parse($conn, $membersSql);
oci_execute($membersResult);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <title>Admin - Delete Members</title>
</head>
<body>
  <div class="container">
    <h2>Admin - Delete Members</h2>
    <?php if (isset($_SESSION['successMessage'])): ?>
      <div class="alert alert-success"><?php echo $_SESSION['successMessage']; unset($_SESSION['successMessage']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['errorMessage'])): ?>
      <div class="alert alert-danger"><?php echo $_SESSION['errorMessage']; unset($_SESSION['errorMessage']); ?></div>
    <?php endif; ?>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>University ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone Number</th>
          <th>Semester</th>
          <th>Rank</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php
        while ($memberRow = oci_fetch_assoc($membersResult)) {
            echo "<tr>";
            echo "<td>" . $memberRow['UNI_ID'] . "</td>";
            echo "<td>" . $memberRow['NAME'] . "</td>";
            echo "<td>" . $memberRow['EMAIL'] . "</td>";
            echo "<td>" . $memberRow['PHONE_NUM'] . "</td>";
            echo "<td>" . $memberRow['SEM_NO'] . "</td>";
            echo "<td>" . $memberRow['RANK'] . "</td>";
            echo "<td><button type='button' class='btn btn-danger deleteBtn' data-toggle='modal' data-target='#deleteModal' data-uni-id='" . $memberRow['UNI_ID'] . "'>Delete</button></td>";
            echo "</tr>";
        }
      ?>
      </tbody>
    </table>
  </div>

  <!-- Delete Member Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Delete Member</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this member?
        </div>
        <div class="modal-footer">
          <form method="post" action="delete_member.php">
            <input type="hidden" name="delete_uni_id" id="delete_uni_id">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            <button type="submit" class="btn btn-danger">Yes</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    $(document).ready(function() {
      // Handle Delete button click
      $('.deleteBtn').click(function() {
        var uni_id = $(this).data('uni-id');
        $('#delete_uni_id').val(uni_id);
      });
    });
  </script>
</body>
</html>
