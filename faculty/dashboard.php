<?php
include('../includes/db.php');
include('../includes/faculty_header.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'faculty') {
    echo "<h2>Unauthorized Access</h2>";
    exit;
}

$user_id = $_SESSION['user']['userId']; // Faculty ID from session

// Fetch faculty's assigned class (department & year)
$classQuery = $conn->prepare("SELECT departmentId, year_of_study FROM class_teachers WHERE faculty_id = ?");
$classQuery->bind_param("i", $user_id);
$classQuery->execute();
$classResult = $classQuery->get_result();
$classAssigned = $classResult->fetch_assoc();

if ($classAssigned) {
    $departmentId = $classAssigned['departmentId'];
$yearOfStudy = $classAssigned['year_of_study'];

// Fetch department short name
$deptQuery = $conn->prepare("SELECT department_short_code FROM departments WHERE department_id = ?");
$deptQuery->bind_param("i", $departmentId);
$deptQuery->execute();
$deptResult = $deptQuery->get_result()->fetch_assoc();
$departmentShortName = $deptResult['department_short_code'] ?? null;

if (!$departmentShortName) {
    echo "<h2>Department Not Found</h2>";
    // exit;
}

// Get today's date
$date = date('Y-m-d');

// Fetch total students
$totalQuery = $conn->prepare("SELECT COUNT(*) AS total_students FROM students WHERE year_of_study = ? AND department_short_name = ?");
$totalQuery->bind_param("is", $yearOfStudy, $departmentShortName);
$totalQuery->execute();
$totalResult = $totalQuery->get_result()->fetch_assoc();
$totalStudents = $totalResult['total_students'] ?? 0;

// Fetch present students
$presentQuery = $conn->prepare("
    SELECT COUNT(DISTINCT student_id) AS present_count 
    FROM student_attendance 
    WHERE attendance_date = ? 
    AND attendance_status = 'Present' 
    AND year_of_study = ? 
    AND department = ?
");
$presentQuery->bind_param("sis", $date, $yearOfStudy, $departmentShortName);
$presentQuery->execute();
$presentResult = $presentQuery->get_result()->fetch_assoc();
$presentCount = $presentResult['present_count'] ?? 0;

// Calculate absent students
$absentCount = max(0, $totalStudents - $presentCount);
}else{
    echo "<h2>No Class Assigned</h2>";
}


?>

<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
            <h3 class="font-weight-bold">Faculty Dashboard</h3>
            <p class="font-weight-normal">Class Teacher Attendance Overview</p>
        </div>
    </div>

    <?php if($classAssigned){ ?>
    <div class="row">
        <div class="col-md-4 mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <div class="card-body">
                    <p class="mb-4">Total Students</p>
                    <p class="fs-30 mb-2"><?= $totalStudents ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4 stretch-card transparent">
            <div class="card card-tale">
                <div class="card-body">
                    <p class="mb-4">Today's Attendance</p>
                    <p class="fs-30 mb-2"><?= $presentCount ?></p>
                    <p><?= $totalStudents > 0 ? round(($presentCount / $totalStudents) * 100, 2) . "%" : "0%" ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4 stretch-card transparent">
            <div class="card card-light-blue">
                <div class="card-body">
                    <p class="mb-4">Today's Absent</p>
                    <p class="fs-30 mb-2"><?= $absentCount ?></p>
                    <p><?= $totalStudents > 0 ? round(($absentCount / $totalStudents) * 100, 2) . "%" : "0%" ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <a href="student_attendance.php" class="btn btn-info btn-block">Mark Attendance</a>
        </div>
        <div class="col-md-6">
            <a href="view_attendance_details.php" class="btn btn-success btn-block">View Attendance</a>
        </div>
    </div>
    <?php } else {?>
        <div class="row">
        <div class="col-md-6">
            <a href="student_attendance.php" class="btn btn-info btn-block">Mark Attendance</a>
        </div>

    </div>
    <?php }?>
</div>

<?php include '../includes/admin_footer.php'; ?>
