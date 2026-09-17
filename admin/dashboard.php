<?php
include('../includes/db.php');
include('../includes/admin_header.php');

// Fetch all departments
$departments_query = "SELECT department_short_code, department_name 
FROM departments 
WHERE department_name != 'management' AND status = 1";
$departments_result = $conn->query($departments_query);
$departments = [];

while ($dept = $departments_result->fetch_assoc()) {
    $departments[] = $dept;
}

// Define all lecture time slots
$time_slots = [
    "10:05 11:05",
    "11:05 12:05",
    "12:05 01:05",
    "01:00 02:00",
    "02:00 03:00",
    "03:00 04:00",
    "04:00 05:00"
];

// Fetch attendance data for each year
$years = ['1', '2', '3']; // 1st Year to 3rd Year
?>

<style>
    h2 {
        background: #3498db;
        color: white;
        padding: 10px;
        display: inline-block;
        width: 100%;
        font-size: 1.790rem;
    }
    .table-container {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 20px;
        padding: 0 10px;
        overflow-x: auto;
    }
    table {
        width: 90%;
        margin: 10px 0;
        background: #ffffff;
        font-size: 14px;
        border-radius: 8px;
        overflow: hidden;
        text-align: center;
    }
    th, td {
        padding: 6px;
        text-align: center;
        font-size: 13px;
        border: 2px solid #ccc;
    }
    th {
        background: #96C2DB;
        color: white;
    }
    .high { background-color: #28a745; color: white; }
    .medium { background-color: #ffc107; color: black; }
    .low { background-color: #dc3545; color: white; }
</style>

<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12">
            <h3 class="font-weight-bold">Dashboard</h3>
            <!-- <p class="font-weight-normal">Check attendance percentage year-wise.</p> -->
        </div>
    </div>

    <!-- Date Picker -->
    <div class="row mb-3">
        <div class="col-md-4">
            <label>Select Date:</label>
            <input type="date" id="attendance_date" class="form-control" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary mt-4" onclick="fetchAttendance()">Get Attendance</button>
        </div>
    </div>

    <div class="table-container" id="attendance_data">
        <!-- Attendance Tables will be loaded here via AJAX -->
    </div>
</div>

<script>
function fetchAttendance() {
    let selectedDate = document.getElementById("attendance_date").value;
    
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../ajax/fetch_students_attendance.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("attendance_data").innerHTML = xhr.responseText;
        }
    };

    xhr.send("date=" + selectedDate);
}

// Load today's data on page load
document.addEventListener("DOMContentLoaded", function() {
    fetchAttendance();
    setInterval(fetchAttendance, 60000);
});
</script>

<?php include '../includes/admin_footer.php'; ?>
