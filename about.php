<?php
require_once __DIR__ . '/config/cn.php'; // DB connection

$products = [];
try {
    $stmt = $conn->query("SELECT * FROM dbo.products ORDER BY id DESC");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $products[] = $row;
    }
} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Gift Shop</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      margin: 0;
      font-family: 'Fredoka', 'Comic Sans MS', cursive, sans-serif;
      background: #fffafc;
    }

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
      margin: 8px;
      position: absolute;
      top: 0;
      left: 0;
      z-index: 200;
      animation: chickWatch 1.2s infinite alternate cubic-bezier(.68,-0.55,.27,1.55);
    }

    @keyframes chickWatch {
      0%   { transform: scale(1) rotate(-6deg); }
      50%  { transform: scale(1.1) rotate(6deg); }
      100% { transform: scale(1) rotate(0); }
    }

    header {
      background: linear-gradient(90deg, #ffe4ec 0%, #e0f7fa 100%);
      padding-bottom: 0.5rem;
      border-radius: 0 0 32px 32px;
      box-shadow: 0 4px 18px #f8bbd0;
      position: relative;
      z-index: 2;
    }

    .main-nav {
      display: flex;
      justify-content: center;
      gap: 24px;
      padding: 1rem 0;
    }

    .main-nav a {
      text-decoration: none;
      color: #e91e63;
      font-weight: bold;
      font-size: 1.1rem;
    }

    .grid {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      padding: 2rem;
    }

    .card {
      width: 22%;
      background: #fff0f6;
      border-radius: 16px;
      box-shadow: 0 6px 14px rgba(231, 84, 128, 0.15);
      overflow: hidden;
      transition: transform 0.25s ease;
    }

    .card:hover {
      transform: scale(1.05);
    }

    .card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-bottom: 4px solid #ffe6f0;
    }

    .info {
      padding: 12px;
      text-align: center;
    }

    .info h4 {
      font-size: 1.3rem;
      color: #e91e63;
      margin: 8px 0;
    }

    .price {
      font-size: 1.1rem;
      color: #ff4081;
      font-weight: bold;
      display: block;
      margin: 10px 0 4px;
    }

    .icons span {
      font-size: 1.3rem;
      margin: 0 4px;
      color: #f06292;
    }

    footer {
      text-align: center;
      margin-top: 30px;
      padding: 18px 0;
      background: #fffbe7;
      border-top: 2px solid #ffe6a7;
    }

    footer img {
      height: 60px;
      border-radius: 50%;
      box-shadow: 0 2px 8px rgba(231,84,128,0.10);
    }

    /* CUTE MEDIA QUERIES */
    @media (max-width: 1024px) {
      .card { width: 45%; }
    }

    @media (max-width: 768px) {
      .main-nav {
        flex-direction: column;
        background: #fffbe7;
        border-radius: 0 0 24px 24px;
        box-shadow: 0 2px 18px #f8bbd0;
        display: none;
        padding-bottom: 1rem;
      }

      .main-nav.open {
        display: flex !important;
      }

      .header-collapse-btn {
        display: flex;
      }

      .card {
        width: 80%;
      }
    }

    @media (max-width: 480px) {
      .card { width: 95%; }
    }
       margin: 0;
      font-family: 'Comic Sans MS', cursive, sans-serif;
      background-color: #fff0f5;
      color: #333;
      overflow-x: hidden;
    }

     .cute-background {
      position: fixed;
      top: 0;
      left: 0;
      width: 110%;
      height: 110%;
      background: url('imag/13.jpg') no-repeat center center;
      background-size: cover;
      opacity: 0.1;
      z-index: -1;
      animation: moveBackground 30s linear infinite;
    }

    @keyframes moveBackground {
      0%   { transform: translate(0, 0); }
      50%  { transform: translate(-10px, -10px); }
      100% { transform: translate(0, 0); }
    }

    .gallery-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      padding: 30px;
      gap: 20px;
      position: relative;
      z-index: 1;
    }

    .flower-box {
      background-color: #fff;
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      overflow: hidden;
      text-align: center;
      width: 300px;
      opacity: 0;
      transform: translateY(50px);
      animation: fadeInUp 1s ease forwards, floatCute 3s ease-in-out infinite;
      animation-delay: var(--delay);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .flower-box:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }

    .flower-box img {
      width: 100%;
      height: auto;
      display: block;
      cursor: pointer;
    }

    .price-tag {
      background-color: #ffccf9;
      color: #a60083;
      padding: 10px;
      font-size: 18px;
      font-weight: bold;
      border-top: 1px solid #ffd6fa;
    }

    @keyframes fadeInUp {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes floatCute {
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-10px);
      }
    }

    @media screen and (max-width: 600px) {
      .flower-box {
        width: 100%;
        max-width: 90%;
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
    @keyframes fall {
  to {
    transform: translateY(100vh) rotate(360deg);
    opacity: 0;
  }
}

.falling-flower {
  animation-timing-function: linear;
}

  </style>
</head>
<body>

<header>
  <button class="header-collapse-btn" id="headerCollapseBtn" aria-label="Toggle nav">🐣</button>
  <nav class="main-nav" id="mainNavMenu">
    <a href="index.php">Home</a>
    <a href="about.php">About</a>
    <a href="./menu/login.php">Login</a>
    <a href="./menu/result.php">Result</a>
  </nav>
</header>
<div class="cute-background"></div>

  <div class="gallery-container">
    <div class="flower-box">
      <img src="imag/11.jpg" alt="Flower 1">
      <div class="price-tag">$20 cute</div>
    </div>
    <div class="flower-box">
      <img src="imag/2.jpg" alt="Flower 2">
      <div class="price-tag">$20 cute</div>
    </div>
    <div class="flower-box">
      <img src="imag/12.jpg" alt="Flower 3">
      <div class="price-tag">$20 cute</div>
    </div>
    <div class="flower-box">
      <img src="imag/4.jpg" alt="Flower 4">
      <div class="price-tag">$20 cute</div>
    </div>
    <div class="flower-box">
      <img src="imag/5.jpg" alt="Flower 5">
      <div class="price-tag">$20 cute</div>
    </div>
  </div>
<div class="grid">
  <?php foreach ($products as $p): ?>
    <?php 
      $title = isset($p['title']) ? htmlspecialchars($p['title']) : 'Untitled';
      $price = isset($p['price']) ? htmlspecialchars($p['price']) : 'N/A';
      $image = isset($p['image']) && !empty($p['image']) 
        ? 'uploads/' . basename($p['image']) 
        : 'https://via.placeholder.com/200x200.png?text=No+Image';
    ?>
    <div class="card">
      <img src="<?= $image ?>" alt="<?= $title ?>" onclick="window.location.href='../admin.php'">
      <div class="info">
        <h4><?= $title ?></h4>
        <div class="icons">
          <span>🔒</span><span>♡</span>
        </div>
        <span class="price">$<?= $price ?></span>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<footer>
  <div style="display: flex; justify-content: center; gap: 12px; flex-wrap: wrap;">
    <?php for ($i = 1; $i <= 5; $i++): ?>
      <img src="imag/<?= $i ?>.jpg" alt="Flower <?= $i ?>">
    <?php endfor; ?>
  </div>
  <div style="color:#e57399; font-weight:bold; font-size:1.2rem; margin-top:10px;">🌸 Thank you for visiting our flower shop! 🌸</div>
  <a href="about.php" style="display:inline-block; margin-top:10px; padding:8px 20px; background:#f8bbd0; color:#880e4f; border-radius:10px; text-decoration:none;">See All Flowers</a>
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
