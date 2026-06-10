<?php
session_start();
require_once '../../../backend/middleware/student.php';
requireStudent();

require_once '../../../backend/models/User.php';
require_once '../../../backend/models/Course.php';
require_once '../../../backend/models/Progress.php';

$userId   = $_SESSION['user_id'];
$userObj  = new User();
$user     = $userObj->findById($userId);
$userXP   = $user['xp'];
$userLevel= $user['level'];

$courseObj   = new Course();
$progressObj = new Progress();
$courses     = $courseObj->getAll();

// Mark explored
foreach ($courses as &$c) {
  $c['is_explored'] = $progressObj->hasDoneCourse($userId, $c['id']);
}
unset($c);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Courses — Hogwarts Academy</title>
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
      <h2>📚 Courses</h2>
      <p>Explore magical subjects and earn XP for each course you complete.</p>
    </div>

    <!-- Search -->
    <div class="page-toolbar">
      <div class="search-wrap" style="max-width:320px;width:100%">
        <span class="search-icon">🔍</span>
        <input type="text" id="courseSearch" class="form-control"
          placeholder="Search courses...">
      </div>
      <div style="font-size:0.82rem;color:var(--text-muted)">
        <?= count($courses) ?> courses available
      </div>
    </div>

    <!-- Courses Grid -->
    <div class="grid-3" id="coursesGrid">
      <?php foreach ($courses as $course): ?>
        <div class="item-card <?= $course['is_explored'] ? 'explored' : '' ?>"
          onclick="openCourseModal(<?= htmlspecialchars(json_encode($course)) ?>)"
          data-name="<?= htmlspecialchars(strtolower($course['course_name'])) ?>">

          <?php if ($course['is_explored']): ?>
            <div style="position:absolute;top:12px;right:12px;color:var(--gold);font-size:0.75rem;font-weight:700">
              ✓ Explored
            </div>
          <?php endif; ?>

          <div class="item-card-name"><?= htmlspecialchars($course['course_name']) ?></div>
          <div class="item-card-sub">Prof. <?= htmlspecialchars($course['professor']) ?></div>

          <?php
            $diffMap = ['Beginner'=>'badge-beginner','Intermediate'=>'badge-intermediate','Advanced'=>'badge-advanced'];
            $badgeClass = $diffMap[$course['difficulty']] ?? '';
          ?>
          <span class="badge <?= $badgeClass ?>"><?= $course['difficulty'] ?></span>

          <div class="item-card-footer">
            <span class="xp-pill">+<?= $course['xp_reward'] ?> XP</span>
            <span style="font-size:0.78rem;color:var(--text-muted)">Tap to view →</span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <?php if (empty($courses)): ?>
      <div class="empty-state">
        <div class="empty-state-icon">📚</div>
        <div class="empty-state-text">No courses available yet.</div>
      </div>
    <?php endif; ?>
  </main>
</div>

<!-- Course Modal -->
<div class="modal-backdrop" id="courseModal">
  <div class="modal">
    <div class="modal-header">
      <div>
        <div id="modalCourseName" class="modal-title"></div>
        <div id="modalCourseProfessor" style="font-size:0.82rem;color:var(--text-muted);margin-top:4px"></div>
      </div>
      <button class="modal-close" onclick="closeModal('courseModal')">✕</button>
    </div>
    <div class="modal-body">
      <div id="modalAlertBox"></div>

      <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px">
        <span id="modalCourseDifficulty" class="badge"></span>
        <span id="modalCourseXP" class="xp-pill"></span>
      </div>

      <div style="margin-bottom:16px">
        <div style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--text-muted);margin-bottom:6px">Description</div>
        <p id="modalCourseDescription" style="font-size:0.925rem;color:var(--text-secondary);line-height:1.6"></p>
      </div>

      <div id="modalActionArea">
        <button id="markExploredBtn" class="btn btn-primary" style="width:100%"
          onclick="markCourseExplored()">
          ✓ Mark as Explored (+<span id="modalXPReward">200</span> XP)
        </button>
      </div>
    </div>
  </div>
