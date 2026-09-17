<?php 
include('../includes/db.php'); 
include('../includes/hod_header.php'); 
?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row mb-3">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">View Attendance</h3>
                    <p class="font-weight-normal">Filter and check attendance records.</p>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="justify-content-end d-flex">
                        <a class="btn btn-primary" href="user_attendance.php">Mark Attendance</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <div class="card-body">
                    <p class="mb-4">Users</p>
                    <p class="fs-30 mb-2" id="totalUsers">0</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card card-tale">
                <div class="card-body">
                    <p class="mb-4">Today's Attendance</p>
                    <p class="fs-30 mb-2" id="presentToday">00</p>
                    <!-- <p id="attendancePercentage">90.00%</p> -->
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card card-light-blue">
                <div class="card-body">
                    <p class="mb-4">Today's Absent</p>
                    <p class="fs-30 mb-2" id="absentToday">00</p>
                    <!-- <p id="absentPercentage">10.00%</p> -->
                </div>
            </div>
        </div>
    </div>
    <div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Faculty Attendance List</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Department</th>
                                <th>Role</th>
                                <th>Attendance Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="faculty-attendance-list">
                            <!-- Data will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<script>
    
    // Load Faculty Attendance
function loadFacultyAttendance() {
    fetch("../ajax/get_faculty_attendance.php") // Ensure this is the correct API path
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let rows = "";
                data.attendance.forEach(user => {
                    rows += `<tr>
                        <td>${user.id}</td>
                        <td>${user.full_name}</td>
                        <td>${user.department_name}</td>
                        <td>${user.role_name}</td>
                        <td>${user.attendance_date}</td>
                        <td><span class="badge ${user.status === 'Present' ? 'badge-success' : 'badge-danger'}">
                            ${user.status}
                        </span></td>
                    </tr>`;
                });
                document.getElementById("faculty-attendance-list").innerHTML = rows;
            } else {
                document.getElementById("faculty-attendance-list").innerHTML = `<tr><td colspan="6">No records found</td></tr>`;
            }
        })
        .catch(error => console.error("Error fetching attendance:", error));
}




    function loadDashboardStats() {
        fetch("../ajax/fetch_faculty_stats.php")
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById("totalUsers").innerText = data.total_users;
                    document.getElementById("presentToday").innerText = data.present_today;
                    document.getElementById("absentToday").innerText = data.absent_today;
                    // document.getElementById("attendancePercentage").innerText = data.attendance_percentage + "%";
                    // document.getElementById("absentPercentage").innerText = data.absent_percentage + "%";
                } else {
                    console.error("Error fetching dashboard stats.");
                }
            })
            .catch(error => console.error("Error:", error));
    }
    document.addEventListener("DOMContentLoaded", function () {
        loadFacultyAttendance();
        loadDashboardStats();
    });
</script>

<?php include '../includes/admin_footer.php'; ?>