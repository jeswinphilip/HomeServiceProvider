<?php
session_start();
include('conn.php');  // Ensure the connection is properly included

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Correct query with the right column name
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);

    // Check if the query executed successfully
    if (!$result) {
        die("Query Failed: " . $conn->error);  // Show the exact error
    }

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Store user info in session
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['user_type'] = $row['user_type'];

        // Redirect based on user type
        if ($row['user_type'] == 'admin') {
            header('Location: admin_dashboard.php');
        } elseif ($row['user_type'] == 'staff') {
            header('Location: staff_dashboard.php');
        } else {
            header('Location: user_dashboard.php');
        }
        exit();
    } else {
        echo "<div class='error'>Invalid Username or Password</div>";
    }
}
?>
