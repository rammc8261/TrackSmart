<?php

include('../includes/db.php');
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

function sendAbsenceEmail($studentName, $email, $date, $slot, $subjectName)
{
    $host = getenv('SMTP_HOST');
    $username = getenv('SMTP_USERNAME');
    $password = getenv('SMTP_PASSWORD');
    $fromAddress = getenv('SMTP_FROM_ADDRESS');

    // Email alerts are optional; attendance recording must work without SMTP.
    if (!$host || !$username || !$password || !$fromAddress) {
        return false;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = (int) (getenv('SMTP_PORT') ?: 587);
        $mail->setFrom($fromAddress, getenv('SMTP_FROM_NAME') ?: 'TrackSmart');
        $mail->addAddress($email, $studentName);
        $mail->Subject = "Absent for {$subjectName} on {$date}";
        $mail->Body = "Dear {$studentName},\n\nYou were marked absent for {$slot} in {$subjectName} on {$date}.\n\nIf this is a mistake, kindly contact your class teacher.\n\nRegards,\nCollege Admin";

        return $mail->send();
    } catch (Exception $e) {
        error_log('Absence email could not be sent. Check SMTP environment settings.');
        return false;
    }
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

$department = $_POST['department'] ?? '';
$year = $_POST['year'] ?? '';
$facultyId = (int) ($_POST['faculty_id'] ?? 0);
$lectureTimeSlot = $_POST['lecture_time_slot'] ?? '';
$subjectName = $_POST['subject_name'] ?? '';
$absentRollNumbers = array_filter(array_map('trim', explode(',', $_POST['absent_roll_numbers'] ?? '')));
$attendanceDate = date('Y-m-d');

if (!$department || !$year || !$facultyId || !$lectureTimeSlot || !$subjectName) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Missing attendance details.']);
    exit;
}

$studentsQuery = 'SELECT id, roll_no, student_name, email FROM students WHERE department_short_name = ? AND year_of_study = ?';
$students = $conn->prepare($studentsQuery);
$students->bind_param('ss', $department, $year);
$students->execute();
$result = $students->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'No students found for this department and year.']);
    exit;
}

$checkQuery = 'SELECT id FROM student_attendance WHERE student_id = ? AND faculty_id = ? AND lecture_time_slot = ? AND subject_name = ? AND year_of_study = ? AND attendance_date = ?';
$check = $conn->prepare($checkQuery);
$insertQuery = 'INSERT INTO student_attendance (student_id, department, year_of_study, faculty_id, lecture_time_slot, subject_name, attendance_status, attendance_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
$insert = $conn->prepare($insertQuery);
$addedRecords = 0;

while ($student = $result->fetch_assoc()) {
    $status = in_array($student['roll_no'], $absentRollNumbers, true) ? 'Absent' : 'Present';
    $check->bind_param('iissss', $student['id'], $facultyId, $lectureTimeSlot, $subjectName, $year, $attendanceDate);
    $check->execute();
    $check->store_result();

    if ($check->num_rows === 0) {
        $insert->bind_param('ississss', $student['id'], $department, $year, $facultyId, $lectureTimeSlot, $subjectName, $status, $attendanceDate);
        $insert->execute();

        if (!$insert->error) {
            $addedRecords++;
            if ($status === 'Absent' && !empty($student['email'])) {
                sendAbsenceEmail($student['student_name'], $student['email'], $attendanceDate, $lectureTimeSlot, $subjectName);
            }
        }
    }

    $check->free_result();
}

echo json_encode([
    'success' => $addedRecords > 0,
    'message' => $addedRecords > 0 ? "{$addedRecords} attendance record(s) added." : 'Attendance was already recorded.',
]);
