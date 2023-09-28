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

// Update member details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uni_id = $_POST['uni_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_num = $_POST['phone_num'];
    $sem_no = $_POST['sem_no'];
    $rank = $_POST['rank'];

    $updateSql = "UPDATE member 
                  SET name = :name, email = :email, phone_num = :phone_num, sem_no = :sem_no, rank = :rank
                  WHERE uni_id = :uni_id";

    $stmt = oci_parse($conn, $updateSql);

    oci_bind_by_name($stmt, ':name', $name);
    oci_bind_by_name($stmt, ':email', $email);
    oci_bind_by_name($stmt, ':phone_num', $phone_num);
    oci_bind_by_name($stmt, ':sem_no', $sem_no);
    oci_bind_by_name($stmt, ':rank', $rank);
    oci_bind_by_name($stmt, ':uni_id', $uni_id);

    $result = oci_execute($stmt);

    if ($result) {
        $_SESSION['successMessage'] = "Member details updated successfully!";
    } else {
        $_SESSION['errorMessage'] = "Error updating member details: " . oci_error($stmt);
    }

    oci_free_statement($stmt);
    header("Location: edit_member.php");
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
  <title>Admin - Edit Members</title>
</head>
<body>
  <div class="container">
    <h2>Admin - Edit Members</h2>
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
            echo "<td><button type='button' class='btn btn-primary editBtn' data-toggle='modal' data-target='#editModal' data-uni-id='" . $memberRow['UNI_ID'] . "'>Edit</button></td>";
            echo "</tr>";
        }
      ?>
      </tbody>
    </table>
  </div>

  <!-- Edit Member Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Member Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="edit_member.php">
            <input type="hidden" name="uni_id" id="uni_id">
            <div class="form-group">
              <label for="name">Name:</label>
              <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="email">Email:</label>
              <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="phone_num">Phone Number:</label>
              <input type="text" id="phone_num" name="phone_num" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="sem_no">Semester:</label>
              <input type="text" id="sem_no" name="sem_no" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="rank">Rank:</label>
              <input type="text" id="rank" name="rank" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
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
      // Handle Edit button click
      $('.editBtn').click(function() {
        var uni_id = $(this).data('uni-id');
        var name = $(this).closest('tr').find('td:eq(1)').text();
        var email = $(this).closest('tr').find('td:eq(2)').text();
        var phone_num = $(this).closest('tr').find('td:eq(3)').text();
        var sem_no = $(this).closest('tr').find('td:eq(4)').text();
        var rank = $(this).closest('tr').find('td:eq(5)').text();

        $('#uni_id').val(uni_id);
        $('#name').val(name);
        $('#email').val(email);
        $('#phone_num').val(phone_num);
        $('#sem_no').val(sem_no);
        $('#rank').val(rank);
      });
    });
  </script>
</body>
</html>







