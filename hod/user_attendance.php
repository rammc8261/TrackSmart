<?php
include('../includes/db.php');
include('../includes/hod_header.php');
?>

<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
            <h3 class="font-weight-bold">Mark Attendance</h3>
            <p class="font-weight-normal">Select the attendance status for each faculty member.</p>
        </div>
        <div class="col-12 col-xl-4">
            <div class="justify-content-end d-flex">
                <a class="btn btn-primary" href="view_user_attendance.php">View Attendance</a>
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
                    <p class="fs-30 mb-2" id="presentToday">0</p>
                    <p id="attendancePercentage">00.00%</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card card-light-blue">
                <div class="card-body">
                    <p class="mb-4">Today's Absent</p>
                    <p class="fs-30 mb-2" id="absentToday">0</p>
                    <p id="absentPercentage">00.00%</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form id="attendanceForm">
                        <div class="col-sm-5 form-group">
                            <label for="attendance_date">Select Date:</label>
                            <input type="date" class="form-control" id="attendance_date" name="attendance_date"
                                required>
                        </div>

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Full Name</th>
                                    <th>Department</th>
                                    <th>Role</th>
                                    <th>Attendance</th>
                                </tr>
                            </thead>
                            <tbody id="faculty-list">
                                <!-- Faculty data will load here via AJAX -->
                            </tbody>
                        </table>

                        <button type="submit" class="btn btn-primary">Submit Attendance</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Load faculty list via AJAX
    function loadFaculty() {
        fetch("../ajax/get_hod_faculty.php")
            .then(response => response.json())
            .then(data => {
                console.log("Response from get_users.php:", data); // Debugging Log
                if (data.success) {
                    let rows = "";
                    data.users.forEach(user => {
                        rows += `<tr>
                    <td>${user.id}</td>
                    <td>${user.full_name}</td>
                    <td>${user.department_name}</td>
                    <td>${user.role_name}</td>
                    <td>
                        <select name="attendance[${user.id}]" class="form-control">
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                            <option value="Leave">Leave</option>
                        </select>
                    </td>
                </tr>`;
                    });
                    document.getElementById("faculty-list").innerHTML = rows;
                } else {
                    console.error("Error:", data.message);
                }
            })
            .catch(error => console.error("Fetch error:", error)); // Log fetch errors
    }

    // Submit attendance
    document.getElementById("attendanceForm").addEventListener("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        fetch("../ajax/mark_user_attendance.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    loadFaculty(); // Refresh faculty list
                }
            })
            .catch(error => console.error("Error submitting attendance:", error));
    });

    function loadDashboardStats() {
        fetch("../ajax/fetch_faculty_stats.php")
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
        loadFaculty();
        loadDashboardStats();
    });
</script>


<?php include '../includes/admin_footer.php'; ?>