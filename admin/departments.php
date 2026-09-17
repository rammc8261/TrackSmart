<?php 
 include('../includes/db.php'); 
$msg = "";
$department_name = $department_short_code = $action = $status = '';
if(isset($_POST['action']) && $_POST['action']=='create'){
    $department_name = $_POST['department_name'];
    $department_short_code = $_POST['department_short_code'];
    $status = $_POST['status'];
    $action = $_POST['action'];
    
    $sql = "INSERT INTO departments (department_name, department_short_code, status) VALUES ('$department_name', '$department_short_code', '$status')";
    if($conn->query($sql) === TRUE){
        echo "<script>alert('New department created successfully'); location.href = 'departments.php'; </script>";
        // $msg = "New department created successfully";
        $_POST = array();
    } else {
        $msg =  "Error: ". $sql. "<br>". $conn->error;
    }
}

if(isset($_GET['editid'])){
    $editid = $_GET['editid'];
    $sql = "SELECT * FROM departments WHERE department_id=$editid";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
    
        // print_r($row);die();
        $department_name = $row['department_name'];
        $department_short_code = $row['department_short_code'];
        $status = $row['status'];
        $action = "edit";
    }
}

if(isset($_POST['action']) && $_POST['action']=='update'){
    $editid = $_POST['editid'];
    $department_name = $_POST['department_name'];
    $department_short_code = $_POST['department_short_code'];
    $status = $_POST['status'];
    
    $sql = "UPDATE departments SET department_name='$department_name', department_short_code='$department_short_code', status='$status' WHERE department_id=$editid";
    
    if($conn->query($sql) === TRUE){
        // $msg = "Department updated successfully";
        echo "<script>alert('Department updated successfully'); location.href = 'departments.php'; </script>";
        $_POST = array();
    } else {
        $msg =  "Error: ". $sql. "<br>". $conn->error;
    }
}

$sql = "SELECT * FROM departments";

$result = $conn->query($sql);

include('../includes/admin_header.php'); 
?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Deparments</h3>
                    <h6 class="font-weight-normal mb-0">All systems are running smoothly! </h6>
                </div>
                <div class="col-12 col-xl-4">
                 <div class="justify-content-end d-flex">
                    <a class="btn btn-primary" href="departments.php">New Deparments</a>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Register New Department</h4>
                    <form class="form-sample" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="card-description">
                                    Department info
                                    <div class="text-center text-danger"><?php echo $msg; ?></div>
                                </p>
                                <div class="form-group">
                                    <input type="hidden" name="action" value="<?php echo (isset($_GET['editid'])) ? 'update':'create';?>">                                    
                                    <input type="hidden" name="editid" value="<?php echo (isset($_GET['editid'])) ? $_GET['editid'] : '';?>">
                                    <label class="form-label">Department Name</label>
                                    <input type="text" class="form-control" value="<?php echo $department_name; ?>" name="department_name" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Deparment Shord code</label>
                                    <input type="text" class="form-control" value="<?php echo $department_short_code ?>" name="department_short_code" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <select class="form-control" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="1" <?php if($status == 1) echo "selected"; ?>>Active</option>
                                        <option value="0" <?php if($status == 0) echo "selected"; ?>>Block</option>
                                    </select>
                                </div>
                                <div class="form-group">                                
                                    <input type="submit" value="<?php echo (isset($_GET['editid'])) ? 'Update':'Create';?>" class="mx-4 btn btn-primary" name="save_dept">
                                </div>
                            </div>

                            <div class="col-md-6" >
                                <p class="card-description">
                                    Department List
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
                                                if ($result->num_rows > 0) {
                                                    // output data of each row
                                                    while($row = $result->fetch_assoc()) {
                                                    ?>
                                            <tr>
                                                <td><?php echo ++$id;?></td>
                                                <td><?php echo $row['department_name'];?></td>
                                                <td class="text-primary"><?php echo $row['department_short_code'];?></td>
                                                <td><?php echo $row['status'] ? '<label class="badge badge-success">Active</label>' : '<label class="badge badge-danger">Blocked</label>';?>
                                                </td>
                                                <td>
                                                    <a href="?editid=<?php echo $row['department_id'];?>"
                                                        class="badge badge-info px-3"><i class="ti-pencil"></i></a>
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
<?php include '../includes/admin_footer.php'; ?>