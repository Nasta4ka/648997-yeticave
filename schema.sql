CREATE DATABASE `648997-yeticave` CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `648997-yeticave`;

CREATE TABLE categories (
id INT AUTO_INCREMENT PRIMARY KEY,
category VARCHAR(100) NOT NULL UNIQUE
); 

CREATE TABLE lots (
id INT AUTO_INCREMENT PRIMARY KEY,
creation_date DATETIME DEFAULT NOW(),
title VARCHAR(100) NOT NULL,
description VARCHAR(200) NOT NULL,
picture VARCHAR(100) DEFAULT NULL,
start_price INT NOT NULL,
end_time DATETIME NOT NULL,
rate INT DEFAULT NULL,
author_id INT NOT NULL,
winner_id INT DEFAULT NULL,
category_id INT NOT NULL
); 

CREATE TABLE bids (
id INT AUTO_INCREMENT PRIMARY KEY,
placement_date DATETIME NOT NULL DEFAULT NOW(),
sum INT NOT NULL,
user_id INT NOT NULL,
lot_id INT NOT NULL
); 

CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
registration_date DATETIME NOT NULL DEFAULT NOW(),
email VARCHAR(100) NOT NULL UNIQUE,
name VARCHAR(100) NOT NULL,
password VARCHAR(100) NOT NULL,
avatar VARCHAR(100) DEFAULT NULL,
contacts VARCHAR(100) DEFAULT NULL
);

CREATE INDEX author_id ON lots(author_id);
CREATE INDEX winner_id ON lots(winner_id);
CREATE INDEX category_id ON lots(category_id);
CREATE INDEX user_id ON bids(user_id);
CREATE INDEX lot_id ON bids(lot_id);
CREATE FULLTEXT INDEX title_descr ON lots (title, description);