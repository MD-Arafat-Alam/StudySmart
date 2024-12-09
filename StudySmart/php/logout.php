<?php
// Start session
session_start();

// Destroy the session
session_unset();
session_destroy();

// Optionally, display a logout message before redirect
echo "<script>
        alert('You have logged out successfully.');
        window.location.href = '../login.html';
      </script>";
exit;
?>
