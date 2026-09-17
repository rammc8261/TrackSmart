<?php
include('../includes/db.php');

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=attendance_details.xls");
header("Pragma: no-cache");
header("Expires: 0");

$dept = $_GET['dept'] ?? '';
$year = $_GET['year'] ?? '';
$slot = $_GET['slot'] ?? '';
$date = $_GET['date'] ?? '';

if (!$dept || !$year || !$slot || !$date) {
    die("Invalid request. Missing parameters.");
}

// Fetch attendance details
$query = "SELECT s.enrollment_no, s.roll_no, s.student_name, s.student_contact, sa.attendance_status 
          FROM student_attendance sa
          JOIN students s ON sa.student_id = s.id
          WHERE sa.department = ? AND sa.year_of_study = ? 
          AND sa.lecture_time_slot = ? AND sa.attendance_date = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("siss", $dept, $year, $slot, $date);
$stmt->execute();
$result = $stmt->get_result();

// Start output buffer to avoid interference with AJAX response
ob_start();

echo "Enrollment No.\tRoll No\tStudent Name\tContact No.\tAttendance Status\n";

while ($row = $result->fetch_assoc()) {
    echo $row['enrollment_no'] . "\t" . $row['roll_no'] . "\t" . $row['student_name'] . "\t" . $row['student_contact'] . "\t" . $row['attendance_status'] . "\n";
}

// Send the output as an Excel file
$output = ob_get_clean();
echo $output;
?>
