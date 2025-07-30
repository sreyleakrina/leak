<?php
session_start();
require_once __DIR__ . '/../config/cn.php';

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// Admin password
$adminPassword = "010704";

// Password form
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        if ($_POST['password'] === $adminPassword) {
            $_SESSION['is_admin'] = true;
            header("Location: admin.php");
            exit;
        } else {
            $error = "Incorrect password!";
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
          font-family: Arial, sans-serif;
          background: #fff0f6;
          display: flex;
          justify-content: center;
          align-items: center;
          height: 100vh;
        }
        .login-box {
          background: #fff;
          padding: 30px;
          border-radius: 12px;
          box-shadow: 0 4px 12px rgba(248, 187, 208, 0.3);
          text-align: center;
        }
        .login-box h2 {
          color: #e91e63;
          margin-bottom: 20px;
        }
        .login-box input {
          padding: 10px;
          font-size: 1rem;
          border: 2px dashed #f8bbd0;
          border-radius: 8px;
          width: 100%;
          margin-bottom: 20px;
        }
        .login-box button {
          padding: 10px 20px;
          font-size: 1rem;
          background: #e91e63;
          color: white;
          border: none;
          border-radius: 8px;
          cursor: pointer;
        }
        .error {
          color: red;
          margin-bottom: 10px;
        }
      </style>
    </head>
    <body>
      <form method="POST" class="login-box">
        <h2>🔐 Admin Login</h2>
        <?php if (!empty($error)): ?>
          <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <input type="password" name="password" placeholder="Enter admin password..." required>
        <button type="submit">Login</button>
      </form>
    </body>
    </html>
    <?php
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];
    try {
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$deleteId]);
        header("Location: admin.php");
        exit;
    } catch (PDOException $e) {
        die("Delete failed: " . $e->getMessage());
    }
}

// Handle add product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $imagePath = '';

    if (!empty($_FILES['image']['name'])) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $fileName = basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imagePath = 'uploads/' . $fileName;
        } else {
            die("Upload failed. Check folder permissions.");
        }
    }

    try {
        $stmt = $conn->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
        $stmt->execute([$name, $price, $imagePath]);
        header("Location: admin.php");
        exit;
    } catch (PDOException $e) {
        die("Add failed: " . $e->getMessage());
    }
}

// Get all products
try {
    $stmt = $conn->query("SELECT * FROM products ORDER BY id DESC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Fetch failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Products</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #fffafc;
      padding: 2rem;
    }
    h1 {
      text-align: center;
      color: #e91e63;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 2rem;
      background: #fff;
      box-shadow: 0 2px 12px rgba(248, 187, 208, 0.4);
      border-radius: 12px;
      overflow: hidden;
    }
    th, td {
      padding: 12px;
      border-bottom: 1px dashed #f8bbd0;
      text-align: center;
    }
    th {
      background: #fff0f6;
      color: #ad1457;
    }
    img {
      height: 60px;
      border-radius: 8px;
      object-fit: cover;
    }
    a.edit, a.delete {
      padding: 6px 12px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
    }
    a.edit {
      background: #f8bbd0;
      color: #e91e63;
    }
    a.delete {
      background: #ffcdd2;
      color: #c62828;
    }
    form.add-form {
      max-width: 500px;
      margin: 2rem auto;
      background: #fff0f6;
      padding: 24px;
      border-radius: 12px;
      box-shadow: 0 4px 16px rgba(248, 187, 208, 0.4);
    }
    form.add-form input[type="text"],
    form.add-form input[type="number"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 1rem;
      border-radius: 8px;
      border: 2px dashed #f8bbd0;
    }
    form.add-form input[type="file"] {
      margin-bottom: 1rem;
    }
    form.add-form button {
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      background: #e91e63;
      color: white;
      font-size: 1rem;
      cursor: pointer;
    }
  </style>
</head>
<body>

<h1>Admin - Manage Products</h1>



</div>

<form method="POST" enctype="multipart/form-data" class="add-form">
  <h2 style="text-align:center; color:#e91e63;">Add New Product</h2>
  <input type="text" name="name" placeholder="Product Name" required>
  <input type="number" step="0.01" name="price" placeholder="Price" required>
  <input type="file" name="image" required>
  <button type="submit" name="add_product">Add Product</button>
</form>

<table>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Price ($)</th>
    <th>Image</th>
    <th>Action</th>
  </tr>
  <?php foreach ($products as $product): ?>
    <tr>
      <td><?= $product['id'] ?></td>
      <td><?= htmlspecialchars($product['name']) ?></td>
      <td>$<?= number_format($product['price'], 2) ?></td>
      <td>
        <?php 
          $imagePath = !empty($product['image']) ? '/G7/' . htmlspecialchars($product['image']) : 'https://via.placeholder.com/60x60?text=No+Image';
        ?>
        <img src="<?= $imagePath ?>" alt="Product Image">
      </td>
      <td>
        <a href="edit.php?product=<?= $product['id'] ?>" class="edit">✏️ Edit</a>
        <a href="?delete=<?= $product['id'] ?>" class="delete" onclick="return confirm('Delete this product?')">🗑️ Delete</a>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<footer>
  <div style="text-align: center; margin-top: 30px;">
    <a href="http://localhost/G7/index.php" style="
      display: inline-block;
      background: #f8bbd0;
      color: #880e4f;
      padding: 10px 20px;
      border-radius: 10px;
      text-decoration: none;
      font-weight: bold;
      box-shadow: 0 4px 10px rgba(248, 187, 208, 0.4);
      transition: background 0.3s ease;
    " onmouseover="this.style.background='#f48fb1'" onmouseout="this.style.background='#f8bbd0'">
      🏡 Go to Homepage
    </a>
  </div>
</footer>

</body>
</html>
