<?php
include('../includes/db.php'); 

$selected_date = $_POST['date'] ?? date('Y-m-d');

// Fetch all departments
$departments_query = "SELECT department_short_code FROM departments WHERE department_name != 'management' AND status = 1";
$departments_result = $conn->query($departments_query);
$departments = [];

while ($dept = $departments_result->fetch_assoc()) {
    $departments[] = $dept['department_short_code'];
}

// Define all lecture time slots
$time_slots = [
    "10:05 11:05",
    "11:05 12:05",
    "12:05 01:05",
    "01:00 02:00",
    "02:00 03:00",
    "03:00 04:00",
    "04:00 05:00"
];

$years = ['1', '2', '3'];

echo "<div class='row'>";
foreach ($years as $year) {
    echo "<div class='col-sm-6'><h2 >{$year} Year Attendance</h2>";
    echo "<table><thead><tr><th>Department</th>";

    foreach ($time_slots as $slot) {
        echo "<th>$slot</th>";
    }
    echo "</tr></thead><tbody>";

    foreach ($departments as $dept_code) {
        echo "<tr><td>{$dept_code}</td>";

        foreach ($time_slots as $slot) {
            // Get total students
            $total_students_query = "SELECT COUNT(*) AS total FROM students WHERE department_short_name = ? AND year_of_study = ?";
            $stmt_total = $conn->prepare($total_students_query);
            $stmt_total->bind_param("si", $dept_code, $year);
            $stmt_total->execute();
            $total_students_result = $stmt_total->get_result();
            $total_students = $total_students_result->fetch_assoc()['total'] ?? 0;

            // Get present students
            $present_students_query = "SELECT COUNT(*) AS present FROM student_attendance 
                                       WHERE department = ? AND year_of_study = ? 
                                       AND lecture_time_slot = ? 
                                       AND attendance_status = 'Present' 
                                       AND attendance_date = ?";
            $stmt_present = $conn->prepare($present_students_query);
            $stmt_present->bind_param("siss", $dept_code, $year, $slot, $selected_date);
            $stmt_present->execute();
            $present_students_result = $stmt_present->get_result();
            $present_students = $present_students_result->fetch_assoc()['present'] ?? 0;

            // Calculate attendance percentage
            $attendance_percentage = ($total_students > 0) ? ($present_students / $total_students) * 100 : 0;
            $attendance_percentage = number_format($attendance_percentage, 2);

            // Set background color
            $class = ($attendance_percentage > 85) ? "high" : (($attendance_percentage >= 75) ? "medium" : "low");

            echo "<td class='$class'>{$attendance_percentage}%</td>";
        }
        echo "</tr>";
    }

    echo "</tbody></table></div>";
}

echo "</div>";
?>
