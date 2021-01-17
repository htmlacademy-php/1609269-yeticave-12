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
   date_create datetime not null,
   name varchar(255) not null,
   description varchar(4096) not null,
   user_id int not null,
   winner_id int null DEFAULT NULL,
   category_id int not null,
   img_link varchar(255) not null,
   start_price int not null,
   date_completion datetime not null,
   step_rate int not null
)CHARACTER SET utf8mb4;

CREATE TABLE bids
(
   id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
   date_create datetime not null,
   price int not null,
   lot_id int not null,
   user_id int not null
)CHARACTER SET utf8mb4;

CREATE TABLE users
(
   id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
   date_create datetime not null,
   email varchar(255) not null UNIQUE,
   name varchar(255) not null,
   password varchar(255) not null,
   сontact varchar(255) not null,
   auth_token varchar(255)
)CHARACTER SET utf8mb4;

CREATE INDEX lot_name ON lots(name);
CREATE INDEX lot_creator ON lots(user_id);
CREATE INDEX lot_winner ON lots(winner_id);
CREATE INDEX lot_category ON lots(category_id);

CREATE INDEX lot_id ON bids(lot_id);
CREATE INDEX user_id ON bids(user_id);

CREATE INDEX user_email ON users(email);
CREATE INDEX user_name ON users(name);
CREATE INDEX user_сontact ON users(сontact);

CREATE FULLTEXT INDEX lot_search ON lots(name,description);