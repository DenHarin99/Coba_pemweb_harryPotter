<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Spell.php';
require_once __DIR__ . '/../models/Progress.php';
require_once __DIR__ . '/../middleware/auth.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

$userId  = $_SESSION['user_id'];
$spellId = intval($_POST['spell_id'] ?? 0);

if (!$spellId) {
    echo json_encode(['success' => false, 'message' => 'Spell ID tidak valid']);
    exit();
}

$progress = new Progress();

if ($progress->hasDoneSpell($userId, $spellId)) {
    echo json_encode(['success' => false, 'message' => 'Kamu sudah mempelajari spell ini']);
    exit();
}

$spell = new Spell();
$spellData = $spell->findById($spellId);

if (!$spellData) {
    echo json_encode(['success' => false, 'message' => 'Spell tidak ditemukan']);
    exit();
}

$xpReward = $spellData['xp_reward'];

$progress->recordSpell($userId, $spellId, $xpReward);

$user = new User();
$user->addXP($userId, $xpReward);

$updatedUser = $user->findById($userId);

echo json_encode([
    'success'   => true,
    'message'   => "+{$xpReward} XP didapat!",
    'new_xp'    => $updatedUser['xp'],
    'new_level' => $updatedUser['level']
]);
?>