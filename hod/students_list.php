<<<<<<< HEAD
<?php 
 include('../includes/db.php'); 
$msg = "";

include('../includes/hod_header.php'); ?>
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
                        <a class="btn btn-primary" href="student_registration.php">New Student</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Register New Student</h4>
                    <!-- Search and Sorting -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" id="search1" class="form-control h-75" placeholder="Search by Name, Roll No, or Email">
                        </div>
                        <div class="col-md-4">
                            <select id="sort" class="form-control h-75">
                                <option value="">Sort By</option>
                                <option value="roll_no">Roll No</option>
                                <option value="student_name">Name</option>
                                <option value="year_of_study">Year</option>
                                <option value="department_short_name">Department</option>
                            </select>
                        </div>
                    </div>

                    <div id="studentTable" class="table-responsive">
                        <!-- Student List Will Be Loaded Here -->
                    </div>

                </div>
            </div>
        </div>

    
    </div>

</div>
<!-- content-wrapper ends -->
<!-- partial:partials/_footer.html -->
<?php include '../includes/admin_footer.php'; ?>

<script>
$(document).ready(function () {
    loadTable(1, '', '');

    function loadTable(page, search, sort) {
        $.ajax({
            url: "../ajax/fetch_hod_students.php",
            type: "GET",
            data: { page: page, search: search, sort: sort },
            success: function (response) {
                $("#studentTable").html(response);
            }
        });
    }

    // Handle pagination
    $(document).on("click", ".pagination-link", function (e) {
        e.preventDefault();
        var page = $(this).data("page");
        var search = $("#search1").val();
        var sort = $("#sort").val();
        loadTable(page, search, sort);
    });

    // Handle live search
    $("#search1").on("input", function () {
        var search = $(this).val();
        var sort = $("#sort").val();
        loadTable(1, search, sort);
    });

    // Handle sorting
    $("#sort").change(function () {
        var search = $("#search1").val();
        var sort = $(this).val();
        loadTable(1, search, sort);
    });

    // Delete Student
    $(document).on("click", ".delete-btn", function () {
        if (!confirm("Are you sure you want to delete this student?")) return;
        var studentId = $(this).data("id");

        $.ajax({
            url: "../ajax/delete_student.php",
            type: "POST",
            data: { id: studentId },
            success: function (response) {
                alert(response);
                loadTable(1, $("#search1").val(), $("#sort").val());
            }
        });
    });

    // Edit Student
    $(document).on("click", ".edit-btn", function () {
        var studentId = $(this).data("id");

        $.ajax({
            url: "../ajax/get_student.php",
            type: "GET",
            data: { id: studentId },
            success: function (response) {
                var student = JSON.parse(response);
                $("#edit_id").val(student.id);
                $("#edit_roll_no").val(student.roll_no);
                $("#edit_student_name").val(student.student_name);
                $("#edit_email").val(student.email);
                $("#editModal").modal("show");
            }
        });
    });

    // Update Student
    $("#updateStudentForm").submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: "../ajax/update_student.php",
            type: "POST",
            data: $(this).serialize(),
            success: function (response) {
                alert(response);
                $("#editModal").modal("hide");
                loadTable(1, $("#search1").val(), $("#sort").val());
            }
        });
    });
});

</script>

<!-- Edit Student Modal -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Student</h5>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="updateStudentForm">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label>Roll No:</label>
                        <input type="text" id="edit_roll_no" name="roll_no" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Student Name:</label>
                        <input type="text" id="edit_student_name" name="student_name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Email:</label>
                        <input type="email" id="edit_email" name="email" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
=======
<?php 
 include('../includes/db.php'); 
$msg = "";

include('../includes/hod_header.php'); ?>
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
                        <a class="btn btn-primary" href="student_registration.php">New Student</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Register New Student</h4>
                    <!-- Search and Sorting -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" id="search1" class="form-control h-75" placeholder="Search by Name, Roll No, or Email">
                        </div>
                        <div class="col-md-4">
                            <select id="sort" class="form-control h-75">
                                <option value="">Sort By</option>
                                <option value="roll_no">Roll No</option>
                                <option value="student_name">Name</option>
                                <option value="year_of_study">Year</option>
                                <option value="department_short_name">Department</option>
                            </select>
                        </div>
                    </div>

                    <div id="studentTable" class="table-responsive">
                        <!-- Student List Will Be Loaded Here -->
                    </div>

                </div>
            </div>
        </div>

    
    </div>

</div>
<!-- content-wrapper ends -->
<!-- partial:partials/_footer.html -->
<?php include '../includes/admin_footer.php'; ?>

<script>
$(document).ready(function () {
    loadTable(1, '', '');

    function loadTable(page, search, sort) {
        $.ajax({
            url: "../ajax/fetch_hod_students.php",
            type: "GET",
            data: { page: page, search: search, sort: sort },
            success: function (response) {
                $("#studentTable").html(response);
            }
        });
    }

    // Handle pagination
    $(document).on("click", ".pagination-link", function (e) {
        e.preventDefault();
        var page = $(this).data("page");
        var search = $("#search1").val();
        var sort = $("#sort").val();
        loadTable(page, search, sort);
    });

    // Handle live search
    $("#search1").on("input", function () {
        var search = $(this).val();
        var sort = $("#sort").val();
        loadTable(1, search, sort);
    });

    // Handle sorting
    $("#sort").change(function () {
        var search = $("#search1").val();
        var sort = $(this).val();
        loadTable(1, search, sort);
    });

    // Delete Student
    $(document).on("click", ".delete-btn", function () {
        if (!confirm("Are you sure you want to delete this student?")) return;
        var studentId = $(this).data("id");

        $.ajax({
            url: "../ajax/delete_student.php",
            type: "POST",
            data: { id: studentId },
            success: function (response) {
                alert(response);
                loadTable(1, $("#search1").val(), $("#sort").val());
            }
        });
    });

    // Edit Student
    $(document).on("click", ".edit-btn", function () {
        var studentId = $(this).data("id");

        $.ajax({
            url: "../ajax/get_student.php",
            type: "GET",
            data: { id: studentId },
            success: function (response) {
                var student = JSON.parse(response);
                $("#edit_id").val(student.id);
                $("#edit_roll_no").val(student.roll_no);
                $("#edit_student_name").val(student.student_name);
                $("#edit_email").val(student.email);
                $("#editModal").modal("show");
            }
        });
    });

    // Update Student
    $("#updateStudentForm").submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: "../ajax/update_student.php",
            type: "POST",
            data: $(this).serialize(),
            success: function (response) {
                alert(response);
                $("#editModal").modal("hide");
                loadTable(1, $("#search1").val(), $("#sort").val());
            }
        });
    });
});

</script>

<!-- Edit Student Modal -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Student</h5>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="updateStudentForm">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label>Roll No:</label>
                        <input type="text" id="edit_roll_no" name="roll_no" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Student Name:</label>
                        <input type="text" id="edit_student_name" name="student_name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Email:</label>
                        <input type="email" id="edit_email" name="email" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
>>>>>>> 225f81b85625e790025fab833e204e96e67aba44
</div>