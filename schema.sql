CREATE DATABASE yeticave;
USE yeticave;

CREATE TABLE categories
(
   id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
   category varchar(255) NOT NULL,
   code varchar(255) NOT NULL
) CHARACTER SET utf8mb4;

CREATE TABLE lots
(
   id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
   date_create date not null,
   name varchar(255) not null,
   description varchar(4096) not null,
   users_creater_id int not null,
   users_winner_id int not null,
   category_id int not null,
   img_link varchar(255) not null,
   start_price int not null,
   date_completion date not null,
   step_rate int not null
)CHARACTER SET utf8mb4;

CREATE TABLE bids
(
   id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
   date_create date not null,
   price int not null,
   lot_id int not null,
   user_id int not null
)CHARACTER SET utf8mb4;

CREATE TABLE users
(
   id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
   date_create date not null,
   email varchar(255) not null UNIQUE,
   name varchar(255) not null UNIQUE,
   password varchar(255) not null,
   сontact varchar(255) not null
)CHARACTER SET utf8mb4;