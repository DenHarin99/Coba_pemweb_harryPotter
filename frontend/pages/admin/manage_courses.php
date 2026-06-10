<?php
session_start();
require_once '../../../backend/middleware/admin.php';
requireAdmin();

require_once '../../../backend/models/Course.php';
$courseObj = new Course();
$courses   = $courseObj->getAll();

$msg  = $_GET['msg']  ?? '';
$type = $_GET['type'] ?? 'success';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Courses — Hogwarts Academy</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="app-shell">

  <?php include '../../components/sidebar_admin.php'; ?>

  <main class="main-content">
    <div style="margin-bottom:16px">
      <button class="mobile-toggle" id="sidebarToggle">☰</button>
    </div>

    <div class="page-header">
      <h2>📚 Manage Courses</h2>
      <p>Add, edit, and remove courses from the academy curriculum.</p>
    </div>

    <?php if ($msg): ?>
      <div class="alert alert-<?= $type ?>"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <div class="page-toolbar">
      <div class="search-wrap" style="max-width:280px;width:100%">
        <span class="search-icon">🔍</span>
        <input type="text" id="courseSearch" class="form-control" placeholder="Search courses...">
      </div>
      <a href="course_form.php" class="btn btn-primary">➕ Add Course</a>
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Course</th>
            <th>Professor</th>
            <th>Difficulty</th>
            <th>XP Reward</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="coursesTableBody">
          <?php if (empty($courses)): ?>
            <tr>
              <td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted)">
                No courses yet.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($courses as $i => $c): ?>
              <?php
                $diffMap = ['Beginner'=>'badge-beginner','Intermediate'=>'badge-intermediate','Advanced'=>'badge-advanced'];
                $dc = $diffMap[$c['difficulty']] ?? '';
              ?>
              <tr data-name="<?= htmlspecialchars(strtolower($c['course_name'])) ?>">
                <td style="color:var(--text-muted)"><?= $i + 1 ?></td>
                <td>
                  <span style="font-weight:600"><?= htmlspecialchars($c['course_name']) ?></span>
                </td>
                <td style="color:var(--text-secondary)">Prof. <?= htmlspecialchars($c['professor']) ?></td>
                <td><span class="badge <?= $dc ?>"><?= $c['difficulty'] ?></span></td>
                <td><span class="xp-pill">+<?= $c['xp_reward'] ?> XP</span></td>
                <td>
                  <div style="display:flex;gap:6px">
                    <a href="course_form.php?id=<?= $c['id'] ?>" class="btn btn-outline btn-sm">✏️ Edit</a>
                    <button class="btn btn-danger btn-sm"
                      onclick="deleteCourse(<?= $c['id'] ?>, '<?= htmlspecialchars(addslashes($c['course_name'])) ?>')">
                      🗑️ Delete
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<script src="../../assets/js/main.js"></script>
<script>
document.getElementById('courseSearch').addEventListener('input', function() {
  const q = this.value.toLowerCase().trim();
  document.querySelectorAll('#coursesTableBody tr[data-name]').forEach(row => {
    row.style.display = (!q || row.dataset.name.includes(q)) ? '' : 'none';
  });
});

async function deleteCourse(id, name) {
  if (!confirm(`Delete course "${name}"?`)) return;
  const form = new FormData();
  form.append('id', id);
  try {
    const res  = await fetch('../../../backend/actions/delete_course.php', { method:'POST', body: form });
    const data = await res.json();
    if (data.success) {
      window.location.href = 'manage_courses.php?msg=' + encodeURIComponent(data.message) + '&type=success';
    } else {
      alert(data.message || 'Failed.');
    }
  } catch { alert('Connection error.'); }
}
</script>
</body>
</html>