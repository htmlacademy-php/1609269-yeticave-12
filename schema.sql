CREATE DATABASE yeticave;
USE yeticave;

CREATE TABLE categorys
(
   categorys_ID INT NOT NULL PRIMARY KEY,
   category char(50) NOT NULL
);

CREATE TABLE lot
(
   lot_ID INT NOT NULL PRIMARY KEY,
   lot_date_create int not null,
   lot_name char(50) not null,
   lot_description char(50) not null,
   lot_img_link char(50) not null,
   lot_start_price int not null,
   lot_date_completion int not null,
   lot_step_rate int not null
);

CREATE TABLE rate
(
   rate_ID INT NOT NULL PRIMARY KEY,
   rate_date_create int not null,
   rate_price int not null
);

CREATE TABLE users
(
   users_ID INT NOT NULL PRIMARY KEY,
   users_date_create int not null,
   users_email char(50) not null UNIQUE,
   users_name char(50) not null UNIQUE,
   user_password char(50) not null,
   user_email char(50) not null,
   users_contact int not null
);