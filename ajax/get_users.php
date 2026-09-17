<?php
// include('../includes/db.php');
// header('Content-Type: application/json');

// $result = $conn->query("SELECT users.*, departments.department_name, user_roles.role_name 
//     FROM users 
//     JOIN departments ON users.department = departments.department_id
//     JOIN user_roles ON users.role = user_roles.id");

// $users = [];
// while ($row = $result->fetch_assoc()) {
//     $users[] = $row;
// }

// echo json_encode(['users' => $users]);

include('../includes/db.php');
header('Content-Type: application/json');

try {
    $stmt = $conn->prepare("SELECT u.id, u.full_name, u.contact_number, u.email, u.user_id, d.department_name, r.role_name 
                            FROM users u
                            LEFT JOIN departments d ON u.department = d.department_id
                            LEFT JOIN user_roles r ON u.role = r.id");

    $stmt->execute();
    $users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    echo json_encode(['success' => true, 'users' => $users]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error fetching users.', 'error' => $e->getMessage()]);
}
?>
