<?php
// Database connection parameters
$servername = "localhost";
$username = "root";  // Default XAMPP MySQL username
$password = "";      // Default XAMPP MySQL password (usually empty)
$dbname = "homeservice"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];  // No hashing
$phone = $_POST['phone'];
$user_type = $_POST['user_type'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO users (username, email, password, phone, user_type) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $username, $email, $password, $phone, $user_type);

// Execute the query
if ($stmt->execute()) {
    echo "Registration successful!";
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
