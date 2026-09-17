<<<<<<< HEAD
<?php 
include('../includes/db.php'); 

if(isset($_GET['delId'])){
    $delId = $_GET['delId'];

    $stmt = $conn->prepare("DELETE FROM class_teachers WHERE id = ?");
    $stmt->bind_param("i", $delId);

    if ($stmt->execute()) {
        echo "<script>alert('Class Teacher Deleted Successfully!'); window.location.replace('assign_class_teacher.php');</script>";
    } else {
        echo "<script>alert('Error Deleting Class Teacher!'); window.location.replace('assign_class_teacher.php');</script>";
    }
}
 // Fetch all departments
$departments = $conn->query("SELECT * FROM departments")->fetch_all(MYSQLI_ASSOC);

// Fetch all faculties (users with role 'faculty')
$faculties = $conn->query("SELECT * FROM users WHERE role = (SELECT id FROM user_roles WHERE role_name = 'faculty')")->fetch_all(MYSQLI_ASSOC);

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $department_id = $_POST['department_id'];
    $year_of_study = $_POST['year_of_study'];
    $faculty_id = $_POST['faculty_id'];

    // Check if already assigned
    $check = $conn->prepare("SELECT id FROM class_teachers WHERE departmentId = ? AND year_of_study = ?");
    $check->bind_param("is", $department_id, $year_of_study);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Class teacher already assigned for this department and year!');</script>";
    } else {
        // Assign new class teacher
        $stmt = $conn->prepare("INSERT INTO class_teachers (departmentId, year_of_study, faculty_id) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $department_id, $year_of_study, $faculty_id);

        if ($stmt->execute()) {
            echo "<script>alert('Class Teacher Assigned Successfully!'); window.location.replace('assign_class_teacher.php');</script>";
        } else {
            echo "<script>alert('Error Assigning Class Teacher!'); window.location.replace('assign_class_teacher.php');</script>";
        }
    }
}



$query = "SELECT ct.id, d.department_name, ct.year_of_study, u.full_name 
          FROM class_teachers ct
          JOIN departments d ON ct.departmentId = d.department_id
          JOIN users u ON ct.faculty_id = u.id";

$teachers = $conn->query($query)->fetch_all(MYSQLI_ASSOC);

include('../includes/admin_header.php');
?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Assign Teacher</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-5 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Register New Roles</h4>
                    <form method="POST">
                        <div class="mb-3">
                            <label>Department</label>
                            <select name="department_id" class="form-control" required>
                                <option value="">Select Department</option>
                                <?php foreach ($departments as $dept) { ?>
                                <option value="<?= $dept['department_id'] ?>"><?= $dept['department_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Year of Study</label>
                            <select name="year_of_study" class="form-control" required>
                                <option value="">Select Year</option>
                                <option value="1">First Year</option>
                                <option value="2">Second Year</option>
                                <option value="3">Third Year</option>
                                <option value="4">Final Year</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Faculty</label>
                            <select name="faculty_id" class="form-control" required>
                                <option value="">Select Faculty</option>
                                div <?php foreach ($faculties as $faculty) { ?>
                                <option value="<?= $faculty['id'] ?>"><?= $faculty['full_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Assign</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-7 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Register New Roles</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Department</th>
                                    <th>Year</th>
                                    <th>Faculty Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($teachers as $teacher) { ?>
                                <tr>
                                    <td><?= $teacher['id'] ?></td>
                                    <td><?= $teacher['department_name'] ?></td>
                                    <td><?= $teacher['year_of_study'] ?></td>
                                    <td><?= $teacher['full_name'] ?></td>
                                    <td>
                                        
                                        <a href="assign_class_teacher.php?delId=<?= $teacher['id'] ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content-wrapper ends -->
<!-- partial:partials/_footer.html -->
=======
<?php 
include('../includes/db.php'); 

if(isset($_GET['delId'])){
    $delId = $_GET['delId'];

    $stmt = $conn->prepare("DELETE FROM class_teachers WHERE id = ?");
    $stmt->bind_param("i", $delId);

    if ($stmt->execute()) {
        echo "<script>alert('Class Teacher Deleted Successfully!'); window.location.replace('assign_class_teacher.php');</script>";
    } else {
        echo "<script>alert('Error Deleting Class Teacher!'); window.location.replace('assign_class_teacher.php');</script>";
    }
}
 // Fetch all departments
$departments = $conn->query("SELECT * FROM departments")->fetch_all(MYSQLI_ASSOC);

// Fetch all faculties (users with role 'faculty')
$faculties = $conn->query("SELECT * FROM users WHERE role = (SELECT id FROM user_roles WHERE role_name = 'faculty')")->fetch_all(MYSQLI_ASSOC);

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $department_id = $_POST['department_id'];
    $year_of_study = $_POST['year_of_study'];
    $faculty_id = $_POST['faculty_id'];

    // Check if already assigned
    $check = $conn->prepare("SELECT id FROM class_teachers WHERE departmentId = ? AND year_of_study = ?");
    $check->bind_param("is", $department_id, $year_of_study);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Class teacher already assigned for this department and year!');</script>";
    } else {
        // Assign new class teacher
        $stmt = $conn->prepare("INSERT INTO class_teachers (departmentId, year_of_study, faculty_id) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $department_id, $year_of_study, $faculty_id);

        if ($stmt->execute()) {
            echo "<script>alert('Class Teacher Assigned Successfully!'); window.location.replace('assign_class_teacher.php');</script>";
        } else {
            echo "<script>alert('Error Assigning Class Teacher!'); window.location.replace('assign_class_teacher.php');</script>";
        }
    }
}



$query = "SELECT ct.id, d.department_name, ct.year_of_study, u.full_name 
          FROM class_teachers ct
          JOIN departments d ON ct.departmentId = d.department_id
          JOIN users u ON ct.faculty_id = u.id";

$teachers = $conn->query($query)->fetch_all(MYSQLI_ASSOC);

include('../includes/admin_header.php');
?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Assign Teacher</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-5 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Register New Roles</h4>
                    <form method="POST">
                        <div class="mb-3">
                            <label>Department</label>
                            <select name="department_id" class="form-control" required>
                                <option value="">Select Department</option>
                                <?php foreach ($departments as $dept) { ?>
                                <option value="<?= $dept['department_id'] ?>"><?= $dept['department_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Year of Study</label>
                            <select name="year_of_study" class="form-control" required>
                                <option value="">Select Year</option>
                                <option value="1">First Year</option>
                                <option value="2">Second Year</option>
                                <option value="3">Third Year</option>
                                <option value="4">Final Year</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Faculty</label>
                            <select name="faculty_id" class="form-control" required>
                                <option value="">Select Faculty</option>
                                div <?php foreach ($faculties as $faculty) { ?>
                                <option value="<?= $faculty['id'] ?>"><?= $faculty['full_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Assign</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-7 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Register New Roles</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Department</th>
                                    <th>Year</th>
                                    <th>Faculty Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($teachers as $teacher) { ?>
                                <tr>
                                    <td><?= $teacher['id'] ?></td>
                                    <td><?= $teacher['department_name'] ?></td>
                                    <td><?= $teacher['year_of_study'] ?></td>
                                    <td><?= $teacher['full_name'] ?></td>
                                    <td>
                                        
                                        <a href="assign_class_teacher.php?delId=<?= $teacher['id'] ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content-wrapper ends -->
<!-- partial:partials/_footer.html -->
>>>>>>> 225f81b85625e790025fab833e204e96e67aba44
<?php include '../includes/admin_footer.php'; ?>