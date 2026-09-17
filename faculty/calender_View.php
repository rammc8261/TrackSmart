<?php
include('../includes/db.php');
include('../includes/faculty_header.php');

// Ensure only class teachers can access
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'faculty') {
    echo "<h2>Unauthorized Access</h2>";
    exit;
}

$user_id = $_SESSION['user']['userId']; // Faculty ID from session

// Get the class teacher's department and year
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

// Fetch department short code
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
            <p class="font-weight-normal">Attendance for <?= htmlspecialchars($departmentShortName) ?>, Year <?= htmlspecialchars($yearOfStudy) ?></p>
        </div>
    </div>

    <!-- Date Input -->
    <div class="row mb-3">
        <div class="col-md-4">
            <label>Select Month:</label>
            <input type="month" id="month-select" class="form-control" value="<?= date('Y-m') ?>">
        </div>
    </div>

    <!-- Calendar Display -->
    <div class="calendar-container card" id="calendar-container">
        <!-- Calendar will render here -->
    </div>

    <!-- Attendance Table -->
    <div class="mt-4">
        <h4 class="mb-3" id="selected-date-title">Select a date to view attendance</h4>
        <div class="card p-2">
        <table class="table" id="attendance-table" style="display: none;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Roll No</th>
                    <th>Enrollment No</th>
                    <th>Student Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        </div>
    </div>
</div>

<script>
// 🎯 Function to render calendar
function renderCalendar(year, month) {
    const container = document.getElementById("calendar-container");
    container.innerHTML = ""; // Clear existing content

    let firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    let calendarHTML = "<table class='table table-bordered table-striped'><thead><tr>";
    const weekDays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
    weekDays.forEach(day => calendarHTML += `<th class="bg-primary text-white">${day}</th>`);
    calendarHTML += "</tr></thead><tbody><tr>";

    // Empty cells for previous month's trailing days
    for (let i = 0; i < firstDay; i++) calendarHTML += "<td></td>";

    // Render day cells
    for (let day = 1; day <= daysInMonth; day++) {
        calendarHTML += `<td class='calendar-day' data-date="${year}-${(month+1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}">${day}</td>`;
        if ((firstDay + day) % 7 === 0) calendarHTML += "</tr><tr>";
    }

    // Empty cells for next month's leading days
    while ((firstDay + daysInMonth) % 7 !== 0) {
        calendarHTML += "<td></td>";
        firstDay++;
    }

    calendarHTML += "</tr></tbody></table>";
    container.innerHTML = calendarHTML;

    // Add click events to each date cell
    document.querySelectorAll(".calendar-day").forEach(dayCell => {
        dayCell.addEventListener("click", () => fetchAttendance(dayCell.dataset.date));
    });
}

// 🎯 Fetch attendance data for selected date
function fetchAttendance(date) {
    document.getElementById("selected-date-title").innerText = `Attendance on ${date}`;
    let table = document.getElementById("attendance-table");
    table.style.display = "none";

    fetch(`../ajax/fetch_attendance_by_date.php?date=${date}`)
        .then(response => response.json())
        .then(data => {
            const tbody = table.querySelector("tbody");
            tbody.innerHTML = ""; // Clear previous rows
            let sr = 0;
            if (data.length > 0) {
                data.forEach(student => {
                    let slotsHTML = '';

                    student.slots.forEach((slot, index) => {
                        const statusColor = slot === 'Present' ? 'badge-success' :
                            slot === 'Absent' ? 'badge-danger' : 'badge-secondary';

                        slotsHTML += `
                            <span class="badge ${statusColor}">Slot ${index + 1}: ${slot}</span> `;
                    });

                    tbody.innerHTML += `
                        <tr>
                            <td>${++sr}</td>
                            <td>${student.roll_no}</td>
                            <td>${student.enrollment_no}</td>
                            <td>${student.student_name}</td>
                            <td>${slotsHTML}</td>
                        </tr>`;
                });
                table.style.display = "table";
            } else {
                tbody.innerHTML = "<tr><td colspan='4' class='text-center'>No records found</td></tr>";
                table.style.display = "table";
            }
        })
        .catch(error => console.error("Error fetching data:", error));
}

// 🎯 Handle month change
document.getElementById("month-select").addEventListener("change", (e) => {
    const [year, month] = e.target.value.split("-");
    renderCalendar(parseInt(year), parseInt(month) - 1);
});

// 🎯 Load the current month's calendar on page load
const currentDate = new Date();
renderCalendar(currentDate.getFullYear(), currentDate.getMonth());
</script>

<?php include('../includes/admin_footer.php'); ?>
