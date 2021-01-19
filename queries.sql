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
(1,'2020-11-23 17:00:00','2014 Rossignol District Snowboard','',1,null,1,'img/lot-1.jpg',10999,"2020-11-23 17:00:00",200),
(2,'2020-11-23 17:00:00','DC Ply Mens 2016/2017 Snowboard','',1,null,1,'img/lot-2.jpg',159999,"2020-11-23 17:00:00",200),
(3,'2020-11-23 17:00:00','Крепления Union Contact Pro 2015 года размер L/XL','',2,1,2,'img/lot-3.jpg',8000,"2020-11-23 17:00:00",200),
(4,'2020-11-23 17:00:00','Ботинки для сноуборда DC Mutiny Charocal','',1,null,3,'img/lot-4.jpg',10999,"2020-11-23 17:00:00",200),
(5,'2020-11-23 17:00:00','Куртка для сноуборда DC Mutiny Charocal','',1,null,4,'img/lot-5.jpg',7500,"2020-11-23 17:00:00",200),
(6,'2020-11-23 17:00:00','Маска Oakley Canopy','',1,null,6,'img/lot-6.jpg',5400,"2020-11-23 17:00:00",200);

INSERT INTO bids VALUES
(1,'2020-11-23',15999,1,1),
(2,'2020-11-23',12999,1,1),
(3,'2020-11-23',6000,6,2);

/*получить все категории*/
SELECT categories.* FROM categories; 

/*получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, текущую цену, название категории;*/
SELECT lots.id ,name,start_price,img_link,
MAX(COALESCE(bids.price,lots.start_price)) AS price, 
category

FROM lots
LEFT JOIN bids
ON lots.id = bids.lot_id

LEFT JOIN categories
ON lots.id = categories.id

WHERE lots.date_completion >= NOW()
GROUP BY lots.id
ORDER BY lots.date_create DESC;

/*показать лот по его id. Получите также название категории, к которой принадлежит лот*/
SELECT lots.*, category  
FROM lots
JOIN categories
ON lots.category_id = categories.id;

/*обновить название лота по его идентификатору*/
UPDATE lots 
SET name = '2019 Rossignol District Snowboard'
WHERE id = 1;

/*получить список ставок для лота по его идентификатору с сортировкой по дате*/
SELECT bids.*,lots.*
FROM bids
JOIN lots 
ON bids.lot_id = lots.id
GROUP BY bids.id
ORDER BY bids.date_create DESC;