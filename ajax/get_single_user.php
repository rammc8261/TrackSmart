<<<<<<< HEAD
<?php
include('../includes/db.php');
header('Content-Type: application/json');

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM users WHERE id = $id");

if ($row = $result->fetch_assoc()) {
    echo json_encode(['success' => true, 'user' => $row]);
} else {
    echo json_encode(['success' => false, 'message' => 'User not found']);
}
?>
=======
<?php
include('../includes/db.php');
header('Content-Type: application/json');

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM users WHERE id = $id");

if ($row = $result->fetch_assoc()) {
    echo json_encode(['success' => true, 'user' => $row]);
} else {
    echo json_encode(['success' => false, 'message' => 'User not found']);
}
?>
>>>>>>> 225f81b85625e790025fab833e204e96e67aba44
