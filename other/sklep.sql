-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Май 28 2024 г., 02:27
-- Версия сервера: 10.4.28-MariaDB
-- Версия PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `sklep`
--

-- --------------------------------------------------------

--
-- Структура таблицы `koszyk`
--

CREATE TABLE `koszyk` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `towar_id` int(11) NOT NULL,
  `ilosc` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `like`
--

CREATE TABLE `like` (
  `like_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `towar_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `like`
--

INSERT INTO `like` (`like_id`, `user_id`, `towar_id`) VALUES
(14, 3, 2),
(15, 3, 3),
(19, 3, 4),
(20, 3, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `towary`
--

CREATE TABLE `towary` (
  `towar_id` int(11) NOT NULL,
  `nazwa` varchar(30) NOT NULL,
  `opis` varchar(255) NOT NULL,
  `cena` int(11) NOT NULL,
  `wlasciciel_id` int(11) NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `ilosc` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `towary`
--

INSERT INTO `towary` (`towar_id`, `nazwa`, `opis`, `cena`, `wlasciciel_id`, `img`, `ilosc`) VALUES
(2, 'test123', 'super test', 9999, 3, 'https://fullbax.pl/wp-content/uploads/2020/05/Towar-z-Chin-bez-VAT.jpg', 300),
(3, 'newtest', 'nie wiem', 100, 3, 'https://tappy.pl/wp-content/uploads/2021/08/AdobeStock_405028138-Kopiowanie.jpeg', 5),
(4, 'Tygrysek', 'Tygrysek siedzi w liściach', 100000, 3, 'https://media2.castorama.pl/is/image/CastoramaPL/obraz-glasspik-leo-flower-80-x-120-cm~5902841530270_02c_PL_CP?$MOB_PREV$=&$width=64&$height=64', 5000),
(5, 'Księżyc', 'Obraz na płótnie piękny obraz pies', 400, 4, 'https://www.dekowizja.pl/img2/700/93317985/obraz-na-plotnie-piekny-obraz-pies.jpg', 4),
(6, 'Pawie', '\"Złote Pawie\" artystyczny obraz', 500, 4, 'https://studiodruku.com.pl/wp-content/uploads/Zlote-Pawie-obraz-na-plotnie.jpg', 2),
(7, 'Drzewa', 'Kolorowe drzewka', 280, 4, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQH4BJGaCGh8sQAEmU4z-dXk6sKAvwXQZ-tYMFuRdHAqw&s', 10),
(8, 'Mieścina', 'Obraz XXL Mała średniowieczna mieścina - obraz polskiej wsi podczas jesieni', 450, 4, 'https://pl.bimago.media/mediacache/catalog/product/cache/6/7/151576/image/750x1120/7dce3b39a29241bc41a1c55a7f7be783/151576_6.jpg', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `money` decimal(10,2) DEFAULT 0.00,
  `role` varchar(50) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `money`, `role`, `avatar`) VALUES
(1, 'first', 'Kvadratok!', 0.00, 'Użytkownik', NULL),
(2, 'Test', 'zaq12wsx', 0.00, 'Użytkownik', NULL),
(3, 'NeckitWin', '$2y$10$v/K5wmAk2aC4LQuCMMCucuRQrRIJdKg0z8ibKH93IMYBw8ts.CWmW', 99999999.99, 'Test', 'https://ew.com/thmb/qH6e1QIMvbT-yPg6xn7eJ3hKEQI=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/simp_homersingle08_f_hires2-2000-cf09d1b1345c4e66b57bced2bebbe492.jpg'),
(4, 'kirill', '$2y$10$ZNKUJDypoyNmQ5ZUZRS2GuytWGFXC3SdlSMjXWI4O7v.nNK6znmam', 99999999.99, 'Sprzedawca', NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `koszyk`
--
ALTER TABLE `koszyk`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `towar_id` (`towar_id`);

--
-- Индексы таблицы `like`
--
ALTER TABLE `like`
  ADD PRIMARY KEY (`like_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `towar_id` (`towar_id`);

--
-- Индексы таблицы `towary`
--
ALTER TABLE `towary`
  ADD PRIMARY KEY (`towar_id`),
  ADD KEY `wlasciciel_id` (`wlasciciel_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `koszyk`
--
ALTER TABLE `koszyk`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `like`
--
ALTER TABLE `like`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `towary`
--
ALTER TABLE `towary`
  MODIFY `towar_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `koszyk`
--
ALTER TABLE `koszyk`
  ADD CONSTRAINT `koszyk_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `koszyk_ibfk_2` FOREIGN KEY (`towar_id`) REFERENCES `towary` (`towar_id`);

--
-- Ограничения внешнего ключа таблицы `like`
--
ALTER TABLE `like`
  ADD CONSTRAINT `like_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `like_ibfk_2` FOREIGN KEY (`towar_id`) REFERENCES `towary` (`towar_id`);

--
-- Ограничения внешнего ключа таблицы `towary`
--
ALTER TABLE `towary`
  ADD CONSTRAINT `towary_ibfk_1` FOREIGN KEY (`wlasciciel_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
