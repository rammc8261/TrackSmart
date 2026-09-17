<?php
include('../includes/db.php'); 

$limit = 10; // Records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';

// Search Filter Condition
$search_condition = "";
if (!empty($search)) {
    $search_condition = "WHERE student_name LIKE '%$search%' 
                         OR enrollment_no LIKE '%$search%' 
                         OR email LIKE '%$search%'";
}

// Sorting Condition
$allowed_sort = ["enrollment_no", "student_name", "year_of_study", "department_short_name"];
$sort_by = in_array($sort, $allowed_sort) ? $sort : "id";

// Get total records after filtering
$total_query = "SELECT COUNT(*) AS total FROM students $search_condition";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

// Fetch student data with search and sorting
$query = "SELECT * FROM students $search_condition ORDER BY $sort_by LIMIT $start, $limit";
$result = $conn->query($query);
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
                    <td><?php
                        if($row['year_of_study'] ==1){
                            echo "First Year";
                        }else if($row['year_of_study'] == 2){
                            echo "Second Year";
                        }else if($row['year_of_study'] == 3){
                            echo "Third Year";
                        }else{
                            echo "";
                        }
                        
                    ?></td>
                    <td><?= $row['department_short_name'] ?></td>
                    <td><?= $row['student_contact'] ?></td>
                    <td><?= $row['email'] ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center">No students found.</td>
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
