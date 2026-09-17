<?php
include('../includes/db.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $attendance_date = $_POST['attendance_date'];
    $attendance = $_POST['attendance'];

    foreach ($attendance as $student_id => $lectures) {
        foreach ($lectures as $lecture => $status) {
            // Check if attendance is already marked
            $stmt = $conn->prepare("SELECT id FROM mark_attendance WHERE student_id = ? AND attendance_date = ? AND lecture = ?");
            $stmt->bind_param("iss", $student_id, $attendance_date, $lecture);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 0) {
                // Insert attendance if not already marked
                $stmt = $conn->prepare("INSERT INTO mark_attendance (student_id, attendance_date, lecture, status) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $student_id, $attendance_date, $lecture, $status);
                $stmt->execute();
            }
        }
    }

    echo json_encode(['success' => true, 'message' => 'Attendance marked successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
