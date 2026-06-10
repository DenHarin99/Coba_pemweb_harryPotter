<?php
$username    = $_SESSION['username'] ?? 'Admin';
$house       = $_SESSION['house']    ?? 'Gryffindor';
$houseClass  = strtolower($house);
$initial     = strtoupper(substr($username, 0, 1));
$currentPage = basename($_SERVER['PHP_SELF']);

function isActiveAdmin($page) {
  global $currentPage;
  return $currentPage === $page ? 'active' : '';
}
?>

<aside class="sidebar house-<?= $houseClass ?>" id="sidebar">

  <div class="sidebar-logo">
    <span class="logo-icon">⚡</span>
    <div class="logo-name">Hogwarts<br>Academy Portal</div>
  </div>

  <div class="sidebar-user">
    <div class="user-avatar"><?= $initial ?></div>
    <div class="user-info">
      <div class="user-name"><?= htmlspecialchars($username) ?></div>
      <div class="user-house">
        <span class="badge badge-admin" style="font-size:0.65rem;padding:2px 7px">Professor</span>
      </div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-label">Overview</div>
    <a href="dashboard.php" class="nav-link <?= isActiveAdmin('dashboard.php') ?>">
      <span class="nav-icon">📊</span> Dashboard
    </a>

    <div class="nav-label">Management</div>
    <a href="manage_students.php" class="nav-link <?= isActiveAdmin('manage_students.php') ?>">
      <span class="nav-icon">👥</span> Students
    </a>
    <a href="manage_courses.php" class="nav-link <?= isActiveAdmin('manage_courses.php') ?>">
      <span class="nav-icon">📚</span> Courses
    </a>
    <a href="manage_spells.php" class="nav-link <?= isActiveAdmin('manage_spells.php') ?>">
      <span class="nav-icon">✨</span> Spells
    </a>
  </nav>

  <div class="sidebar-footer">
    <a href="../../backend/actions/logout.php" class="nav-link" style="color:var(--text-muted)">
      <span class="nav-icon">🚪</span> Logout
    </a>
  </div>

</aside>