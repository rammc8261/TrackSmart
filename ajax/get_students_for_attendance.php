<<<<<<< HEAD
<?php
include('../includes/db.php');

$year = isset($_GET['year']) ? $_GET['year'] : '';
$department = isset($_GET['department']) ? $_GET['department'] : '';

$query = "SELECT * FROM students WHERE 1";

if (!empty($year)) {
    $query .= " AND year_of_study = '$year'";
}
if (!empty($department)) {
    $query .= " AND department_short_name = '$department'";
}

$result = $conn->query($query);
$students = [];

while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

echo json_encode(['success' => true, 'students' => $students]);
?>
=======
<?php
include('../includes/db.php');

$year = isset($_GET['year']) ? $_GET['year'] : '';
$department = isset($_GET['department']) ? $_GET['department'] : '';

$query = "SELECT * FROM students WHERE 1";

if (!empty($year)) {
    $query .= " AND year_of_study = '$year'";
}
if (!empty($department)) {
    $query .= " AND department_short_name = '$department'";
}

$result = $conn->query($query);
$students = [];

while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

echo json_encode(['success' => true, 'students' => $students]);
?>
>>>>>>> 225f81b85625e790025fab833e204e96e67aba44
