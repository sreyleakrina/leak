<?php
session_start();

$adminPassword = "010704"; // Set your password here
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if ($_POST['password'] === $adminPassword) {
        $_SESSION['is_admin'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = "❌ Incorrect password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: #ffe4ec;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .login-container {
      background: #fff;
      padding: 30px 40px;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(233, 30, 99, 0.2);
      text-align: center;
    }
    .login-container h2 {
      margin-bottom: 20px;
      color: #e91e63;
    }
    .login-container input[type="password"] {
      padding: 12px;
      width: 100%;
      border: 2px dashed #f8bbd0;
      border-radius: 10px;
      margin-bottom: 20px;
      font-size: 1rem;
    }
    .login-container button {
      padding: 10px 20px;
      background: #e91e63;
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    .login-container button:hover {
      background: #d81b60;
    }
    .error {
      color: red;
      margin-bottom: 10px;
    }
    a.back-home {
      display: inline-block;
      margin-top: 20px;
      color: #e91e63;
      text-decoration: none;
      font-weight: bold;
    }
    a.back-home:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <form method="POST" class="login-container">
    <h2>🔐 Admin Login</h2>
    <?php if (!empty($error)): ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <input type="password" name="password" placeholder="Enter admin password..." required>
    <button type="submit">Login</button>

    <br>
  <a href="../index.php" class="back-home">🏡 Back to Homepage</a>
  </form>
</body>
</html>
