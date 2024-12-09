<?php
// Include the common login validation and user retrieval script
include('php/after_login.php');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <title>Welcome - StudySmart</title>
</head>
<body style="background-image: url(./asset/hand-drawn-colorful-science-education-wallpaper_23-2148489183.avif);
background-repeat: no-repeat;
background-size: cover;
;
">
    <!-- Navigation Bar -->
    <div class="nav-bar">
        <ul>
            <li><a href="main.php" class="active">Main</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="php/logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-container">
        <h1>Welcome, <?php echo htmlspecialchars($user['last_name']); ?>!</h1>
        <p>Select an option below to get started:</p>

        <!-- Special Buttons Section -->
        <div class="special-buttons">
            <a href="task_creation.php" class="button">Task Creation</a>
            <a href="task_records.php" class="button">Task Records</a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 StudySmart. All Rights Reserved.</p>
    </footer>
</body>
</html>
