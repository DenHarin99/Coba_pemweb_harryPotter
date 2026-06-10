CREATE DATABASE IF NOT EXISTS hogwarts_academy;
USE hogwarts_academy;

-- =====================
-- TABLE: users
-- =====================
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin') DEFAULT 'student',
    house ENUM('Gryffindor', 'Slytherin', 'Ravenclaw', 'Hufflepuff') NOT NULL,
    xp INT DEFAULT 0,
    level ENUM('Beginner Wizard', 'Advanced Wizard', 'Expert Wizard') DEFAULT 'Beginner Wizard',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================
-- TABLE: courses
-- =====================
CREATE TABLE courses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_name VARCHAR(100) NOT NULL,
    professor VARCHAR(100) NOT NULL,
    difficulty ENUM('Beginner', 'Intermediate', 'Advanced') NOT NULL,
    xp_reward INT DEFAULT 200,
    description TEXT
);

-- =====================
-- TABLE: spells
-- =====================
CREATE TABLE spells (
    id INT PRIMARY KEY AUTO_INCREMENT,
    spell_name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    difficulty ENUM('Beginner', 'Intermediate', 'Advanced') NOT NULL,
    xp_reward INT DEFAULT 50,
    description TEXT
);

-- =====================
-- TABLE: progress
-- =====================
CREATE TABLE progress (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    course_id INT DEFAULT NULL,
    spell_id INT DEFAULT NULL,
    xp_earned INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE SET NULL,
    FOREIGN KEY (spell_id) REFERENCES spells(id) ON DELETE SET NULL
);

-- =====================
-- SEED DATA (untuk testing)
-- =====================
INSERT INTO users (username, email, password, role, house, xp, level) VALUES
('admin', 'admin@hogwarts.edu', '$2y$10$YourHashedPasswordHere', 'admin', 'Gryffindor', 0, 'Beginner Wizard'),
('harry', 'harry@hogwarts.edu', '$2y$10$YourHashedPasswordHere', 'student', 'Gryffindor', 450, 'Beginner Wizard');

INSERT INTO courses (course_name, professor, difficulty, xp_reward, description) VALUES
('Potions', 'Professor Snape', 'Intermediate', 200, 'Learn the art of potion-making'),
('Charms', 'Professor Flitwick', 'Beginner', 200, 'Master the art of charms'),
('Herbology', 'Professor Sprout', 'Beginner', 200, 'Study magical plants'),
('Defense Against the Dark Arts', 'Professor Lupin', 'Advanced', 200, 'Protect yourself from dark forces'),
('Transfiguration', 'Professor McGonagall', 'Advanced', 200, 'Transform objects and beings');

INSERT INTO spells (spell_name, type, difficulty, xp_reward, description) VALUES
('Lumos', 'Charm', 'Beginner', 50, 'Creates light at the tip of your wand'),
('Alohomora', 'Charm', 'Beginner', 50, 'Unlocks doors and windows'),
('Wingardium Leviosa', 'Charm', 'Beginner', 50, 'Levitates objects'),
('Expelliarmus', 'Defensive', 'Intermediate', 50, 'Disarms your opponent'),
('Expecto Patronum', 'Defensive', 'Advanced', 50, 'Conjures a Patronus to repel Dementors'),
('Avada Kedavra', 'Dark Arts', 'Advanced', 50, 'The killing curse - forbidden magic');