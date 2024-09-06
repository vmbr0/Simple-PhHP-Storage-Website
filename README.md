# Simple-PhHP-Storage-Website
A very simple PHP website for store data

Create the Folder upload and the folder share

And use this SQL command to create table

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    uploader_id INT NOT NULL,
    is_shared BOOLEAN DEFAULT 0,
    FOREIGN KEY (uploader_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE shared_files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_id INT NOT NULL,
    shared_with INT NOT NULL,
    FOREIGN KEY (file_id) REFERENCES files(id) ON DELETE CASCADE,
    FOREIGN KEY (shared_with) REFERENCES users(id) ON DELETE CASCADE
);
