<?php
require_once __DIR__ . '/../config/database.php';

class Progress {
    private $conn;

    public function __construct() {
        $this->conn = getConnection();
    }

    // Cek apakah user sudah explore course ini
    public function hasDoneCourse($userId, $courseId) {
        $stmt = $this->conn->prepare(
            "SELECT id FROM progress WHERE user_id = ? AND course_id = ?"
        );
        $stmt->bind_param("ii", $userId, $courseId);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // Cek apakah user sudah learn spell ini
    public function hasDoneSpell($userId, $spellId) {
        $stmt = $this->conn->prepare(
            "SELECT id FROM progress WHERE user_id = ? AND spell_id = ?"
        );
        $stmt->bind_param("ii", $userId, $spellId);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // Catat progress course
    public function recordCourse($userId, $courseId, $xpEarned) {
        $stmt = $this->conn->prepare(
            "INSERT INTO progress (user_id, course_id, xp_earned) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("iii", $userId, $courseId, $xpEarned);
        return $stmt->execute();
    }

    // Catat progress spell
    public function recordSpell($userId, $spellId, $xpEarned) {
        $stmt = $this->conn->prepare(
            "INSERT INTO progress (user_id, spell_id, xp_earned) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("iii", $userId, $spellId, $xpEarned);
        return $stmt->execute();
    }

    // Statistik untuk dashboard student
    public function getStudentStats($userId) {
        // Total courses explored
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) as count FROM progress WHERE user_id = ? AND course_id IS NOT NULL"
        );
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $courses = $stmt->get_result()->fetch_assoc()['count'];

        // Total spells learned
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) as count FROM progress WHERE user_id = ? AND spell_id IS NOT NULL"
        );
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $spells = $stmt->get_result()->fetch_assoc()['count'];

        // Total XP earned
        $stmt = $this->conn->prepare(
            "SELECT SUM(xp_earned) as total FROM progress WHERE user_id = ?"
        );
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $totalXP = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

        // Recent courses (3 terakhir)
        $stmt = $this->conn->prepare(
            "SELECT c.course_name FROM progress p 
             JOIN courses c ON p.course_id = c.id 
             WHERE p.user_id = ? AND p.course_id IS NOT NULL 
             ORDER BY p.created_at DESC LIMIT 3"
        );
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $recentCourses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Recent spells (3 terakhir)
        $stmt = $this->conn->prepare(
            "SELECT s.spell_name FROM progress p 
             JOIN spells s ON p.spell_id = s.id 
             WHERE p.user_id = ? AND p.spell_id IS NOT NULL 
             ORDER BY p.created_at DESC LIMIT 3"
        );
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $recentSpells = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        return [
            'courses_explored' => $courses,
            'spells_learned'   => $spells,
            'total_xp_earned'  => $totalXP,
            'recent_courses'   => $recentCourses,
            'recent_spells'    => $recentSpells
        ];
    }
}
?>