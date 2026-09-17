<?php
include('../includes/db.php');

$enrollment_no = $_GET['enrollment_no'] ?? '';

if (!$enrollment_no) {
    echo json_encode([]);
    exit;
}

// Fetch all slots' attendance data for the student
$query = "SELECT attendance_date, attendance_status FROM student_attendance sa
          JOIN students s ON sa.student_id = s.id
          WHERE s.enrollment_no = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $enrollment_no);
$stmt->execute();
$result = $stmt->get_result();

$attendance_data = [];

while ($row = $result->fetch_assoc()) {
    $day = date('j', strtotime($row['attendance_date']));

    // If student has even one "Present" for the day, mark the day as present
    if (!isset($attendance_data[$day])) {
        $attendance_data[$day] = 'absent'; // Default as absent
    }

    if ($row['attendance_status'] == 'Present') {
        $attendance_data[$day] = 'present'; // Override to present if found
    }
}

// Return JSON encoded attendance data
echo json_encode($attendance_data);
?>
