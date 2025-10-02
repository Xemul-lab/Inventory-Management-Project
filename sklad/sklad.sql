-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Окт 02 2025 г., 18:58
-- Версия сервера: 5.6.34-log
-- Версия PHP: 7.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `sklad`
--

-- --------------------------------------------------------

--
-- Структура таблицы `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `clientname` varchar(50) NOT NULL,
  `phone` bigint(16) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(200) NOT NULL,
  `purchases` int(11) NOT NULL,
  `totalprice` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `hortek`
--

CREATE TABLE `hortek` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `namename` varchar(250) NOT NULL,
  `code` bigint(50) NOT NULL,
  `quantityskl` int(11) NOT NULL,
  `quantitysold` int(11) NOT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kurs`
--

CREATE TABLE `kurs` (
  `id` int(11) NOT NULL,
  `euro` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `kurs`
--

INSERT INTO `kurs` (`id`, `euro`) VALUES
(1, 113);

-- --------------------------------------------------------

--
-- Структура таблицы `tovar`
--

CREATE TABLE `tovar` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `namename` varchar(250) NOT NULL,
  `place` varchar(250) NOT NULL,
  `code` bigint(13) NOT NULL,
  `quantityskl` int(11) NOT NULL,
  `quantitysold` int(11) NOT NULL,
  `price` float NOT NULL,
  `euro_price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tovar`
--

INSERT INTO `tovar` (`id`, `name`, `namename`, `place`, `code`, `quantityskl`, `quantitysold`, `price`, `euro_price`) VALUES
(1, '7575', 'Test1', 'b2', 646445, 7, 3, 9, 0),
(2, '75973', 'Test', 'b2', 4242, 1, 1, 142, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `tovar1`
--

CREATE TABLE `tovar1` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `namename` varchar(250) NOT NULL,
  `code` bigint(50) NOT NULL,
  `quantityskl` int(11) NOT NULL,
  `quantitysold` int(11) NOT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tovar2`
--

CREATE TABLE `tovar2` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `namename` text NOT NULL,
  `code` bigint(50) NOT NULL,
  `quantityskl` int(11) NOT NULL,
  `quantitysold` int(11) NOT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tovar3`
--

CREATE TABLE `tovar3` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `namename` varchar(250) NOT NULL,
  `code` bigint(50) NOT NULL,
  `quantityskl` int(11) NOT NULL,
  `quantitysold` int(11) NOT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `viega`
--

CREATE TABLE `viega` (
  `id` int(13) NOT NULL,
  `name` varchar(200) NOT NULL,
  `namename` varchar(200) NOT NULL,
  `price` bigint(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `viega`
--

INSERT INTO `viega` (`id`, `name`, `namename`, `price`) VALUES
(1, '267780		', 'Муфта Rp 2\'\', бронза, модель 3270																', 984),
(2, '271855		', 'Муфта редукционная Rp 2 1/2\" х Rp 2\'\', бронза, модель 3240																', 2047),
(3, 'R74AY108		', 'Косой сетчатый фильтр R74A, 2\", Giacomini																', 2988),
(4, '1076016', 'Кран шаровый Optibal Ду 50, В-В, ручка - рычаг																', 5030),
(5, '109196		', 'Соединение с наружной резьбой под пайку 54 мм х R 2\", модель 94243G																', 795),
(6, '1076020		', 'Кран шаровый Optibal Ду 65, В-В, ручка - рычаг																', 15951),
(7, '138202		', 'Соединение с наружной резьбой под пайку 64,0 мм х R 2 1/2\", модель 94243G																', 1506),
(8, '131722		', 'Тройник под пайку 64,0 мм, модель 95130																', 7931),
(9, '594374		', 'Муфта редукционная под пайку 64 х 54 мм, модель 95240																', 1720),
(10, '119652		', 'Тройник под пайку 54 x 42 x 54 мм, модель 95130																', 3560),
(11, '115937		', 'Соединение разъемное ВР (конусное уплотнение) под пайку 42 мм х Rp 1 1/2\", модель 94340G																', 1863),
(12, '119829		', 'Отвод ВВ 90° под пайку 64,0 мм, модель 95002A																', 4444),
(13, '104115		', 'Отвод ВВ 90° под пайку 54 мм, модель 95002A																', 1285),
(14, '7011437		', 'Труба медная SANCO  64x2,0 не отожженная (Штанга 5 м)																', 4857),
(15, '7134772		', 'Труба медная SANCO  54x1,5 не отожженная (Штанга 5 м)																', 3041),
(16, '7011360		', 'Труба медная SANCO  42х1,5 не отожженная (Штанга 5 м)																', 2355);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `hortek`
--
ALTER TABLE `hortek`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `kurs`
--
ALTER TABLE `kurs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tovar`
--
ALTER TABLE `tovar`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tovar1`
--
ALTER TABLE `tovar1`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tovar2`
--
ALTER TABLE `tovar2`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tovar3`
--
ALTER TABLE `tovar3`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `viega`
--
ALTER TABLE `viega`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT для таблицы `hortek`
--
ALTER TABLE `hortek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `kurs`
--
ALTER TABLE `kurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `tovar`
--
ALTER TABLE `tovar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `tovar1`
--
ALTER TABLE `tovar1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=501;
--
-- AUTO_INCREMENT для таблицы `tovar2`
--
ALTER TABLE `tovar2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1001;
--
-- AUTO_INCREMENT для таблицы `tovar3`
--
ALTER TABLE `tovar3`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1442;
--
-- AUTO_INCREMENT для таблицы `viega`
--
ALTER TABLE `viega`
  MODIFY `id` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
