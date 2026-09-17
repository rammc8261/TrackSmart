<?php
include('../includes/db.php');
include('../includes/faculty_header.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'faculty') {
    echo "<h2>Unauthorized Access</h2>";
    exit;
}

$facultyId = $_SESSION['user']['userId'];

// Fetch class teacher's assigned department and year
$classQuery = $conn->prepare("SELECT departmentId, year_of_study FROM class_teachers WHERE faculty_id = ?");
$classQuery->bind_param("i", $facultyId);
$classQuery->execute();
$classResult = $classQuery->get_result();
$classAssigned = $classResult->fetch_assoc();

if (!$classAssigned) {
    echo "<h2>No class assigned to this faculty!</h2>";
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
?>

<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12">
            <h3 class="font-weight-bold">Monthly Attendance Overview</h3>
            <p class="font-weight-normal">Department: <?= htmlspecialchars($departmentShortName) ?> | Year: <?= htmlspecialchars($yearOfStudy) ?></p>
        </div>
    </div>

    <!-- Date Selector -->
    <form method="GET" class="row mb-3">
        <div class="col-md-4">
            <label for="selected_date">Select Date:</label>
            <input type="date" name="selected_date" class="form-control" value="<?= date('Y-m-d') ?>" id="selected_date">
        </div>
        <div class="col-md-4">
            <label>&nbsp;</label>
            <button type="button" class="btn btn-primary btn-block" onclick="loadAttendance()">Fetch Attendance</button>
        </div>
        <div class="col-md-4 text-right">
            <button class="btn btn-success" onclick="downloadAttendanceReport()">
                <i class="fas fa-file-download"></i> Download Report
            </button>
        </div>
    </form>
    <div class="">
    </div>

    <!-- Attendance Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h3 class="mb-0">Student Attendance Report</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="attendance_table">
                        <thead>
                            <tr>
                                <th>Sr. No</th>
                                <th>Roll No</th>
                                <th>Enrollment No</th>
                                <th>Student Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be filled dynamically -->
                        </tbody>
                    </table>

                    <p class="text-center" id="no_data" style="display:none;">No attendance data available for this date.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
function loadAttendance() {
    const selectedDate = document.getElementById('selected_date').value;

    fetch(`../ajax/fetch_attendance_by_date.php?date=${selectedDate}`)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector("#attendance_table tbody");
            tableBody.innerHTML = "";
            let srNo = 1;

            if (data.length > 0) {
                data.forEach(student => {
                    let slotsHTML = '';

                    student.slots.forEach((slot, index) => {
                        const statusColor = slot === 'Present' ? 'badge-success' :
                            slot === 'Absent' ? 'badge-danger' : 'badge-secondary';

                        slotsHTML += `
                            <span class="badge ${statusColor}">Slot ${index + 1}: ${slot}</span> `;
                    });

                    tableBody.innerHTML += `
                        <tr>
                            <td>${srNo++}</td>
                            <td>${student.roll_no}</td>
                            <td>${student.enrollment_no}</td>
                            <td>${student.student_name}</td>
                            <td>${slotsHTML}</td>
                        </tr>`;
                });
            } else {
                document.getElementById('no_data').style.display = 'block';
            }
        })
        .catch(error => console.error('Error fetching data:', error));
}

function downloadAttendanceReport() {
    const table = document.getElementById('attendance_table');
    const selectedDate = document.getElementById('selected_date').value || "NoDate";
    const department = "<?= htmlspecialchars($departmentShortName) ?>";
    const year = "<?= htmlspecialchars($yearOfStudy) ?>";
    const filename = `Attendance_Report_${department}_Year${year}_${selectedDate}.xlsx`;

    if (table.rows.length <= 1) {
        alert("No data to export!");
        return;
    }

    let data = [];
    const headers = [];

    // Extract table headers
    for (const th of table.querySelectorAll("th")) {
        headers.push(th.innerText.trim());
    }
    data.push(headers);

    // Extract table rows
    for (const row of table.querySelectorAll("tbody tr")) {
        const rowData = [];
        for (const cell of row.querySelectorAll("td")) {
            rowData.push(cell.innerText.trim());
        }
        data.push(rowData);
    }

    const worksheet = XLSX.utils.aoa_to_sheet(data);
    const workbook = XLSX.utils.book_new();

    XLSX.utils.book_append_sheet(workbook, worksheet, "Attendance Report");
    XLSX.writeFile(workbook, filename);
}

</script>

<?php include('../includes/admin_footer.php'); ?>
