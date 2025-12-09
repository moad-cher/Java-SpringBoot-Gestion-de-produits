CREATE DATABASE IF NOT EXISTS tp;

USE tp;

CREATE TABLE IF NOT EXISTS user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255),
    prenom VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    enabled BOOLEAN DEFAULT TRUE
);

CREATE TABLE IF NOT EXISTS produit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255),
    prix DECIMAL(10, 2),
    devise VARCHAR(10),
    taxe DECIMAL(10, 2),
    date_expiration DATE,
    fournisseur VARCHAR(255),
    image VARCHAR(255)
);

-- Insert test user
INSERT IGNORE INTO
    user (
        nom,
        prenom,
        email,
        password,
        enabled
    )
VALUES (
        'Chergui',
        'Moad',
        'moadchergui13@gmail.com',
        '$2a$10$encrypted_password_here',
        TRUE
    );