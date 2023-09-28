<?php
session_start();
$conn = oci_connect("ahnaf", "123", "localhost/XE");
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventId = $_POST["eventId"];
    $uni_id = $_SESSION['uni_id']; // You need to replace this with the user's uni_id
    $role = $_POST["role"];

    $sql = "INSERT INTO participants (P_ID, SUBMITTED_WORK, UNI_ID, ROLE) VALUES ('$eventId', 'Nothing','$uni_id', '$role')";

    $result = oci_parse($conn, $sql);
    oci_execute($result);
}

oci_close($conn);

// Redirect back to the event list page
header("Location: event_list.php");
exit;
?>