</div>

<script src="../../assets/js/main.js"></script>
<script>
let currentCourse = null;

function openCourseModal(course) {
  currentCourse = course;
  clearAlert('modalAlertBox');

  document.getElementById('modalCourseName').textContent       = course.course_name;
  document.getElementById('modalCourseProfessor').textContent  = 'Professor ' + course.professor;
  document.getElementById('modalCourseDescription').textContent= course.description || 'No description available.';
  document.getElementById('modalXPReward').textContent         = course.xp_reward;
  document.getElementById('modalCourseXP').textContent         = '+' + course.xp_reward + ' XP';

  const diff = course.difficulty;
  const diffMap = { Beginner: 'badge-beginner', Intermediate: 'badge-intermediate', Advanced: 'badge-advanced' };
  const diffEl = document.getElementById('modalCourseDifficulty');
  diffEl.className = 'badge ' + (diffMap[diff] || '');
  diffEl.textContent = diff;

  const actionArea = document.getElementById('modalActionArea');
  if (course.is_explored) {
    actionArea.innerHTML = `
      <div class="alert alert-success" style="justify-content:center">
        ✓ You have already explored this course!
      </div>`;
  } else {
    actionArea.innerHTML = `
      <button id="markExploredBtn" class="btn btn-primary" style="width:100%"
        onclick="markCourseExplored()">
        ✓ Mark as Explored (+${course.xp_reward} XP)
      </button>`;
  }

  openModal('courseModal');
}

async function markCourseExplored() {
  if (!currentCourse) return;
  const btn = document.getElementById('markExploredBtn');
  if (btn) { btn.disabled = true; btn.textContent = 'Saving...'; }

  const form = new FormData();
  form.append('course_id', currentCourse.id);

  try {
    const res  = await fetch('../../../backend/actions/mark_course.php', { method:'POST', body: form });
    const data = await res.json();

    if (data.success) {
      showXPToast(currentCourse.xp_reward, data.new_level);

      // Update UI
      document.getElementById('modalActionArea').innerHTML = `
        <div class="alert alert-success" style="justify-content:center">
          ✓ Course explored! ${data.message}
        </div>`;

      // Mark card as explored
      const cards = document.querySelectorAll('.item-card');
      cards.forEach(card => {
        if (card.dataset.name === currentCourse.course_name.toLowerCase()) {
          card.classList.add('explored');
          if (!card.querySelector('.explored-label')) {
            const label = document.createElement('div');
            label.className = 'explored-label';
            label.style.cssText = 'position:absolute;top:12px;right:12px;color:var(--gold);font-size:0.75rem;font-weight:700';
            label.textContent = '✓ Explored';
            card.appendChild(label);
          }
        }
      });

      // Update sidebar XP
      updateSidebarXP(data.new_xp, data.new_level);
      currentCourse.is_explored = true;
    } else {
      showAlert('modalAlertBox', data.message || 'Something went wrong.', 'error');
      if (btn) { btn.disabled = false; btn.textContent = '✓ Mark as Explored'; }
    }
  } catch (err) {
    showAlert('modalAlertBox', 'Connection error.', 'error');
    if (btn) { btn.disabled = false; }
  }
}

function updateSidebarXP(newXP, newLevel) {
  const xpEl    = document.getElementById('sidebarXP');
  const levelEl = document.getElementById('sidebarLevel');
  const barEl   = document.getElementById('sidebarXPBar');
  if (xpEl)    xpEl.textContent    = newXP + ' XP';
  if (levelEl) levelEl.textContent = newLevel;
  if (barEl) {
    const progress = getLevelProgress(newXP);
    barEl.style.width = progress.percent + '%';
  }
}

// Search
document.getElementById('courseSearch').addEventListener('input', function() {
  const q = this.value.toLowerCase().trim();
  document.querySelectorAll('#coursesGrid .item-card').forEach(card => {
    card.style.display = (!q || card.dataset.name.includes(q)) ? '' : 'none';
  });
});
</script>
</body>
</html>