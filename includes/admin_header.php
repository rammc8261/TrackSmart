<?php 

session_start();
// Check if the user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
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
      <?php include 'admin_sidebar.php'; ?>
      <!-- ./sidebar -->
      
      <div class="main-panel">

