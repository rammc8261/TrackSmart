<?php
include('../includes/db.php');
session_start();
header('Content-Type: application/json');

// Ensure user is logged in and is an HOD
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'hod') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

$user_id = $_SESSION['user']['user_id']; // Get logged-in HOD's user_id

// Fetch the HOD's department
$hodQuery = $conn->prepare("SELECT department FROM users WHERE user_id = ?");
$hodQuery->bind_param("s", $user_id);
$hodQuery->execute();
$hodResult = $hodQuery->get_result()->fetch_assoc();
$department_id = $hodResult['department'] ?? null;

if (!$department_id) {
    echo json_encode(['success' => false, 'message' => 'HOD department not found.']);
    exit;
}

$date = date('Y-m-d'); // Get today's date

// **Corrected Query (Using Your Working SQL)**
$query = "
    SELECT 
    u.id, 
    u.full_name, 
    d.department_name, 
    r.role_name, 
    a.attendance_date, 
    a.status 
FROM attendance a
INNER JOIN users u ON a.user_id = u.id
INNER JOIN departments d ON u.department = d.department_id
INNER JOIN user_roles r ON u.role = r.id
WHERE a.attendance_date = CURDATE()  -- Fetch only today's records
AND u.role = 6  -- Faculty role
AND u.department = ?  -- HOD's department
AND a.status = 'Present'; -- Only present faculties
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $department_id);
$stmt->execute();
$result = $stmt->get_result();
$present_faculties = $result->fetch_all(MYSQLI_ASSOC);

// **Return Correct JSON Response**
echo json_encode(['success' => true, 'attendance' => $present_faculties]);
?>
