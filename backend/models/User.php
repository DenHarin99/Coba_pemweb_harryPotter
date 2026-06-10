<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;

    public function __construct() {
        $this->conn = getConnection();
    }

    // Cek apakah username/email sudah ada
    public function exists($username, $email) {
        $stmt = $this->conn->prepare(
            "SELECT id FROM users WHERE username = ? OR email = ?"
        );
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // Buat user baru
    public function create($username, $email, $password, $house, $role = 'student') {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare(
            "INSERT INTO users (username, email, password, house, role) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("sssss", $username, $email, $hashed, $house, $role);
        return $stmt->execute();
    }

    // Login: cari user berdasarkan username atau email
    public function findByUsernameOrEmail($identifier) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1"
        );
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Ambil semua student (untuk admin)
    public function getAllStudents() {
        $result = $this->conn->query(
            "SELECT id, username, house, xp, level FROM users WHERE role = 'student' ORDER BY xp DESC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Ambil user by ID
    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Update student (oleh admin)
    public function update($id, $username, $email, $house, $role, $password = null) {
        if ($password) {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->conn->prepare(
                "UPDATE users SET username=?, email=?, house=?, role=?, password=? WHERE id=?"
            );
            $stmt->bind_param("sssssi", $username, $email, $house, $role, $hashed, $id);
        } else {
            $stmt = $this->conn->prepare(
                "UPDATE users SET username=?, email=?, house=?, role=? WHERE id=?"
            );
            $stmt->bind_param("ssssi", $username, $email, $house, $role, $id);
        }
        return $stmt->execute();
    }

    // Hapus student
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Tambah XP + update level otomatis
    public function addXP($userId, $xpAmount) {
        // Ambil XP sekarang
        $user = $this->findById($userId);
        $newXP = $user['xp'] + $xpAmount;
        
        // Hitung level baru
        $newLevel = $this->calculateLevel($newXP);
        
        $stmt = $this->conn->prepare(
            "UPDATE users SET xp = ?, level = ? WHERE id = ?"
        );
        $stmt->bind_param("isi", $newXP, $newLevel, $userId);
        return $stmt->execute();
    }

    // Logic level
    private function calculateLevel($xp) {
        if ($xp >= 1500) return 'Expert Wizard';
        if ($xp >= 500)  return 'Advanced Wizard';
        return 'Beginner Wizard';
    }
}
?>