<<<<<<< HEAD
<?php
include('../includes/db.php');
include('../includes/hod_header.php');

// Fetch departments
$departments_query = "SELECT department_id, department_name, department_short_code FROM departments WHERE status = 1";
$departments_result = $conn->query($departments_query);

// Fetch faculty members
$faculty_query = "SELECT id, full_name FROM users WHERE role = 6";
$faculty_result = $conn->query($faculty_query);
?>

<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
            <h3 class="font-weight-bold">Mark Student Attendance</h3>
            <p class="font-weight-normal">Fill in the details to mark attendance.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form id="studentAttendanceForm">
                        <div class="row">
                             <!-- Select Faculty -->
                              <div class="col-sm-4 form-group">
                             <label for="faculty">Select Faculty:</label>
                              <select class="form-control" id="faculty" name="faculty" required>
                                    <option value="">-- Select Faculty --</option>
                                    <?php while ($row = $faculty_result->fetch_assoc()) { ?>
                                        <option value="<?= $row['id'] ?>"><?= $row['full_name'] ?></option>
                                    <?php } ?>
                                </select>
                                </div>


                            <!-- Select Department -->
                            <div class="col-sm-4 form-group">
                                <label for="department">Select Department:</label>
                                <select class="form-control" id="department" name="department" required>
                                    <option value="">-- Select Department --</option>
                                    <?php while ($row = $departments_result->fetch_assoc()) { ?>
                                        <option value="<?= $row['department_short_code'] ?>"><?= $row['department_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <!-- Select Year -->
                            <div class="col-sm-4 form-group">
                                <label for="year">Select Year:</label>
                                <select class="form-control" id="year" name="year" required>
                                    <option value="">-- Select Year --</option>
                                    <option value="1">1st Year</option>
                                    <option value="2">2nd Year</option>
                                    <option value="3">3rd Year</option>
                                    <option value="4">4th Year</option>
                                </select>
                            </div>

                        </div>

                        <!-- Select Lecture Time Slot -->
                        <div class="form-group">
                            <label>Lecture Time Slots:</label><br>
                            <div class="col-sm-8 d-flex justify-content-between">
                                <label><input type="radio" name="lecture_time_slot" value="10:05 11:05"> 10:05 AM - 11:05 AM</label>
                                <label><input type="radio" name="lecture_time_slot" value="11:05 12:05"> 11:05 AM - 12:05 PM</label>
                                <label><input type="radio" name="lecture_time_slot" value="12:05 01:05"> 12:05 PM - 01:05 PM</label>
                            </div>                            
                            <div class="col-sm-8 d-flex justify-content-between">
                                <label><input type="radio" name="lecture_time_slot" value="01:00 02:00"> 01:00 PM - 02:00 PM</label>
                                <label><input type="radio" name="lecture_time_slot" value="02:00 03:00"> 02:00 PM - 03:00 PM</label>
                                <label><input type="radio" name="lecture_time_slot" value="03:00 04:00"> 03:00 PM - 04:00 PM</label>
                            </div>                            
                            <div class="col-sm-8 d-flex justify-content-between">
                                <label><input type="radio" name="lecture_time_slot" value="04:00 05:00"> 04:00 PM - 05:00 PM</label>
                            </div>
                        </div>


                        <!-- Subject Name -->
                        <div class="form-group">
                            <label for="subject_name">Subject Name:</label>
                            <input type="text" class="form-control" id="subject_name" name="subject_name" required>
                        </div>

                        <!-- Enter Absent Students -->
                        <div class="form-group">
                            <label for="absent_roll_numbers">Enter Absent Students Roll Numbers (comma-separated):</label>
                            <input type="text" class="form-control" id="absent_roll_numbers" name="absent_roll_numbers">
                        </div>

                        <button type="submit" class="btn btn-primary">Mark Attendance</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById("studentAttendanceForm").addEventListener("submit", function (e) {
    e.preventDefault();
    let formData = new FormData(this);

    fetch("../ajax/mark_student_attendance.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
    })
    .catch(error => console.error("Error submitting attendance:", error));
});
</script>

<?php include '../includes/admin_footer.php'; ?>
=======
<?php
include('../includes/db.php');
include('../includes/hod_header.php');

// Fetch departments
$departments_query = "SELECT department_id, department_name, department_short_code FROM departments WHERE status = 1";
$departments_result = $conn->query($departments_query);

// Fetch faculty members
$faculty_query = "SELECT id, full_name FROM users WHERE role = 6";
$faculty_result = $conn->query($faculty_query);
?>

<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
            <h3 class="font-weight-bold">Mark Student Attendance</h3>
            <p class="font-weight-normal">Fill in the details to mark attendance.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form id="studentAttendanceForm">
                        <div class="row">
                             <!-- Select Faculty -->
                              <div class="col-sm-4 form-group">
                             <label for="faculty">Select Faculty:</label>
                              <select class="form-control" id="faculty" name="faculty" required>
                                    <option value="">-- Select Faculty --</option>
                                    <?php while ($row = $faculty_result->fetch_assoc()) { ?>
                                        <option value="<?= $row['id'] ?>"><?= $row['full_name'] ?></option>
                                    <?php } ?>
                                </select>
                                </div>


                            <!-- Select Department -->
                            <div class="col-sm-4 form-group">
                                <label for="department">Select Department:</label>
                                <select class="form-control" id="department" name="department" required>
                                    <option value="">-- Select Department --</option>
                                    <?php while ($row = $departments_result->fetch_assoc()) { ?>
                                        <option value="<?= $row['department_short_code'] ?>"><?= $row['department_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <!-- Select Year -->
                            <div class="col-sm-4 form-group">
                                <label for="year">Select Year:</label>
                                <select class="form-control" id="year" name="year" required>
                                    <option value="">-- Select Year --</option>
                                    <option value="1">1st Year</option>
                                    <option value="2">2nd Year</option>
                                    <option value="3">3rd Year</option>
                                    <option value="4">4th Year</option>
                                </select>
                            </div>

                        </div>

                        <!-- Select Lecture Time Slot -->
                        <div class="form-group">
                            <label>Lecture Time Slots:</label><br>
                            <div class="col-sm-8 d-flex justify-content-between">
                                <label><input type="radio" name="lecture_time_slot" value="10:05 11:05"> 10:05 AM - 11:05 AM</label>
                                <label><input type="radio" name="lecture_time_slot" value="11:05 12:05"> 11:05 AM - 12:05 PM</label>
                                <label><input type="radio" name="lecture_time_slot" value="12:05 01:05"> 12:05 PM - 01:05 PM</label>
                            </div>                            
                            <div class="col-sm-8 d-flex justify-content-between">
                                <label><input type="radio" name="lecture_time_slot" value="01:00 02:00"> 01:00 PM - 02:00 PM</label>
                                <label><input type="radio" name="lecture_time_slot" value="02:00 03:00"> 02:00 PM - 03:00 PM</label>
                                <label><input type="radio" name="lecture_time_slot" value="03:00 04:00"> 03:00 PM - 04:00 PM</label>
                            </div>                            
                            <div class="col-sm-8 d-flex justify-content-between">
                                <label><input type="radio" name="lecture_time_slot" value="04:00 05:00"> 04:00 PM - 05:00 PM</label>
                            </div>
                        </div>


                        <!-- Subject Name -->
                        <div class="form-group">
                            <label for="subject_name">Subject Name:</label>
                            <input type="text" class="form-control" id="subject_name" name="subject_name" required>
                        </div>

                        <!-- Enter Absent Students -->
                        <div class="form-group">
                            <label for="absent_roll_numbers">Enter Absent Students Roll Numbers (comma-separated):</label>
                            <input type="text" class="form-control" id="absent_roll_numbers" name="absent_roll_numbers">
                        </div>

                        <button type="submit" class="btn btn-primary">Mark Attendance</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById("studentAttendanceForm").addEventListener("submit", function (e) {
    e.preventDefault();
    let formData = new FormData(this);

    fetch("../ajax/mark_student_attendance.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
    })
    .catch(error => console.error("Error submitting attendance:", error));
});
</script>

<?php include '../includes/admin_footer.php'; ?>
>>>>>>> 225f81b85625e790025fab833e204e96e67aba44
