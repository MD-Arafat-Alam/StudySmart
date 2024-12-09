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

// Handle task actions (mark as completed or delete)
if (isset($_GET['action'])) {
    $task_id = $_GET['task_id'];
    $action = $_GET['action'];

    if ($action == 'complete') {
        // Mark task as complete
        $sql = "UPDATE tasks SET status = 'completed' WHERE task_id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $conn->error);  // Debugging message
        }
        $stmt->bind_param("ii", $task_id, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
    } elseif ($action == 'delete') {
        // Delete task
        $sql = "DELETE FROM tasks WHERE task_id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $conn->error);  // Debugging message
        }
        $stmt->bind_param("ii", $task_id, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
    }
}

// Retrieve current tasks for the user (not completed)
$sql_current = "SELECT * FROM tasks WHERE user_id = ? AND status != 'completed'";
$stmt_current = $conn->prepare($sql_current);
if ($stmt_current === false) {
    die('MySQL prepare error for current tasks: ' . $conn->error);  // Debugging message
}
$stmt_current->bind_param("i", $_SESSION['user_id']);
$stmt_current->execute();
$current_tasks = $stmt_current->get_result();

// Retrieve completed tasks for the user
$sql_completed = "SELECT * FROM tasks WHERE user_id = ? AND status = 'completed'";
$stmt_completed = $conn->prepare($sql_completed);
if ($stmt_completed === false) {
    die('MySQL prepare error for completed tasks: ' . $conn->error);  // Debugging message
}
$stmt_completed->bind_param("i", $_SESSION['user_id']);
$stmt_completed->execute();
$completed_tasks = $stmt_completed->get_result();

// Close connection
$stmt_current->close();
$stmt_completed->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Records - StudySmart</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <!-- Navigation Bar -->
    <div class="nav-bar">
        <ul>
            <li><a href="main.php">Home</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="php/logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Current Tasks Table -->
    <div class="task-container">
        <h1>Current Tasks</h1>
        <table>
            <tr>
                <th>Priority</th>
                <th>Category</th>
                <th>Task Info</th>
                <th>Date</th>
                <th>Time</th>
                <th>Action</th>
            </tr>
            <?php
            if ($current_tasks->num_rows > 0) {
                while ($task = $current_tasks->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($task['priority']) . "</td>
                            <td>" . htmlspecialchars($task['category']) . "</td>
                            <td>" . htmlspecialchars($task['task_info']) . "</td>
                            <td>" . htmlspecialchars($task['task_date']) . "</td>
                            <td>" . htmlspecialchars($task['task_time']) . "</td>
                            <td>
                                <a href='?action=complete&task_id=" . $task['task_id'] . "'>Mark as Completed</a> | 
                                <a href='?action=delete&task_id=" . $task['task_id'] . "'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No current tasks found.</td></tr>";
            }
            ?>
        </table>
    </div>

    <!-- Completed Tasks Table -->
    <div class="task-container">
        <h1>Completed Tasks</h1>
        <table>
            <tr>
                <th>Priority</th>
                <th>Category</th>
                <th>Task Info</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
            <?php
            if ($completed_tasks->num_rows > 0) {
                while ($task = $completed_tasks->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($task['priority']) . "</td>
                            <td>" . htmlspecialchars($task['category']) . "</td>
                            <td>" . htmlspecialchars($task['task_info']) . "</td>
                            <td>" . htmlspecialchars($task['task_date']) . "</td>
                            <td>" . htmlspecialchars($task['task_time']) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No completed tasks found.</td></tr>";
            }
            ?>
        </table>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 StudySmart. All rights reserved.</p>
    </footer>
</body>
</html>
