<<<<<<< HEAD
<?php
include('../includes/db.php');
header('Content-Type: application/json');

$id = $_GET['id'];
$success = $conn->query("DELETE FROM users WHERE id = $id");

echo json_encode(['success' => $success, 'message' => $success ? 'Faculty deleted!' : 'Failed to delete.']);
?>
=======
<?php
include('../includes/db.php');
header('Content-Type: application/json');

$id = $_GET['id'];
$success = $conn->query("DELETE FROM users WHERE id = $id");

echo json_encode(['success' => $success, 'message' => $success ? 'Faculty deleted!' : 'Failed to delete.']);
?>
>>>>>>> 225f81b85625e790025fab833e204e96e67aba44
