<?php
// Include database connection
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password']; // In production, consider hashing passwords
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

    // Prepare the SQL statement to insert into users table
    $stmt = $conn->prepare("INSERT INTO `users` (`username`, `email`, `password`, `user_type`, `phone`, `created_at`, `identity_proof`, `profile_photo`) VALUES (?, ?, ?, 'staff', ?, NOW(), ?, ?)");
    
    // Check for errors in preparation
    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("ssssss", $username, $email, $password, $phone, $identityProofPath, $profilePhotoPath);

    // Execute the statement
    if ($stmt->execute()) {
        // Get the last inserted user ID to insert into staff_services
        $staffId = $stmt->insert_id;

        // Insert service for the staff
        $serviceId = $_POST['service']; // Assuming this is a single select from dropdown

        // First, get the service name from the services table
        $serviceQuery = $conn->prepare("SELECT service_name FROM services WHERE service_id = ?");
        $serviceQuery->bind_param("i", $serviceId);
        $serviceQuery->execute();
        $serviceResult = $serviceQuery->get_result();
        $serviceRow = $serviceResult->fetch_assoc();
        $serviceName = $serviceRow['service_name'];

        // Insert into staff_services
        $serviceStmt = $conn->prepare("INSERT INTO `staff_services` (`staff_id`, `service_id`) VALUES (?, ?)");
        $serviceStmt->bind_param("ii", $staffId, $serviceId);
        $serviceStmt->execute();

        // Update the users table to include the service provided
        $updateStmt = $conn->prepare("UPDATE `users` SET `service_provided` = ? WHERE `user_id` = ?");
        $updateStmt->bind_param("si", $serviceName, $staffId);
        $updateStmt->execute();

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
    <form action="staff_signup.php" method="POST" enctype="multipart/form-data">
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

        <label for="service">Service Provided:</label>
        <select name="service" required>
            <?php
            $serviceQuery = "SELECT * FROM services";
            $serviceResult = $conn->query($serviceQuery);
            while ($serviceRow = $serviceResult->fetch_assoc()) {
                echo "<option value='{$serviceRow['service_id']}'>{$serviceRow['service_name']}</option>";
            }
            ?>
        </select><br>

        <input type="submit" value="Register Staff">
    </form>
</body>
</html>
