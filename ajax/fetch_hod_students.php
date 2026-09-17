<<<<<<< HEAD
<?php
include('../includes/db.php');
session_start();

$user_id = $_SESSION['user']['user_id'] ?? null; // Get logged-in HOD's user_id
if (!$user_id) {
    echo "<p class='text-danger'>Unauthorized access.</p>";
    exit;
}

// Get HOD's department
$hodQuery = $conn->prepare("SELECT department FROM users WHERE user_id = ?");
$hodQuery->bind_param("s", $user_id);
$hodQuery->execute();
$hodResult = $hodQuery->get_result()->fetch_assoc();
$department_id = $hodResult['department'] ?? null;

if (!$department_id) {
    echo "<p class='text-danger'>HOD's department not found.</p>";
    exit;
}

// Get department short code
$shortCodeQuery = $conn->prepare("SELECT department_short_code FROM departments WHERE department_id = ?");
$shortCodeQuery->bind_param("i", $department_id);
$shortCodeQuery->execute();
$shortCodeResult = $shortCodeQuery->get_result()->fetch_assoc();
$department_short_code = $shortCodeResult['department_short_code'] ?? null;

if (!$department_short_code) {
    echo "<p class='text-danger'>Department short code not found.</p>";
    exit;
}

$limit = 10; // Records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';

// Allowed sorting fields
$allowed_sort = ["roll_no", "student_name", "year_of_study", "department_short_name"];
$sort_by = in_array($sort, $allowed_sort) ? $sort : "id";

// Prepare Search & Filter Conditions
$search_condition = "WHERE department_short_name = ?";
$params = [$department_short_code];
$param_types = "s";

if (!empty($search)) {
    $search_condition .= " AND (student_name LIKE ? OR roll_no LIKE ? OR email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $param_types .= "sss";
}

// Get total records after filtering
$total_query = "SELECT COUNT(*) AS total FROM students $search_condition";
$stmt = $conn->prepare($total_query);
$stmt->bind_param($param_types, ...$params);
$stmt->execute();
$total_result = $stmt->get_result();
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

// Fetch student data
$query = "SELECT * FROM students $search_condition ORDER BY $sort_by LIMIT ?, ?";
$params[] = $start;
$params[] = $limit;
$param_types .= "ii";

$stmt = $conn->prepare($query);
$stmt->bind_param($param_types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>            
            <th>Actions</th>
            <th>ID</th>
            <th>Roll No</th>
            <th>Enrollment No</th>
            <th>Student Name</th>
            <th>Year</th>
            <th>Department</th>
            <th>Contact</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <button class="btn btn-primary btn-sm edit-btn" data-id="<?= $row['id'] ?>">Edit</button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $row['id'] ?>">Delete</button>
                    </td>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['roll_no'] ?></td>
                    <td><?= $row['enrollment_no'] ?></td>
                    <td><?= $row['student_name'] ?></td>
                    <td>
                        <?php
                        echo match ($row['year_of_study']) {
                            1 => "First Year",
                            2 => "Second Year",
                            3 => "Third Year",
                            default => "",
                        };
                        ?>
                    </td>
                    <td><?= $row['department_short_name'] ?></td>
                    <td><?= $row['student_contact'] ?></td>
                    <td><?= $row['email'] ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="9" class="text-center">No students found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Pagination -->
<div class="col-sm-7 text-center mx-auto mt-3">
<nav>
    <ul class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a href="#" class="page-link pagination-link" data-page="<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
</div>
<?php $conn->close(); ?>
=======
<?php
include('../includes/db.php');
session_start();

$user_id = $_SESSION['user']['user_id'] ?? null; // Get logged-in HOD's user_id
if (!$user_id) {
    echo "<p class='text-danger'>Unauthorized access.</p>";
    exit;
}

// Get HOD's department
$hodQuery = $conn->prepare("SELECT department FROM users WHERE user_id = ?");
$hodQuery->bind_param("s", $user_id);
$hodQuery->execute();
$hodResult = $hodQuery->get_result()->fetch_assoc();
$department_id = $hodResult['department'] ?? null;

if (!$department_id) {
    echo "<p class='text-danger'>HOD's department not found.</p>";
    exit;
}

// Get department short code
$shortCodeQuery = $conn->prepare("SELECT department_short_code FROM departments WHERE department_id = ?");
$shortCodeQuery->bind_param("i", $department_id);
$shortCodeQuery->execute();
$shortCodeResult = $shortCodeQuery->get_result()->fetch_assoc();
$department_short_code = $shortCodeResult['department_short_code'] ?? null;

if (!$department_short_code) {
    echo "<p class='text-danger'>Department short code not found.</p>";
    exit;
}

$limit = 10; // Records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';

// Allowed sorting fields
$allowed_sort = ["roll_no", "student_name", "year_of_study", "department_short_name"];
$sort_by = in_array($sort, $allowed_sort) ? $sort : "id";

// Prepare Search & Filter Conditions
$search_condition = "WHERE department_short_name = ?";
$params = [$department_short_code];
$param_types = "s";

if (!empty($search)) {
    $search_condition .= " AND (student_name LIKE ? OR roll_no LIKE ? OR email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $param_types .= "sss";
}

// Get total records after filtering
$total_query = "SELECT COUNT(*) AS total FROM students $search_condition";
$stmt = $conn->prepare($total_query);
$stmt->bind_param($param_types, ...$params);
$stmt->execute();
$total_result = $stmt->get_result();
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

// Fetch student data
$query = "SELECT * FROM students $search_condition ORDER BY $sort_by LIMIT ?, ?";
$params[] = $start;
$params[] = $limit;
$param_types .= "ii";

$stmt = $conn->prepare($query);
$stmt->bind_param($param_types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>            
            <th>Actions</th>
            <th>ID</th>
            <th>Roll No</th>
            <th>Enrollment No</th>
            <th>Student Name</th>
            <th>Year</th>
            <th>Department</th>
            <th>Contact</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <button class="btn btn-primary btn-sm edit-btn" data-id="<?= $row['id'] ?>">Edit</button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $row['id'] ?>">Delete</button>
                    </td>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['roll_no'] ?></td>
                    <td><?= $row['enrollment_no'] ?></td>
                    <td><?= $row['student_name'] ?></td>
                    <td>
                        <?php
                        echo match ($row['year_of_study']) {
                            1 => "First Year",
                            2 => "Second Year",
                            3 => "Third Year",
                            default => "",
                        };
                        ?>
                    </td>
                    <td><?= $row['department_short_name'] ?></td>
                    <td><?= $row['student_contact'] ?></td>
                    <td><?= $row['email'] ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="9" class="text-center">No students found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Pagination -->
<div class="col-sm-7 text-center mx-auto mt-3">
<nav>
    <ul class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a href="#" class="page-link pagination-link" data-page="<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
</div>
<?php $conn->close(); ?>
>>>>>>> 225f81b85625e790025fab833e204e96e67aba44
