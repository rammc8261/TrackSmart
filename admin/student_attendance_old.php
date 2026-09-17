<<<<<<< HEAD
<?php
include('../includes/db.php');
include('../includes/admin_header.php');
?>

<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
            <h3 class="font-weight-bold">Mark Student Attendance</h3>
            <p class="font-weight-normal">Select the attendance status for each student.</p>
        </div>
        <div class="col-12 col-xl-4">
            <div class="justify-content-end d-flex">
                <a class="btn btn-primary" href="view_student_attendance.php">View Attendance</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-5 form-group">
            <label for="attendance_date">Select Date:</label>
            <input type="date" class="form-control" id="attendance_date" name="attendance_date" required>
        </div>

        <div class="col-sm-3 form-group">
            <label for="filter_year">Year:</label>
            <select class="form-control"  id="year" name="year">
                <option value="">All</option>
                <option value="1">1st Year</option>
                <option value="2">2nd Year</option>
                <option value="3">3rd Year</option>
                <option value="4">4th Year</option>
            </select>
        </div>

        <div class="form-group col-sm-3">
    <label for="department">Select Department:</label>
    <select class="form-control" id="department" name="department">
        <option value="">All Departments</option>
        <?php
        include('../includes/db.php');
        $query = "SELECT department_short_code, department_name FROM departments WHERE status = 1";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['department_short_code'] . "'>" . $row['department_name'] . "</option>";
        }
        ?>
    </select>
</div>

        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form id="attendanceForm">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Roll No</th>
                                    <th>Name</th>
                                    <th>Year</th>
                                    <th>Department</th>
                                    <th>First Lecture</th>
                                    <th>Last Lecture</th>
                                </tr>
                            </thead>
                            <tbody id="student-list">
                                <!-- Student data will load here via AJAX -->
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
    function loadStudents() {
    let department = document.getElementById("department").value;
    let year = document.getElementById("year").value;

    fetch(`ajax/load_students.php?department=${department}&year=${year}`)
        .then(response => response.json())
        .then(data => {
            let rows = "";
            if (data.success) {
                data.students.forEach(student => {
                    rows += `<tr>
                        <td>${student.roll_no}</td>
                        <td>${student.student_name}</td>
                        <td>${student.year_of_study}</td>
                        <td>${student.department_short_name}</td>
                        <td>
                            <input type="checkbox" name="attendance[${student.id}][first]" value="Present"> First Lecture
                            <input type="checkbox" name="attendance[${student.id}][last]" value="Present"> Last Lecture
                        </td>
                    </tr>`;
                });
            } else {
                rows = "<tr><td colspan='5'>No students found.</td></tr>";
            }
            document.getElementById("student-list").innerHTML = rows;
        })
        .catch(error => console.error("Error fetching students:", error));
}

// Reload students when department or year is changed
document.getElementById("department").addEventListener("change", loadStudents);
document.getElementById("year").addEventListener("change", loadStudents);

// Mark attendance for selected students
    document.getElementById("attendanceForm").addEventListener("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        fetch("../ajax/mark_student_attendance.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    loadStudents();
                }
            })
            .catch(error => console.error("Error:", error));
    });

   // window.onload = loadStudents;
</script>

<?php include '../includes/admin_footer.php'; ?>
=======
<?php
include('../includes/db.php');
include('../includes/admin_header.php');
?>

<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
            <h3 class="font-weight-bold">Mark Student Attendance</h3>
            <p class="font-weight-normal">Select the attendance status for each student.</p>
        </div>
        <div class="col-12 col-xl-4">
            <div class="justify-content-end d-flex">
                <a class="btn btn-primary" href="view_student_attendance.php">View Attendance</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-5 form-group">
            <label for="attendance_date">Select Date:</label>
            <input type="date" class="form-control" id="attendance_date" name="attendance_date" required>
        </div>

        <div class="col-sm-3 form-group">
            <label for="filter_year">Year:</label>
            <select class="form-control"  id="year" name="year">
                <option value="">All</option>
                <option value="1">1st Year</option>
                <option value="2">2nd Year</option>
                <option value="3">3rd Year</option>
                <option value="4">4th Year</option>
            </select>
        </div>

        <div class="form-group col-sm-3">
    <label for="department">Select Department:</label>
    <select class="form-control" id="department" name="department">
        <option value="">All Departments</option>
        <?php
        include('../includes/db.php');
        $query = "SELECT department_short_code, department_name FROM departments WHERE status = 1";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['department_short_code'] . "'>" . $row['department_name'] . "</option>";
        }
        ?>
    </select>
</div>

        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form id="attendanceForm">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Roll No</th>
                                    <th>Name</th>
                                    <th>Year</th>
                                    <th>Department</th>
                                    <th>First Lecture</th>
                                    <th>Last Lecture</th>
                                </tr>
                            </thead>
                            <tbody id="student-list">
                                <!-- Student data will load here via AJAX -->
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
    function loadStudents() {
    let department = document.getElementById("department").value;
    let year = document.getElementById("year").value;

    fetch(`ajax/load_students.php?department=${department}&year=${year}`)
        .then(response => response.json())
        .then(data => {
            let rows = "";
            if (data.success) {
                data.students.forEach(student => {
                    rows += `<tr>
                        <td>${student.roll_no}</td>
                        <td>${student.student_name}</td>
                        <td>${student.year_of_study}</td>
                        <td>${student.department_short_name}</td>
                        <td>
                            <input type="checkbox" name="attendance[${student.id}][first]" value="Present"> First Lecture
                            <input type="checkbox" name="attendance[${student.id}][last]" value="Present"> Last Lecture
                        </td>
                    </tr>`;
                });
            } else {
                rows = "<tr><td colspan='5'>No students found.</td></tr>";
            }
            document.getElementById("student-list").innerHTML = rows;
        })
        .catch(error => console.error("Error fetching students:", error));
}

// Reload students when department or year is changed
document.getElementById("department").addEventListener("change", loadStudents);
document.getElementById("year").addEventListener("change", loadStudents);

// Mark attendance for selected students
    document.getElementById("attendanceForm").addEventListener("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        fetch("../ajax/mark_student_attendance.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    loadStudents();
                }
            })
            .catch(error => console.error("Error:", error));
    });

   // window.onload = loadStudents;
</script>

<?php include '../includes/admin_footer.php'; ?>
>>>>>>> 225f81b85625e790025fab833e204e96e67aba44
