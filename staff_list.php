<?php
// Query staff members from the database
$staff_sql = "SELECT * FROM users WHERE user_type = 'staff'";
$staff_result = $conn->query($staff_sql);

if ($staff_result->num_rows > 0) {
    echo "<table><tr><th>ID</th><th>Username</th><th>Email</th><th>Phone</th><th>Actions</th></tr>";
    while ($staff = $staff_result->fetch_assoc()) {
        echo "<tr>
            <td>{$staff['user_id']}</td>
            <td>{$staff['username']}</td>
            <td>{$staff['email']}</td>
            <td>{$staff['phone']}</td>
            <td>
                <a href='edit_staff.php?id={$staff['user_id']}'>Edit</a> | 
                <a href='delete_staff.php?id={$staff['user_id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
            </td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "No staff found.";
}
?>
