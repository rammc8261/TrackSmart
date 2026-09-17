<?php
include('../includes/db.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $roll_no = $_POST['roll_no'];
    $student_name = $_POST['student_name'];
    $email = $_POST['email'];

    // Validate inputs
    if (empty($id) || empty($roll_no) || empty($student_name) || empty($email)) {
        echo "All fields are required!";
        exit;
    }

    // Update query
    $query = "UPDATE students SET roll_no = ?, student_name = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $roll_no, $student_name, $email, $id);

    if ($stmt->execute()) {
        echo "Student updated successfully!";
    } else {
        echo "Error updating student.";
    }

    $stmt->close();
}

$conn->close();
?>
