<<<<<<< HEAD
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

if (!$classAssigned) {
    echo "<h2>No Class Assigned</h2>";
    exit;
}

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
    exit;
}

// Get filters from URL
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$selectedStatus = isset($_GET['status']) ? $_GET['status'] : '';

// Fetch attendance records
$query = "
    SELECT s.roll_no, s.enrollment_no, s.student_name, sa.attendance_status, sa.attendance_date, sa.lecture_time_slot 
    FROM student_attendance sa 
    JOIN students s ON sa.student_id = s.id 
    WHERE sa.year_of_study = ? 
    AND sa.department = ?
    AND sa.attendance_date = ?";

if ($selectedStatus) {
    $query .= " AND sa.attendance_status = ?";
}

$stmt = $conn->prepare($query);

if ($selectedStatus) {
    $stmt->bind_param("isss", $yearOfStudy, $departmentShortName, $selectedDate, $selectedStatus);
} else {
    $stmt->bind_param("iss", $yearOfStudy, $departmentShortName, $selectedDate);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12">
            <h3 class="font-weight-bold">View Student Attendance</h3>
            <p class="font-weight-normal">Attendance for <?= htmlspecialchars($departmentShortName) ?>, Year <?= htmlspecialchars($yearOfStudy) ?></p>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="" class="row mb-3">
        <div class="col-md-4">
            <label>Date:</label>
            <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($selectedDate) ?>">
        </div>
        <div class="col-md-4">
            <label>Status:</label>
            <select name="status" class="form-control">
                <option value="">All</option>
                <option value="Present" <?= ($selectedStatus == 'Present') ? 'selected' : '' ?>>Present</option>
                <option value="Absent" <?= ($selectedStatus == 'Absent') ? 'selected' : '' ?>>Absent</option>
            </select>
        </div>
        <div class="col-md-4">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-primary btn-block">Filter</button>
        </div>
    </form>

    <!-- Attendance Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h3 class="mb-0">Attendance Records</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sr. No</th>
                                <th>Roll No</th>
                                <th>Enrollment No</th>
                                <th>Student Name</th>
                                <th>Date</th>
                                <th>Time Slot</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $srNo = 1;
                            while ($row = $result->fetch_assoc()) : ?>
                                <tr>
                                    <td><?= $srNo++ ?></td>
                                    <td><?= htmlspecialchars($row['roll_no']) ?></td>
                                    <td>
                                    <a class="text-primary" role="button" onclick="loadAttendance('<?= $row['enrollment_no'] ?>')">
                                        <?= $row['enrollment_no'] ?>
                                    </a>
                                    </td>
                                    <td><?= htmlspecialchars($row['student_name']) ?></td>
                                    <td><?= htmlspecialchars($row['attendance_date']) ?></td>
                                    <td><?= htmlspecialchars($row['lecture_time_slot']) ?></td>
                                    <td>
                                        <span class="badge <?= $row['attendance_status'] == 'Present' ? 'badge-success' : 'badge-danger' ?>">
                                            <?= htmlspecialchars($row['attendance_status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                    <?php if ($result->num_rows == 0) : ?>
                        <p class="text-center">No attendance records found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Attendance Calendar -->

<div class="calendar-modal" id="calendar-modal">
    <div class="calendar-content">
        <button class="close-btn" onclick="closeCalendar()">&#10006;</button>
        <div class="calendar-header" id="calendar-header">Attendance Calendar</div>
        <table class="calendar-table">
            <thead>
                <tr>
                    <th>SU</th><th>MO</th><th>TU</th><th>WE</th><th>TH</th><th>FR</th><th>SA</th>
                </tr>
            </thead>
            <tbody id="calendar-body">
                <!-- Days will be dynamically loaded here -->
            </tbody>
        </table>
        <div class="legend">
            <ul style="display: flex; list-style: none; padding: 0; margin: 0;justify-content: space-between;">
                <li><span class="legend-box" style="background-color: #2ECC71;"></span> Present</li>
                <li><span class="legend-box" style="background-color: #E74C3C;"></span> Absent</li>
                <li><span class="legend-box" style="background-color: #F1C40F;"></span> Holiday</li>
                <li><span class="legend-box" style="background-color: white; border: 1px solid #ddd;"></span> Not Taken</li>
                <button class="btn btn-primary" id="download-report" onclick="downloadStudentReport()">Download Report</button>
            </ul>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<!-- JavaScript to Load Calendar -->
<script>
let studentAttendanceData = {};
let currentEnrollmentNo = '';

function showCalendar() {
    document.getElementById('calendar-modal').style.display = 'flex';
}

function closeCalendar() {
    document.getElementById('calendar-modal').style.display = 'none';
}

// Load attendance for the student
function loadAttendance(enrollmentNo) {
    currentEnrollmentNo = enrollmentNo;
    document.getElementById('calendar-header').innerText = `Attendance Calendar for (${enrollmentNo})`;

    fetch(`../ajax/fetch_single_student_attendance.php?enrollment_no=${enrollmentNo}`)
        .then(response => response.json())
        .then(data => {
            studentAttendanceData = data; // Save data for report export

            const calendarBody = document.getElementById('calendar-body');
            calendarBody.innerHTML = '';

            let week = '';
            for (let day = 1; day <= 31; day++) {
                let status = data[day] || 'not-taken'; // Default status
                let statusClass = status === 'present' ? 'present' : status === 'absent' ? 'absent' : 'not-taken';

                week += `<td class="${statusClass}">${day}</td>`;

                if (day % 7 === 0 || day === 31) {
                    calendarBody.innerHTML += `<tr>${week}</tr>`;
                    week = '';
                }
            }

            showCalendar();
        })
        .catch(error => console.error('Error fetching data:', error));
}

// 🎯 Function to Export Report to Excel
function downloadStudentReport() {
    if (!currentEnrollmentNo || Object.keys(studentAttendanceData).length === 0) {
        alert("No data available to download!");
        return;
    }

    const monthName = new Date().toLocaleString('default', { month: 'long' });
    const year = new Date().getFullYear();

    const reportData = [["Date", "Status"]];

    // Populate rows with day & status
    for (let day = 1; day <= 31; day++) {
        const status = studentAttendanceData[day] || 'Not Taken';
        reportData.push([`${day}-${monthName}-${year}`, status.charAt(0).toUpperCase() + status.slice(1)]);
    }

    // Create worksheet and workbook
    const worksheet = XLSX.utils.aoa_to_sheet(reportData);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, `${monthName} Attendance`);

    // Save the file
    XLSX.writeFile(workbook, `Attendance_Report_${currentEnrollmentNo}_${monthName}_${year}.xlsx`);
}

</script>




<?php include('../includes/admin_footer.php'); ?>
=======
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

if (!$classAssigned) {
    echo "<h2>No Class Assigned</h2>";
    exit;
}

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
    exit;
}

// Get filters from URL
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$selectedStatus = isset($_GET['status']) ? $_GET['status'] : '';

// Fetch attendance records
$query = "
    SELECT s.roll_no, s.enrollment_no, s.student_name, sa.attendance_status, sa.attendance_date, sa.lecture_time_slot 
    FROM student_attendance sa 
    JOIN students s ON sa.student_id = s.id 
    WHERE sa.year_of_study = ? 
    AND sa.department = ?
    AND sa.attendance_date = ?";

if ($selectedStatus) {
    $query .= " AND sa.attendance_status = ?";
}

$stmt = $conn->prepare($query);

if ($selectedStatus) {
    $stmt->bind_param("isss", $yearOfStudy, $departmentShortName, $selectedDate, $selectedStatus);
} else {
    $stmt->bind_param("iss", $yearOfStudy, $departmentShortName, $selectedDate);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12">
            <h3 class="font-weight-bold">View Student Attendance</h3>
            <p class="font-weight-normal">Attendance for <?= htmlspecialchars($departmentShortName) ?>, Year <?= htmlspecialchars($yearOfStudy) ?></p>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="" class="row mb-3">
        <div class="col-md-4">
            <label>Date:</label>
            <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($selectedDate) ?>">
        </div>
        <div class="col-md-4">
            <label>Status:</label>
            <select name="status" class="form-control">
                <option value="">All</option>
                <option value="Present" <?= ($selectedStatus == 'Present') ? 'selected' : '' ?>>Present</option>
                <option value="Absent" <?= ($selectedStatus == 'Absent') ? 'selected' : '' ?>>Absent</option>
            </select>
        </div>
        <div class="col-md-4">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-primary btn-block">Filter</button>
        </div>
    </form>

    <!-- Attendance Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h3 class="mb-0">Attendance Records</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sr. No</th>
                                <th>Roll No</th>
                                <th>Enrollment No</th>
                                <th>Student Name</th>
                                <th>Date</th>
                                <th>Time Slot</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $srNo = 1;
                            while ($row = $result->fetch_assoc()) : ?>
                                <tr>
                                    <td><?= $srNo++ ?></td>
                                    <td><?= htmlspecialchars($row['roll_no']) ?></td>
                                    <td>
                                    <a class="text-primary" role="button" onclick="loadAttendance('<?= $row['enrollment_no'] ?>')">
                                        <?= $row['enrollment_no'] ?>
                                    </a>
                                    </td>
                                    <td><?= htmlspecialchars($row['student_name']) ?></td>
                                    <td><?= htmlspecialchars($row['attendance_date']) ?></td>
                                    <td><?= htmlspecialchars($row['lecture_time_slot']) ?></td>
                                    <td>
                                        <span class="badge <?= $row['attendance_status'] == 'Present' ? 'badge-success' : 'badge-danger' ?>">
                                            <?= htmlspecialchars($row['attendance_status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                    <?php if ($result->num_rows == 0) : ?>
                        <p class="text-center">No attendance records found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Attendance Calendar -->

<div class="calendar-modal" id="calendar-modal">
    <div class="calendar-content">
        <button class="close-btn" onclick="closeCalendar()">&#10006;</button>
        <div class="calendar-header" id="calendar-header">Attendance Calendar</div>
        <table class="calendar-table">
            <thead>
                <tr>
                    <th>SU</th><th>MO</th><th>TU</th><th>WE</th><th>TH</th><th>FR</th><th>SA</th>
                </tr>
            </thead>
            <tbody id="calendar-body">
                <!-- Days will be dynamically loaded here -->
            </tbody>
        </table>
        <div class="legend">
            <ul style="display: flex; list-style: none; padding: 0; margin: 0;justify-content: space-between;">
                <li><span class="legend-box" style="background-color: #2ECC71;"></span> Present</li>
                <li><span class="legend-box" style="background-color: #E74C3C;"></span> Absent</li>
                <li><span class="legend-box" style="background-color: #F1C40F;"></span> Holiday</li>
                <li><span class="legend-box" style="background-color: white; border: 1px solid #ddd;"></span> Not Taken</li>
                <button class="btn btn-primary" id="download-report" onclick="downloadStudentReport()">Download Report</button>
            </ul>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<!-- JavaScript to Load Calendar -->
<script>
let studentAttendanceData = {};
let currentEnrollmentNo = '';

function showCalendar() {
    document.getElementById('calendar-modal').style.display = 'flex';
}

function closeCalendar() {
    document.getElementById('calendar-modal').style.display = 'none';
}

// Load attendance for the student
function loadAttendance(enrollmentNo) {
    currentEnrollmentNo = enrollmentNo;
    document.getElementById('calendar-header').innerText = `Attendance Calendar for (${enrollmentNo})`;

    fetch(`../ajax/fetch_single_student_attendance.php?enrollment_no=${enrollmentNo}`)
        .then(response => response.json())
        .then(data => {
            studentAttendanceData = data; // Save data for report export

            const calendarBody = document.getElementById('calendar-body');
            calendarBody.innerHTML = '';

            let week = '';
            for (let day = 1; day <= 31; day++) {
                let status = data[day] || 'not-taken'; // Default status
                let statusClass = status === 'present' ? 'present' : status === 'absent' ? 'absent' : 'not-taken';

                week += `<td class="${statusClass}">${day}</td>`;

                if (day % 7 === 0 || day === 31) {
                    calendarBody.innerHTML += `<tr>${week}</tr>`;
                    week = '';
                }
            }

            showCalendar();
        })
        .catch(error => console.error('Error fetching data:', error));
}

// 🎯 Function to Export Report to Excel
function downloadStudentReport() {
    if (!currentEnrollmentNo || Object.keys(studentAttendanceData).length === 0) {
        alert("No data available to download!");
        return;
    }

    const monthName = new Date().toLocaleString('default', { month: 'long' });
    const year = new Date().getFullYear();

    const reportData = [["Date", "Status"]];

    // Populate rows with day & status
    for (let day = 1; day <= 31; day++) {
        const status = studentAttendanceData[day] || 'Not Taken';
        reportData.push([`${day}-${monthName}-${year}`, status.charAt(0).toUpperCase() + status.slice(1)]);
    }

    // Create worksheet and workbook
    const worksheet = XLSX.utils.aoa_to_sheet(reportData);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, `${monthName} Attendance`);

    // Save the file
    XLSX.writeFile(workbook, `Attendance_Report_${currentEnrollmentNo}_${monthName}_${year}.xlsx`);
}

</script>




<?php include('../includes/admin_footer.php'); ?>
>>>>>>> 225f81b85625e790025fab833e204e96e67aba44
