<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
        background-color: #f4f6f9;
        font-family: 'Segoe UI', sans-serif;
    }

    .register-container {
        max-width: 500px;
        margin: 80px auto;
        padding: 30px 40px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }

    .register-container h2 {
        text-align: center;
        margin-bottom: 25px;
    }

    .form-label {
        font-weight: bold;
    }

    #hint {
        color: gray;
        text-align: center;
        margin-top: 10px;
    }

    .message {
        text-align: center;
        margin-top: 20px;
    }
  </style>

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
<?php include 'nav.php'; ?>
<div class="register-container">
  <h2>User Registration</h2>
  <form method="POST" action="register.php">
    <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <input type="text" class="form-control" name="username" minlength="3" maxlength="20" required
             onfocus="showHint('Enter a username between 3 and 20 characters.')"
             onblur="clearHint()">
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email (Optional)</label>
      <input type="email" class="form-control" name="email">
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" name="password" minlength="6" required
             onfocus="showHint('Password must be at least 6 characters long.')"
             onblur="clearHint()">
    </div>

    <div class="d-grid">
      <button type="submit" class="btn btn-primary" name="register">Register</button>
    </div>
  </form>

<p id="hint"></p>

<div class="message">

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
                echo "<div class='text-danger'>Username already exists. Please choose another.</div>";
            } else {
                $stmt = $pdo->prepare("INSERT INTO Users (username, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $hashed_password]);

                echo "<div class='text-success'>Registration successful! You can now <a href='login.php'>log in</a>.</div>";
            }
        } catch (PDOException $e) {
            echo "<div class='text-danger'>Database Error: " . $e->getMessage() . "</div>";
        }
    }
    ?>
  </div>
</div>
</body>
</html>