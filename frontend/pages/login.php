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
  <title>Login — Hogwarts Academy</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body class="auth-body">

<div class="auth-container">
  <div class="auth-logo">
    <span class="auth-logo-icon">⚡</span>
    <div class="auth-logo-text">Hogwarts Academy</div>
    <div class="auth-logo-sub">Student Portal</div>
  </div>

  <div class="auth-card">
    <h2 class="auth-card-title">Welcome Back</h2>
    <p class="auth-card-sub">Sign in to continue your magical education</p>

    <div id="alertBox"></div>

    <div class="form-group">
      <label class="form-label" for="identifier">Username or Email</label>
      <input type="text" id="identifier" class="form-control"
        placeholder="Enter username or email" autocomplete="username">
    </div>

    <div class="form-group">
      <label class="form-label" for="password">Password</label>
      <input type="password" id="password" class="form-control"
        placeholder="Enter your password" autocomplete="current-password">
    </div>

    <button id="loginBtn" class="btn btn-primary" style="width:100%;margin-top:8px"
      onclick="handleLogin()">
      ⚡ Enter the Portal
    </button>

    <div class="auth-footer-text">
      New student?
      <a href="register.php">Create an account</a>
    </div>

    <div style="text-align:center;margin-top:12px">
      <a href="landing.php" style="font-size:0.8rem;color:var(--text-muted)">
        ← Back to Home
      </a>
    </div>
  </div>
</div>

<script src="../assets/js/main.js"></script>
<script>
async function handleLogin() {
  const identifier = document.getElementById('identifier').value.trim();
  const password   = document.getElementById('password').value;

  clearAlert('alertBox');

  if (!identifier || !password) {
    showAlert('alertBox', 'Please fill in all fields.', 'error');
    return;
  }

  setLoading('loginBtn', true);

  try {
    const form = new FormData();
    form.append('identifier', identifier);
    form.append('password', password);

    const res  = await fetch('../../backend/actions/login.php', { method:'POST', body: form });
    const data = await res.json();

    if (data.success) {
      showAlert('alertBox', '✓ Login successful! Redirecting...', 'success');
      setTimeout(() => { window.location.href = data.redirect; }, 800);
    } else {
      showAlert('alertBox', data.message || 'Login failed.', 'error');
      setLoading('loginBtn', false);
    }
  } catch (err) {
    showAlert('alertBox', 'Connection error. Please try again.', 'error');
    setLoading('loginBtn', false);
  }
}

// Allow Enter key
document.addEventListener('keydown', (e) => {
  if (e.key === 'Enter') handleLogin();
});
</script>
</body>
</html>