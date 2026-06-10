/* ============================================
   HOGWARTS ACADEMY PORTAL — MAIN JS
   ============================================ */

// ============ SIDEBAR TOGGLE (MOBILE) ============
function initSidebar() {
  const toggle = document.getElementById('sidebarToggle');
  const sidebar = document.getElementById('sidebar');
  if (!toggle || !sidebar) return;

  toggle.addEventListener('click', () => {
    sidebar.classList.toggle('mobile-open');
  });

  // Close when clicking outside
  document.addEventListener('click', (e) => {
    if (sidebar.classList.contains('mobile-open') &&
        !sidebar.contains(e.target) &&
        e.target !== toggle) {
      sidebar.classList.remove('mobile-open');
    }
  });
}

// ============ STICKY NAV ============
function initStickyNav() {
  const nav = document.querySelector('.landing-nav');
  if (!nav) return;
  window.addEventListener('scroll', () => {
    nav.classList.toggle('scrolled', window.scrollY > 40);
  });
}

// ============ XP TOAST ============
function showXPToast(xpAmount, newLevel) {
  let toast = document.getElementById('xpToast');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'xpToast';
    toast.className = 'xp-toast';
    toast.innerHTML = `
      <div class="xp-toast-icon">⚡</div>
      <div>
        <div class="xp-toast-title">XP Earned!</div>
        <div class="xp-toast-value" id="xpToastValue">+${xpAmount} XP</div>
        <div id="xpToastLevel" style="font-size:0.75rem;color:var(--text-muted);margin-top:2px;"></div>
      </div>
    `;
    document.body.appendChild(toast);
  }

  document.getElementById('xpToastValue').textContent = `+${xpAmount} XP`;
  const levelEl = document.getElementById('xpToastLevel');
  if (newLevel && levelEl) {
    levelEl.textContent = newLevel;
    levelEl.style.color = 'var(--gold)';
  } else if (levelEl) {
    levelEl.textContent = '';
  }

  toast.classList.add('show');
  setTimeout(() => toast.classList.remove('show'), 3500);
}

// ============ MODAL ============
function openModal(id) {
  const modal = document.getElementById(id);
  if (modal) {
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
}

function closeModal(id) {
  const modal = document.getElementById(id);
  if (modal) {
    modal.classList.remove('open');
    document.body.style.overflow = '';
  }
}

// Close modal when clicking backdrop
document.addEventListener('click', (e) => {
  if (e.target.classList.contains('modal-backdrop')) {
    e.target.classList.remove('open');
    document.body.style.overflow = '';
  }
});

// ESC key to close modal
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    document.querySelectorAll('.modal-backdrop.open').forEach(m => {
      m.classList.remove('open');
      document.body.style.overflow = '';
    });
  }
});

// ============ ALERT HELPER ============
function showAlert(containerId, message, type = 'error') {
  const container = document.getElementById(containerId);
  if (!container) return;
  const icons = { error: '⚠', success: '✓', info: 'ℹ' };
  container.innerHTML = `
    <div class="alert alert-${type}">
      <span>${icons[type] || ''}</span> ${message}
    </div>
  `;
  container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function clearAlert(containerId) {
  const container = document.getElementById(containerId);
  if (container) container.innerHTML = '';
}

// ============ SEARCH/FILTER ============
function initSearch(inputId, targetSelector, nameSelector) {
  const input = document.getElementById(inputId);
  if (!input) return;

  input.addEventListener('input', () => {
    const q = input.value.toLowerCase().trim();
    document.querySelectorAll(targetSelector).forEach(el => {
      const name = el.querySelector(nameSelector)?.textContent?.toLowerCase() || '';
      el.style.display = (!q || name.includes(q)) ? '' : 'none';
    });
  });
}

// ============ CONFIRM DELETE ============
function confirmDelete(message = 'Are you sure you want to delete this?') {
  return window.confirm(message);
}

// ============ FORM HELPERS ============
function setLoading(btnId, loading) {
  const btn = document.getElementById(btnId);
  if (!btn) return;
  if (loading) {
    btn.disabled = true;
    btn._origText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner" style="width:16px;height:16px;border-width:2px"></span>';
  } else {
    btn.disabled = false;
    btn.innerHTML = btn._origText || 'Submit';
  }
}

// ============ XP BAR ANIMATION ============
function animateXPBar(fillId, percent) {
  const el = document.getElementById(fillId);
  if (!el) return;
  el.style.width = '0%';
  requestAnimationFrame(() => {
    setTimeout(() => { el.style.width = percent + '%'; }, 50);
  });
}

// ============ LEVEL PROGRESS ============
function getLevelProgress(xp) {
  if (xp < 500) {
    return {
      current: 'Beginner Wizard',
      next: 'Advanced Wizard',
      percent: Math.min((xp / 500) * 100, 100),
      nextXP: 500
    };
  } else if (xp < 1500) {
    return {
      current: 'Advanced Wizard',
      next: 'Expert Wizard',
      percent: Math.min(((xp - 500) / 1000) * 100, 100),
      nextXP: 1500
    };
  } else {
    return {
      current: 'Expert Wizard',
      next: null,
      percent: 100,
      nextXP: null
    };
  }
}

// ============ HOUSE BADGE CLASS ============
function getHouseBadgeClass(house) {
  if (!house) return '';
  return 'badge-house-' + house.toLowerCase();
}

function getHouseBodyClass(house) {
  if (!house) return '';
  return 'house-' + house.toLowerCase();
}

// ============ DIFFICULTY BADGE ============
function getDifficultyBadge(difficulty) {
  const map = {
    'Beginner':     'badge-beginner',
    'Intermediate': 'badge-intermediate',
    'Advanced':     'badge-advanced'
  };
  return `<span class="badge ${map[difficulty] || ''}">${difficulty}</span>`;
}

// ============ INIT ============
document.addEventListener('DOMContentLoaded', () => {
  initSidebar();
  initStickyNav();
});