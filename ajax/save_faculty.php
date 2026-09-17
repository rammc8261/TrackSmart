<?php
include('../includes/db.php');
header('Content-Type: application/json');

$id = $_POST['faculty_id'] ?? null;
$name = $_POST['faculty_name'];
$contact = $_POST['contact'];
$email = $_POST['email'];
$user_id = $_POST['user_id'];
$password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
$department = $_POST['department'];
$role = $_POST['role'];

if ($id) {
    if ($password) {
        // Update with password change
        $stmt = $conn->prepare("UPDATE users SET full_name=?, contact_number=?, email=?, user_id=?, password=?, department=?, role=? WHERE id=?");
        $stmt->bind_param("ssssssii", $name, $contact, $email, $user_id, $password, $department, $role, $id);
    } else {
        // Update without changing password
        $stmt = $conn->prepare("UPDATE users SET full_name=?, contact_number=?, email=?, user_id=?, department=?, role=? WHERE id=?");
        $stmt->bind_param("ssssiii", $name, $contact, $email, $user_id, $department, $role, $id);
    }
} else {
    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (full_name, contact_number, email, user_id, password, department, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $name, $contact, $email, $user_id, $password, $department, $role);
}

$success = $stmt->execute();
echo json_encode(['success' => $success, 'message' => $success ? 'Faculty saved successfully!' : 'Failed to save faculty.']);
?>
