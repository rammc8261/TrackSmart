<?php
include('../includes/db.php');
header('Content-Type: application/json');

$name = $_POST['faculty_name'];
$contact = $_POST['contact'];
$email = $_POST['email'];
$department = $_POST['department'];
$role = $_POST['role'];

$stmt = $conn->prepare("INSERT INTO users (full_name, contact_number, email, department, role) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $name, $contact, $email, $department, $role);
$success = $stmt->execute();

echo json_encode(['success' => $success, 'message' => $success ? 'Faculty added' : 'Failed']);
?>
