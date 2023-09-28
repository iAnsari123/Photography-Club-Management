<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <title>Create Discussion</title>
</head>
<body>
  <div class="container mt-5">
    <h2>Create Discussion</h2>
    <form method="POST" action="insert_discussion.php">
      <div class="form-group">
        <label for="topic">Topic:</label>
        <input type="text" id="topic" name="topic" class="form-control" required>
      </div>

      <div class="form-group">
        <label for="creation_date">Creation Date:</label>
        <input type="date" id="creation_date" name="creation_date" class="form-control" required>
      </div>

      <div class="form-group">
        <label for="description">Description:</label>
        <textarea id="description" name="description" class="form-control" rows="5" required></textarea>
      </div>

      <input type="submit" value="Submit" class="btn btn-primary">
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
