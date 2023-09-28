<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['uni_id'])) {
    header("Location: login.php");
    exit();
}

$conn = oci_connect("ahnaf", "123", "localhost/XE");
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$uni_id = $_SESSION['uni_id'];
$galleries = array();

// Fetch gallery data
$gallerySql = "SELECT * FROM gallery";
$galleryResult = oci_parse($conn, $gallerySql);
oci_execute($galleryResult);

while ($galleryRow = oci_fetch_assoc($galleryResult)) {
    $galleries[] = $galleryRow;
}

// Fetch photos data
$photosSql = "SELECT * FROM photos WHERE uni_id = :uni_id";
$photosResult = oci_parse($conn, $photosSql);
oci_bind_by_name($photosResult, ':uni_id', $uni_id);
oci_execute($photosResult);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    .image-container {
      position: relative;
      cursor: pointer;
      overflow: hidden;
    }

    .image-zoom {
      max-width: 100%;
      transition: transform 0.3s;
    }

    .image-zoom:hover {
      transform: scale(1.1);
    }

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      padding-top: 50px;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.9);
    }

    .modal-content {
      margin: auto;
      display: block;
      width: 80%;
      max-width: 700px;
    }

    .close {
      color: #ffffff;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: #bbb;
      text-decoration: none;
      cursor: pointer;
    }
  </style>
  <title>Gallery</title>
</head>
<body>
  <div class="container">
    <h2>Gallery</h2>
    <div class="row">
      <div class="col-md-4">
        <h4>Select a Gallery</h4>
        <ul class="list-group">
          <?php
            foreach ($galleries as $gallery) {
                echo "<li class='list-group-item'><a href='gallery.php?g_no=" . $gallery['G_NO'] . "'>" . $gallery['G_NO'] . "</a></li>";
            }
          ?>
        </ul>
      </div>
      <div class="col-md-8">
        <h4>Images</h4>
        <div class="row">
          <?php
            while ($photoRow = oci_fetch_assoc($photosResult)) {
                echo "<div class='col-md-3 mb-4'>";
                echo "<div class='image-container'>";
                echo "<img src='" . $photoRow['PHOTO_PATH'] . "' class='img-fluid image-zoom' data-src='" . $photoRow['PHOTO_PATH'] . "'>";
                echo "</div>";
                echo "<p>" . $photoRow['P_NAME'] . "</p>";
                echo "</div>";
            }
          ?>
        </div>
      </div>
    </div>
  </div>

  <div id="imageModal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="modalImage">
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    const images = document.querySelectorAll('.image-zoom');
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const closeModal = document.querySelector('.close');

    images.forEach(image => {
      image.addEventListener('click', () => {
        modal.style.display = 'block';
        modalImage.src = image.getAttribute('data-src');
      });
    });

    closeModal.addEventListener('click', () => {
      modal.style.display = 'none';
    });
  </script>
</body>
</html>


