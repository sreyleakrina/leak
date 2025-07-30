<?php
// ✅ MySQL Connection
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'flower_shop');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$students = [];
$editData = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string(trim($_POST['name'] ?? ''));
    $class = $conn->real_escape_string(trim($_POST['class'] ?? ''));
    $academic_year = $conn->real_escape_string(trim($_POST['academic_year'] ?? ''));
    $pay_type = $conn->real_escape_string($_POST['pay_type'] ?? '');
    $date = $conn->real_escape_string($_POST['date'] ?? '');
    $flower_price = floatval($_POST['flower_price'] ?? 0);

    if (!empty($_POST['edit_index'])) {
        $edit_id = (int)$_POST['edit_index'];
        $stmt = $conn->prepare("UPDATE students SET name=?, class=?, academic_year=?, pay_type=?, date=?, price=? WHERE id=?");
        $stmt->bind_param("ssssssi", $name, $class, $academic_year, $pay_type, $date, $flower_price, $edit_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO students (name, class, academic_year, pay_type, date, price) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssd", $name, $class, $academic_year, $pay_type, $date, $flower_price);
    }
    $stmt->execute();
    $stmt->close();
    header("Location: result.php");
    exit;
}

if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $res = $conn->query("SELECT * FROM students WHERE id = $edit_id");
    $editData = $res->fetch_assoc();
}

if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $conn->query("DELETE FROM students WHERE id = $delete_id");
    header("Location: result.php");
    exit;
}

$res = $conn->query("SELECT * FROM students ORDER BY id DESC");
while ($row = $res->fetch_assoc()) {
    $students[] = $row;
}
?>

<!DOCTYPE html>
<html lang="km">
<head>
  <meta charset="UTF-8">
  <title>តារាងបង់ថ្លៃទំនិញរបស់ភ្ញៀវ</title>
  <link rel="stylesheet" href="style.css">
   <style>
   

    .falling {
      position: absolute;
      top: -50px;
      pointer-events: none;
      user-select: none;
      animation: fall linear forwards;
    }

    @keyframes fall {
      to {
        transform: translateY(100vh) rotate(360deg);
        opacity: 0;
      }
    }



  </style>
