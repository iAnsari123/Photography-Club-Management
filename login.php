<?php
session_start();
$conn = oci_connect("ahnaf", "123", "localhost/XE") or die("Connection Failed");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if($_POST['uni_id'] == "admin" && $_POST['password'] == "admin")
    {
        $_SESSION['name'] = "admin";
        header('Location: admin/admin.php');
    }
    else
    {

    $uni_id = $_POST['uni_id'];
    $password = $_POST['password'];

    $checkQuery = "SELECT name FROM member WHERE uni_id = '$uni_id' AND password = '$password'";
    $checkResult = oci_parse($conn, $checkQuery);
    oci_execute($checkResult);
    $row = oci_fetch_assoc($checkResult);

    if ($row) {
        $_SESSION['name'] = $row['NAME'];  // Assuming the column name in the database is 'name'
        $_SESSION['uni_id'] = $uni_id;

        header('Location: member/member.php');
        exit();
    } else {
        echo "<script>alert('User does not exist');</script>";
        header("Location: signup.php");
        exit();
    }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f2f2f2;
            padding: 20px;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login Form</h2>
        <form method="POST" action="login.php">

            <div class="form-group">
                <label for="universityid">University ID:</label>
                <input type="text" id="universityid" name="uni_id" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <input type="submit" value="Login" class="btn btn-primary">
        </form>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>