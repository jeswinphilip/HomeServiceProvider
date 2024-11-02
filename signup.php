<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
</head>
<body>
    <h2>Sign Up</h2>
    <form action="process_signup.php" method="POST">
        <label>Username:</label>
        <input type="text" name="username" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>

        <label>Phone:</label>
        <input type="text" name="phone" required><br><br>

        <label>User Type:</label>
        <select name="user_type" required>
            <option value="user">User</option>
            <option value="staff">Staff</option>
        </select><br><br>

        <button type="submit">Sign Up</button>
    </form>
</body>
</html>
