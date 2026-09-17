<?php 

session_start();

?>
<!DOCTYPE html>
<html lang="en">
    <?php include 'head_tag.php'; ?>
<body>
  <div class="container-scroller">
    <!-- navbar -->
    <?php include 'admin_top_nav.php'; ?>
    <!-- ./navbar -->
    <div class="container-fluid page-body-wrapper">
      <!-- sidebar -->
      <?php 
// Check if the user is logged in and is an admin
if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {
  include 'admin_sidebar.php';
}else if(isset($_SESSION['user']) && $_SESSION['user']['role'] == 'hod'){
  include 'hod_sidebar.php';
}else if(isset($_SESSION['user']) && $_SESSION['user']['role'] == 'faculty'){
  include 'faculty_sidebar.php';
}
else{
  header("Location: ../logout.php"); // Redirect to login page
  exit();
}?>
      <?php  ?>
      <!-- ./sidebar -->
      
      <div class="main-panel">

