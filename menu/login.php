<!DOCTYPE html>
<html lang="km">
<head>
  <meta charset="UTF-8">
  <title>បញ្ចូលព័ត៌មានដែលអ្នកចង់ទិញ</title>
  <link rel="stylesheet" href="style.css">
  <style>
  .header-collapse-btn {
    display: none;
    background: linear-gradient(90deg, #f8bbd0 0%, #ffe6a7 100%);
    border: 2.5px dashed #e57399;
    border-radius: 50%;
    width: 48px;
    height: 48px;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #e57399;
    box-shadow: 0 2px 8px #f8bbd0;
    cursor: pointer;
    margin: 8px 8px 0 8px;
    transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
    position: absolute;
    left: 0;
    top: 0;
    z-index: 200;
    animation: chickWatch 1.2s infinite alternate cubic-bezier(.68,-0.55,.27,1.55);
  }

  @media (max-width: 900px) {
    .header-collapse-btn {
      display: flex;
    }
    .main-nav {
      display: none;
      flex-direction: column;
      background: #fffbe7;
      border-radius: 0 0 24px 24px;
      box-shadow: 0 2px 18px #f8bbd0;
      position: absolute;
      top: 100%;
      left: 0;
      width: 100%;
      padding: 1rem 0 0.5rem 0;
      z-index: 150;
      margin-left: 0;
    }
    .main-nav.open {
      display: flex !important;
    }
  }

  @keyframes chickWatch {
    0% {
      transform: scale(1) rotate(-8deg);
      filter: drop-shadow(0 2px 8px #f8bbd0);
    }
    40% {
      transform: scale(1.08) rotate(8deg);
      filter: drop-shadow(0 6px 16px #e57399);
    }
    60% {
      transform: scale(1.12) rotate(-6deg);
      filter: drop-shadow(0 2px 8px #f8bbd0);
    }
    100% {
      transform: scale(1) rotate(0deg);
      filter: drop-shadow(0 2px 8px #f8bbd0);
    }
  }

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
  <header style="background: linear-gradient(90deg, #ffe4ec 0%, #e0f7fa 100%); box-shadow: 0 4px 18px #f8bbd0; border-radius: 0 0 32px 32px; margin-bottom: 2.2rem; padding-bottom: 0.5rem; position: relative; z-index: 2;">
<button class="header-collapse-btn" id="headerCollapseBtn" aria-label="Toggle navigation">🐣</button>
<nav class="main-nav" id="mainNavMenu">
  <a href="../index.php">Home</a>
  <a href="../about.php">About</a>
  <a href="./login.php">Login</a>
  <a href="./result.php">Result</a>
</nav>
  </header>
  <div class="cute-bg"></div>
  <form method="POST" action="result.php">
    <h2>បញ្ចូលព័ត៌មានរបស់ទំនិញ</h2>
    <label>ឈ្មោះ​ភ្ញៀវ</label>
    <input type="text" name="name" placeholder="ឈ្មោះ​ភ្ញៀវ" required>
    <label>ប្រភេទផ្កា</label>
    <input type="text" name="class" placeholder="a.b.c" required>
    <label>ចំនួនក្នុងការទិញ</label>
    <input type="text" name="academic_year" placeholder="1.2.3." required>    
    <label>បង់ជា</label>
    <select name="pay_type" required>
      <option value="">ជ្រើសរើស</option>
      <option value="លុយ៛">លុយ៛</option>
      <option value="លុយ$">លុយ$</option>
    </select>
    <label>ថ្ងៃទិញរបស់ភ្ញៀវ:</label>
    <input type="date" name="date" required min="2025-07-01">
    <label>តម្លៃផ្កា ($)</label>
    <input type="text" name="flower_price" id="flowerPrice" placeholder="តម្លៃផ្កា" style="background:#f3e5f5; color:#ad1457; font-weight:bold;">
    <button type="submit">បញ្ជូន</button>
  </form>
<?php
// ✅ MySQL Connection (Fix: use correct driver and hostname)
define('DB_HOST', '127.0.0.1'); // Use 'localhost' or '127.0.0.1' if local XAMPP
define('DB_USER', 'root'); // Default user for XAMPP
define('DB_PASS', ''); // No password by default for XAMPP
define('DB_NAME', 'flower_shop');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die('❌ Connection failed: ' . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fees = [
        1 => ['total' => 380, 'half' => 195],
        2 => ['total' => 400, 'half' => 205],
        3 => ['total' => 450, 'half' => 230],
        4 => ['total' => 450, 'half' => 230],
        5 => ['total' => 230, 'half' => 230],
    ];
    $rankDiscount = [1 => 0.50, 2 => 0.30, 3 => 0.20, 4 => 0.10, 5 => 0.10];

    $lvl = (int)($_POST['level'] ?? 1);
    $ptype = ($_POST['pay_type'] === 'full' && $lvl !== 5) ? 'total' : 'half';
    $base = $fees[$lvl][$ptype] ?? 0;
    $after10 = ($_POST['pay_type'] === 'full') ? max(0, $base - 10) : $base;
    $rawRank = trim($_POST['rank'] ?? '');
    $rate = (ctype_digit($rawRank) && isset($rankDiscount[(int)$rawRank])) ? $rankDiscount[(int)$rawRank] : 0;
    $final = round($after10 * (1 - $rate), 2);

    $name = $conn->real_escape_string(trim($_POST['name'] ?? ''));
    $class = $conn->real_escape_string(trim($_POST['class'] ?? ''));
    $academic_year = $conn->real_escape_string(trim($_POST['academic_year'] ?? ''));
    $pay_type = $conn->real_escape_string($_POST['pay_type'] ?? '');
    $date = $conn->real_escape_string($_POST['date'] ?? '');

    $flowerPrices = [
        'Endearing Pink Roses Bouquet' => 27,
        'Mixed Roses Bunch' => 22,
        'Lovely Pink Roses Arrangement' => 20,
        'Mesmerising Orchids Bouquet' => 20,
        'Sunflower Joy' => 21,
        'Purple Lilies' => 22,
        'White Tulips' => 26
    ];
    $flower_price = isset($flowerPrices[$class]) ? $flowerPrices[$class] : floatval($_POST['flower_price'] ?? 0);

    $stmt = $conn->prepare("INSERT INTO students (name, class, academic_year, level, pay_type, rank, date, price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisssd", $name, $class, $academic_year, $lvl, $pay_type, $rawRank, $date, $flower_price);
    $stmt->execute();
    $stmt->close();

    header('Location: result.php');
    exit;
}
?>

<!-- The rest of your HTML remains unchanged -->

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
      <a href="../index.php
" style="color:#e75480; font-weight:bold; text-decoration:none; border:1px solid #ffe6a7; border-radius:6px; padding:6px 18px; background:#fff; transition:background 0.2s;">Back home</a>
    </div>
  </footer>
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
      document.querySelector('input[name="class"]').addEventListener('input', function() {
        const price = flowerPrices[this.value] || '';
        const priceInput = document.getElementById('flowerPrice');
        if (price && !priceInput.value) priceInput.value = price;
      });
    
    const toggleBtn = document.getElementById('headerCollapseBtn');
    const navMenu = document.getElementById('mainNavMenu');

    toggleBtn.addEventListener('click', () => {
      navMenu.classList.toggle('open');
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
