<?php
$conn = oci_connect("ahnaf", "123", "localhost/XE") or die("Connection Failed");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $uni_id = $_POST['uni_id'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $sem = $_POST['sem'];
    $rank = $_POST['rank'];

    $checkQuery = "SELECT COUNT(*) FROM member WHERE uni_id = '$uni_id' OR email = '$email'";
    $checkResult = oci_parse($conn, $checkQuery);
    oci_execute($checkResult);
    $rowCount = oci_fetch_row($checkResult)[0];

    if ($rowCount > 0) {
        echo "<script>alert('User already exist');</script>";
        header('Location: login.php');
    } 
    else {
        $sql = "INSERT INTO member(uni_id, name, email, phone_num, sem_no, rank, club_id, password) values('{$uni_id}','{$name}','{$email}', '{$phone}','{$sem}','{$rank}', '1','{$password}')";

        $stmt = oci_parse($conn, $sql);
        oci_execute($stmt);
        if ($stmt) {
            echo "<script>alert('Data inserted successfully.');</script>";
            header("refresh:0; url = signup.php");
        } 
        else {
            $e = oci_error($stmt);
            echo "Error: " . htmlentities($e['message']);
        }   
        oci_free_statement($stmt);
        oci_close($conn); 
    }


}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Signup Form</title>
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

    .already-member {
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
        color: #888;
    }

    .already-member a {
        color: #007bff;
        text-decoration: none;
    }

    .already-member a:hover {
        text-decoration: underline;
    }
    </style>
</head>
<body>
    <div class="container">
        <h2>Signup Form</h2>
        <form method="POST" action="signup.php">
            <div class="form-group">
                <label for="Uni_id">University ID:</label>
                <input type="text" id="Uni_id" name="uni_id" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="Name">Name:</label>
                <input type="text" id="Name" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="Emai">Email:</label>
                <input type="email" id="Emai" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="Phone">Phone Number:</label>
                <input type="text" id="Phone" name="phone" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="Sem">Semester No:</label>
                <input type="text" id="Sem" name="sem" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="Rank">Rank</label>
                <input type="text" id="Rank" name="rank" placeholder="manager/photographer/member" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="confirmpassword">Confirm Password:</label>
                <input type="password" id="confirmpassword" name="confirmpassword" class="form-control" required>
            </div>

            <input type="submit" value="Signup" class="btn btn-primary">
        </form>

        <p class="already-member">
            Already a member? <a href="login.php">Click here to login</a>
        </p>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

