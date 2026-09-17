<?php
include('../includes/db.php');

$year = isset($_GET['year']) ? $_GET['year'] : "";
$department = isset($_GET['department']) ? $_GET['department'] : "";

$query = "SELECT * FROM students WHERE status = 1";
if (!empty($year)) {
    $query .= " AND year_of_study = '$year'";
}
if (!empty($department)) {
    $query .= " AND department_short_name = '$department'";
}
$query .= " ORDER BY student_name ASC"; // Sorting by student name

$result = $conn->query($query);

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

echo json_encode(['success' => true, 'students' => $students]);
?>
