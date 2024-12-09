<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
require_once 'db_connect.php';

// Retrieve form data and sanitize inputs
$first_name = $conn->real_escape_string($_POST['first_name']);
$last_name = $conn->real_escape_string($_POST['last_name']);
$email = $conn->real_escape_string($_POST['email']);
$mobile_number = $conn->real_escape_string($_POST['mobile_number']);
$school_info = $conn->real_escape_string($_POST['school_info']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<div style='text-align: center; margin-top: 20%; font-family: Arial, sans-serif;'>
            <h2 style='color: red;'>Error: Invalid email format!</h2>
          </div>";
    exit;
}

// Check if passwords match
if ($password !== $confirm_password) {
    echo "<div style='text-align: center; margin-top: 20%; font-family: Arial, sans-serif;'>
            <h2 style='color: red;'>Error: Passwords do not match!</h2>
          </div>";
    exit;
}

// Hash the password
$password_hash = password_hash($password, PASSWORD_BCRYPT);

// Check if the email already exists
$sql_check = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql_check);

if ($result->num_rows > 0) {
    echo "<div style='text-align: center; margin-top: 20%; font-family: Arial, sans-serif;'>
            <h2 style='color: red;'>Error: Email already exists!</h2>
          </div>";
    exit;
}

// Insert data into database
$sql = "INSERT INTO users (first_name, last_name, email, mobile_number, school_info, password_hash)
        VALUES ('$first_name', '$last_name', '$email', '$mobile_number', '$school_info', '$password_hash')";

if ($conn->query($sql) === TRUE) {
    echo "<div style='
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
            z-index: 9999;'>
            <div style='
                background: #fff;
                padding: 20px 40px;
                text-align: center;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);'>
                <h1 style='color: green;'>Sign Up Successful!</h1>
                <p style='color: #555;'>Redirecting to Home...</p>
            </div>
          </div>
          <script>
            setTimeout(function() {
                window.location.href = '../index.html';
            }, 3000); // Redirect after 3 seconds
          </script>";
} else {
    echo "<div style='text-align: center; margin-top: 20%; font-family: Arial, sans-serif;'>
            <h2 style='color: red;'>Error inserting data: " . $conn->error . "</h2>
          </div>";
    exit;
}

// Close connection
$conn->close();
?>
