<?php
session_start();
require_once '../../../backend/middleware/student.php';
requireStudent();

require_once '../../../backend/models/User.php';
require_once '../../../backend/models/Progress.php';

$userId      = $_SESSION['user_id'];
$userObj     = new User();
$user        = $userObj->findById($userId);
$progressObj = new Progress();
$stats       = $progressObj->getStudentStats($userId);

$userXP    = $user['xp'];
$userLevel = $user['level'];
$initial   = strtoupper(substr($user['username'], 0, 1));
$house     = $user['house'];
$houseClass= strtolower($house);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile — Hogwarts Academy</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="app-shell">

  <?php include '../../components/sidebar_student.php'; ?>

  <main class="main-content">
    <div style="margin-bottom:16px">
      <button class="mobile-toggle" id="sidebarToggle">☰</button>
    </div>

    <div class="page-header">
      <h2>👤 Profile</h2>
      <p>Your magical identity and progress summary.</p>
    </div>

    <div class="grid-2" style="align-items:start">

      <!-- Profile Card -->
      <div class="profile-card house-<?= $houseClass ?>">
        <div class="profile-avatar"><?= $initial ?></div>
        <div class="profile-username"><?= htmlspecialchars($user['username']) ?></div>
        <div class="profile-email"><?= htmlspecialchars($user['email']) ?></div>

        <div style="display:flex;gap:8px;justify-content:center;flex-wrap:wrap;margin-bottom:24px">
          <span class="badge <?= 'badge-house-' . $houseClass ?>"><?= $house ?></span>
          <span class="badge badge-student">Student</span>
        </div>

        <div style="text-align:left;background:var(--bg-surface);border-radius:var(--radius-md);padding:20px">
          <div style="font-family:'Cinzel',serif;font-size:0.75rem;color:var(--gold);text-transform:uppercase;letter-spacing:0.12em;margin-bottom:12px">
            Wizard Status
          </div>

          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
            <span style="font-size:0.82rem;color:var(--text-muted)">Level</span>
            <span style="font-family:'Cinzel',serif;font-size:0.85rem;color:var(--text-primary)"><?= htmlspecialchars($user['level']) ?></span>
          </div>
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
            <span style="font-size:0.82rem;color:var(--text-muted)">Total XP</span>
            <span style="font-family:'Cinzel',serif;font-size:1rem;color:var(--gold);font-weight:700"><?= number_format($user['xp']) ?> XP</span>
          </div>
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
            <span style="font-size:0.82rem;color:var(--text-muted)">Courses Explored</span>
            <span style="font-weight:600;color:var(--text-primary)"><?= $stats['courses_explored'] ?></span>
          </div>
          <div style="display:flex;justify-content:space-between;align-items:center">
            <span style="font-size:0.82rem;color:var(--text-muted)">Spells Learned</span>
            <span style="font-weight:600;color:var(--text-primary)"><?= $stats['spells_learned'] ?></span>
          </div>
        </div>

        <div style="margin-top:24px">
          <a href="../../backend/actions/logout.php" class="btn btn-danger" style="width:100%">
            🚪 Logout
          </a>
        </div>
      </div>

      <!-- Activity Summary -->
      <div style="display:flex;flex-direction:column;gap:16px">

        <div class="card">
          <div class="card-title" style="margin-bottom:16px">⚡ XP Progress</div>
          <?php
            if ($user['xp'] < 500) {
              $nextLevel = 'Advanced Wizard'; $nextXP = 500;
              $pct = ($user['xp'] / 500) * 100;
            } elseif ($user['xp'] < 1500) {
              $nextLevel = 'Expert Wizard'; $nextXP = 1500;
              $pct = (($user['xp'] - 500) / 1000) * 100;
            } else {
              $nextLevel = null; $nextXP = null; $pct = 100;
            }
          ?>
          <div style="background:var(--bg-void);border-radius:99px;height:10px;overflow:hidden;margin-bottom:8px">
            <div style="height:100%;width:<?= min($pct, 100) ?>%;background:linear-gradient(90deg,var(--gold-dim),var(--gold));border-radius:99px;box-shadow:0 0 10px rgba(201,168,76,0.4)"></div>
          </div>
          <div style="display:flex;justify-content:space-between;font-size:0.78rem;color:var(--text-muted)">
            <span><?= number_format($user['xp']) ?> XP</span>
            <?php if ($nextXP): ?>
              <span>→ <?= $nextLevel ?> (<?= number_format($nextXP) ?> XP)</span>
            <?php else: ?>
              <span style="color:var(--gold)">Expert Wizard — Max Level</span>
            <?php endif; ?>
          </div>
        </div>

        <div class="card">
          <div class="card-title" style="margin-bottom:16px">📊 Statistics</div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <div style="text-align:center;padding:16px;background:var(--bg-surface);border-radius:var(--radius-sm)">
              <div style="font-family:'Cinzel',serif;font-size:1.6rem;color:var(--gold);font-weight:700"><?= $stats['courses_explored'] ?></div>
              <div style="font-size:0.75rem;color:var(--text-muted);margin-top:4px">Courses Explored</div>
            </div>
            <div style="text-align:center;padding:16px;background:var(--bg-surface);border-radius:var(--radius-sm)">
              <div style="font-family:'Cinzel',serif;font-size:1.6rem;color:var(--gold);font-weight:700"><?= $stats['spells_learned'] ?></div>
              <div style="font-size:0.75rem;color:var(--text-muted);margin-top:4px">Spells Learned</div>
            </div>
            <div style="text-align:center;padding:16px;background:var(--bg-surface);border-radius:var(--radius-sm)">
              <div style="font-family:'Cinzel',serif;font-size:1.6rem;color:var(--gold);font-weight:700"><?= number_format($stats['total_xp_earned']) ?></div>
              <div style="font-size:0.75rem;color:var(--text-muted);margin-top:4px">Total XP Earned</div>
            </div>
            <div style="text-align:center;padding:16px;background:var(--bg-surface);border-radius:var(--radius-sm)">
              <div style="font-family:'Cinzel',serif;font-size:1.4rem;color:var(--gold);font-weight:700;font-size:0.85rem"><?= htmlspecialchars($user['level']) ?></div>
              <div style="font-size:0.75rem;color:var(--text-muted);margin-top:4px">Current Level</div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-title" style="margin-bottom:4px">🏠 House</div>
          <?php
            $houseCrests = ['Gryffindor'=>'🦁','Slytherin'=>'🐍','Ravenclaw'=>'🦅','Hufflepuff'=>'🦡'];
            $crest = $houseCrests[$house] ?? '⚡';
          ?>
          <div style="display:flex;align-items:center;gap:16px;padding:16px;background:var(--bg-surface);border-radius:var(--radius-md);margin-top:12px">
            <span style="font-size:2.5rem"><?= $crest ?></span>
            <div>
              <div style="font-family:'Cinzel',serif;font-size:1rem;color:var(--text-primary);font-weight:700"><?= $house ?></div>
              <span class="badge <?= 'badge-house-' . $houseClass ?>"><?= $house ?></span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </main>
</div>

<script src="../../assets/js/main.js"></script>
</body>
</html>