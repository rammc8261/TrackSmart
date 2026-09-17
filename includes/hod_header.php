<<<<<<< HEAD
<?php 

session_start();
// Check if the user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'hod') {
  header("Location: ../logout.php"); // Redirect to login page
  exit();
}
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
      <?php include 'hod_sidebar.php'; ?>
      <!-- ./sidebar -->
      
      <div class="main-panel">

=======
<?php 

session_start();
// Check if the user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'hod') {
  header("Location: ../logout.php"); // Redirect to login page
  exit();
}
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
      <?php include 'hod_sidebar.php'; ?>
      <!-- ./sidebar -->
      
      <div class="main-panel">

>>>>>>> 225f81b85625e790025fab833e204e96e67aba44
