<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Progress.php';
require_once __DIR__ . '/../middleware/auth.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

$userId   = $_SESSION['user_id'];
$courseId = intval($_POST['course_id'] ?? 0);

if (!$courseId) {
    echo json_encode(['success' => false, 'message' => 'Course ID tidak valid']);
    exit();
}

$progress = new Progress();

// Cek apakah sudah pernah explore course ini
if ($progress->hasDoneCourse($userId, $courseId)) {
    echo json_encode(['success' => false, 'message' => 'Kamu sudah mengeksplorasi course ini']);
    exit();
}

$course = new Course();
$courseData = $course->findById($courseId);

if (!$courseData) {
    echo json_encode(['success' => false, 'message' => 'Course tidak ditemukan']);
    exit();
}

$xpReward = $courseData['xp_reward'];

// Catat progress
$progress->recordCourse($userId, $courseId, $xpReward);

// Tambah XP ke user
$user = new User();
$user->addXP($userId, $xpReward);

// Ambil data user terbaru untuk response
$updatedUser = $user->findById($userId);

echo json_encode([
    'success'   => true,
    'message'   => "+{$xpReward} XP didapat!",
    'new_xp'    => $updatedUser['xp'],
    'new_level' => $updatedUser['level']
]);
?>