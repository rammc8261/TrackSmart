<?php
include '../includes/db.php';
if (isset($_POST['action']) && $_POST['action'] == 'create_student' && $_SERVER["REQUEST_METHOD"] == "POST") {
    $roll_no = $_POST['roll_no'];
    $enrollment_no = $_POST['enrollment_no'];
    $student_name = $_POST['student_name'];
    $year_of_study = $_POST['year_of_study'];
    $department_short_name = $_POST['department_short_name'];
    $student_contact = $_POST['student_contact'];
    $parent_contact = $_POST['parent_contact'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // Insert into database
    $sql = "INSERT INTO students (roll_no, enrollment_no, student_name, year_of_study, department_short_name, student_contact, parent_contact, email, address) 
            VALUES ('$roll_no', '$enrollment_no', '$student_name', '$year_of_study', '$department_short_name', '$student_contact', '$parent_contact', '$email', '$address')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Student Registered Successfully!'); window.location.href='student_registration.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}