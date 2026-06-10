<?php
// Redirect if already logged in
session_start();
if (isset($_SESSION['user_id'])) {
  $redirect = $_SESSION['role'] === 'admin'
    ? '../admin/dashboard.php'
    : '../student/dashboard.php';
  header("Location: $redirect");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hogwarts Academy Portal</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/landing.css">
</head>
<body>

<!-- ======== NAVBAR ======== -->
<nav class="landing-nav" id="landingNav">
  <a href="#" class="nav-logo">
    <span class="nav-logo-icon">⚡</span>
    <div class="nav-logo-text">
      Hogwarts Academy
      <span>Portal</span>
    </div>
  </a>
  <div class="nav-actions">
    <a href="login.php" class="btn btn-ghost btn-sm">Login</a>
    <a href="register.php" class="btn btn-primary btn-sm">Get Started</a>
  </div>
</nav>

<!-- ======== HERO ======== -->
<section class="hero">
  <div style="position:relative;z-index:1;width:100%">
    <div class="hero-eyebrow">✦ Welcome to Hogwarts Academy ✦</div>
    <h1>Master the Art<br><em>of Magic</em></h1>
    <p class="hero-desc">
      Enroll, choose your House, explore ancient courses, and learn powerful spells.
      Your journey to becoming an Expert Wizard begins now.
    </p>
    <div class="hero-actions">
      <a href="register.php" class="btn btn-primary btn-lg">
        ⚡ Begin Your Journey
      </a>
      <a href="#about" class="btn btn-outline btn-lg">
        Learn More
      </a>
    </div>
  </div>
  <div class="hero-scroll">
    <span>Scroll</span>
    <span>↓</span>
  </div>
</section>

<!-- ======== ABOUT ======== -->
<section class="section" id="about">
  <div class="container">
    <div style="text-align:center">
      <div class="section-eyebrow">✦ About The Portal ✦</div>
      <h2 class="section-title">Your Complete Academy Experience</h2>
      <p class="section-desc">
        An enchanted portal that tracks your magical progress as you explore courses and learn spells.
      </p>
    </div>
    <div class="about-grid">
      <div class="about-item">
        <span class="about-item-icon">📚</span>
        <h3>Explore Courses</h3>
        <p>Dive into Potions, Charms, Transfiguration, and more. Each course earns you XP on your journey.</p>
      </div>
      <div class="about-item">
        <span class="about-item-icon">✨</span>
        <h3>Learn Spells</h3>
        <p>Build your Spell Book with dozens of spells. From Lumos to Expecto Patronum — master them all.</p>
      </div>
      <div class="about-item">
        <span class="about-item-icon">⚡</span>
        <h3>Level Up</h3>
        <p>Earn XP through learning. Rise from Beginner to Advanced to Expert Wizard as you grow.</p>
      </div>
    </div>
  </div>
</section>

<!-- ======== GAMIFICATION ======== -->
<section class="section" style="background:var(--bg-surface);padding:80px 0;">
  <div class="container">
    <div style="text-align:center;margin-bottom:48px">
      <div class="section-eyebrow">✦ Gamification System ✦</div>
      <h2 class="section-title">Three Levels of Mastery</h2>
    </div>
    <div class="grid-3" style="max-width:800px;margin:0 auto">
      <div class="card" style="text-align:center;padding:28px">
        <div style="font-size:2.5rem;margin-bottom:12px">🪄</div>
        <div style="font-family:'Cinzel',serif;font-size:1rem;color:var(--text-primary);margin-bottom:6px">Beginner Wizard</div>
        <div style="font-size:0.82rem;color:var(--gold);font-weight:600;margin-bottom:8px">0 – 499 XP</div>
        <p style="font-size:0.82rem">Start your magical education. Every spell and course brings you closer to mastery.</p>
      </div>
      <div class="card" style="text-align:center;padding:28px;border-color:var(--gold-dim)">
        <div style="font-size:2.5rem;margin-bottom:12px">⚗️</div>
        <div style="font-family:'Cinzel',serif;font-size:1rem;color:var(--text-primary);margin-bottom:6px">Advanced Wizard</div>
        <div style="font-size:0.82rem;color:var(--gold);font-weight:600;margin-bottom:8px">500 – 1499 XP</div>
        <p style="font-size:0.82rem">Your skills are sharpening. Professors take notice of your dedication.</p>
      </div>
      <div class="card" style="text-align:center;padding:28px">
        <div style="font-size:2.5rem;margin-bottom:12px">🏆</div>
        <div style="font-family:'Cinzel',serif;font-size:1rem;color:var(--text-primary);margin-bottom:6px">Expert Wizard</div>
        <div style="font-size:0.82rem;color:var(--gold);font-weight:600;margin-bottom:8px">1500+ XP</div>
        <p style="font-size:0.82rem">The pinnacle of magical learning. You stand among Hogwarts' finest.</p>
      </div>
    </div>
  </div>
</section>

<!-- ======== HOUSES ======== -->
<section class="houses-section" id="houses">
  <div class="container">
    <div style="text-align:center">
      <div class="section-eyebrow">✦ The Four Houses ✦</div>
      <h2 class="section-title">Choose Your Path</h2>
      <p class="section-desc">Your House defines your identity at Hogwarts. Choose wisely during registration.</p>
    </div>
    <div class="houses-grid">
      <div class="house-card house-card-gryffindor">
        <span class="house-crest">🦁</span>
        <h3>Gryffindor</h3>
        <p>Where dwell the brave at heart. Their daring, nerve, and chivalry set Gryffindors apart.</p>
        <span class="house-trait">Bravery • Courage</span>
      </div>
      <div class="house-card house-card-slytherin">
        <span class="house-crest">🐍</span>
        <h3>Slytherin</h3>
        <p>Those cunning folk use any means to achieve their ends. Ambition and resourcefulness reign.</p>
        <span class="house-trait">Ambition • Cunning</span>
      </div>
      <div class="house-card house-card-ravenclaw">
        <span class="house-crest">🦅</span>
        <h3>Ravenclaw</h3>
        <p>Wit beyond measure is man's greatest treasure. Learning is the path to power.</p>
        <span class="house-trait">Wisdom • Wit</span>
      </div>
      <div class="house-card house-card-hufflepuff">
        <span class="house-crest">🦡</span>
        <h3>Hufflepuff</h3>
        <p>Patient, hardworking, and true. Loyalty and dedication are Hufflepuff's hallmarks.</p>
        <span class="house-trait">Loyalty • Patience</span>
      </div>
    </div>
  </div>
</section>

<!-- ======== CTA ======== -->
<section class="section" style="text-align:center">
  <div class="container-sm">
    <div style="font-size:3rem;margin-bottom:20px">⚡</div>
    <h2 style="margin-bottom:16px;color:var(--text-primary)">Ready to Begin?</h2>
    <p style="margin-bottom:32px;font-size:1.1rem">
      Join hundreds of young witches and wizards already learning at Hogwarts Academy Portal.
    </p>
    <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap">
      <a href="register.php" class="btn btn-primary btn-lg">⚡ Register Now</a>
      <a href="login.php" class="btn btn-outline btn-lg">Sign In</a>
    </div>
  </div>
</section>

<!-- ======== FOOTER ======== -->
<footer class="landing-footer">
  <p style="margin-bottom:6px">
    <span style="font-family:'Cinzel',serif;color:var(--gold)">⚡ Hogwarts Academy Portal</span>
  </p>
  <p>Crafted with magic · Pemrograman Web Project</p>
</footer>

<script src="../assets/js/main.js"></script>
</body>
</html>