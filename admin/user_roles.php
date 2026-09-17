<<<<<<< HEAD
<?php 
include('../includes/db.php'); 
 
$msg = "";
$role_name = $role_short_code =  $status = '';
if(isset($_POST['action']) && $_POST['action']=='create'){
    $role_name = $_POST['role_name'];
    $role_short_code = $_POST['role_short_code'];
    $status = $_POST['status'];
    $action = $_POST['action'];

    $sql = "INSERT INTO user_roles (role_name, role_short_code, status) VALUES ('$role_name', '$role_short_code', '$status')";
    
    if($conn->query($sql) === TRUE){

        //$msg = "New Role created successfully";
        echo "<script>alert('New Role created successfully'); location.href = 'user_roles.php'; </script>";
    } else {
        $msg =  "Error: ". $sql. "<br>". $conn->error;
    }
}

if(isset($_GET['editid'])){
    $editid = $_GET['editid'];
    $sql = "SELECT * FROM user_roles WHERE id=$editid";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
    
        $role_name = $row['role_name'];
        $role_short_code = $row['role_short_code'];
        $status = $row['status'];
        $action = "edit";
    }
}

if(isset($_POST['action']) && $_POST['action']=='update'){
    $editid = $_POST['editid'];
    $role_name = $_POST['role_name'];
    $role_short_code = $_POST['role_short_code'];
    $status = $_POST['status'];

    $sql = "UPDATE user_roles SET role_name='$role_name', role_short_code='$role_short_code', status='$status' WHERE id=$editid";
    
    if($conn->query($sql) === TRUE){
        echo "<script>alert('User Role updated successfully'); location.href = 'user_roles.php'; </script>";
    } else {
        $msg =  "Error: ". $sql. "<br>". $conn->error;
    }
}

// delete user role

if(isset($_GET['deleteid'])){
    $deleteid = $_GET['deleteid'];
    $sql = "DELETE FROM user_roles WHERE id=$deleteid";
    
    if($conn->query($sql) === TRUE){
        echo "<script>alert('Role deleted successfully'); location.href = 'user_roles.php'; </script>";
    } else {
        $msg =  "Error: ". $sql. "<br>". $conn->error;
    }
}

$sql = "SELECT * FROM user_roles";

$result = $conn->query($sql);

include('../includes/admin_header.php');
?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">User Roles</h3>
                </div>
                <div class="col-12 col-xl-4">
                 <div class="justify-content-end d-flex">
                    <a class="btn btn-primary" href="user_roles.php">Roles</a>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Register New Roles</h4>
                    <form class="form-sample" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="card-description">
                                    User Role info
                                    <div class="text-center text-danger"><?php echo $msg; ?></div>
                                </p>
                                <div class="form-group">
                                    <label class="form-label">Role</label>
                                    <input type="hidden" name="action" value="<?php echo (isset($_GET['editid'])) ? 'update':'create';?>">                                    
                                    <input type="hidden" name="editid" value="<?php echo (isset($_GET['editid'])) ? $_GET['editid'] : '';?>">
                                    <input type="text" class="form-control" value="<?php echo $role_name; ?>" name="role_name" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Deparment Shord code</label>
                                    <input type="text" class="form-control" value="<?php echo $role_short_code ?>" name="role_short_code" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <select class="form-control" name="status" name="department_short_code" required>
                                        <option value="">Select Status</option>
                                        <option value="1" <?php if($status == 1) echo "selected"; ?>>Active</option>
                                        <option value="0" <?php if($status == 0) echo "selected"; ?>>Block</option>
                                    </select>
                                </div>
                                <div class="form-group">                                
                                    <input type="submit" value="<?php echo (isset($_GET['editid'])) ? 'Update':'Create';?>" class="mx-4 btn btn-primary" name="save_dept">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <p class="card-description">
                                    Roles List
                                </p>
                                <div class="table-responsive" style="max-height:400px; overflow-y: scroll;">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Short Code</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $id = 0;
                                                $sql = "SELECT * FROM user_roles";

                                                $result = $conn->query($sql);
                                                if ($result->num_rows > 0) {
                                                    // output data of each row
                                                    while($row = $result->fetch_assoc()) {
                                                    ?>
                                            <tr>
                                                <td><?php echo ++$id;?></td>
                                                <td><?php echo $row['role_name'];?></td>
                                                <td class="text-primary"><?php echo $row['role_short_code'];?>
                                                </td>
                                                <td><?php echo $row['status'] ? '<label class="badge badge-success">Active</label>' : '<label class="badge badge-danger">Blocked</label>';?>
                                                </td>
                                                <td>
                                                    <a href="?editid=<?php echo $row['id'];?>"
                                                        class="badge badge-info px-3"><i class="ti-pencil"></i></a>
                                                    <a href="?deleteid=<?php echo $row['id'];?>"
                                                        class="badge badge-danger px-3"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <?php
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='5'>0 results</td></tr>";
                                                }?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
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
 
$msg = "";
$role_name = $role_short_code =  $status = '';
if(isset($_POST['action']) && $_POST['action']=='create'){
    $role_name = $_POST['role_name'];
    $role_short_code = $_POST['role_short_code'];
    $status = $_POST['status'];
    $action = $_POST['action'];

    $sql = "INSERT INTO user_roles (role_name, role_short_code, status) VALUES ('$role_name', '$role_short_code', '$status')";
    
    if($conn->query($sql) === TRUE){

        //$msg = "New Role created successfully";
        echo "<script>alert('New Role created successfully'); location.href = 'user_roles.php'; </script>";
    } else {
        $msg =  "Error: ". $sql. "<br>". $conn->error;
    }
}

if(isset($_GET['editid'])){
    $editid = $_GET['editid'];
    $sql = "SELECT * FROM user_roles WHERE id=$editid";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
    
        $role_name = $row['role_name'];
        $role_short_code = $row['role_short_code'];
        $status = $row['status'];
        $action = "edit";
    }
}

