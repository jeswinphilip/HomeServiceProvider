<?php
// Include database connection
include('db_connect.php');

// Fetch users from the database
$usersResult = $conn->query("SELECT * FROM users WHERE user_type = 'user'");
$staffResult = $conn->query("SELECT * FROM users WHERE user_type = 'staff'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Optional CSS file for styling -->
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            width: 50px; /* Adjust as needed */
            height: auto;
        }
        h2 {
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>

    <h2>Manage Users</h2>
    <table>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Profile Photo</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $usersResult->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['phone']); ?></td>
            <td>
                <?php if ($row['profile_photo']): ?>
                    <img src="<?php echo htmlspecialchars($row['profile_photo']); ?>" alt="Profile Photo">
                <?php else: ?>
                    No Photo
                <?php endif; ?>
            </td>
            <td>
                <a href="edit_user.php?id=<?php echo $row['user_id']; ?>">Edit</a>
                <a href="delete_user.php?id=<?php echo $row['user_id']; ?>" onclick="return confirm('Are you sure you want to delete <?php echo htmlspecialchars($row['username']); ?>?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>Manage Staff</h2>
    <table>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Profile Photo</th>
            <th>Identity Proof</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $staffResult->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['phone']); ?></td>
            <td>
                <?php if ($row['profile_photo']): ?>
                    <img src="<?php echo htmlspecialchars($row['profile_photo']); ?>" alt="Profile Photo">
                <?php else: ?>
                    No Photo
                <?php endif; ?>
            </td>
            <td>
                <?php if ($row['identity_proof']): ?>
                    <img src="<?php echo htmlspecialchars($row['identity_proof']); ?>" alt="Identity Proof">
                <?php else: ?>
                    No Proof
                <?php endif; ?>
            </td>
            <td>
                <a href="edit_staff.php?id=<?php echo $row['user_id']; ?>">Edit</a>
                <a href="delete_user.php?id=<?php echo $row['user_id']; ?>" onclick="return confirm('Are you sure you want to delete <?php echo htmlspecialchars($row['username']); ?>?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
