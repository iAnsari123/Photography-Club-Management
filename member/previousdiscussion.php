<?php
session_start();

$conn = oci_connect("ahnaf", "123", "localhost/XE");
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$sql = "SELECT d.d_no, d.topic, d.creation_date, m.name, d.description
        FROM discussion d
        JOIN member m ON d.uni_id = m.uni_id
        ORDER BY d.d_no DESC";

$result = oci_parse($conn, $sql);
oci_execute($result);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f2f2f2;
            padding: 20px;
        }

        .container {
            max-width: 800px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Discussion List</h2>
        <table>
            <thead>
                <tr>
                    <th>Discussion Number</th>
                    <th>Topic</th>
                    <th>Creation Date</th>
                    <th>Author</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = oci_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['D_NO'] . "</td>";
                    echo "<td>" . $row['TOPIC'] . "</td>";
                    echo "<td>" . $row['CREATION_DATE'] . "</td>";
                    echo "<td>" . $row['NAME'] . "</td>";
                    echo "<td>" . $row['DESCRIPTION'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
