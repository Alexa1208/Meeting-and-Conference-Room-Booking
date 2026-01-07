CREATE DATABASE booking_system;
USE booking_system;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50),
  password VARCHAR(50)
);

INSERT INTO users (username, password) VALUES ('admin', 'admin123');

CREATE TABLE meeting_bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  room VARCHAR(100),
  date DATE,
  start_time TIME,
  end_time TIME,
  purpose TEXT,
  booked_by VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE studio_bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  studio VARCHAR(100),
  type VARCHAR(50),
  date DATE,
  start_time TIME,
  end_time TIME,
  project TEXT,
  booked_by VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
