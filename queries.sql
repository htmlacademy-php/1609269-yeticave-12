INSERT INTO categories VALUES 
(1,'Доски и лыжи','boards'),
(2,'Крепления','attachment'),
(3,'Ботинки','boots'),
(4,'Одежда','clothing'),
(5,'Инструменты','tools'),
(6,'Разное','other');

INSERT INTO users VALUES
(1,'2020-11-23','yandex@mail.ru','yandex','$2y$10$NvdjJOFXly/ybiO1IwSMre0x0mraCebAnGNxe8RJC27yMn4P/vSfK','+7988'),
(2,'2020-11-23','google@mail.ru','google','$2y$10$hOtl9f4C3VcFdXkBGF.EH.j9cdbnZ3noct/mWGwGjaQryzY3qkFxO','+7989');

INSERT INTO lots VALUES
(1,'2020-11-23','2014 Rossignol District Snowboard','',1,0,1,'img/lot-1.jpg',10999,'2020-11-25',200),
(2,'2020-11-23','DC Ply Mens 2016/2017 Snowboard','',1,0,1,'img/lot-2.jpg',159999,'2020-12-23',200),
(3,'2020-11-23','Крепления Union Contact Pro 2015 года размер L/XL','',2,1,2,'img/lot-3.jpg',8000,'2020-11-24',200),
(4,'2020-11-23','Ботинки для сноуборда DC Mutiny Charocal','',1,2,3,'img/lot-4.jpg',10999,'2020-11-23',200),
(5,'2020-11-23','Куртка для сноуборда DC Mutiny Charocal','',1,0,4,'img/lot-5.jpg',7500,'2020-11-27',200),
(6,'2020-11-23','Маска Oakley Canopy','',1,2,6,'img/lot-6.jpg',5400,'2020-11-23',200);

INSERT INTO bids VALUES
(1,'2020-11-23',12999,1,1),
(2,'2020-11-23',6000,6,2);

/*получить все категории*/
SELECT category FROM categories; 

/*получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, текущую цену, название категории;*/
SELECT lots.id ,name, start_price, img_link, step_rate, category
FROM lots
JOIN categories
ON lots.category_id = categories.id
WHERE lots.date_completion >= NOW()
ORDER BY lots.date_create DESC; 

/*показать лот по его id. Получите также название категории, к которой принадлежит лот*/
SELECT lots.id, category  
FROM lots
JOIN categories
ON lots.id = categories.id;

/*обновить название лота по его идентификатору*/
UPDATE lots 
SET name = '2019 Rossignol District Snowboard'
WHERE id = 1;

/*получить список ставок для лота по его идентификатору с сортировкой по дате*/
SELECT bids.id,bids.date_create,lots.*
FROM bids
JOIN lots 
ON bids.lot_id = lots.id
ORDER BY bids.date_create DESC;
