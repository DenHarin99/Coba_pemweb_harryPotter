<?php
session_start();
require_once '../../../backend/middleware/admin.php';
requireAdmin();

require_once '../../../backend/models/User.php';
$userObj  = new User();
$students = $userObj->getAllStudents();

$msg  = $_GET['msg']  ?? '';
$type = $_GET['type'] ?? 'success';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Students — Hogwarts Academy</title>
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
      <h2>👥 Manage Students</h2>
      <p>View, add, edit, and remove students from the academy.</p>
    </div>

    <?php if ($msg): ?>
      <div class="alert alert-<?= $type ?>">
        <?= htmlspecialchars($msg) ?>
      </div>
    <?php endif; ?>

    <div class="page-toolbar">
      <div class="search-wrap" style="max-width:280px;width:100%">
        <span class="search-icon">🔍</span>
        <input type="text" id="studentSearch" class="form-control" placeholder="Search students...">
      </div>
      <a href="student_form.php" class="btn btn-primary">➕ Add Student</a>
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Username</th>
            <th>House</th>
            <th>XP</th>
            <th>Level</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="studentsTableBody">
          <?php if (empty($students)): ?>
            <tr>
              <td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted)">
                No students enrolled yet.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($students as $i => $student): ?>
              <tr data-name="<?= htmlspecialchars(strtolower($student['username'])) ?>">
                <td style="color:var(--text-muted)"><?= $i + 1 ?></td>
                <td>
                  <div style="display:flex;align-items:center;gap:8px">
                    <div style="width:28px;height:28px;border-radius:50%;background:var(--bg-elevated);display:flex;align-items:center;justify-content:center;font-family:'Cinzel',serif;font-size:0.7rem;font-weight:700;color:var(--gold)">
                      <?= strtoupper(substr($student['username'], 0, 1)) ?>
                    </div>
                    <span><?= htmlspecialchars($student['username']) ?></span>
                  </div>
                </td>
                <td>
                  <span class="badge <?= 'badge-house-' . strtolower($student['house']) ?>">
                    <?= $student['house'] ?>
                  </span>
                </td>
                <td>
                  <span style="font-family:'Cinzel',serif;color:var(--gold);font-weight:600">
                    <?= number_format($student['xp']) ?> XP
                  </span>
                </td>
                <td style="font-size:0.82rem;color:var(--text-secondary)"><?= $student['level'] ?></td>
                <td>
                  <div style="display:flex;gap:6px">
                    <a href="student_form.php?id=<?= $student['id'] ?>" class="btn btn-outline btn-sm">
                      ✏️ Edit
                    </a>
                    <button class="btn btn-danger btn-sm"
                      onclick="deleteStudent(<?= $student['id'] ?>, '<?= htmlspecialchars(addslashes($student['username'])) ?>')">
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

    <div style="font-size:0.8rem;color:var(--text-muted);margin-top:12px;text-align:right">
      Total: <?= count($students) ?> students
    </div>
  </main>
</div>

<script src="../../assets/js/main.js"></script>
<script>
// Search
document.getElementById('studentSearch').addEventListener('input', function() {
  const q = this.value.toLowerCase().trim();
  document.querySelectorAll('#studentsTableBody tr[data-name]').forEach(row => {
    row.style.display = (!q || row.dataset.name.includes(q)) ? '' : 'none';
  });
});

async function deleteStudent(id, username) {
  if (!confirm(`Delete student "${username}"? This cannot be undone.`)) return;

  const form = new FormData();
  form.append('id', id);

  try {
    const res  = await fetch('../../../backend/actions/delete_student.php', { method:'POST', body: form });
    const data = await res.json();
    if (data.success) {
      window.location.href = 'manage_students.php?msg=' + encodeURIComponent(data.message) + '&type=success';
    } else {
      alert(data.message || 'Failed to delete student.');
    }
  } catch {
    alert('Connection error.');
  }
}
</script>
</body>
</html>