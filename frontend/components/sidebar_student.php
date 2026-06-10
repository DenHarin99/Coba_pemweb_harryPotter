<?php
// Requires: $user = current user data array from session/DB
$username  = $_SESSION['username'] ?? 'Student';
$house     = $_SESSION['house']    ?? 'Gryffindor';
$houseClass= strtolower($house);
$initial   = strtoupper(substr($username, 0, 1));

// XP data — passed in from page or fetched from DB
$xp        = $userXP    ?? 0;
$level     = $userLevel ?? 'Beginner Wizard';

$progress  = getLevelProgressData($xp);

function getLevelProgressData($xp) {
  if ($xp < 500) {
    return ['percent' => ($xp / 500) * 100, 'next' => 'Advanced Wizard', 'nextXP' => 500];
  } elseif ($xp < 1500) {
    return ['percent' => (($xp - 500) / 1000) * 100, 'next' => 'Expert Wizard', 'nextXP' => 1500];
  } else {
    return ['percent' => 100, 'next' => null, 'nextXP' => null];
  }
}

$currentPage = basename($_SERVER['PHP_SELF']);
function isActive($page) {
  global $currentPage;
  return $currentPage === $page ? 'active' : '';
}
?>

<aside class="sidebar house-<?= $houseClass ?>" id="sidebar">

  <!-- Logo -->
  <div class="sidebar-logo">
    <span class="logo-icon">⚡</span>
    <div class="logo-name">Hogwarts<br>Academy Portal</div>
  </div>

  <!-- User -->
  <div class="sidebar-user">
    <div class="user-avatar"><?= $initial ?></div>
    <div class="user-info">
      <div class="user-name"><?= htmlspecialchars($username) ?></div>
      <div class="user-house"><?= $house ?></div>
    </div>
  </div>

  <!-- XP Bar -->
  <div class="sidebar-xp">
    <div class="xp-label">Wizard Progress</div>
    <div class="xp-level" id="sidebarLevel"><?= htmlspecialchars($level) ?></div>
    <div class="xp-bar-wrap">
      <div class="xp-bar-fill" id="sidebarXPBar"
        style="width: <?= min($progress['percent'], 100) ?>%"></div>
    </div>
    <div class="xp-numbers">
      <span id="sidebarXP"><?= $xp ?> XP</span>
      <?php if ($progress['next']): ?>
        <span>Next: <?= $progress['nextXP'] ?> XP</span>
      <?php else: ?>
        <span style="color:var(--gold)">Max Level!</span>
      <?php endif; ?>
    </div>
  </div>

  <!-- Nav -->
  <nav class="sidebar-nav">
    <div class="nav-label">Main</div>

    <a href="dashboard.php" class="nav-link <?= isActive('dashboard.php') ?>">
      <span class="nav-icon">🏠</span> Dashboard
    </a>
    <a href="courses.php" class="nav-link <?= isActive('courses.php') ?>">
      <span class="nav-icon">📚</span> Courses
    </a>
    <a href="spells.php" class="nav-link <?= isActive('spells.php') ?>">
      <span class="nav-icon">✨</span> Spell Book
    </a>

    <div class="nav-label">Account</div>
    <a href="profile.php" class="nav-link <?= isActive('profile.php') ?>">
      <span class="nav-icon">👤</span> Profile
    </a>
  </nav>

  <div class="sidebar-footer">
    <a href="../../backend/actions/logout.php" class="nav-link" style="color:var(--text-muted)">
      <span class="nav-icon">🚪</span> Logout
    </a>
  </div>

</aside>