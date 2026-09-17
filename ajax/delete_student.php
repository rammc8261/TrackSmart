<<<<<<< HEAD
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
=======
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
>>>>>>> 225f81b85625e790025fab833e204e96e67aba44
