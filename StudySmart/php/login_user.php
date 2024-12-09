<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session to manage user login state
session_start();

// Include the database connection
require_once 'db_connect.php';

// Retrieve form data and sanitize
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// Validate user input
if (empty($email) || empty($password)) {
    echo "<script>
            alert('Error: Please fill in both email and password.');
            window.location.href = '../login.html';
          </script>";
    exit;
}

// Validate user credentials
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Verify the password
    if (password_verify($password, $row['password_hash'])) {
        // Set session variables for user login
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['user_email'] = $row['email'];

        // Debug: Check session values
        // echo "Session Set: User ID = " . $_SESSION['user_id'] . ", Email = " . $_SESSION['user_email']; exit;

        // Show success message and redirect after 3 seconds
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
                <h1 style='color: green;'>Login Successful!</h1>
                <p style='color: #555;'>Redirecting to Home...</p>
            </div>
          </div>
          <script>
            setTimeout(function() {
                window.location.href = '../main.php';
            }, 3000); // Redirect after 3 seconds
          </script>";

        
    } else {
        // Incorrect password
        echo "<script>
                alert('Error: Incorrect password.');
                window.location.href = '../login.html';
              </script>";
    }
} else {
    // Email not found in the database
    echo "<script>
            alert('Error: No account found with this email.');
            window.location.href = '../login.html';
          </script>";
}

// Close connection
$stmt->close();
$conn->close();
?>
