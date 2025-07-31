
-- Create Database
CREATE DATABASE IF NOT EXISTS vault;
USE vault;

-- Customers Table
CREATE TABLE Customers (
  customer_id INT PRIMARY KEY AUTO_INCREMENT,
  full_name VARCHAR(100),
  email VARCHAR(100)
);

-- Credit Cards Table
CREATE TABLE CreditCards (
  card_id INT PRIMARY KEY AUTO_INCREMENT,
  customer_id INT,
  card_number VARBINARY(255),
  expiry_date DATE,
  cvv VARBINARY(100),
  FOREIGN KEY (customer_id) REFERENCES Customers(customer_id)
);

-- Users Table
CREATE TABLE Users (
  user_id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50),
  password_hash CHAR(64),
  role ENUM('admin', 'clerk', 'auditor')
);

-- Insert Sample Customer
INSERT INTO Customers (full_name, email)
VALUES ('Alice Example', 'alice@example.com');

-- Insert Sample Users
INSERT INTO Users (username, password_hash, role) VALUES
('admin1', SHA2('adminpass', 256), 'admin'),
('clerk1', SHA2('clerkpass', 256), 'clerk'),
('auditor1', SHA2('auditpass', 256), 'auditor');
