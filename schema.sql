CREATE DATABASE yeticave;
USE yeticave;

CREATE TABLE categorys
(
   ID INT NOT NULL PRIMARY KEY,
   category char(50) NOT NULL
);

CREATE TABLE lot
(
   ID INT NOT NULL PRIMARY KEY,
   date_create int not null,
   name char(50) not null,
   description char(50) not null,
   img_link char(50) not null,
   start_price int not null,
   date_completion int not null,
   step_rate int not null
);

CREATE TABLE rate
(
   ID INT NOT NULL PRIMARY KEY,
   date_create int not null,
   price int not null
);

CREATE TABLE users
(
   ID INT NOT NULL PRIMARY KEY,
   date_create int not null,
   email char(50) not null UNIQUE,
   name char(50) not null UNIQUE,
   password char(50) not null,
   сontact int not null
);