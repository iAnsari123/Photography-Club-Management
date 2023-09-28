<?php
session_start();


$conn = oci_connect("ahnaf", "123", "localhost/XE");
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $topic = $_POST['topic'];
    $creation_date = $_POST['creation_date'];
    $description = $_POST['description'];

    // Assuming you have the logged-in user's uni_id, replace 'YOUR_UNI_ID' with the actual value
    $uni_id = $_SESSION['uni_id'];

    $sql = "INSERT INTO discussion (d_no, topic, creation_date, uni_id, description)
            VALUES (discussion_seq.NEXTVAL, :topic, :creation_date, :uni_id, :description)";

    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ':topic', $topic);
    oci_bind_by_name($stmt, ':creation_date', $creation_date);
    oci_bind_by_name($stmt, ':uni_id', $uni_id);
    oci_bind_by_name($stmt, ':description', $description);

    $result = oci_execute($stmt);

    if ($result) {
        echo "Discussion created successfully!";
    } else {
        echo "Error creating discussion: " . oci_error($stmt);
    }

    oci_free_statement($stmt);
}

oci_close($conn);
?>
