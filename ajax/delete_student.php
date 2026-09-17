<?php
include('../includes/db.php'); 

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM students WHERE id = $id";
    
    if ($conn->query($query)) {
        echo "Student deleted successfully.";
    } else {
        echo "Error deleting student.";
    }
}

$conn->close();
?>
