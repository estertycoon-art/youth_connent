CREATE DATABASE IF NOT EXISTS church_youth CHARACTER SET utf8mb4;
USE church_youth;
CREATE TABLE users(
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('member','admin') NOT NULL DEFAULT 'member',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE events(
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  description TEXT,
  event_date DATETIME NOT NULL,
  location VARCHAR(150),
  image VARCHAR(80)
);
CREATE TABLE media(
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150),
  file VARCHAR(80) NOT NULL,
  type ENUM('image','video') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE games(
  id INT AUTO_INCREMENT PRIMARY KEY,
  question VARCHAR(255) NOT NULL,
  option_a VARCHAR(120), option_b VARCHAR(120), option_c VARCHAR(120), option_d VARCHAR(120),
  answer CHAR(1) NOT NULL
);
INSERT INTO games(question,option_a,option_b,option_c,option_d,answer) VALUES
('Who built the ark?','Moses','Noah','Abraham','David','b'),
('How many disciples did Jesus choose?','7','10','12','40','c'),
('Who was swallowed by a great fish?','Jonah','Peter','Elijah','Paul','a');
