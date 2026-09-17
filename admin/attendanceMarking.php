<?php 
include('../includes/db.php');
include('../sendSms.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['attendance_date'];
    $faculty_id = $_POST['faculty_id'];
    $attendance_data = $_POST['attendance']; // Example: ['student_id' => 'Present/Absent']

    foreach ($attendance_data as $student_id => $status) {
        // Get student details
        $student_query = "SELECT student_name, parent_contact FROM students WHERE id = ?";
        $stmt = $conn->prepare($student_query);
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();

        // Insert attendance record
        $insert_query = "INSERT INTO student_attendance (student_id, attendance_status, attendance_date, faculty_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("issi", $student_id, $status, $date, $faculty_id);
        $stmt->execute();

        // Send SMS if student is absent
        if ($status == 'Absent') {
            $message = "Dear Parent, your child {$student['student_name']} was absent on {$date}. Please ensure their attendance.";
            sendSMS($student['parent_contact'], $message);
        }
    }
}
