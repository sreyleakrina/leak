<?php
require_once __DIR__ . '/config/cn.php';

$products = [];

try {
    $stmt = $conn->query("SELECT * FROM products ORDER BY id DESC");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $products[] = $row;
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Gift Flowers</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      margin: 0;
      font-family: 'Fredoka', sans-serif;
      background: #fffefc;
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
      margin: 8px 8px 0 8px;
      transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
      position: absolute;
      left: 0;
      top: 0;
      z-index: 200;
      animation: chickWatch 1.2s infinite alternate cubic-bezier(.68,-0.55,.27,1.55);
    }

    @keyframes chickWatch {
      0% { transform: scale(1) rotate(-8deg); }
      40% { transform: scale(1.08) rotate(8deg); }
      60% { transform: scale(1.12) rotate(-6deg); }
      100% { transform: scale(1) rotate(0deg); }
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

    .hero {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      min-height: 100vh;
      padding: 4% 6%;
      background: linear-gradient(120deg, #fff0f6, #e0f7fa);
    }

    .hero .text {
      flex: 1 1 50%;
      z-index: 2;
    }

    .hero .text h1 {
      font-size: 3.5rem;
      color: #e91e63;
      margin-bottom: 0.5rem;
    }

    .hero .text span {
      color: #f06292;
    }

    .hero .text p {
      font-size: 1.25rem;
      color: #ad1457;
      margin-bottom: 1.5rem;
    }

    .hero form {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    .hero form select,
    .hero form button {
      padding: 10px 18px;
      border: 2px dashed #e57399;
      border-radius: 12px;
      font-size: 1rem;
      background: #fffbe7;
      color: #ad1457;
      cursor: pointer;
    }

    .hero .image {
      flex: 1 1 45%;
      display: flex;
      justify-content: center;
      align-items: center;
      animation: floatCute 5s ease-in-out infinite;
    }

    .hero .image img {
      width: 100%;
      max-width: 500px;
      border-radius: 30px;
      box-shadow: 0 10px 30px rgba(248, 187, 208, 0.4);
    }

    @keyframes floatCute {
      0% { transform: translateY(0); }
      50% { transform: translateY(-15px); }
      100% { transform: translateY(0); }
    }

    .products {
      padding: 40px 20px;
      text-align: center;
    }

    .products h2 {
      color: #e91e63;
      font-size: 2rem;
      margin-bottom: 20px;
    }

    .grid {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 24px;
    }

    .product {
      background: #fff0f6;
      border-radius: 24px;
      box-shadow: 0 6px 14px rgba(231, 84, 128, 0.15);
      overflow: hidden;
      text-align: center;
      transition: transform 0.3s ease;
      width: 100%;
      max-width: 300px;
    }

    .product:hover {
      transform: translateY(-5px) scale(1.03);
    }

    .flower-img {
      width: 100%;
      height: 300px;
      object-fit: cover;
      border-bottom: 4px solid #ffd6ea;
      border-radius: 24px 24px 0 0;
    }

    .chick-move {
      animation: chickFloat 3s ease-in-out infinite;
      cursor: pointer;
    }

    @keyframes chickFloat {
      0%   { transform: translateY(0px) rotate(-2deg); }
      50%  { transform: translateY(-10px) rotate(2deg); }
      100% { transform: translateY(0px) rotate(-2deg); }
    }

    .product h3 {
      font-size: 1.4rem;
      color: #e91e63;
      margin: 12px 0 4px;
    }

    .product p {
      font-size: 1.2rem;
      color: #ff4081;
      font-weight: bold;
      margin-bottom: 12px;
    }

    footer {
      text-align:center;
      margin-top:40px;
      padding:22px 0;
      background:#fffbe7;
      border-top:2px solid #ffe6a7;
      border-radius: 0 0 32px 32px;
      box-shadow: 0 -2px 18px #f8bbd0;
    }

    footer img {
      height:60px;
      border-radius:50%;
      box-shadow:0 2px 8px #f8bbd0;
    }

    footer a {
      color:#fff;
      font-weight:bold;
      text-decoration:none;
      border:1px solid #e57399;
      border-radius:8px;
      padding:10px 28px;
      background:#e57399;
      transition:background 0.2s;
      font-size:1.1rem;
      box-shadow:0 2px 8px #f8bbd0;
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
  </style>
</head>
<body>

<header style="background: linear-gradient(90deg, #ffe4ec 0%, #e0f7fa 100%); box-shadow: 0 4px 18px #f8bbd0; border-radius: 0 0 32px 32px; margin-bottom: 2.2rem; padding-bottom: 0.5rem; position: relative; z-index: 2;">
  <div class="topbar"><span>Call: 0888283179</span></div>
  <button class="header-collapse-btn" id="headerCollapseBtn" aria-label="Toggle navigation">🐣</button>
  <nav class="main-nav" id="mainNavMenu">
    <a href="index.php">Home</a>
    <a href="about.php">About</a>
    <a href="./menu/login.php">Login</a>
    <a href="./menu/result.php">Result</a>
    <form action="./menu/result.php" method="get" style="display:flex;align-items:center;gap:4px;background:#fffbe7;padding:4px 10px;border-radius:12px;box-shadow:0 2px 8px #f8bbd0;">
      <input type="text" name="q" placeholder="Search orders..." style="padding:6px 12px; border-radius:6px; border:1.5px dashed #e57399; font-size:1rem; background:#fff0f6; color:#ad1457;">
      <button type="submit" class="header-btn" style="padding:8px 18px; min-width:90px; border-radius:18px; border:2.5px dashed #e57399; background:linear-gradient(90deg,#f8bbd0 0%,#e57399 100%); font-size:1.08rem;">🔍 Search</button>
    </form>
  </nav>
</header>

<section class="hero">
  <div class="text">
    <h1><span>like you</span><br>mean it.</h1>
    <p>If you’re going to use a passage of Lorem Ipsum, you need to be sure...</p>
    <form method="POST" action="about.php">
      <select name="occasion">
        <option value="">Occasions</option>
        <option value="Birthday">Birthday</option>
      </select>
      <select name="recipient">
        <option value="">Recipient</option>
        <option value="Her">Her</option>
      </select>
      <button type="submit">Find</button>
    </form>
  </div>
  <div class="image">
    <img src="imag/13.jpg" alt="imag">
  </div>
<div class="cute-background"></div>

  <div class="gallery-container">
    <div class="flower-box">
      <img src="imag/11.jpg" alt="Flower 1">
      <div class="price-tag">Lily $20 cute</div>
    </div>
    <div class="flower-box">
      <img src="imag/4.jpg" alt="Flower 2">
      <div class="price-tag">Ros $20 cute</div>
    </div>
    <div class="flower-box">
      <img src="imag/5.jpg" alt="Flower 3">
      <div class="price-tag">Ros $20 cute</div>
    </div>
    <div class="flower-box">
      <img src="imag/6.jpg" alt="Flower 4">
      <div class="price-tag">Rosread $20 cute</div>
    </div>
    <div class="flower-box">
      <img src="imag/11.jpg" alt="Flower 5">
      <div class="price-tag">Bela $20 cute</div>
    </div>
  </div>
</section>
<section class="products">
<div style="margin-bottom: 30px;">
  <h2>🎁 Gifts for Every Occasion</h2>
</div>
 </div>
  <div class="grid">
    <?php foreach ($products as $product): ?>
      <div class="product">
        <a href="about/lomin.php?product=<?= urlencode($product['id']) ?>" class="flower-link" title="Manage flower">
          <img class="flower-img chick-move" src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        </a>
        <h3><?= htmlspecialchars($product['name']) ?></h3>
        <p>$<?= number_format($product['price'], 2) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<footer>
  <div style="display: flex; justify-content: center; align-items: center; gap: 18px; flex-wrap: wrap; margin-bottom: 12px;">
    <img src="imag/1.jpg" alt="Flower 1">
    <img src="imag/2.jpg" alt="Flower 2">
    <img src="imag/3.jpg" alt="Flower 3">
    <img src="imag/4.jpg" alt="Flower 4">
    <img src="imag/5.jpg" alt="Flower 5">
  </div>
  <div style="color:#e57399; font-weight:bold; font-size:1.2rem; margin-bottom:8px;">🌸 Thank you for visiting our flower shop! 🌸</div>
  <div style="margin-top:10px;">
    <a href="about/lomin.php">See All Flowers admin</a>
  </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const collapseBtn = document.getElementById('headerCollapseBtn');
  const navMenu = document.getElementById('mainNavMenu');

  if (collapseBtn && navMenu) {
    collapseBtn.addEventListener('click', e => {
      e.stopPropagation();
      navMenu.classList.toggle('open');
    });

    document.addEventListener('click', e => {
      if (!collapseBtn.contains(e.target) && !navMenu.contains(e.target)) {
        navMenu.classList.remove('open');
      }
    });
  }
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
