<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <style>
    body {
      padding: 20px;
    }

    h2 {
      margin-bottom: 30px;
      text-align: center;
    }

    .admin-panel {
      max-width: 600px;
      margin: 0 auto;
    }

    .admin-panel h3 {
      margin-top: 30px;
    }

    .admin-panel ul li {
      margin-bottom: 10px;
    }

    .logout {
      display: block;
      margin-top: 30px;
      text-align: center;
    }
  </style>
</head>
<body>
  <h2>Welcome, Admin!</h2>
  <div class="admin-panel">
    <h3>Manage Users</h3>
    <ul class="list-group">
      <li class="list-group-item"><a href="add_user.php">Add User</a></li>
    </ul>

    <h3>Manage Members</h3>
    <ul class="list-group">
        <li class="list-group-item"><a href="view_member.php">View Members</a></li>
        <li class="list-group-item"><a href="edit_member.php">Edit Members</a></li>
        <li class="list-group-item"><a href="delete_member.php">Delete Members</a></li>

    </ul>

    <h3>Manage Events</h3>
    <ul class="list-group">
      <li class="list-group-item"><a href="view_event.php">View Events</a></li>
      <li class="list-group-item"><a href="add_event.php">Add Events</a></li>
    </ul>

    <h3>Fund</h3>
    <ul class="list-group">
      <li class="list-group-item"><a href="view_fund.php">View Fund</a></li>
      <li class="list-group-item"><a href="add_fund.php">Add Fund</a></li>
      <li class="list-group-item"><a href="edit_fund.php">Update Fund</a></li>
    </ul>

    <h3>Competition</h3>
    <ul class="list-group">
      <li class="list-group-item"><a href="view_competition.php">View Competition</a></li>
      <li class="list-group-item"><a href="add_competition.php">Add Competition</a></li>
    </ul>

    <!-- Add more menu items and functionality as needed -->

    <a href="logout.php" class="btn btn-danger logout">Logout</a>
  </div>

  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
