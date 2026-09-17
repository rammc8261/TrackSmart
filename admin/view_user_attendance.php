<?php 
include('../includes/db.php'); 
include('../includes/admin_header.php'); 
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
                    <p class="fs-30 mb-2" id="totalUsers">50</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card card-tale">
                <div class="card-body">
                    <p class="mb-4">Today's Attendance</p>
                    <p class="fs-30 mb-2" id="presentToday">24</p>
                    <p id="attendancePercentage">90.00%</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card card-light-blue">
                <div class="card-body">
                    <p class="mb-4">Today's Absent</p>
                    <p class="fs-30 mb-2" id="absentToday">5</p>
                    <p id="absentPercentage">10.00%</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                        <h4 class="card-title">Attendance Records</h4>
                        <p class="font-weight-normal">Filter and check attendance records.</p>
                    </div>
                    <div class="col-12 col-xl-4">
                        <div class="justify-content-end d-flex">
                            <select id="roleFilter" class="form-control">
                                    <option value="">All Roles</option>
                                    <?php
                                    $roles = $conn->query("SELECT * FROM user_roles");
                                    while ($role = $roles->fetch_assoc()) {
                                        echo "<option value='{$role['id']}'>{$role['role_name']}</option>";
                                    }
                                    ?>
                                </select>
                        </div>
                    </div>
                </div>
                    
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>ID</th>
                                    <th>Full Name</th>
                                    <th>Department</th>
                                    <th>Role</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="attendance-list">
                                <!-- Attendance data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function loadAttendance(role = "") {
        fetch("get_user_attendance.php?role=" + role)
            .then(response => response.json())
            .then(data => {
                let rows = "";
                if (data.success) {
                    data.attendance.forEach(record => {
                        rows += `<tr>
                        <td>${record.id}</td>
                        <td>${record.full_name}</td>
                        <td>${record.department_name}</td>
                        <td>${record.role_name}</td>
                        <td>${record.attendance_date}</td>
                        <td>${record.status}</td>
                    </tr>`;
                    });
                } else {
                    rows = "<tr><td colspan='6'>No records found</td></tr>";
                }
                document.getElementById("attendance-list").innerHTML = rows;
            })
            .catch(error => console.error("Fetch error:", error));
    }


    // Filter by role
    document.getElementById("roleFilter").addEventListener("change", function () {
        loadAttendance(this.value);
    });

    function loadDashboardStats() {
        fetch("fetch_dashboard_stats.php")
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
    document.addEventListener("DOMContentLoaded", function () {
        loadAttendance();
        loadDashboardStats();
    });
</script>

<?php include '../includes/admin_footer.php'; ?>