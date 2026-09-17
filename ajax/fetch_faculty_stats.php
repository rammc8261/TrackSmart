<?php
include('../includes/db.php');
session_start();
header('Content-Type: application/json');

// Check if user is logged in and is an HOD
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'hod') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

$user_id = $_SESSION['user']['user_id']; // Get logged-in HOD's user_id

// Get HOD's department
$hodQuery = $conn->prepare("SELECT department FROM users WHERE user_id = ?");
$hodQuery->bind_param("s", $user_id);
$hodQuery->execute();
$hodResult = $hodQuery->get_result()->fetch_assoc();
$department_id = $hodResult['department'] ?? null;

if (!$department_id) {
    echo json_encode(['success' => false, 'message' => 'HOD department not found.']);
    exit;
}

// Get today's date
$date = date('Y-m-d'); 

// 1. Total Faculty Count in HOD's Department
$userQuery = $conn->prepare("SELECT COUNT(*) AS total_faculty FROM users WHERE role = 6 AND department = ?");
$userQuery->bind_param("i", $department_id);
$userQuery->execute();
$userResult = $userQuery->get_result()->fetch_assoc();
$totalFaculty = $userResult['total_faculty'];

// 2. Today's Present Faculty Count
$presentQuery = $conn->prepare("
    SELECT COUNT(*) AS present_count 
    FROM attendance a 
    JOIN users u ON a.user_id = u.id 
    WHERE a.attendance_date = CURDATE() AND a.status = 'Present' AND u.role = 6 AND u.department = ?
");
$presentQuery->bind_param("i", $department_id);
$presentQuery->execute();
$presentResult = $presentQuery->get_result()->fetch_assoc();
$presentCount = $presentResult['present_count'];

// 3. Today's Absent Faculty Count
$absentQuery = $conn->prepare("
    SELECT COUNT(*) AS absent_count 
    FROM users u 
    WHERE u.role = 6 AND u.department = ? 
    AND u.id NOT IN (
        SELECT user_id FROM attendance WHERE attendance_date = CURDATE() AND status = 'Present'
    )
");
$absentQuery->bind_param("i", $department_id);
$absentQuery->execute();
$absentResult = $absentQuery->get_result()->fetch_assoc();
$absentCount = $absentResult['absent_count'];

// 4. Calculate Attendance Percentage
$totalMarked = $presentCount + $absentCount;
$attendancePercentage = ($totalMarked > 0) ? round(($presentCount / $totalMarked) * 100, 2) : 0;
$absentPercentage = 100 - $attendancePercentage;

// Return JSON Response
echo json_encode([
    'success' => true,
    'total_users' => $totalFaculty,
    'present_today' => $presentCount,
    'absent_today' => $absentCount,
    'attendance_percentage' => $attendancePercentage,
    'absent_percentage' => $absentPercentage
]);
?>
