<<<<<<< HEAD
<?php
include('../includes/db.php');
session_start();

header('Content-Type: application/json');

try {
    // 1️⃣ Check if HOD is logged in
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'hod') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
        exit;
    }

    // 2️⃣ Get HOD's department ID
    $user_id = $_SESSION['user']['user_id'];
    $hodQuery = $conn->prepare("SELECT department FROM users WHERE user_id = ?");
    $hodQuery->bind_param("s", $user_id);
    $hodQuery->execute();
    $hodResult = $hodQuery->get_result()->fetch_assoc();
    $department_id = $hodResult['department'] ?? null;

    if (!$department_id) {
        echo json_encode(['success' => false, 'message' => 'Department not found for HOD.']);
        exit;
    }

    // 3️⃣ Fetch faculties of the same department
    $stmt = $conn->prepare("
        SELECT u.id, u.full_name, u.contact_number, u.email, u.user_id, d.department_name, r.role_name 
        FROM users u
        LEFT JOIN departments d ON u.department = d.department_id
        LEFT JOIN user_roles r ON u.role = r.id
        WHERE u.department = ? AND u.role = 6
    ");
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    echo json_encode(['success' => true, 'users' => $users]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error fetching users.', 'error' => $e->getMessage()]);
}
?>
=======
<?php
include('../includes/db.php');
session_start();

header('Content-Type: application/json');

try {
    // 1️⃣ Check if HOD is logged in
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'hod') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
        exit;
    }

    // 2️⃣ Get HOD's department ID
    $user_id = $_SESSION['user']['user_id'];
    $hodQuery = $conn->prepare("SELECT department FROM users WHERE user_id = ?");
    $hodQuery->bind_param("s", $user_id);
    $hodQuery->execute();
    $hodResult = $hodQuery->get_result()->fetch_assoc();
    $department_id = $hodResult['department'] ?? null;

    if (!$department_id) {
        echo json_encode(['success' => false, 'message' => 'Department not found for HOD.']);
        exit;
    }

    // 3️⃣ Fetch faculties of the same department
    $stmt = $conn->prepare("
        SELECT u.id, u.full_name, u.contact_number, u.email, u.user_id, d.department_name, r.role_name 
        FROM users u
        LEFT JOIN departments d ON u.department = d.department_id
        LEFT JOIN user_roles r ON u.role = r.id
        WHERE u.department = ? AND u.role = 6
    ");
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    echo json_encode(['success' => true, 'users' => $users]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error fetching users.', 'error' => $e->getMessage()]);
}
?>
>>>>>>> 225f81b85625e790025fab833e204e96e67aba44
