<?php
session_start();
require_once '../../../backend/middleware/student.php';
requireStudent();

require_once '../../../backend/models/User.php';
require_once '../../../backend/models/Spell.php';
require_once '../../../backend/models/Progress.php';

$userId      = $_SESSION['user_id'];
$userObj     = new User();
$user        = $userObj->findById($userId);
$userXP      = $user['xp'];
$userLevel   = $user['level'];

$spellObj    = new Spell();
$progressObj = new Progress();
$spells      = $spellObj->getAll();

// Collect spell types for filter
$types = array_unique(array_column($spells, 'type'));
sort($types);

// Mark learned
foreach ($spells as &$s) {
  $s['is_learned'] = $progressObj->hasDoneSpell($userId, $s['id']);
}
unset($s);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Spell Book — Hogwarts Academy</title>
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
      <h2>✨ Spell Book</h2>
      <p>Learn spells to earn XP and expand your magical repertoire.</p>
    </div>

    <!-- Toolbar -->
    <div class="page-toolbar">
      <div style="display:flex;gap:10px;flex-wrap:wrap;flex:1">
        <div class="search-wrap" style="max-width:260px;width:100%">
          <span class="search-icon">🔍</span>
          <input type="text" id="spellSearch" class="form-control" placeholder="Search spells...">
        </div>
        <select id="typeFilter" class="form-control" style="max-width:160px">
          <option value="">All Types</option>
          <?php foreach ($types as $t): ?>
            <option value="<?= htmlspecialchars($t) ?>"><?= htmlspecialchars($t) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div style="font-size:0.82rem;color:var(--text-muted)">
        <?= count($spells) ?> spells total
      </div>
    </div>

    <!-- Spells Grid -->
    <div class="grid-3" id="spellsGrid">
      <?php foreach ($spells as $spell): ?>
        <?php
          $diffMap = ['Beginner'=>'badge-beginner','Intermediate'=>'badge-intermediate','Advanced'=>'badge-advanced'];
          $badgeClass = $diffMap[$spell['difficulty']] ?? '';
        ?>
        <div class="item-card <?= $spell['is_learned'] ? 'explored' : '' ?>"
          onclick="openSpellModal(<?= htmlspecialchars(json_encode($spell)) ?>)"
          data-name="<?= htmlspecialchars(strtolower($spell['spell_name'])) ?>"
          data-type="<?= htmlspecialchars($spell['type']) ?>">

          <?php if ($spell['is_learned']): ?>
            <div style="position:absolute;top:12px;right:12px;color:var(--gold);font-size:0.75rem;font-weight:700">
              ✓ Learned
            </div>
          <?php endif; ?>

          <div class="item-card-name"><?= htmlspecialchars($spell['spell_name']) ?></div>
          <div class="item-card-sub"><?= htmlspecialchars($spell['type']) ?></div>

          <span class="badge <?= $badgeClass ?>"><?= $spell['difficulty'] ?></span>

          <div class="item-card-footer">
            <span class="xp-pill">+<?= $spell['xp_reward'] ?> XP</span>
            <span style="font-size:0.78rem;color:var(--text-muted)">Tap to view →</span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <?php if (empty($spells)): ?>
      <div class="empty-state">
        <div class="empty-state-icon">✨</div>
        <div class="empty-state-text">No spells available yet.</div>
      </div>
    <?php endif; ?>
  </main>
</div>

<!-- Spell Modal -->
<div class="modal-backdrop" id="spellModal">
  <div class="modal">
    <div class="modal-header">
      <div>
        <div id="modalSpellName" class="modal-title"></div>
        <div id="modalSpellType" style="font-size:0.82rem;color:var(--text-muted);margin-top:4px"></div>
      </div>
      <button class="modal-close" onclick="closeModal('spellModal')">✕</button>
    </div>
    <div class="modal-body">
      <div id="modalSpellAlertBox"></div>

      <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px">
        <span id="modalSpellDifficulty" class="badge"></span>
        <span id="modalSpellXP" class="xp-pill"></span>
      </div>

      <div style="margin-bottom:20px">
        <div style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--text-muted);margin-bottom:6px">Description</div>
        <p id="modalSpellDescription" style="font-size:0.925rem;color:var(--text-secondary);line-height:1.6"></p>
      </div>

      <div id="modalSpellActionArea"></div>
    </div>
  </div>
