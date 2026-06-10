<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Progress.php';
require_once __DIR__ . '/../middleware/auth.php';

requireLogin();

$action = $_GET['action'] ?? 'list';
$course = new Course();

switch ($action) {
    case 'list':
        $courses = $course->getAll();
        
        // Tandai course yang sudah diexplore oleh user
        if ($_SESSION['role'] === 'student') {
            $progress = new Progress();
            foreach ($courses as &$c) {
                $c['is_explored'] = $progress->hasDoneCourse($_SESSION['user_id'], $c['id']);
            }
        }
        echo json_encode(['success' => true, 'data' => $courses]);
        break;

    case 'detail':
        $id = intval($_GET['id'] ?? 0);
        $data = $course->findById($id);
        if ($data) {
            echo json_encode(['success' => true, 'data' => $data]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Course tidak ditemukan']);
        }
        break;
}
?>