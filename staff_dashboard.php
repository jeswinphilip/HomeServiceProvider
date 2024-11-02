<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'staff') {
    header('Location: login.html');
    exit();
}
?>
<h1>Welcome Staff Member!</h1>
<p>Manage your tasks and view schedules here.</p>
<a href="logout.php">Logout</a>
