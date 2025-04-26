-- Create the database
CREATE DATABASE IF NOT EXISTS army_crypt;

-- Select the database to use
USE army_crypt;

-- Create the encrypted data table
CREATE TABLE encrypted_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    soldier_name TEXT NOT NULL,
    rank TEXT NOT NULL,
    mission_report TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
