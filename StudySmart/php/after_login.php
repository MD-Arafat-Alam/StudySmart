<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('You must log in to access this page.');
            window.location.href = 'login.html';
          </script>";
    exit;
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studysmart";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user information from the database
$user_id = $_SESSION['user_id'];

// Query to get user details
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);

// Check if query preparation failed
if ($stmt === false) {
    die('MySQL prepare error: ' . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "<script>
            alert('Error: No user found.');
            window.location.href = 'login.html';
          </script>";
    exit;
}

// Close connection
$stmt->close();
?>