</div>

<script src="../../assets/js/main.js"></script>
<script>
let currentSpell = null;

function openSpellModal(spell) {
  currentSpell = spell;
  clearAlert('modalSpellAlertBox');

  document.getElementById('modalSpellName').textContent        = spell.spell_name;
  document.getElementById('modalSpellType').textContent        = spell.type;
  document.getElementById('modalSpellDescription').textContent = spell.description || 'No description available.';
  document.getElementById('modalSpellXP').textContent          = '+' + spell.xp_reward + ' XP';

  const diff = spell.difficulty;
  const diffMap = { Beginner: 'badge-beginner', Intermediate: 'badge-intermediate', Advanced: 'badge-advanced' };
  const diffEl = document.getElementById('modalSpellDifficulty');
  diffEl.className = 'badge ' + (diffMap[diff] || '');
  diffEl.textContent = diff;

  const actionArea = document.getElementById('modalSpellActionArea');
  if (spell.is_learned) {
    actionArea.innerHTML = `
      <div class="alert alert-success" style="justify-content:center">
        ✓ You have already learned this spell!
      </div>`;
  } else {
    actionArea.innerHTML = `
      <button id="learnSpellBtn" class="btn btn-primary" style="width:100%"
        onclick="learnSpell()">
        ✨ Learn Spell (+${spell.xp_reward} XP)
      </button>`;
  }

  openModal('spellModal');
}

async function learnSpell() {
  if (!currentSpell) return;
  const btn = document.getElementById('learnSpellBtn');
  if (btn) { btn.disabled = true; btn.textContent = 'Learning...'; }

  const form = new FormData();
  form.append('spell_id', currentSpell.id);

  try {
    const res  = await fetch('../../../backend/actions/learn_spell.php', { method:'POST', body: form });
    const data = await res.json();

    if (data.success) {
      showXPToast(currentSpell.xp_reward, data.new_level);

      document.getElementById('modalSpellActionArea').innerHTML = `
        <div class="alert alert-success" style="justify-content:center">
          ✨ Spell learned! ${data.message}
        </div>`;

      // Update card
      document.querySelectorAll('#spellsGrid .item-card').forEach(card => {
        if (card.dataset.name === currentSpell.spell_name.toLowerCase()) {
          card.classList.add('explored');
          if (!card.querySelector('[data-learned]')) {
            const label = document.createElement('div');
            label.dataset.learned = '1';
            label.style.cssText = 'position:absolute;top:12px;right:12px;color:var(--gold);font-size:0.75rem;font-weight:700';
            label.textContent = '✓ Learned';
            card.appendChild(label);
          }
        }
      });

      updateSidebarXP(data.new_xp, data.new_level);
      currentSpell.is_learned = true;
    } else {
      showAlert('modalSpellAlertBox', data.message || 'Something went wrong.', 'error');
      if (btn) { btn.disabled = false; btn.textContent = '✨ Learn Spell'; }
    }
  } catch (err) {
    showAlert('modalSpellAlertBox', 'Connection error.', 'error');
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
    const p = getLevelProgress(newXP);
    barEl.style.width = p.percent + '%';
  }
}

// Search
document.getElementById('spellSearch').addEventListener('input', filterSpells);
document.getElementById('typeFilter').addEventListener('change', filterSpells);

function filterSpells() {
  const q    = document.getElementById('spellSearch').value.toLowerCase().trim();
  const type = document.getElementById('typeFilter').value.toLowerCase();

  document.querySelectorAll('#spellsGrid .item-card').forEach(card => {
    const nameMatch = !q    || card.dataset.name.includes(q);
    const typeMatch = !type || card.dataset.type.toLowerCase() === type;
    card.style.display = (nameMatch && typeMatch) ? '' : 'none';
  });
}
</script>
</body>
</html>