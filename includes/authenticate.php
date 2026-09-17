<?php
session_start();
include('db.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = (int)($_POST['role'] ?? 0); // Ensure role is an integer

    error_log("Login Attempt - User: $user_id, Role ID: $role");

    // Fetch user details with role name
    $stmt = $conn->prepare("
        SELECT u.*, r.role_name, r.role_short_code
        FROM users u
        JOIN user_roles r ON u.role = r.id
        WHERE u.user_id = ? AND u.role = ?
    ");
    $stmt->bind_param("si", $user_id, $role); // Use 'i' for integer binding
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // error_log("User found: " . print_r($user, true));
        // error_log("Stored Password Hash: " . $user['password']);

        // echo("Entered Password: " . $password);
        // echo("Stored Hash: " . $user['password']);

        // if (password_verify($password, $user['password'])) {
        //     echo("Password Matched!");
        // } else {
        //     echo("Password Mismatch!");
        // }
        if (password_verify($password, $user['password'])) {
            $role_name = $user['role_short_code'];

            session_regenerate_id(true);
            $_SESSION['user'] = [
                'userId' => $user['id'],
                'user_id' => $user['user_id'],
                'full_name' => $user['full_name'],
                'role' => $role_name
            ];

            $redirect_urls = [
                'admin' => 'admin/dashboard.php',
                'faculty' => 'faculty/dashboard.php',
                'hod' => 'hod/dashboard.php'
            ];

            echo json_encode(['success' => true, 'redirect' => $redirect_urls[$role_name] ?? 'index.php']);
        } else {
            error_log("Password verification failed for user: $user_id");
            echo json_encode(['success' => false, 'message' => 'Invalid credentials.']);
        }
    } else {
        error_log("No user found with User ID: $user_id and Role ID: $role");
        echo json_encode(['success' => false, 'message' => 'Invalid credentials.']);
    }

    $stmt->close();
    $conn->close();
}
?>
