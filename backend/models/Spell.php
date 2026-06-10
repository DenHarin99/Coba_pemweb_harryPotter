<?php
require_once __DIR__ . '/../config/database.php';

class Spell {
    private $conn;

    public function __construct() {
        $this->conn = getConnection();
    }

    public function getAll() {
        $result = $this->conn->query("SELECT * FROM spells ORDER BY spell_name");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM spells WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($name, $type, $difficulty, $xpReward, $description) {
        $stmt = $this->conn->prepare(
            "INSERT INTO spells (spell_name, type, difficulty, xp_reward, description) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("sssds", $name, $type, $difficulty, $xpReward, $description);
        return $stmt->execute();
    }

    public function update($id, $name, $type, $difficulty, $xpReward, $description) {
        $stmt = $this->conn->prepare(
            "UPDATE spells SET spell_name=?, type=?, difficulty=?, xp_reward=?, description=? WHERE id=?"
        );
        $stmt->bind_param("sssdsi", $name, $type, $difficulty, $xpReward, $description, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM spells WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>