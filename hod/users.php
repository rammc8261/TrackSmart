<?php 
include('../includes/db.php'); 
include('../includes/hod_header.php'); 

$user_id = $_SESSION['user']['user_id']; // Get logged-in HOD's user_id

// Get HOD's department
$hodQuery = $conn->prepare("SELECT department FROM users WHERE user_id = ?");
$hodQuery->bind_param("s", $user_id);
$hodQuery->execute();
$hodResult = $hodQuery->get_result()->fetch_assoc();
$department_id = $hodResult['department'] ?? null;

// If HOD's department is not found, restrict access
if (!$department_id) {
    echo "<script>alert('Your department is not found!'); window.location.href = '../logout.php';</script>";
    exit;
}

// Get Department Name
$deptQuery = $conn->prepare("SELECT department_name FROM departments WHERE department_id = ?");
$deptQuery->bind_param("i", $department_id);
$deptQuery->execute();
$deptResult = $deptQuery->get_result()->fetch_assoc();
$department_name = $deptResult['department_name'] ?? 'Unknown';
?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Faculty / Staff</h3>
                    <h6 class="font-weight-normal mb-0">Manage Faculty and Staff.</h6>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="justify-content-end d-flex">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#facultyModal">
                            Add Faculty
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Faculty Table -->
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Faculty List</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Full Name</th>
                                    <th>Contact</th>
                                    <th>Email</th>
                                    <th>User ID</th>
                                    <th>Department</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="user-list"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Faculty Modal -->
<div class="modal fade" id="facultyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Faculty</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="facultyForm">
                    <input type="hidden" id="faculty_id" name="faculty_id">
                    <div class="form-group">
                        <input type="text" class="form-control" id="faculty_name" name="faculty_name" placeholder="Full Name" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="contact" name="contact" placeholder="Contact Number" required>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="user_id" name="user_id" placeholder="User ID" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" value="<?= $department_name; ?>" disabled>
                        <input type="hidden" id="department" name="department" value="<?= $department_id; ?>">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" value="Faculty" disabled>
                        <input type="hidden" id="role" name="role" value="6">
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Load Users
function loadUsers() {
    fetch("../ajax/get_hod_users.php")
    .then(res => res.json())
    .then(data => {
        let rows = "";
        data.users.forEach(user => {
            rows += `<tr>
                <td>${user.id}</td>
                <td>${user.full_name}</td>
                <td>${user.contact_number}</td>
                <td>${user.email}</td>
                <td>${user.user_id}</td>
                <td>${user.department_name}</td>
                <td>${user.role_name}</td>
                <td>
                    <button class="btn btn-warning btn-sm edit-btn" data-id="${user.id}">Edit</button>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="${user.id}">Delete</button>
                </td>
            </tr>`;
        });
        document.getElementById("user-list").innerHTML = rows;
    });
}
window.onload = loadUsers;

// Open Edit Modal
document.addEventListener("click", function (e) {
    if (e.target.classList.contains("edit-btn")) {
        let id = e.target.getAttribute("data-id");

        // Fetch faculty details
        fetch("../ajax/get_single_user.php?id=" + id)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("faculty_id").value = data.user.id;
                document.getElementById("faculty_name").value = data.user.full_name;
                document.getElementById("contact").value = data.user.contact_number;
                document.getElementById("email").value = data.user.email;
                document.getElementById("user_id").value = data.user.user_id;
                document.getElementById("department").value = data.user.department;
                document.getElementById("role").value = data.user.role;

                document.getElementById("modalTitle").innerText = "Edit Faculty";
                document.getElementById("password").removeAttribute("required"); // Password is not required for edit
                
                $("#facultyModal").modal("show");
            } else {
                alert("Failed to fetch data.");
            }
        });
    }
});

// Add / Edit Faculty
document.getElementById("facultyForm").addEventListener("submit", function (e) {
    e.preventDefault();
    let formData = new FormData(this);
    fetch("../ajax/save_faculty.php", { method: "POST", body: formData })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        loadUsers();
    });
});

// Delete Faculty
document.addEventListener("click", function(e) {
    if (e.target.classList.contains("delete-btn")) {
        if (confirm("Are you sure?")) {
            let id = e.target.getAttribute("data-id");
            fetch("../ajax/delete_faculty.php?id=" + id)
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                loadUsers();
            });
        }
    }
});
</script>

<?php include '../includes/admin_footer.php'; ?>
