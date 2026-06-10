<?php
session_start();
if (isset($_SESSION['user_id'])) {
  $redirect = $_SESSION['role'] === 'admin' ? '../admin/dashboard.php' : '../student/dashboard.php';
  header("Location: $redirect");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register — Hogwarts Academy</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body class="auth-body">

<div class="auth-container" style="max-width:520px">
  <div class="auth-logo">
    <span class="auth-logo-icon">⚡</span>
    <div class="auth-logo-text">Hogwarts Academy</div>
    <div class="auth-logo-sub">Student Portal</div>
  </div>

  <div class="auth-card">
    <h2 class="auth-card-title">Begin Your Journey</h2>
    <p class="auth-card-sub">Create your account and choose your House</p>

    <div id="alertBox"></div>

    <div class="grid-2">
      <div class="form-group">
        <label class="form-label" for="username">Username</label>
        <input type="text" id="username" class="form-control" placeholder="e.g. harrypotter">
      </div>
      <div class="form-group">
        <label class="form-label" for="email">Email</label>
        <input type="email" id="email" class="form-control" placeholder="harry@hogwarts.edu">
      </div>
    </div>

    <div class="grid-2">
      <div class="form-group">
        <label class="form-label" for="password">Password</label>
        <input type="password" id="password" class="form-control" placeholder="Min. 6 characters">
      </div>
      <div class="form-group">
        <label class="form-label" for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" class="form-control" placeholder="Repeat password">
      </div>
    </div>

    <div class="form-group">
      <label class="form-label">Choose Your House</label>
      <div class="house-select-grid">

        <div class="house-option">
          <input type="radio" name="house" id="houseGryffindor" value="Gryffindor">
          <label class="house-option-label" for="houseGryffindor">
            <span class="house-option-icon">🦁</span>
            <span class="house-option-name">Gryffindor</span>
          </label>
        </div>

        <div class="house-option">
          <input type="radio" name="house" id="houseSlytherin" value="Slytherin">
          <label class="house-option-label" for="houseSlytherin">
            <span class="house-option-icon">🐍</span>
            <span class="house-option-name">Slytherin</span>
          </label>
        </div>

        <div class="house-option">
          <input type="radio" name="house" id="houseRavenclaw" value="Ravenclaw">
          <label class="house-option-label" for="houseRavenclaw">
            <span class="house-option-icon">🦅</span>
            <span class="house-option-name">Ravenclaw</span>
          </label>
        </div>

        <div class="house-option">
          <input type="radio" name="house" id="houseHufflepuff" value="Hufflepuff">
          <label class="house-option-label" for="houseHufflepuff">
            <span class="house-option-icon">🦡</span>
            <span class="house-option-name">Hufflepuff</span>
          </label>
        </div>

      </div>
    </div>

    <button id="registerBtn" class="btn btn-primary"
      style="width:100%;margin-top:8px" onclick="handleRegister()">
      ⚡ Join Hogwarts
    </button>

    <div class="auth-footer-text">
      Already enrolled?
      <a href="login.php">Sign in</a>
    </div>
    <div style="text-align:center;margin-top:12px">
      <a href="landing.php" style="font-size:0.8rem;color:var(--text-muted)">← Back to Home</a>
    </div>
  </div>
</div>

<script src="../assets/js/main.js"></script>
<script>
async function handleRegister() {
  const username  = document.getElementById('username').value.trim();
  const email     = document.getElementById('email').value.trim();
  const password  = document.getElementById('password').value;
  const confirm   = document.getElementById('confirm_password').value;
  const houseEl   = document.querySelector('input[name="house"]:checked');

  clearAlert('alertBox');

  if (!username || !email || !password || !confirm) {
    showAlert('alertBox', 'Please fill in all fields.', 'error'); return;
  }
  if (password.length < 6) {
    showAlert('alertBox', 'Password must be at least 6 characters.', 'error'); return;
  }
  if (password !== confirm) {
    showAlert('alertBox', 'Passwords do not match.', 'error'); return;
  }
  if (!houseEl) {
    showAlert('alertBox', 'Please choose your House.', 'error'); return;
  }

  setLoading('registerBtn', true);

  try {
    const form = new FormData();
    form.append('username', username);
    form.append('email', email);
    form.append('password', password);
    form.append('confirm_password', confirm);
    form.append('house', houseEl.value);

    const res  = await fetch('../../backend/actions/register.php', { method:'POST', body: form });
    const data = await res.json();

    if (data.success) {
      showAlert('alertBox', '✓ ' + data.message, 'success');
      setTimeout(() => { window.location.href = 'login.php'; }, 1500);
    } else {
      showAlert('alertBox', data.message || 'Registration failed.', 'error');
      setLoading('registerBtn', false);
    }
  } catch (err) {
    showAlert('alertBox', 'Connection error. Please try again.', 'error');
    setLoading('registerBtn', false);
  }
}
</script>
</body>
</html>