CREATE DATABASE yeticave;
USE yeticave;

CREATE TABLE products
(
   ID INT NOT NULL PRIMARY KEY,
   name_pr char(50) NOT NULL,
   category_pr char(50) NOT NULL,
   price_pr int NOT NULL,
   img_link_pr char(50) NOT NULL,
   date_pr int NOT NULL
);

CREATE TABLE categorys
(
   ID INT NOT NULL PRIMARY KEY,
   categorys_pr char(50) NOT NULL
);