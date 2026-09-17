<<<<<<< HEAD
<?php
include('../includes/db.php');
header('Content-Type: application/json');

$role_filter = isset($_GET['role']) ? $_GET['role'] : '';

$query = "SELECT a.id, u.full_name, d.department_name, r.role_name, a.attendance_date, a.status
          FROM attendance a
          INNER JOIN users u ON a.user_id = u.id
          INNER JOIN departments d ON u.department = d.department_id
          INNER JOIN user_roles r ON u.role = r.id";

if (!empty($role_filter)) {
    $query .= " WHERE u.role = ?";
}

$stmt = $conn->prepare($query);

if (!empty($role_filter)) {
    $stmt->bind_param("i", $role_filter);
}

$stmt->execute();
$result = $stmt->get_result();
$attendance = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode(['success' => true, 'attendance' => $attendance]);
?>
=======
<?php
include('../includes/db.php');
header('Content-Type: application/json');

$role_filter = isset($_GET['role']) ? $_GET['role'] : '';

$query = "SELECT a.id, u.full_name, d.department_name, r.role_name, a.attendance_date, a.status
          FROM attendance a
          INNER JOIN users u ON a.user_id = u.id
          INNER JOIN departments d ON u.department = d.department_id
          INNER JOIN user_roles r ON u.role = r.id";

if (!empty($role_filter)) {
    $query .= " WHERE u.role = ?";
}

$stmt = $conn->prepare($query);

if (!empty($role_filter)) {
    $stmt->bind_param("i", $role_filter);
}

$stmt->execute();
$result = $stmt->get_result();
$attendance = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode(['success' => true, 'attendance' => $attendance]);
?>
>>>>>>> 225f81b85625e790025fab833e204e96e67aba44
