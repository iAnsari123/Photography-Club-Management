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

// Retrieve membership applicants with status not accepted
$applicantsSql = "SELECT ma.UNI_ID, ma.NAME, ma.EMAIL, ma.PHONE_NUM, ma.SEM_NO, ma.RANK, ma.CLUB_ID, ma.PASSWORD
                  FROM membership_applicants ma
                  JOIN membership_application a ON ma.A_ID = a.A_ID
                  WHERE a.STATUS <> 'accepted'";
$applicantsResult = oci_parse($conn, $applicantsSql);
oci_execute($applicantsResult);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uni_id = $_POST['uni_id'];
    $action = $_POST['action'];

    if ($action == "accept") {
        // Retrieve applicant details
        $applicantSql = "SELECT * FROM membership_applicants WHERE UNI_ID = :uni_id";
        $applicantResult = oci_parse($conn, $applicantSql);
        oci_bind_by_name($applicantResult, ':uni_id', $uni_id);
        oci_execute($applicantResult);
        $applicantRow = oci_fetch_assoc($applicantResult);

        // Insert applicant details into member table
        $insertSql = "INSERT INTO member (UNI_ID, NAME, EMAIL, PHONE_NUM, SEM_NO, RANK, CLUB_ID, PASSWORD)
                      VALUES (:uni_id, :name, :email, :phone_num, :sem_no, :rank, :club_id, :password)";
        $stmt = oci_parse($conn, $insertSql);

        oci_bind_by_name($stmt, ':uni_id', $applicantRow['UNI_ID']);
        oci_bind_by_name($stmt, ':name', $applicantRow['NAME']);
        oci_bind_by_name($stmt, ':email', $applicantRow['EMAIL']);
        oci_bind_by_name($stmt, ':phone_num', $applicantRow['PHONE_NUM']);
        oci_bind_by_name($stmt, ':sem_no', $applicantRow['SEM_NO']);
        oci_bind_by_name($stmt, ':rank', $applicantRow['RANK']);
        oci_bind_by_name($stmt, ':club_id', $applicantRow['CLUB_ID']);
        oci_bind_by_name($stmt, ':password', $applicantRow['PASSWORD']);

        $result = oci_execute($stmt);

        if ($result) {
            // Delete applicant record from membership_applicants
            $deleteSql = "DELETE FROM membership_applicants WHERE UNI_ID = :uni_id";
            $deleteStmt = oci_parse($conn, $deleteSql);
            oci_bind_by_name($deleteStmt, ':uni_id', $uni_id);
            oci_execute($deleteStmt);

            $_SESSION['successMessage'] = "Applicant accepted and added as a member!";
        } else {
            $_SESSION['errorMessage'] = "Error accepting applicant: " . oci_error($stmt);
        }

        oci_free_statement($stmt);
        oci_free_statement($deleteStmt);
    } elseif ($action == "reject") {
        // Delete applicant record from membership_applicants
        $deleteSql = "DELETE FROM membership_applicants WHERE UNI_ID = :uni_id";
        $stmt = oci_parse($conn, $deleteSql);
        oci_bind_by_name($stmt, ':uni_id', $uni_id);

        $result = oci_execute($stmt);

        if ($result) {
            $_SESSION['successMessage'] = "Applicant rejected and record deleted!";
        } else {
            $_SESSION['errorMessage'] = "Error rejecting applicant: " . oci_error($stmt);
        }

        oci_free_statement($stmt);
    }

    header("Location: add_user.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <title>Add User</title>
</head>
<body>
  <div class="container">
    <h2>Add User</h2>
    <?php if (isset($_SESSION['successMessage'])): ?>
      <div class="alert alert-success"><?php echo $_SESSION['successMessage']; ?></div>
      <?php unset($_SESSION['successMessage']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['errorMessage'])): ?>
      <div class="alert alert-danger"><?php echo $_SESSION['errorMessage']; ?></div>
      <?php unset($_SESSION['errorMessage']); ?>
    <?php endif; ?>
    <table class="table">
      <thead>
        <tr>
          <th>UNI ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone Number</th>
          <th>Semester</th>
          <th>Rank</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($applicantRow = oci_fetch_assoc($applicantsResult)): ?>
          <tr>
            <td><?php echo $applicantRow['UNI_ID']; ?></td>
            <td><?php echo $applicantRow['NAME']; ?></td>
            <td><?php echo $applicantRow['EMAIL']; ?></td>
            <td><?php echo $applicantRow['PHONE_NUM']; ?></td>
            <td><?php echo $applicantRow['SEM_NO']; ?></td>
            <td><?php echo $applicantRow['RANK']; ?></td>
            <td>
              <form method="POST">
                <input type="hidden" name="uni_id" value="<?php echo $applicantRow['UNI_ID']; ?>">
                <button type="submit" name="action" value="accept" class="btn btn-success">Accept</button>
                <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
