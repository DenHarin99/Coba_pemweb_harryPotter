<?php
session_start();
require_once '../../../backend/middleware/admin.php';
requireAdmin();

require_once '../../../backend/models/User.php';
require_once '../../../backend/models/Course.php';
require_once '../../../backend/models/Spell.php';

$userObj   = new User();
$courseObj = new Course();
$spellObj  = new Spell();

$totalStudents = count($userObj->getAllStudents());
$totalCourses  = count($courseObj->getAll());
$totalSpells   = count($spellObj->getAll());
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard — Hogwarts Academy</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="app-shell">

  <?php include '../../components/sidebar_admin.php'; ?>

  <main class="main-content">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px">
      <button class="mobile-toggle" id="sidebarToggle">☰</button>
      <div style="font-family:'Cinzel',serif;font-size:0.85rem;color:var(--text-muted)">
        Welcome, <span style="color:var(--gold)">Professor <?= htmlspecialchars($_SESSION['username']) ?></span>
      </div>
    </div>

    <div class="page-header">
      <h2>📊 Dashboard</h2>
      <p>Overview of Hogwarts Academy Portal.</p>
    </div>

    <!-- Stats -->
    <div class="grid-3" style="margin-bottom:28px">
      <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-value"><?= $totalStudents ?></div>
        <div class="stat-label">Total Students</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">📚</div>
        <div class="stat-value"><?= $totalCourses ?></div>
        <div class="stat-label">Total Courses</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">✨</div>
        <div class="stat-value"><?= $totalSpells ?></div>
        <div class="stat-label">Total Spells</div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="card" style="margin-bottom:24px">
      <div class="card-header">
        <div class="card-title">⚡ Quick Actions</div>
      </div>
      <div style="display:flex;gap:12px;flex-wrap:wrap">
        <a href="student_form.php" class="btn btn-primary">
          ➕ Add Student
        </a>
        <a href="course_form.php" class="btn btn-outline">
          ➕ Add Course
        </a>
        <a href="spell_form.php" class="btn btn-outline">
          ➕ Add Spell
        </a>
      </div>
    </div>

    <!-- Management Cards -->
    <div class="grid-3">
      <a href="manage_students.php" style="text-decoration:none">
        <div class="card" style="cursor:pointer;transition:all 0.2s;text-align:center;padding:28px">
          <div style="font-size:2.5rem;margin-bottom:12px">👥</div>
          <div style="font-family:'Cinzel',serif;font-size:0.95rem;color:var(--text-primary);margin-bottom:6px">Manage Students</div>
          <div style="font-size:0.8rem;color:var(--text-muted)"><?= $totalStudents ?> students enrolled</div>
        </div>
      </a>
      <a href="manage_courses.php" style="text-decoration:none">
        <div class="card" style="cursor:pointer;transition:all 0.2s;text-align:center;padding:28px">
          <div style="font-size:2.5rem;margin-bottom:12px">📚</div>
          <div style="font-family:'Cinzel',serif;font-size:0.95rem;color:var(--text-primary);margin-bottom:6px">Manage Courses</div>
          <div style="font-size:0.8rem;color:var(--text-muted)"><?= $totalCourses ?> courses available</div>
        </div>
      </a>
      <a href="manage_spells.php" style="text-decoration:none">
        <div class="card" style="cursor:pointer;transition:all 0.2s;text-align:center;padding:28px">
          <div style="font-size:2.5rem;margin-bottom:12px">✨</div>
          <div style="font-family:'Cinzel',serif;font-size:0.95rem;color:var(--text-primary);margin-bottom:6px">Manage Spells</div>
          <div style="font-size:0.8rem;color:var(--text-muted)"><?= $totalSpells ?> spells in library</div>
        </div>
      </a>
    </div>

  </main>
</div>

<script src="../../assets/js/main.js"></script>
</body>
</html>