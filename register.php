<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<script>
function showHint(message) {
    document.getElementById('hint').textContent = message;
}
function clearHint() {
    document.getElementById('hint').textContent = '';
}
</script>
</head>

<body>
    <h2>User Registration</h2>
    <form method="POST" action="register.php">
        <label for="username">Username:</label>
        <input type="text" name="username" minlength="3" maxlength="20" required
            onfocus="showHint('Enter a username between 3 and 20 characters.')"
            onblur="clearHint()"><br><br>

        <label for="email">Email(Optional):</label>
        <input type="email" name="email"><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" minlength="6" required
            onfocus="showHint('Password must be at least 6 characters long.')"
            onblur="clearHint()"><br><br>

        <input type="submit" name="register" value="Register">
    </form>

<p id="hint" style="color:gray;"></p>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'db_config.php';

    $username = $_POST['username'];
    $email = $_POST['email'];
    $raw_password = $_POST['password'];
    $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

    try {
        $check = $pdo->prepare("SELECT * FROM Users WHERE username = ?");
        $check->execute([$username]);

        if ($check->rowCount() > 0) {
            echo "<p style='color:red;'>Username already exists. Please choose another.</p>";
        } else {
            $stmt = $pdo->prepare("INSERT INTO Users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_password]);

            echo "<p style='color:green;'>Registration successful! You can now <a href='login.php'>log in</a>.</p>";
        }
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    }
}
?>
</body>
</html>