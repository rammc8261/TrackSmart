<?php 
include('../includes/db.php'); 

// Handle Delete Request
if(isset($_GET['delId'])){
    $delId = $_GET['delId'];

    $stmt = $conn->prepare("DELETE FROM assigned_hods WHERE id = ?");
    $stmt->bind_param("i", $delId);

    if ($stmt->execute()) {
        echo "<script>alert('HOD Deleted Successfully!'); window.location.replace('assign_hod.php');</script>";
    } else {
        echo "<script>alert('Error Deleting HOD!'); window.location.replace('assign_hod.php');</script>";
    }
}

// Fetch all departments
$departments = $conn->query("SELECT * FROM departments")->fetch_all(MYSQLI_ASSOC);

// Fetch all users with the role 'HOD'
$hods = $conn->query("
    SELECT * FROM users 
    WHERE role = (SELECT id FROM user_roles WHERE role_name = 'HOD')
")->fetch_all(MYSQLI_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $department_id = $_POST['department_id'];
    $hod_id = $_POST['hod_id'];

    // Check if HOD is already assigned
    $check = $conn->prepare("SELECT id FROM assigned_hods WHERE departmentId = ?");
    $check->bind_param("i", $department_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('HOD already assigned for this department!');</script>";
    } else {
        // Assign new HOD
        $stmt = $conn->prepare("INSERT INTO assigned_hods (departmentId, hod_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $department_id, $hod_id);

        if ($stmt->execute()) {
            echo "<script>alert('HOD Assigned Successfully!'); window.location.replace('assign_hod.php');</script>";
        } else {
            echo "<script>alert('Error Assigning HOD!'); window.location.replace('assign_hod.php');</script>";
        }
    }
}

// Fetch assigned HODs
$query = "
    SELECT ah.id, d.department_name, u.full_name AS hod_name 
    FROM assigned_hods ah
    JOIN departments d ON ah.departmentId = d.department_id
    JOIN users u ON ah.hod_id = u.id
";
$assigned_hods = $conn->query($query)->fetch_all(MYSQLI_ASSOC);

include('../includes/admin_header.php');
?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <h3 class="font-weight-bold">Assign HOD</h3>
        </div>
    </div>
    
    <div class="row">
        <div class="col-5 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Assign New HOD</h4>
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
                            <label>HOD</label>
                            <select name="hod_id" class="form-control" required>
                                <option value="">Select HOD</option>
                                <?php foreach ($hods as $hod) { ?>
                                    <option value="<?= $hod['id'] ?>"><?= $hod['full_name'] ?></option>
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
                    <h4 class="card-title">Assigned HODs</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Department</th>
                                    <th>HOD Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($assigned_hods as $hod) { ?>
                                    <tr>
                                        <td><?= $hod['id'] ?></td>
                                        <td><?= $hod['department_name'] ?></td>
                                        <td><?= $hod['hod_name'] ?></td>
                                        <td>
                                            <a href="assign_hod.php?delId=<?= $hod['id'] ?>" 
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

<?php include '../includes/admin_footer.php'; ?>
