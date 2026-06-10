<?php
session_start();
require_once '../../../backend/middleware/admin.php';
requireAdmin();

require_once '../../../backend/models/User.php';

$isEdit   = isset($_GET['id']) && is_numeric($_GET['id']);
$student  = null;

if ($isEdit) {
  $userObj = new User();
  $student = $userObj->findById((int)$_GET['id']);
  if (!$student) {
    header('Location: manage_students.php?msg=Student+not+found&type=error');
    exit();
  }
}

$pageTitle = $isEdit ? 'Edit Student' : 'Add Student';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?> — Hogwarts Academy</title>
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
      <div style="display:flex;align-items:center;gap:12px">
        <a href="manage_students.php" class="btn btn-ghost btn-sm">← Back</a>
        <h2><?= $pageTitle ?></h2>
      </div>
      <p><?= $isEdit ? 'Update student information.' : 'Enroll a new student into the academy.' ?></p>
    </div>

    <div style="max-width:600px">
      <div class="card">
        <div id="alertBox"></div>

        <div class="grid-2">
          <div class="form-group">
            <label class="form-label" for="username">Username</label>
            <input type="text" id="username" class="form-control" placeholder="harrypotter"
              value="<?= $isEdit ? htmlspecialchars($student['username']) : '' ?>">
          </div>
          <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input type="email" id="email" class="form-control" placeholder="harry@hogwarts.edu"
              value="<?= $isEdit ? htmlspecialchars($student['email']) : '' ?>">
          </div>
        </div>

        <div class="grid-2">
          <div class="form-group">
            <label class="form-label" for="password">
              Password <?= $isEdit ? '<span style="font-weight:400;color:var(--text-muted)">(leave blank to keep)</span>' : '' ?>
            </label>
            <input type="password" id="password" class="form-control"
              placeholder="<?= $isEdit ? 'Leave blank to keep current' : 'Min. 6 characters' ?>">
          </div>
          <div class="form-group">
            <label class="form-label" for="house">House</label>
            <select id="house" class="form-control">
              <option value="">— Select House —</option>
              <?php foreach (['Gryffindor','Slytherin','Ravenclaw','Hufflepuff'] as $h): ?>
                <option value="<?= $h ?>"
                  <?= ($isEdit && $student['house'] === $h) ? 'selected' : '' ?>>
                  <?= $h ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="role">Role</label>
          <select id="role" class="form-control" style="max-width:200px">
            <option value="student" <?= ($isEdit && $student['role'] === 'student') ? 'selected' : '' ?>>Student</option>
            <option value="admin"   <?= ($isEdit && $student['role'] === 'admin')   ? 'selected' : '' ?>>Admin / Professor</option>
          </select>
        </div>

        <div style="display:flex;gap:12px;margin-top:8px">
          <button id="submitBtn" class="btn btn-primary"
            onclick="handleSubmit(<?= $isEdit ? $student['id'] : 'null' ?>)">
            <?= $isEdit ? '💾 Save Changes' : '✓ Enroll Student' ?>
          </button>
          <a href="manage_students.php" class="btn btn-ghost">Cancel</a>
        </div>
      </div>
    </div>
  </main>
</div>

<script src="../../assets/js/main.js"></script>
<script>
async function handleSubmit(editId) {
  const username = document.getElementById('username').value.trim();
  const email    = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value;
  const house    = document.getElementById('house').value;
  const role     = document.getElementById('role').value;

  clearAlert('alertBox');

  if (!username || !email || !house) {
    showAlert('alertBox', 'Username, email, and house are required.', 'error');
    return;
  }

  if (!editId && !password) {
    showAlert('alertBox', 'Password is required for new students.', 'error');
    return;
  }

  if (password && password.length < 6) {
    showAlert('alertBox', 'Password must be at least 6 characters.', 'error');
    return;
  }

  setLoading('submitBtn', true);

  const form = new FormData();
  form.append('username', username);
  form.append('email', email);
  form.append('house', house);
  form.append('role', role);
  if (password) form.append('password', password);
  if (editId)   form.append('id', editId);

  const endpoint = editId
    ? '../../../backend/actions/edit_student.php'
    : '../../../backend/actions/add_student.php';

  try {
    const res  = await fetch(endpoint, { method:'POST', body: form });
    const data = await res.json();

    if (data.success) {
      window.location.href = 'manage_students.php?msg=' + encodeURIComponent(data.message) + '&type=success';
    } else {
      showAlert('alertBox', data.message || 'Something went wrong.', 'error');
      setLoading('submitBtn', false);
    }
  } catch (err) {
    showAlert('alertBox', 'Connection error.', 'error');
    setLoading('submitBtn', false);
  }
}
</script>
</body>
</html>