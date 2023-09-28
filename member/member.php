<?php
session_start();

?>
<!DOCTYPE html>
<html>
<head>
  <title>Member Dashboard</title>
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
<h2>Welcome, <?php echo $_SESSION['name']; ?>!</h2>
  <div class="admin-panel">
    <h3>Events</h3>
    <ul class="list-group">
      <li class="list-group-item"><a href="availableevent.php">Available Events</a></li>
      <li class="list-group-item"><a href="event_list.php">Participated Events</a></li>
    </ul>

    <h3>Discussion</h3>
    <ul class="list-group">
      <li class="list-group-item"><a href="creatediscussion.php">Create Discussion</a></li>
      <li class="list-group-item"><a href="previousdiscussion.php">Previous Discussion</a></li>
      </ul>

    <h3>Portfolio</h3>
    <ul class="list-group">
      <li class="list-group-item"><a href="add_photo.php">Add Photo</a></li>
      <li class="list-group-item"><a href="edit_photo.php">Edit Photo</a></li>
      <li class="list-group-item"><a href="delete_photo.php">Delete Photo</a></li>
      <li class="list-group-item"><a href="view_photo.php">View Work</a></li>
      <li class="list-group-item"><a href="gallery.php">View Gallery</a></li>
    </ul>
    <!-- Add more menu items and functionality as needed -->

    <a href="logout.php" class="btn btn-danger logout">Logout</a>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
