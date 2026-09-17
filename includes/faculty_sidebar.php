<<<<<<< HEAD
<?php 
// Check if the user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'faculty') {
  header("Location: ../logout.php"); // Redirect to login page
  exit();
}?>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Faculty Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#attendance" aria-expanded="false" aria-controls="attendance">
              <i class="icon-bar-graph menu-icon"></i>
              <span class="menu-title">Attendance</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="attendance">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="student_attendance.php">Student</a></li>
              </ul>
            </div>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" href="monthly_attendance_overview.php">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Monthly Overview</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="calender_view.php">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Monthly Overview</span>
            </a>
          </li>
          
        </ul>
=======
<?php 
// Check if the user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'faculty') {
  header("Location: ../logout.php"); // Redirect to login page
  exit();
}?>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Faculty Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#attendance" aria-expanded="false" aria-controls="attendance">
              <i class="icon-bar-graph menu-icon"></i>
              <span class="menu-title">Attendance</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="attendance">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="student_attendance.php">Student</a></li>
              </ul>
            </div>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" href="monthly_attendance_overview.php">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Monthly Overview</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="calender_view.php">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Monthly Overview</span>
            </a>
          </li>
          
        </ul>
>>>>>>> 225f81b85625e790025fab833e204e96e67aba44
      </nav>