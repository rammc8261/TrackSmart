<?php
include('../includes/db.php');
include('../includes/admin_header.php');

$dept = $_GET['dept'] ?? '';
$year = $_GET['year'] ?? '';
$slot = $_GET['slot'] ?? '';
$date = $_GET['date'] ?? '';

if (!$dept || !$year || !$slot || !$date) {
    die("<div class='alert alert-danger'>Invalid request. Missing parameters.</div>");
}

// Fetch attendance details, sorted by Roll No
$query = "SELECT s.roll_no, s.student_contact, s.enrollment_no, s.student_name, sa.attendance_status 
          FROM student_attendance sa
          JOIN students s ON sa.student_id = s.id
          WHERE sa.department = ? AND sa.year_of_study = ? 
          AND sa.lecture_time_slot = ? AND sa.attendance_date = ?
          ORDER BY CAST(s.roll_no AS UNSIGNED) ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("siss", $dept, $year, $slot, $date);
$stmt->execute();
$result = $stmt->get_result();
?>


    <style>
        .container { margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 10px; text-align: center; border: 1px solid #ddd; }
        th { background-color: #007bff; color: white; }
        .present { color:rgb(4, 117, 31); }
        .absent { background-color:rgb(161, 10, 25); color: white;}
        .back-btn { margin-bottom: 15px; }
    </style>

<div class="container mb-4">
    <!-- <button class="btn btn-secondary back-btn" onclick="window.history.back()">⬅ Go Back</button> -->
     <div class="row">
        <div class="col-sm-9">
            <h2>Attendance Details</h2>
            <p><strong>Department:</strong> <?= htmlspecialchars($dept) ?> | 
            <strong>Year:</strong> <?= htmlspecialchars($year) ?> | 
            <strong>Time Slot:</strong> <?= htmlspecialchars($slot) ?> | 
            <strong>Date:</strong> <?= htmlspecialchars($date) ?></p>
        </div>
        <div class="col-sm-3">
            <button onclick="exportToExcel()" class="btn btn-primary">📥 Export to Excel</button>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Enrollment No.</th>
                <th>Roll No</th>
                <th>Student Name</th>
                <th>Contact Number</th>
                <th>Attendance Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                    <a href="#" onclick="loadAttendance('<?= $row['enrollment_no'] ?>')">
                        <?= $row['enrollment_no'] ?>
                    </a>
                    </td>
                    <td><?= htmlspecialchars($row['roll_no']) ?></td>
                    <td><?= htmlspecialchars($row['student_name']) ?></td>
                    <td><a href="tel:<?= htmlspecialchars($row['student_contact']) ?>">
                        <?= htmlspecialchars($row['student_contact']) ?>
                    </a></td>
                    <td class="<?= strtolower($row['attendance_status']) ?>">
                        <?= htmlspecialchars($row['attendance_status']) ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<div class="container">

</div>

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
            <ul style="display: flex; list-style: none; padding: 0; margin: 0;">
                <li><span class="legend-box" style="background-color: #2ECC71;"></span> Present</li>
                <li><span class="legend-box" style="background-color: #E74C3C;"></span> Absent</li>
                <li><span class="legend-box" style="background-color: #F1C40F;"></span> Holiday</li>
                <li><span class="legend-box" style="background-color: white; border: 1px solid #ddd;"></span> Not Taken</li>
            </ul>
        </div>
    </div>
</div>


<style type="text/css">


 /* Calendar Styles */
 
</style>


<script>
function showCalendar() {
    document.getElementById('calendar-modal').style.display = 'flex';
}

function closeCalendar() {
    document.getElementById('calendar-modal').style.display = 'none';
}

function loadAttendance(enrollmentNo) {
    document.getElementById('calendar-header').innerText = `Attendance Calendar for (${enrollmentNo})`;

    // AJAX Request to Fetch Attendance Data
    fetch(`../ajax/fetch_single_student_attendance.php?enrollment_no=${enrollmentNo}`)
        .then(response => response.json())
        .then(data => {
            const calendarBody = document.getElementById('calendar-body');
            calendarBody.innerHTML = '';

            let week = '';
            for (let day = 1; day <= 31; day++) {
                let status = data[day] || 'not-taken'; // Default status
                week += `<td class="${status}">${day}</td>`;

                if (day % 7 === 0 || day === 31) {
                    calendarBody.innerHTML += `<tr>${week}</tr>`;
                    week = '';
                }
            }

            showCalendar();
        })
        .catch(error => console.error('Error fetching data:', error));
}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    // Export Attendance to Excel
    function exportToExcel() {
        let table = document.querySelector("table"); // Select the attendance table
        let wb = XLSX.utils.book_new(); // Create a new workbook
        let ws = XLSX.utils.table_to_sheet(table); // Convert table to sheet

        // Fetch details from the page
        let department = "<?= $dept ?>";
        let year = "<?= $year ?>";
        let slot = "<?= $slot ?>";
        let date = "<?= $date ?>";

        // Add details at the top
        let details = [
            ["Attendance Details"], // Title
            [`Date: ${date}`], 
            [`Department: ${department}`], 
            [`Year: ${year}`], 
            [`Slot: ${slot}`], 
            [] // Empty row before table
        ];

        // Convert details into a sheet
        let detailsSheet = XLSX.utils.aoa_to_sheet(details);

        // Merge details and table data
        XLSX.utils.sheet_add_json(detailsSheet, XLSX.utils.sheet_to_json(ws, { header: 1 }), { origin: "A7" });

        // Append sheet to workbook
        XLSX.utils.book_append_sheet(wb, detailsSheet, "Attendance Details");

        // Download file
        XLSX.writeFile(wb, "attendance_details.xlsx");
    }
</script>



<?php include('../includes/admin_footer.php'); ?>
