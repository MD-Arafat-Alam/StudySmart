<?php
// Include the common login validation and user retrieval script
include('php/after_login.php');
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - StudySmart</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body style="background-image: url(./asset/Savin-NY-Website-Background-Web.jpg);
background-repeat: no-repeat;
background-size: cover;">
    <!-- Navigation Bar -->
    <div class="nav-bar">
        <ul>
            <li><a href="main.php">Home</a></li>
            <li><a href="profile.php" class="active">Profile</a></li>
            <li><a href="php/logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Profile Information -->
    <div class="profile-container">
        <h1>User Profile</h1>
        <table>
            <tr>
                <th>First Name</th>
                <td><?php echo htmlspecialchars($user['first_name']); ?></td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td><?php echo htmlspecialchars($user['last_name']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
            </tr>
            <tr>
                <th>Mobile Number</th>
                <td><?php echo htmlspecialchars($user['mobile_number']); ?></td>
            </tr>
            <tr>
                <th>School Information</th>
                <td><?php echo htmlspecialchars($user['school_info']); ?></td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 StudySmart. All rights reserved.</p>
    </footer>
</body>
</html>
