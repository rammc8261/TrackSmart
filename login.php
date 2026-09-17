<?php
session_start();
include 'includes/db.php';
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrackSmart</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div id="login-container">
        <div class="login-box">
            <h1>Login</h1>
            <input type="text" id="username" placeholder="Username" name="user_id" required>
            <input type="password" id="password" name="password" placeholder="Password (min 6 chars, 1 uppercase, 1 number)" required>
            <select id="role" name="role" required>
            <?php
            $sql = "SELECT * FROM user_roles";

            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<option value='". $row["id"]. "'>". $row["role_name"]. "</option>";
                }
                } else {
                echo "0 results";
            } ?>
                
            </select>
            <div class="error-message" id="error-message"></div>
            <button type="button" onclick="validateForm()">Login</button>
        </div>
    </div>

    <script>
        function validateForm() {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const role = document.getElementById('role').value;
            const errorMessage = document.getElementById('error-message');

            const passwordRegex = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d@#$%^&+=!]{6,}$/;
console.log(role)
            if (!username || !password || !role) {
                errorMessage.textContent = 'All fields are required.';
                return;
            }

            if (!passwordRegex.test(password)) {
                errorMessage.textContent = 'Password must be at least 6 characters long, contain 1 uppercase letter, and 1 number.';
                return;
            }

            errorMessage.textContent = '';

            // Send data to PHP for authentication
            const formData = new FormData();
            formData.append('user_id', username);
            formData.append('password', password);
            formData.append('role', role);

            fetch('includes/authenticate.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    errorMessage.textContent = data.message;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorMessage.textContent = 'An error occurred. Please try again.';
            });
        }
    </script>
</body>
</html>
