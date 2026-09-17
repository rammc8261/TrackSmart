<<<<<<< HEAD
<?php
include '../includes/db.php';

if (isset($_POST["import"])) {
    $file = $_FILES["file"]["tmp_name"];

    if (!empty($file)) {
        $handle = fopen($file, "r");

        $count = 0; // Skip header row
        $inserted_records = 0; // Count successful inserts

        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($count == 0) { // Skip header row
                $count++;
                continue;
            }

            // ✅ **Skip empty rows**
            if (empty(array_filter($row))) {
                continue; // Skip if the entire row is empty
            }

            // Extract data from CSV
            $roll_no = trim($row[0]);
            $enrollment_no = trim($row[1]);
            $student_name = trim($row[2]);
            $year_of_study = trim($row[3]);
            $department_short_name = trim($row[4]);
            $student_contact = trim($row[5]);
            $parent_contact = trim($row[6]);
            $email = trim($row[7]);

            // ✅ **Ensure mandatory fields are not empty**
            if (empty($enrollment_no) || empty($student_name) || empty($year_of_study) || empty($department_short_name)) {
                continue; // Skip row if critical fields are empty
            }

            // ✅ **Check if enrollment number already exists**
            $check_sql = "SELECT id FROM students WHERE enrollment_no = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("s", $enrollment_no);
            $check_stmt->execute();
            $check_stmt->store_result();

            if ($check_stmt->num_rows == 0) { // Insert only if not exists
                $sql = "INSERT INTO students (roll_no, enrollment_no, student_name, year_of_study, department_short_name, student_contact, parent_contact, email) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssss", $roll_no, $enrollment_no, $student_name, $year_of_study, $department_short_name, $student_contact, $parent_contact, $email);
                $stmt->execute();

                if (!$stmt->error) {
                    $inserted_records++;
                }
                $stmt->close();
            }
            $check_stmt->close();
        }

        fclose($handle);

        if ($inserted_records > 0) {
            echo "<script>alert('$inserted_records students imported successfully!'); window.location.href='student_registration.php';</script>";
        } else {
            echo "<script>alert('No new students added. All enrollment numbers already exist or rows were empty.'); window.location.href='student_registration.php';</script>";
        }
    } else {
        echo "<script>alert('Please select a file!'); window.location.href='student_registration.php';</script>";
    }
}
?>
=======
<?php
include '../includes/db.php';

if (isset($_POST["import"])) {
    $file = $_FILES["file"]["tmp_name"];

    if (!empty($file)) {
        $handle = fopen($file, "r");

        $count = 0; // Skip header row
        $inserted_records = 0; // Count successful inserts

        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($count == 0) { // Skip header row
                $count++;
                continue;
            }

            // ✅ **Skip empty rows**
            if (empty(array_filter($row))) {
                continue; // Skip if the entire row is empty
            }

            // Extract data from CSV
            $roll_no = trim($row[0]);
            $enrollment_no = trim($row[1]);
            $student_name = trim($row[2]);
            $year_of_study = trim($row[3]);
            $department_short_name = trim($row[4]);
            $student_contact = trim($row[5]);
            $parent_contact = trim($row[6]);
            $email = trim($row[7]);

            // ✅ **Ensure mandatory fields are not empty**
            if (empty($enrollment_no) || empty($student_name) || empty($year_of_study) || empty($department_short_name)) {
                continue; // Skip row if critical fields are empty
            }

            // ✅ **Check if enrollment number already exists**
            $check_sql = "SELECT id FROM students WHERE enrollment_no = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("s", $enrollment_no);
            $check_stmt->execute();
            $check_stmt->store_result();

            if ($check_stmt->num_rows == 0) { // Insert only if not exists
                $sql = "INSERT INTO students (roll_no, enrollment_no, student_name, year_of_study, department_short_name, student_contact, parent_contact, email) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssss", $roll_no, $enrollment_no, $student_name, $year_of_study, $department_short_name, $student_contact, $parent_contact, $email);
                $stmt->execute();

                if (!$stmt->error) {
                    $inserted_records++;
                }
                $stmt->close();
            }
            $check_stmt->close();
        }

        fclose($handle);

        if ($inserted_records > 0) {
            echo "<script>alert('$inserted_records students imported successfully!'); window.location.href='student_registration.php';</script>";
        } else {
            echo "<script>alert('No new students added. All enrollment numbers already exist or rows were empty.'); window.location.href='student_registration.php';</script>";
        }
    } else {
        echo "<script>alert('Please select a file!'); window.location.href='student_registration.php';</script>";
    }
}
?>
>>>>>>> 225f81b85625e790025fab833e204e96e67aba44
