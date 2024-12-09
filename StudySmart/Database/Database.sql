CREATE DATABASE studysmart;

USE studysmart;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mobile_number VARCHAR(15),
    school_info VARCHAR(100),
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tasks 
(
    task_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    task_info TEXT NOT NULL,
    priority ENUM('High', 'Medium', 'Low') NOT NULL,
    category ENUM('Study', 'Work', 'Personal', 'Others') NOT NULL,
    task_date DATE NOT NULL,
    task_time TIME NOT NULL,
    status ENUM('completed', 'not_completed') DEFAULT 'not_completed',
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);