if(isset($_POST['action']) && $_POST['action']=='update'){
    $editid = $_POST['editid'];
    $role_name = $_POST['role_name'];
    $role_short_code = $_POST['role_short_code'];
    $status = $_POST['status'];

    $sql = "UPDATE user_roles SET role_name='$role_name', role_short_code='$role_short_code', status='$status' WHERE id=$editid";
    
    if($conn->query($sql) === TRUE){
        echo "<script>alert('User Role updated successfully'); location.href = 'user_roles.php'; </script>";
    } else {
        $msg =  "Error: ". $sql. "<br>". $conn->error;
    }
}

// delete user role

if(isset($_GET['deleteid'])){
    $deleteid = $_GET['deleteid'];
    $sql = "DELETE FROM user_roles WHERE id=$deleteid";
    
    if($conn->query($sql) === TRUE){
        echo "<script>alert('Role deleted successfully'); location.href = 'user_roles.php'; </script>";
    } else {
        $msg =  "Error: ". $sql. "<br>". $conn->error;
    }
}

$sql = "SELECT * FROM user_roles";

$result = $conn->query($sql);

include('../includes/admin_header.php');
?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">User Roles</h3>
                </div>
                <div class="col-12 col-xl-4">
                 <div class="justify-content-end d-flex">
                    <a class="btn btn-primary" href="user_roles.php">Roles</a>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Register New Roles</h4>
                    <form class="form-sample" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="card-description">
                                    User Role info
                                    <div class="text-center text-danger"><?php echo $msg; ?></div>
                                </p>
                                <div class="form-group">
                                    <label class="form-label">Role</label>
                                    <input type="hidden" name="action" value="<?php echo (isset($_GET['editid'])) ? 'update':'create';?>">                                    
                                    <input type="hidden" name="editid" value="<?php echo (isset($_GET['editid'])) ? $_GET['editid'] : '';?>">
                                    <input type="text" class="form-control" value="<?php echo $role_name; ?>" name="role_name" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Deparment Shord code</label>
                                    <input type="text" class="form-control" value="<?php echo $role_short_code ?>" name="role_short_code" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <select class="form-control" name="status" name="department_short_code" required>
                                        <option value="">Select Status</option>
                                        <option value="1" <?php if($status == 1) echo "selected"; ?>>Active</option>
                                        <option value="0" <?php if($status == 0) echo "selected"; ?>>Block</option>
                                    </select>
                                </div>
                                <div class="form-group">                                
                                    <input type="submit" value="<?php echo (isset($_GET['editid'])) ? 'Update':'Create';?>" class="mx-4 btn btn-primary" name="save_dept">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <p class="card-description">
                                    Roles List
                                </p>
                                <div class="table-responsive" style="max-height:400px; overflow-y: scroll;">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Short Code</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $id = 0;
                                                $sql = "SELECT * FROM user_roles";

                                                $result = $conn->query($sql);
                                                if ($result->num_rows > 0) {
                                                    // output data of each row
                                                    while($row = $result->fetch_assoc()) {
                                                    ?>
                                            <tr>
                                                <td><?php echo ++$id;?></td>
                                                <td><?php echo $row['role_name'];?></td>
                                                <td class="text-primary"><?php echo $row['role_short_code'];?>
                                                </td>
                                                <td><?php echo $row['status'] ? '<label class="badge badge-success">Active</label>' : '<label class="badge badge-danger">Blocked</label>';?>
                                                </td>
                                                <td>
                                                    <a href="?editid=<?php echo $row['id'];?>"
                                                        class="badge badge-info px-3"><i class="ti-pencil"></i></a>
                                                    <a href="?deleteid=<?php echo $row['id'];?>"
                                                        class="badge badge-danger px-3"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <?php
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='5'>0 results</td></tr>";
                                                }?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content-wrapper ends -->
<!-- partial:partials/_footer.html -->
>>>>>>> 225f81b85625e790025fab833e204e96e67aba44
<?php include '../includes/admin_footer.php'; ?>