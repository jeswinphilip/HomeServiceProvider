<?php
// Query users from the database
$user_sql = "SELECT * FROM users WHERE user_type = 'user'";
$user_result = $conn->query($user_sql);

if ($user_result->num_rows > 0) {
    echo "<table><tr><th>ID</th><th>Username</th><th>Email</th><th>Phone</th><th>Actions</th></tr>";
    while ($user = $user_result->fetch_assoc()) {
        echo "<tr>
            <td>{$user['user_id']}</td>
            <td>{$user['username']}</td>
            <td>{$user['email']}</td>
            <td>{$user['phone']}</td>
            <td>
                <a href='edit_user.php?id={$user['user_id']}'>Edit</a> | 
                <a href='delete_user.php?id={$user['user_id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
            </td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "No users found.";
}
?>
