<<<<<<< HEAD
<?php
include('../includes/db.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $attendance_date = $_POST['attendance_date'];
    $attendance = $_POST['attendance'];
    $skipped = 0;
    $marked = 0;

    foreach ($attendance as $user_id => $status) {
        // Check if attendance is already marked
        $checkStmt = $conn->prepare("SELECT id FROM attendance WHERE user_id = ? AND attendance_date = ?");
        $checkStmt->bind_param("is", $user_id, $attendance_date);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            // Attendance already exists, skip this user
            $skipped++;
            continue;
        }

        // Insert attendance only if not already marked
        $stmt = $conn->prepare("INSERT INTO attendance (user_id, attendance_date, status) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $attendance_date, $status);
        
        if ($stmt->execute()) {
            $marked++;
        }
    }

    echo json_encode([
        'success' => true,
        'message' => "Attendance marked for $marked user(s). Skipped $skipped already marked user(s)."
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
=======
<?php
include('../includes/db.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $attendance_date = $_POST['attendance_date'];
    $attendance = $_POST['attendance'];
    $skipped = 0;
    $marked = 0;

    foreach ($attendance as $user_id => $status) {
        // Check if attendance is already marked
        $checkStmt = $conn->prepare("SELECT id FROM attendance WHERE user_id = ? AND attendance_date = ?");
        $checkStmt->bind_param("is", $user_id, $attendance_date);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            // Attendance already exists, skip this user
            $skipped++;
            continue;
        }

        // Insert attendance only if not already marked
        $stmt = $conn->prepare("INSERT INTO attendance (user_id, attendance_date, status) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $attendance_date, $status);
        
        if ($stmt->execute()) {
            $marked++;
        }
    }

    echo json_encode([
        'success' => true,
        'message' => "Attendance marked for $marked user(s). Skipped $skipped already marked user(s)."
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
>>>>>>> 225f81b85625e790025fab833e204e96e67aba44
