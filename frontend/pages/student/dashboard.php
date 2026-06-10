<?php
session_start();
require_once '../../../backend/middleware/student.php';
requireStudent();

require_once '../../../backend/models/User.php';
require_once '../../../backend/models/Progress.php';

$userId  = $_SESSION['user_id'];
$userObj = new User();
$user    = $userObj->findById($userId);

$progressObj = new Progress();
$stats       = $progressObj->getStudentStats($userId);

$xp        = $user['xp'];
$level     = $user['level'];
$userXP    = $xp;
$userLevel = $level;

// Calculate next level info
if ($xp < 500) {
  $nextLevel = 'Advanced Wizard';
  $nextXP    = 500;
  $percent   = ($xp / 500) * 100;
} elseif ($xp < 1500) {
  $nextLevel = 'Expert Wizard';
  $nextXP    = 1500;
  $percent   = (($xp - 500) / 1000) * 100;
} else {
  $nextLevel = null;
  $nextXP    = null;
  $percent   = 100;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard — Hogwarts Academy</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="app-shell">

  <?php include '../../components/sidebar_student.php'; ?>

  <main class="main-content">
    <!-- Mobile toggle -->
    <div style="margin-bottom:16px;display:flex;align-items:center;justify-content:space-between">
      <button class="mobile-toggle" id="sidebarToggle">☰</button>
      <div style="font-family:'Cinzel',serif;font-size:0.85rem;color:var(--text-muted)">
        Welcome back, <span style="color:var(--gold)"><?= htmlspecialchars($user['username']) ?></span>
      </div>
    </div>

    <!-- Wizard Status Card -->
    <div class="wizard-card" style="margin-bottom:24px">
      <div class="wizard-level">⚡ Wizard Status</div>
      <div class="wizard-name"><?= htmlspecialchars($user['username']) ?></div>
      <div class="wizard-house">
        <?= $level ?> &nbsp;·&nbsp;
        <span class="badge <?= 'badge-house-' . strtolower($user['house']) ?>"><?= $user['house'] ?></span>
      </div>

      <div class="wizard-xp-bar-wrap">
        <div class="wizard-xp-bar-fill" style="width:<?= min($percent, 100) ?>%"></div>
      </div>
      <div class="wizard-xp-info">
        <span><?= number_format($xp) ?> XP</span>
        <?php if ($nextXP): ?>
          <span><?= number_format($nextXP) ?> XP</span>
        <?php else: ?>
          <span style="color:var(--gold)">Max Level Reached!</span>
        <?php endif; ?>
      </div>
      <?php if ($nextLevel): ?>
        <div class="wizard-next-level">
          Next: <span><?= $nextLevel ?></span>
          (<?= number_format($nextXP - $xp) ?> XP to go)
        </div>
      <?php endif; ?>
    </div>

    <!-- Stats Grid -->
    <div class="grid-3" style="margin-bottom:24px">
      <div class="stat-card">
        <div class="stat-icon">📚</div>
        <div class="stat-value"><?= $stats['courses_explored'] ?></div>
        <div class="stat-label">Courses Explored</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">✨</div>
        <div class="stat-value"><?= $stats['spells_learned'] ?></div>
        <div class="stat-label">Spells Learned</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">⚡</div>
        <div class="stat-value"><?= number_format($stats['total_xp_earned']) ?></div>
        <div class="stat-label">Total XP Earned</div>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid-2">

      <!-- Recent Courses -->
      <div class="card">
        <div class="card-header">
          <div class="card-title">📚 Recent Courses</div>
          <a href="courses.php" class="btn btn-ghost btn-sm">View All</a>
        </div>
        <?php if (empty($stats['recent_courses'])): ?>
          <div class="empty-state">
            <div class="empty-state-icon">📚</div>
            <div class="empty-state-text">No courses explored yet.<br>
              <a href="courses.php">Start exploring →</a>
            </div>
          </div>
        <?php else: ?>
          <ul style="list-style:none;display:flex;flex-direction:column;gap:10px">
            <?php foreach ($stats['recent_courses'] as $c): ?>
              <li style="display:flex;align-items:center;gap:10px;padding:10px;background:var(--bg-surface);border-radius:var(--radius-sm)">
                <span style="font-size:1.2rem">📖</span>
                <span style="font-size:0.875rem;color:var(--text-primary)"><?= htmlspecialchars($c['course_name']) ?></span>
                <span class="xp-pill" style="margin-left:auto">+200 XP</span>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>

      <!-- Recent Spells -->
      <div class="card">
        <div class="card-header">
          <div class="card-title">✨ Recent Spells</div>
          <a href="spells.php" class="btn btn-ghost btn-sm">View All</a>
        </div>
        <?php if (empty($stats['recent_spells'])): ?>
          <div class="empty-state">
            <div class="empty-state-icon">✨</div>
            <div class="empty-state-text">No spells learned yet.<br>
              <a href="spells.php">Open Spell Book →</a>
            </div>
          </div>
        <?php else: ?>
          <ul style="list-style:none;display:flex;flex-direction:column;gap:10px">
            <?php foreach ($stats['recent_spells'] as $s): ?>
              <li style="display:flex;align-items:center;gap:10px;padding:10px;background:var(--bg-surface);border-radius:var(--radius-sm)">
                <span style="font-size:1.2rem">⚗️</span>
                <span style="font-size:0.875rem;color:var(--text-primary)"><?= htmlspecialchars($s['spell_name']) ?></span>
                <span class="xp-pill" style="margin-left:auto">+50 XP</span>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>

    </div>
  </main>
</div>

<script src="../../assets/js/main.js"></script>
</body>
</html>