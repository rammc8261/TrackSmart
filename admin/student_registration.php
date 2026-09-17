<?php 
 include('../includes/db.php'); 
$msg = "";



include('../includes/admin_header.php'); ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Student Registration</h3>
                    <!-- <h6 class="font-weight-normal mb-0">All systems are running smoothly! You have <span class="text-primary">3 unread alerts!</span></h6> -->
                </div>
                <div class="col-12 col-xl-4">
                    <div class="justify-content-end d-flex">
                        <a class="btn btn-primary" href="students_list.php">Students List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Register New Student</h4>
                    <form action="process_regster.php" method="post">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Roll No.</label>
                                <input type="text" name="roll_no" class="form-control" required>
                                <input type="hidden" name="action" value="<?php echo (isset($_GET['editid'])) ? 'update_student':'create_student';?>">                                    
                                <input type="hidden" name="editid" value="<?php echo (isset($_GET['editid'])) ? $_GET['editid'] : '';?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Enrollment No.</label>
                                <input type="text" name="enrollment_no" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Student Name</label>
                            <input type="text" name="student_name" class="form-control" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Year of Study</label>
                                <select name="year_of_study" class="form-control" required>
                                    <option value="1">1st Year</option>
                                    <option value="2">2nd Year</option>
                                    <option value="3">3rd Year</option>
                                    <option value="4">4th Year</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Department Name</label>
                                <select name="department_short_name" class="form-control" required>
                                <?php 
                                    $sql = "SELECT * FROM departments WHERE status = 1";

                                    $result = $conn->query($sql);
                                    
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<option value='". $row["department_short_code"]. "'>". $row["department_name"]. "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No department found</option>";
                                    }
                                ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Student Contact No.</label>
                                <input type="text" name="student_contact" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Parent Contact No.</label>
                                <input type="text" name="parent_contact" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email ID</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Register</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Upload Student List</h4>
                    <form action="process_upload.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="import" id="" value="import">
                        <div class="mb-3">
                            <label class="form-label">Select CSV File (.csv)</label>
                            <input type="file" name="file" class="form-control" accept=".csv" required>
                        </div>
                            <button type="submit" class="btn btn-primary">Upload</button>

                    </form>
                </div>
            </div>
        
            <div class="card mt-5">
                <div class="card-body">
                    <h4 class="card-title">Download Student Template</h4>
                    <a href="../assets/templates/student_template.csv" class="btn btn-primary">Download</a>
                </div>
            </div>
        </div>

    </div>

</div>
<!-- content-wrapper ends -->
<!-- partial:partials/_footer.html -->
<?php include '../includes/admin_footer.php'; ?>