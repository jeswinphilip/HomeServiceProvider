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
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE user_id = ? AND user_type = 'staff'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
}

// Update user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "UPDATE users SET username = ?, email = ?, phone = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $username, $email, $phone, $user_id);
    $stmt->execute();
    header("Location: admin_dashboard.php");
}
?>
<form method="POST">
    <input type="text" name="username" value="<?php echo $result['username']; ?>" required>
    <input type="email" name="email" value="<?php echo $result['email']; ?>" required>
    <input type="text" name="phone" value="<?php echo $result['phone']; ?>" required>
    <button type="submit">Update staff</button>
</form>
