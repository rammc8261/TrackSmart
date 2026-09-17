<?php
include('../includes/db.php');

include('../includes/hod_header.php');
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'hod') {
    echo "<h2>Unauthorized Access</h2>";
    exit;
}

$user_id = $_SESSION['user']['user_id'];


// 1️⃣ Get HOD's department ID
$hodQuery = $conn->prepare("SELECT department FROM users WHERE user_id = ?");
$hodQuery->bind_param("s", $user_id);
$hodQuery->execute();
$hodResult = $hodQuery->get_result()->fetch_assoc();
$department_id = $hodResult['department'] ?? null;

if (!$department_id) {
    echo "<h2>Department Not Found</h2>";
    exit;
}

// 2️⃣ Get Department Short Name
$deptQuery = $conn->prepare("SELECT department_short_code FROM departments WHERE department_id = ?");
$deptQuery->bind_param("i", $department_id);
$deptQuery->execute();
$deptResult = $deptQuery->get_result()->fetch_assoc();
$department_short_name = $deptResult['department_short_code'] ?? null;

if (!$department_short_name) {
    echo "<h2>Department Short Name Not Found</h2>";
    exit;
}

$date = date('Y-m-d'); // Get today's date
$years = ['1st Year' => 1, '2nd Year' => 2, '3rd Year' => 3];

// 3️⃣ Debugging: Check if department_short_name is correct
error_log("HOD Department Short Name: " . $department_short_name);

$stats = [];
foreach ($years as $year_label => $year_value) {
    // Total Students (Fix: Fetch from `students` table directly)
    $totalQuery = $conn->prepare("SELECT COUNT(*) AS total_students FROM students WHERE year_of_study = ? AND department_short_name = ?");
    $totalQuery->bind_param("is", $year_value, $department_short_name);
    $totalQuery->execute();
    $totalResult = $totalQuery->get_result()->fetch_assoc();
    $totalStudents = $totalResult['total_students'] ?? 0;

    // Debugging: Print total students count
    error_log("Total Students in $year_label: " . $totalStudents);

    // Present Students (Check if filtering works correctly)
    $presentQuery = $conn->prepare("
        SELECT COUNT(DISTINCT student_id) AS present_count 
        FROM student_attendance 
        WHERE attendance_date = ? 
        AND attendance_status = 'Present' 
        AND year_of_study = ? 
        AND department = ?
    ");
    $presentQuery->bind_param("sis", $date, $year_value, $department_short_name);
    $presentQuery->execute();
    $presentResult = $presentQuery->get_result()->fetch_assoc();
    $presentCount = $presentResult['present_count'] ?? 0;

    // Debugging: Print present students count
    error_log("Present Students in $year_label: " . $presentCount);

    // Absent Students (Fix: Calculate correctly)
    $absentCount = max(0, $totalStudents - $presentCount); // Ensure it never goes negative

    // Debugging: Print absent students count
    error_log("Absent Students in $year_label: " . $absentCount);

    $stats[] = [
        'year' => $year_label,
        'total' => $totalStudents,
        'present' => $presentCount,
        'absent' => $absentCount
    ];
}
?>

<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
            <h3 class="font-weight-bold">HOD's Dashboard</h3>
            <p class="font-weight-normal">Select the attendance status to view more details.</p>
        </div>
        <div class="col-12 col-xl-4">
            <div class="justify-content-end d-flex">
                <a class="btn btn-primary" href="view_user_attendance.php">View Attendance</a>
         </div>
        </div>
    </div>

    <?php foreach ($stats as $row) { ?>
        <h2><?= $row['year'] ?></h2>
    <div class="row">
    <div class="col-md-3 mb-4 stretch-card transparent">
    <div class="card card-dark-blue" onclick="viewStudents('<?= $department_short_name ?>', '<?= $row['year'] ?>', 'all')">
        <div class="card-body">
            <p class="mb-4">Total Students</p>
            <p class="fs-30 mb-2"><?= $row['total'] ?></p>
        </div>
    </div>
</div>

<div class="col-md-3 mb-4 stretch-card transparent">
    <div class="card card-tale" onclick="viewStudents('<?= $department_short_name ?>', '<?= $row['year'] ?>', 'present')">
        <div class="card-body">
            <p class="mb-4">Today's Attendance</p>
            <p class="fs-30 mb-2"><?= $row['present'] ?></p>
            <p><?= number_format(($row['present'] / max(1, $row['total'])) * 100, 2) ?>%</p>
        </div>
    </div>
</div>

<div class="col-md-3 mb-4 stretch-card transparent">
    <div class="card card-light-blue" onclick="viewStudents('<?= $department_short_name ?>', '<?= $row['year'] ?>', 'absent')">
        <div class="card-body">
            <p class="mb-4">Today's Absent</p>
            <p class="fs-30 mb-2"><?= $row['absent'] ?></p>
            <p><?= number_format(($row['absent'] / max(1, $row['total'])) * 100, 2) ?>%</p>
        </div>
    </div>
</div>

    </div>
    <?php } ?>

</div>

<script>
   

    function loadDashboardStats() {
        fetch("../ajax/fetch_user_stats.php")
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById("totalUsers").innerText = data.total_users;
                    document.getElementById("presentToday").innerText = data.present_today;
                    document.getElementById("absentToday").innerText = data.absent_today;
                    document.getElementById("attendancePercentage").innerText = data.attendance_percentage + "%";
                    document.getElementById("absentPercentage").innerText = data.absent_percentage + "%";
                } else {
                    console.error("Error fetching dashboard stats.");
                }
            })
            .catch(error => console.error("Error:", error));
    }

    // Ensure both functions load when the page is ready
    document.addEventListener("DOMContentLoaded", function () {
        // loadFaculty();
        //loadDashboardStats();
    });
</script>

<script>
function viewStudents(department, year, type) {
    let date = "<?= date('Y-m-d') ?>"; // Get today's date
    window.location.href = `view_hod_students_attendance.php?dept=${department}&year=${year}&type=${type}&date=${date}`;
}
</script>

<?php include '../includes/admin_footer.php'; ?>