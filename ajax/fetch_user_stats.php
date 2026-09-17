<<<<<<< HEAD
<?php
include('../includes/db.php');
header('Content-Type: application/json');

$date = date('Y-m-d'); // Get today's date

// Total Users Count
$userQuery = $conn->query("SELECT COUNT(*) AS total_users FROM users");
$totalUsers = $userQuery->fetch_assoc()['total_users'];

// Today's Present Count
$presentQuery = $conn->prepare("SELECT COUNT(*) AS present_count FROM attendance WHERE attendance_date = ? AND status = 'Present'");
$presentQuery->bind_param("s", $date);
$presentQuery->execute();
$presentResult = $presentQuery->get_result()->fetch_assoc();
$presentCount = $presentResult['present_count'];

// Today's Absent Count
$absentQuery = $conn->prepare("SELECT COUNT(*) AS absent_count FROM attendance WHERE attendance_date = ? AND status = 'Absent'");
$absentQuery->bind_param("s", $date);
$absentQuery->execute();
$absentResult = $absentQuery->get_result()->fetch_assoc();
$absentCount = $absentResult['absent_count'];

// Calculate Attendance Percentage
$totalMarked = $presentCount + $absentCount;
$attendancePercentage = ($totalMarked > 0) ? round(($presentCount / $totalMarked) * 100, 2) : 0;
$absentPercentage = 100 - $attendancePercentage;

// Return JSON Response
echo json_encode([
    'success' => true,
    'total_users' => $totalUsers,
    'present_today' => $presentCount,
    'absent_today' => $absentCount,
    'attendance_percentage' => $attendancePercentage,
    'absent_percentage' => $absentPercentage
]);
?>
=======
<?php
include('../includes/db.php');
header('Content-Type: application/json');

$date = date('Y-m-d'); // Get today's date

// Total Users Count
$userQuery = $conn->query("SELECT COUNT(*) AS total_users FROM users");
$totalUsers = $userQuery->fetch_assoc()['total_users'];

// Today's Present Count
$presentQuery = $conn->prepare("SELECT COUNT(*) AS present_count FROM attendance WHERE attendance_date = ? AND status = 'Present'");
$presentQuery->bind_param("s", $date);
$presentQuery->execute();
$presentResult = $presentQuery->get_result()->fetch_assoc();
$presentCount = $presentResult['present_count'];

// Today's Absent Count
$absentQuery = $conn->prepare("SELECT COUNT(*) AS absent_count FROM attendance WHERE attendance_date = ? AND status = 'Absent'");
$absentQuery->bind_param("s", $date);
$absentQuery->execute();
$absentResult = $absentQuery->get_result()->fetch_assoc();
$absentCount = $absentResult['absent_count'];

// Calculate Attendance Percentage
$totalMarked = $presentCount + $absentCount;
$attendancePercentage = ($totalMarked > 0) ? round(($presentCount / $totalMarked) * 100, 2) : 0;
$absentPercentage = 100 - $attendancePercentage;

// Return JSON Response
echo json_encode([
    'success' => true,
    'total_users' => $totalUsers,
    'present_today' => $presentCount,
    'absent_today' => $absentCount,
    'attendance_percentage' => $attendancePercentage,
    'absent_percentage' => $absentPercentage
]);
?>
>>>>>>> 225f81b85625e790025fab833e204e96e67aba44
