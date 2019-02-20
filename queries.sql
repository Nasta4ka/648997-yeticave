USE `648997-yeticave`;

INSERT INTO categories(category) VALUES
 ('Доски и лыжи'),
  ('Крепления'),
  ('Ботинки'),
  ('Одежда'),
  ('Инструменты'),
  ('Разное');

INSERT INTO lots(description, title, start_price, picture, category_id, end_time, rate, author_id)
VALUES
('DC Ply Mens 2016/2017 Snowboard', 'DC Ply Mens 2016/2017 Snowboard', 159999, 'img/lot-2.jpg', 1, '2019-03-01 14:10:00', 100, 1),
('DC Ply Mens 2016/2017 Snowboard', 'Крепления Union Contact Pro 2015 года размер L/XL', 8000, 'img/lot-3.jpg', 2, '2019-02-16 10:10:00', 50, 2),
('DC Ply Mens 2016/2017 Snowboard', 'Ботинки для сноуборда DC Mutiny Charocal', 10999, 'img/lot-4.jpg', 3, '2019-03-05 14:10:00', 20, 1),
('DC Ply Mens 2016/2017 Snowboard', 'Куртка для сноуборда DC Mutiny Charocal', 7500, 'img/lot-5.jpg', 4, '2019-06-05 09:09:00', 50, 2),
('DC Ply Mens 2016/2017 Snowboard', 'Маска Oakley Canopy', 5400,'img/lot-6.jpg', 5, '2019-03-05 14:00:00', 100, 1);


INSERT INTO users (email, name, password, avatar, contacts)
VALUES
( 'falvs@mail.ru', 'User-1', 12345, 'img/av-1' , '+37529 600 00 90'),
('kitty@mail.ru', 'User-2', 12345, 'img/av-2' , '+37529 600 88 90'),
('firago.a.i@mail.ru', 'User-3', 111111, 'img/av-3' , '+375332052177'),
('abc@mail.ru', 'User-4', 78787, 'img/av-4' , '+375331052173');

INSERT INTO bids (sum, user_id, lot_id)
VALUES
(18000, 3, 1),
(18500, 4, 1),
(18700, 2, 1),
(8000, 4, 4),
(9000, 3, 4),
(5500, 3, 5),
(5600, 2, 5),
(5700, 4, 5);

#выводит все категории
SELECT category FROM categories;

# самые новые, открытые лоты (выводит название, стартовую цену, ссылку на изображение, цену, количество ставок, название категории)

SELECT lots.title, lots.start_price, lots.picture, MAX(bids.sum), COUNT(bids.id) , categories.category FROM lots
LEFT JOIN categories ON lots.category_id = categories.id
LEFT JOIN bids ON lots.id = bids.lot_id
WHERE end_time > NOW()
  GROUP BY lots.id;

#показывает лот по ID, получает название категории

SELECT lots.title, categories.category FROM lots
LEFT JOIN categories ON lots.category_id = categories.id
WHERE lots.ID = 4;

#обновляет лот по id
UPDATE lots SET title = 'Куртка для сноуборда Superdry' WHERE id = 4;

#получает список самых свежих ставок для лота по его идентификатору;
SELECT * FROM bids
LEFT JOIN lots ON bids.lot_id = lots.ID
WHERE bids.lot_id = 1 && lots.end_time > NOW();