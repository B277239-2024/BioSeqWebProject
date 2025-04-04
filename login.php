<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-container {
            max-width: 450px;
            margin: 100px auto;
            padding: 30px 40px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        .form-label {
            font-weight: bold;
        }
        .message {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
<?php include 'nav.php'; ?>
<div class="login-container">
    <h2>User Login</h2>
    <form method="POST" action="login.php">
        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" name="username" required>
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary" name="login">Login</button>
        </div>
    </form>

<div class="message">
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'db_config.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM Users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];

                echo "<div class='text-success'>Login successful! <a href='submit_job.php'>Go to Submit Job</a></div>";
                    } else {
                        echo "<div class='text-danger'>Incorrect password.</div>";
                    }
                } else {
                    echo "<div class='text-danger'>Username not found.</div>";
                }
            } catch (PDOException $e) {
                echo "<div class='text-danger'>Database Error: " . $e->getMessage() . "</div>";
            }
        }
        ?>
    </div>
    <div class="text-center mt-3">
      <p>Don't have an account? <a href="register.php">Create one here</a>.</p>
    </div>
</div>

</body>
</html>