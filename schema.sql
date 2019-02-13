CREATE DATABASE yeticave CHARACTER SET utf8 COLLATE utf8_general_ci;
USE yeticave;

CREATE TABLE categories (
id INT AUTO_INCREMENT PRIMARY KEY,
category CHAR(20) UNIQUE
); 

CREATE TABLE lots (
id INT AUTO_INCREMENT PRIMARY KEY,
dt_lot DATE,
title CHAR(20) NOT NULL UNIQUE,
description CHAR(255) NOT NULL,
picture CHAR(100), 
start_price INT,
end_time DATETIME,
rate INT,
author_id INT, 
winner_id INT, 
category_id INT 
); 

CREATE TABLE bids (
id INT AUTO_INCREMENT PRIMARY KEY,
dt_bit DATETIME,
sum INT,
user_id INT, 
lot_id INT 
); 

CREATE TABLE users ( 
id INT AUTO_INCREMENT PRIMARY KEY,
dt_add DATETIME,
email CHAR(50) NOT NULL UNIQUE, 
name CHAR(50) NOT NULL, 
password CHAR(20) NOT NULL, 
avatar CHAR(100), 
contacts CHAR(100), 
lot_id INT, 
bid_id INT 
);

CREATE INDEX author_id ON lots(author_id);
CREATE INDEX winner_id ON lots(winner_id);
CREATE INDEX category_id ON lots(category_id);
CREATE INDEX user_id ON bids(user_id);
CREATE INDEX lot_id ON bids(lot_id);
CREATE INDEX lot_id ON users(lot_id);
CREATE INDEX bid_id ON users(bid_id);