CREATE DATABASE yeticave CHARACTER SET utf8 COLLATE utf8_general_ci;
USE yeticave;

CREATE TABLE categories (
id INT AUTO_INCREMENT PRIMARY KEY,
category VARCHAR NOT NULL UNIQUE
); 

CREATE TABLE lots (
id INT AUTO_INCREMENT PRIMARY KEY,
creation_date DATETIME NOT NULL,
title VARCHAR NOT NULL UNIQUE,
description VARCHAR DEFAULT NULL,
picture VARCHAR DEFAULT NULL,
start_price INT NOT NULL,
end_time DATETIME NOT NULL,
rate INT DEFAULT NULL,
author_id INT NOT NULL,
winner_id INT DEFAULT NULL,
category_id INT DEFAULT NULL
); 

CREATE TABLE bids (
id INT AUTO_INCREMENT PRIMARY KEY,
placement_date DATETIME NOT NULL,
sum INT NOT NULL,
user_id INT NOT NULL,
lot_id INT NOT NULL
); 

CREATE TABLE users ( 
id INT AUTO_INCREMENT PRIMARY KEY,
registration_date DATETIME,
email VARCHAR NOT NULL UNIQUE,
name VARCHAR NOT NULL,
password VARCHAR NOT NULL,
avatar VARCHAR,
contacts VARCHAR,
);

CREATE INDEX author_id ON lots(author_id);
CREATE INDEX winner_id ON lots(winner_id);
CREATE INDEX category_id ON lots(category_id);
CREATE INDEX user_id ON bids(user_id);
CREATE INDEX lot_id ON bids(lot_id);