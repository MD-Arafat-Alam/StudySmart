<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('You must log in to access this page.');
            window.location.href = '../login.html';
          </script>";
    exit;
}

// Database connection
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

// Variables for priority and category lists
$priority_list = ['High', 'Medium', 'Low'];
$category_list = ['Study', 'Work', 'Personal', 'Others'];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $task_info = trim($_POST['task_info']);
    $priority = $_POST['priority'];
    $category = $_POST['category'];
    $task_date = $_POST['task_date'];
    $task_time = $_POST['task_time'];
    $user_id = $_SESSION['user_id']; // Use the logged-in user's ID

    // Prepared statement to insert task into the database
    $sql = "INSERT INTO tasks (user_id, task_info, priority, category, task_date, task_time) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("isssss", $user_id, $task_info, $priority, $category, $task_date, $task_time);
        
        if ($stmt->execute()) {
            echo "<script>
                    alert('Task created successfully.');
                    window.location.href = 'task_creation.php'; // Reload the page
                  </script>";
        } else {
            echo "<script>
                    alert('Error creating task: " . $stmt->error . "');
                  </script>";
        }
        
        $stmt->close();
    } else {
        echo "<script>
                alert('Error preparing statement: " . $conn->error . "');
              </script>";
    }
}

// Retrieve all tasks for the user
$sql = "SELECT * FROM tasks WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

// Close connection
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Creation - StudySmart</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body style="background-image: url(./asset/Savin-NY-Website-Background-Web.jpg);
background-repeat: no-repeat;
background-size: cover;">
    <!-- Navigation Bar -->
    <div class="nav-bar">
        <ul>
            <li><a href="main.php">Home</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="php/logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Task Creation Form -->
    <div class="task-container">
        <h1>Create a Task</h1>
        <form method="POST" action="task_creation.php">
            <label for="priority">Priority</label>
            <select name="priority" id="priority" required>
                <option value="" disabled selected>Select Priority</option>
                <?php foreach ($priority_list as $priority_option): ?>
                    <option value="<?= htmlspecialchars($priority_option); ?>"><?= htmlspecialchars($priority_option); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="category">Category</label>
            <select name="category" id="category" required>
                <option value="" disabled selected>Select Category</option>
                <?php foreach ($category_list as $category_option): ?>
                    <option value="<?= htmlspecialchars($category_option); ?>"><?= htmlspecialchars($category_option); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="task_info">Task Info</label>
            <textarea name="task_info" id="task_info" required></textarea>

            <label for="task_date">Date</label>
            <input type="date" name="task_date" id="task_date" required>

            <label for="task_time">Time</label>
            <input type="time" name="task_time" id="task_time" required>

            <button type="submit">Create Task</button>
        </form>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 StudySmart. All rights reserved.</p>
    </footer>
</body>
</html>
