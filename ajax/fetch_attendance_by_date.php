<<<<<<< HEAD
<?php
session_start();
include('../includes/db.php');

$date = $_GET['date'] ?? '';
$facultyId = $_SESSION['user']['userId'];

if (!$date) {
    echo json_encode([]);
    exit;
}

// Get faculty's assigned department and year
$classQuery = $conn->prepare("SELECT departmentId, year_of_study FROM class_teachers WHERE faculty_id = ?");
$classQuery->bind_param("i", $facultyId);
$classQuery->execute();
$classResult = $classQuery->get_result()->fetch_assoc();

if (!$classResult) {
    echo json_encode([]);
    exit;
}

$departmentId = $classResult['departmentId'];
$yearOfStudy = $classResult['year_of_study'];

// Fetch students of that department & year
$query = "
    SELECT s.roll_no, s.enrollment_no, s.student_name, sa.lecture_time_slot, sa.attendance_status
    FROM student_attendance sa
    JOIN students s ON sa.student_id = s.id
    WHERE sa.department = (SELECT department_short_code FROM departments WHERE department_id = ?)
    AND sa.year_of_study = ?
    AND sa.attendance_date = ?
    ORDER BY s.roll_no";

$stmt = $conn->prepare($query);
$stmt->bind_param("iis", $departmentId, $yearOfStudy, $date);
$stmt->execute();
$result = $stmt->get_result();

$studentData = [];

while ($row = $result->fetch_assoc()) {
    $enrollment = $row['enrollment_no'];

    if (!isset($studentData[$enrollment])) {
        $studentData[$enrollment] = [
            "roll_no" => $row['roll_no'],
            "enrollment_no" => $enrollment,
            "student_name" => $row['student_name'],
            "slots" => []
        ];
    }

    $studentData[$enrollment]['slots'][] = $row['attendance_status'];
}

echo json_encode(array_values($studentData));
?>
=======
<?php
session_start();
include('../includes/db.php');

$date = $_GET['date'] ?? '';
$facultyId = $_SESSION['user']['userId'];

if (!$date) {
    echo json_encode([]);
    exit;
}

// Get faculty's assigned department and year
$classQuery = $conn->prepare("SELECT departmentId, year_of_study FROM class_teachers WHERE faculty_id = ?");
$classQuery->bind_param("i", $facultyId);
$classQuery->execute();
$classResult = $classQuery->get_result()->fetch_assoc();

if (!$classResult) {
    echo json_encode([]);
    exit;
}

$departmentId = $classResult['departmentId'];
$yearOfStudy = $classResult['year_of_study'];

// Fetch students of that department & year
$query = "
    SELECT s.roll_no, s.enrollment_no, s.student_name, sa.lecture_time_slot, sa.attendance_status
    FROM student_attendance sa
    JOIN students s ON sa.student_id = s.id
    WHERE sa.department = (SELECT department_short_code FROM departments WHERE department_id = ?)
    AND sa.year_of_study = ?
    AND sa.attendance_date = ?
    ORDER BY s.roll_no";

$stmt = $conn->prepare($query);
$stmt->bind_param("iis", $departmentId, $yearOfStudy, $date);
$stmt->execute();
$result = $stmt->get_result();

$studentData = [];

while ($row = $result->fetch_assoc()) {
    $enrollment = $row['enrollment_no'];

    if (!isset($studentData[$enrollment])) {
        $studentData[$enrollment] = [
            "roll_no" => $row['roll_no'],
            "enrollment_no" => $enrollment,
            "student_name" => $row['student_name'],
            "slots" => []
        ];
    }

    $studentData[$enrollment]['slots'][] = $row['attendance_status'];
}

echo json_encode(array_values($studentData));
?>
>>>>>>> 225f81b85625e790025fab833e204e96e67aba44
