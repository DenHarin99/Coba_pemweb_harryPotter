<?php
require_once __DIR__ . '/../config/database.php';

class Course {
    private $conn;

    public function __construct() {
        $this->conn = getConnection();
    }

    public function getAll() {
        $result = $this->conn->query("SELECT * FROM courses ORDER BY course_name");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM courses WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($name, $professor, $difficulty, $xpReward, $description) {
        $stmt = $this->conn->prepare(
            "INSERT INTO courses (course_name, professor, difficulty, xp_reward, description) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("sssds", $name, $professor, $difficulty, $xpReward, $description);
        return $stmt->execute();
    }

    public function update($id, $name, $professor, $difficulty, $xpReward, $description) {
        $stmt = $this->conn->prepare(
            "UPDATE courses SET course_name=?, professor=?, difficulty=?, xp_reward=?, description=? WHERE id=?"
        );
        $stmt->bind_param("sssdsi", $name, $professor, $difficulty, $xpReward, $description, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>