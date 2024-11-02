<?php
// Include database connection
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password']; // Make sure you handle password securely in production
    $location = $_POST['location'];
    $identityProof = $_FILES['identity_proof'];
    $profilePhoto = $_FILES['profile_photo'];

    // Handle file uploads for identity proof
    $identityProofPath = 'uploads/' . basename($identityProof['name']);
    if (move_uploaded_file($identityProof['tmp_name'], $identityProofPath) === false) {
        die("Failed to upload identity proof.");
    }

    // Handle optional profile photo upload
    $profilePhotoPath = null;
    if (!empty($profilePhoto['name'])) {
        $profilePhotoPath = 'uploads/' . basename($profilePhoto['name']);
        if (move_uploaded_file($profilePhoto['tmp_name'], $profilePhotoPath) === false) {
            die("Failed to upload profile photo.");
        }
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO `users` (`username`, `email`, `password`, `user_type`, `phone`, `created_at`, `identity_proof`, `profile_photo`, `services_provided`) VALUES (?, ?, ?, 'staff', ?, NOW(), ?, ?, ?)");
    
    // Check for errors in preparation
    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }

    // Bind parameters
    $servicesProvided = implode(",", $_POST['services']); // Assuming services are coming from a form input named 'services'
    $stmt->bind_param("sssss", $username, $email, $password, $phone, $identityProofPath, $profilePhotoPath, $servicesProvided);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Staff registered successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Signup</title>
</head>
<body>
    <h2>Staff Signup</h2>
    <form action="add_staff.php" method="POST" enctype="multipart/form-data">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="phone">Phone:</label>
        <input type="text" name="phone" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <label for="location">Location:</label>
        <select name="location" required>
            <!-- Populate locations from the database -->
            <?php
            $locationQuery = "SELECT * FROM locations";
            $result = $conn->query($locationQuery);
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['location_name']}</option>";
            }
            ?>
        </select><br>

        <label for="identity_proof">Identity Proof:</label>
        <input type="file" name="identity_proof" required><br>

        <label for="profile_photo">Profile Photo (optional):</label>
        <input type="file" name="profile_photo"><br>

        <label for="services">Services Provided:</label><br>
        <!-- Assuming you have a table named services with an id and service_name -->
        <?php
        $serviceQuery = "SELECT * FROM services";
        $serviceResult = $conn->query($serviceQuery);
        while ($serviceRow = $serviceResult->fetch_assoc()) {
            echo "<input type='checkbox' name='services[]' value='{$serviceRow['service_id']}'> {$serviceRow['service_name']}<br>";
        }
        ?>

        <input type="submit" value="Register Staff">
    </form>
</body>
</html>
