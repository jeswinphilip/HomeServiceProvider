<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your actual username
$password = ""; // Replace with your actual password
$dbname = "homeservice";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $user_type = $_POST['user_type']; // 'user' or 'staff'

    // Handle profile photo upload
    $profile_photo = '';
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/profile_photos/";
        $profile_photo = $target_dir . basename($_FILES["profile_photo"]["name"]);
        move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $profile_photo);
    }

    // Handle identity proof upload
    $identity_proof = '';
    if (isset($_FILES['identity_proof']) && $_FILES['identity_proof']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/profile_photos/";
        $identity_proof = $target_dir . basename($_FILES["identity_proof"]["name"]);
        move_uploaded_file($_FILES["identity_proof"]["tmp_name"], $identity_proof);
    }

    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO users (username, email, phone, user_type, profile_photo, identity_proof) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $email, $phone, $user_type, $profile_photo, $identity_proof);
    
    if ($stmt->execute()) {
        echo "User added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="styles.css"> <!-- Optional CSS file for styling -->
</head>
<body>
    <h1>Add User</h1>
    <form action="add_user.php" method="post" enctype="multipart/form-data">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" required>

        <label for="user_type">User Type:</label>
        <select id="user_type" name="user_type">
            <option value="user">User</option>
            <option value="staff">Staff</option>
        </select>

        <label for="profile_photo">Profile Photo:</label>
        <input type="file" id="profile_photo" name="profile_photo" accept="image/*">

        <label for="identity_proof">Identity Proof:</label>
        <input type="file" id="identity_proof" name="identity_proof" accept="image/*">

        <input type="submit" value="Add User">
    </form>
</body>
</html>