</head>
<body>
  <nav class="main-nav">
    <a href="../index.php">Home</a> 
    <a href="../about.php">About</a>
    <a href="./login.php">បញ្ចូល​ឈ្មោះទំនិញថ្មី</a>
    <a href="./result.php">តារាងបង់ថ្លៃទំនិញ</a>
  </nav>
  <div class="cute-bg"></div>
  <h2>តារាងបង់ថ្លៃទំនិញ ព័ត៌មានរបស់ភ្ញៀវ</h2>
  <div class="nav">
    <a href="index.php">បញ្ចូល​ឈ្មោះទំនិញថ្មី</a>
  </div>
  <?php if ($editData): ?>
  <form method="POST" action="result.php">
    <input type="hidden" name="edit_index" value="<?= $editData['id'] ?>">
    <label>ឈ្មោះ​ភ្ញៀវ</label>
    <input type="text" name="name" value="<?= htmlspecialchars($editData['name']) ?>" required>
    <label>ប្រភេទផ្កា</label>
    <input type="text" name="class" id="flowerType" value="<?= htmlspecialchars($editData['class']) ?>" required>
    <label>ចំនួនក្នុងការទិញ</label>
    <input type="text" name="academic_year" value="<?= htmlspecialchars($editData['academic_year']) ?>" required>
    <label>តម្លៃផ្កា ($)</label>
    <input type="text" name="flower_price" id="flowerPrice" value="<?= htmlspecialchars($editData['flower_price'] ?? '') ?>" readonly style="background:#f3e5f5; color:#ad1457; font-weight:bold;">
    <label>បង់ជា</label>
    <select name="pay_type" required>
      <option value="">ជ្រើស</option>
      <option value="QR" <?= $editData['pay_type']=='QR'?'selected':'' ?>>QR</option>
      <option value="ឲលុយក្រៅ" <?= $editData['pay_type']=='ឲលុយក្រៅ'?'selected':'' ?>>ឲលុយក្រៅ</option>
    </select>
    <label>ថ្ងៃដែលភ្ញៀវមកទិញ:</label>
    <input type="date" name="date" value="<?= htmlspecialchars($editData['date']) ?>" required min="2025-07-01">
    <button type="submit">កែប្រែ</button>
    <a href="result.php">បោះបង់</a>
  </form>
  <script>
    // Map flower type to price from about.php
    const flowerPrices = {
      'Endearing Pink Roses Bouquet': 27,
      'Mixed Roses Bunch': 22,
      'Lovely Pink Roses Arrangement': 20,
      'Mesmerising Orchids Bouquet': 20,
      'Sunflower Joy': 21,
      'Purple Lilies': 22,
      'White Tulips': 26
    };
    document.getElementById('flowerType').addEventListener('input', function() {
      const price = flowerPrices[this.value] || '';
      document.getElementById('flowerPrice').value = price;
    });


  </script>
  <?php endif; ?>
  <table>
    <thead>
      <tr>
        <th>ល.រ</th>
        <th>ឈ្មោះ​ភ្ញៀវ</th>
        <th>ប្រភេទផ្កា</th>
        <th>ចំនួនក្នុងការទិញ</th>
        <th>បង់ជា</th>
        <th>ថ្ងៃបង់របស់ភ្ញៀវ</th>
        <th>តម្លៃ($)</th>
        <th>សកម្មភាពរបស់ភ្ញៀវ</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($students)): ?>
        <tr><td colspan="10" class="empty">គ្មានទិន្នន័យ</td></tr>
      <?php else: ?>
        <?php foreach ($students as $i => $s): ?>
        <tr>
          <td data-label="ល.រ"><?= $i + 1 ?></td>
          <td data-label="ឈ្មោះ​ភ្ញៀវ"><?= htmlspecialchars($s['name'] ?? '', ENT_QUOTES) ?></td>
          <td data-label="ប្រភេទផ្កា"><?= htmlspecialchars($s['class'] ?? '', ENT_QUOTES) ?></td>
          <td data-label="ចំនួនក្នុងការទិញ"><?= htmlspecialchars($s['academic_year'] ?? '', ENT_QUOTES) ?></td>
          <td data-label="បង់ជា"><?= htmlspecialchars($s['pay_type'] ?? '', ENT_QUOTES) ?></td>
          <td data-label="ថ្ងៃបង់របស់ភ្ញៀវ"><?= htmlspecialchars($s['date'] ?? '', ENT_QUOTES) ?></td>
          <td data-label="តម្លៃ($)">
            <?php
              $flowerPrices = [
                'Endearing Pink Roses Bouquet' => 27,
                'Mixed Roses Bunch' => 22,
                'Lovely Pink Roses Arrangement' => 20,
                'Mesmerising Orchids Bouquet' => 20,
                'Sunflower Joy' => 21,
                'Purple Lilies' => 22,
                'White Tulips' => 26
              ];
              $flowerName = $s['class'] ?? '';
              $price = isset($flowerPrices[$flowerName]) ? $flowerPrices[$flowerName] : ($s['price'] ?? 0);
              echo '$' . number_format($price, 2);
            ?>
          </td>
          <td data-label="សកម្មភាពរបស់ភ្ញៀវ">
            <a href="result.php?edit=<?= $s['id'] ?>">កែប្រែ</a> |
            <a href="result.php?delete=<?= $s['id'] ?>" onclick="return confirm('លុបមែនឬ?');">លុប</a>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
  <footer style="text-align:center; margin-top:30px; padding:18px 0; background:#fffbe7; border-top:2px solid #ffe6a7;">
    <div style="display: flex; justify-content: center; align-items: center; gap: 18px; flex-wrap: wrap; margin-bottom: 8px;">
      <img src="../imag/1.jpg" alt="Footer Flower 1" style="height:60px; border-radius:50%; box-shadow:0 2px 8px rgba(231,84,128,0.10);">
      <img src="../imag/2.jpg" alt="Footer Flower 2" style="height:60px; border-radius:50%; box-shadow:0 2px 8px rgba(231,84,128,0.10);">
      <img src="../imag/3.jpg" alt="Footer Flower 3" style="height:60px; border-radius:50%; box-shadow:0 2px 8px rgba(231,84,128,0.10);">
      <img src="../imag/4.jpg" alt="Footer Flower 4" style="height:60px; border-radius:50%; box-shadow:0 2px 8px rgba(231,84,128,0.10);">
      <img src="../imag/5.jpg" alt="Footer Flower 5" style="height:60px; border-radius:50%; box-shadow:0 2px 8px rgba(231,84,128,0.10);">
    </div>
    <div style="color:#e75480; font-weight:bold; font-size:1.1rem;">Thank you for visiting our flower shop!</div>
    <div style="margin-top:10px;">
      <a href="index.php" style="color:#e75480; font-weight:bold; text-decoration:none; border:1px solid #ffe6a7; border-radius:6px; padding:6px 18px; background:#fff; transition:background 0.2s;">Order More</a>
    </div>
  </footer>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('headerCollapseBtn');
    const menu = document.getElementById('mainNavMenu');

    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      menu.classList.toggle('open');
    });

    document.addEventListener('click', function (e) {
      if (!btn.contains(e.target) && !menu.contains(e.target)) {
        menu.classList.remove('open');
      }
    });
  });

  const icons = ['🌸', '🎈', '🌼', '💐', '🌷', '🎉'];

  function createFallingIcon() {
    const el = document.createElement('div');
    el.className = 'falling';
    el.textContent = icons[Math.floor(Math.random() * icons.length)];

    // Style it randomly
    el.style.left = Math.random() * 100 + 'vw';
    el.style.fontSize = (24 + Math.random() * 20) + 'px';
    el.style.animationDuration = (3 + Math.random() * 5) + 's';

    document.body.appendChild(el);

    // Remove after animation
    setTimeout(() => {
      el.remove();
    }, 8000);
  }

  // Create a new icon every 250ms
  setInterval(createFallingIcon, 250);
</script>
</body>
</html>
