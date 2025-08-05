-- Create Database
CREATE DATABASE IF NOT EXISTS vault;
USE vault;

-- Customers Table
CREATE TABLE Customers (
  customer_id INT PRIMARY KEY AUTO_INCREMENT,
  full_name VARCHAR(100),
  email VARCHAR(100)
);

-- Credit Cards Table (with inserted_by tracking)
CREATE TABLE CreditCards (
  card_id INT PRIMARY KEY AUTO_INCREMENT,
  customer_id INT,
  card_number VARBINARY(255),
  expiry_date DATE,
  cvv VARBINARY(100),
  inserted_by VARCHAR(50) NOT NULL,
  FOREIGN KEY (customer_id) REFERENCES Customers(customer_id)
);

-- Users Table (with AES keys)
CREATE TABLE Users (
  user_id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50),
  password_hash CHAR(64),
  role ENUM('admin', 'clerk', 'auditor'),
  aes_key VARCHAR(255) NOT NULL
);

-- Insert Sample Customer
INSERT INTO Customers (full_name, email)
VALUES ('Alice Example', 'alice@example.com');

-- Insert Sample Users (with unique AES keys)
INSERT INTO Users (username, password_hash, role, aes_key) VALUES
('admin1', SHA2('adminpass', 256), 'admin', 'adminsecretkey123'),
('clerk1', SHA2('clerkpass', 256), 'clerk', 'clerksecretkey456'),
('auditor1', SHA2('auditpass', 256), 'auditor', 'auditsecretkey789');
