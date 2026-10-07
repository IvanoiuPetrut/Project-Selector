-- Schema reconstructed from the queries in the PHP code (no original dump exists).
-- Runs automatically the first time the MariaDB container starts with an empty volume.

SET NAMES utf8mb4;

CREATE TABLE roles (
  id   INT PRIMARY KEY,
  name VARCHAR(32) NOT NULL UNIQUE
);

CREATE TABLE `groups` (
  id   INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(16) NOT NULL UNIQUE -- format "222/1" (group/semi-group)
);

CREATE TABLE users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(32)  NOT NULL,
  last_name  VARCHAR(32)  NOT NULL,
  email      VARCHAR(255) NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL, -- password_hash() (bcrypt); legacy unsalted sha256 hex is upgraded on login
  id_group   INT NULL,
  id_role    INT NOT NULL DEFAULT 1,
  FOREIGN KEY (id_group) REFERENCES `groups`(id) ON DELETE SET NULL,
  FOREIGN KEY (id_role)  REFERENCES roles(id)
);

CREATE TABLE projects (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(32)  NOT NULL UNIQUE,
  description VARCHAR(256) NOT NULL
);

CREATE TABLE chosen_projects (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  id_user    INT NOT NULL,
  id_group   INT NULL,
  id_project INT NOT NULL,
  status     TINYINT NOT NULL DEFAULT 0, -- 0 = in progress, 1 = completed
  FOREIGN KEY (id_user)    REFERENCES users(id)    ON DELETE CASCADE,
  FOREIGN KEY (id_group)   REFERENCES `groups`(id) ON DELETE SET NULL,
  FOREIGN KEY (id_project) REFERENCES projects(id) ON DELETE CASCADE
);

-- Seed data ------------------------------------------------------------------

INSERT INTO roles (id, name) VALUES (1, 'student'), (2, 'teacher'), (3, 'admin');

INSERT INTO `groups` (name) VALUES ('221/1'), ('221/2'), ('222/1'), ('222/2');

-- All demo accounts use the password "Password1" (legacy sha256, rehashed with bcrypt on first login)
INSERT INTO users (first_name, last_name, email, password, id_group, id_role) VALUES
  ('Admin',   'User',    'admin@example.com',   SHA2('Password1', 256), NULL, 3),
  ('Teacher', 'User',    'teacher@example.com', SHA2('Password1', 256), NULL, 2),
  ('Student', 'User',    'student@example.com', SHA2('Password1', 256), 3,    1);

INSERT INTO projects (name, description) VALUES
  ('Library System',  'A web application for managing book loans, members and due dates.'),
  ('Weather Station', 'Collect sensor readings and display them on a live dashboard.'),
  ('Chat App',        'Real-time messaging between users with rooms and notifications.');
