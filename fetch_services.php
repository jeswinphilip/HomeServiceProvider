<?php
$servername = "localhost"; // Database server
$username = "root"; // Database username
$password = ""; // Database password
$dbname = "homeservice"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch services without price
$sql = "SELECT service_id, service_name, description FROM services";
$result = $conn->query($sql);

// Store services in an array
$services = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <title>Available Services</title>
</head>
<body>
    <h1>Available Services</h1>
    <div id="services-container">
        <?php foreach ($services as $service): ?>
            <div class="service">
                <h2><?php echo htmlspecialchars($service['service_name']); ?></h2>
                <p><?php echo htmlspecialchars($service['description']); ?></p>
                <button onclick="bookService(<?php echo $service['service_id']; ?>)">Book Now</button>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function bookService(serviceId) {
            alert('Service ID: ' + serviceId); // Replace with actual booking logic
        }
    </script>
</body>
</html>
