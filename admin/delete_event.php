<?php
session_start();

// Check if the user is logged in
if ($_SESSION['name'] != 'admin') {
    header("Location: login.php");
    exit();
}

$conn = oci_connect("ahnaf", "123", "localhost/XE");
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$event_id = $_GET['event_id'];

// Delete event from the database
$deleteSql = "DELETE FROM event WHERE event_no = :event_id";
$stmt = oci_parse($conn, $deleteSql);
oci_bind_by_name($stmt, ':event_id', $event_id);
$result = oci_execute($stmt);

if ($result) {
    $_SESSION['successMessage'] = "Event deleted successfully!";
} else {
    $_SESSION['errorMessage'] = "Error deleting event: " . oci_error($stmt);
}

oci_free_statement($stmt);

header("Location: view_event.php"); // Redirect to the event listing page
exit();
?>
