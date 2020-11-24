INSERT INTO categories VALUES 
(1,'Доски и лыжи',0),
(2,'Крепления',1),
(3,'Ботинки',2),
(4,'Одежда',3),
(5,'Инструменты',4),
(6,'Разное',5);

INSERT INTO users VALUES
(1,'2020-11-23','yandex@mail.ru','yandex','12345y','+7988'),
(2,'2020-11-23','google@mail.ru','google','12345g','+7989');

INSERT INTO lots VALUES
(1,'2020-11-23','2014 Rossignol District Snowboard','',1,0,0,'img/lot-1.jpg',10999,'2020-11-25',15000),
(2,'2020-11-23','DC Ply Mens 2016/2017 Snowboard','',1,0,0,'img/lot-2.jpg',159999,'2020-12-23',200000),
(3,'2020-11-23','Крепления Union Contact Pro 2015 года размер L/XL','',1,2,1,'img/lot-3.jpg',8000,'2020-11-24',8500),
(4,'2020-11-23','Ботинки для сноуборда DC Mutiny Charocal','',1,2,2,'img/lot-4.jpg',10999,'2020-11-23',10999),
(5,'2020-11-23','Куртка для сноуборда DC Mutiny Charocal','',1,0,3,'img/lot-5.jpg',7500,'2020-11-27',7700),
(6,'2020-11-23','Маска Oakley Canopy','',1,2,5,'img/lot-6.jpg',5400,'2020-11-23',6000);

INSERT INTO bids VALUES
(1,'2020-11-23',12999,1,1),
(2,'2020-11-23',6000,6,2);

SELECT category FROM categories; 
/*получить все категории*/

SELECT name, start_price, img_link,step_rate,category_id
FROM lots 
WHERE date_completion >= '2020-11-24' 
ORDER BY date_create DESC; 
/*получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, текущую цену, название категории;*/

SELECT 
/**/
/**/
/**/