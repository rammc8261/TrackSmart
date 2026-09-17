<?php
session_start();
include('../includes/db.php');

// ✅ Ensure user is logged in properly
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['user_id'];
$current_password = trim($_POST['current_password']);
$new_password = trim($_POST['new_password']);
$confirm_password = trim($_POST['confirm_password']);

// ✅ Check if new passwords match
if ($new_password !== $confirm_password) {
    echo "❌ New passwords do not match!";
    exit();
}

// ✅ Fetch user's current password from DB
$query = "SELECT password FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "❌ User not found!";
    exit();
}

// 🔍 Debugging output for password comparison
echo "Stored hash: " . $user['password'] . "<br>";
echo "Entered password: " . $current_password . "<br>";

// Verify the current password (no re-hashing here!)
if (password_verify($current_password, $user['password'])) {
    echo "✅ Password is correct!<br>";

    // ✅ Hash the new password
    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // ✅ Update the password in the DB
    $update_query = "UPDATE users SET password = ? WHERE user_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $new_hashed_password, $user_id);

    if ($stmt->execute()) {
        echo "🎉 Password changed successfully!";
    } else {
        echo "❌ Error changing password: " . $conn->error;
    }

} else {
    echo "❌ Current password is incorrect!";
}
?>
