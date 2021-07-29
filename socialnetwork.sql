-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 15 2021 г., 22:20
-- Версия сервера: 8.0.19
-- Версия PHP: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `socialnetwork`
--

-- --------------------------------------------------------

--
-- Структура таблицы `albums`
--

CREATE TABLE `albums` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `text` text,
  `author_id` int DEFAULT NULL,
  `post_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `comments`
--

INSERT INTO `comments` (`id`, `text`, `author_id`, `post_id`, `created_at`) VALUES
(1, 'Some comment', 2, 1, '2021-05-18 22:30:33'),
(2, 'Пост ни очем', 1, 1, '2021-05-10 22:30:37'),
(50, 'new comment', 1, 59, '2021-06-14 03:04:48'),
(51, 'Hello', 1, 60, '2021-06-16 03:49:15'),
(76, 'Новый', 12, 74, '2021-06-16 08:56:12'),
(77, 'Что там', 12, 73, '2021-06-16 08:56:25'),
(78, 'Лед', 12, 74, '2021-06-16 08:56:36');

-- --------------------------------------------------------

--
-- Структура таблицы `comments_image`
--

CREATE TABLE `comments_image` (
  `id` int NOT NULL,
  `text` text NOT NULL,
  `image_id` int NOT NULL,
  `author_id` int NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `comments_image`
--

INSERT INTO `comments_image` (`id`, `text`, `image_id`, `author_id`, `created_at`) VALUES
(1, 'Comment', 99, 1, '2021-06-17 03:02:28'),
(2, 'Comment2', 99, 1, '2021-06-17 03:15:16'),
(8, 'New comment', 99, 12, '2021-06-17 04:39:31'),
(9, 'Классс', 108, 12, '2021-06-17 04:48:45'),
(10, 'Новый коммент', 110, 13, '2021-06-17 04:52:58'),
(27, 'Komment', 125, 14, '2021-07-07 04:02:44'),
(28, 'Roma', 125, 14, '2021-07-07 04:02:47');

-- --------------------------------------------------------

--
-- Структура таблицы `dialogs`
--

CREATE TABLE `dialogs` (
  `id` int NOT NULL,
  `user1_id` int DEFAULT NULL,
  `user2_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `dialogs`
--

INSERT INTO `dialogs` (`id`, `user1_id`, `user2_id`) VALUES
(14, 4, 12),
(15, 12, 13),
(16, 3, 12);

-- --------------------------------------------------------

--
-- Структура таблицы `friends`
--

CREATE TABLE `friends` (
  `id` int NOT NULL,
  `friend_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `relation_type` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `friends`
--

INSERT INTO `friends` (`id`, `friend_id`, `user_id`, `relation_type`) VALUES
(4, 4, 2, 1),
(5, 2, 3, 1),
(7, 1, 3, 1),
(8, 5, 1, 1),
(9, 4, 1, 1),
(10, 2, 1, 1),
(18, 3, 13, 1),
(19, 6, 13, 1),
(49, 13, 14, 1),
(68, 14, 12, 1),
(70, 7, 12, 1),
(75, 7, 13, 1),
(76, 2, 13, 1),
(77, 5, 13, 1),
(78, 1, 13, 1),
(79, 4, 13, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `images`
--

CREATE TABLE `images` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `user_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `images`
--

INSERT INTO `images` (`id`, `name`, `description`, `user_id`, `created_at`) VALUES
(1, '/images/user_gallery/5ce1e1c7__3b266d58.jpg', NULL, 1, '2021-06-08 04:19:18'),
(93, '/images/user_gallery/81e24a47__76a3626d.jpg', NULL, 1, '2021-06-14 02:22:33'),
(94, '/images/user_gallery/43e2e8bb__138ff3ba.jpg', NULL, 1, '2021-06-14 02:22:33'),
(97, '/images/user_gallery/7b2d6837754d0__08329f5b.jpg', NULL, 1, '2021-06-16 08:33:43'),
(98, '/images/user_gallery/807992600f199c94287965fb5a7723437d4e679e__730018ff.jpeg', ' описание к фото че то там ', 1, '2021-06-16 08:33:43'),
(113, '/images/user_gallery/ava__cf5e9ea6.jpg', 'фото', 12, '2021-06-17 03:58:01'),
(121, '/images/user_gallery/d17ce9a0__f4b4ecac.jpg', 'Desc 4546', 12, '2021-06-29 21:26:15'),
(125, '/images/user_gallery/no_image__f53049ed.jpg', 'Some naom', 14, '2021-07-07 03:01:59'),
(126, '/images/user_gallery/modern-hotel-room-interior-design_4475_800_741__d3547f53.jpg', NULL, 14, '2021-07-07 03:01:59'),
(127, '/images/user_gallery/010a2136850f8__03c95bf4.jpg', NULL, 14, '2021-07-07 03:01:59'),
(131, '/images/user_gallery/7b2d6837754d0__8e9b3ff1.jpg', NULL, 12, '2021-07-08 02:32:28'),
(135, '/images/user_gallery/room__c593f2cf.jpg', NULL, 12, '2021-07-08 02:33:15'),
(136, '/images/user_gallery/Depositphotos_11667118_original__417991db.jpg', NULL, 13, '2021-07-15 00:28:24'),
(137, '/images/user_gallery/XXL__8b9672c3.jpg', NULL, 13, '2021-07-15 00:28:24');

-- --------------------------------------------------------

--
-- Структура таблицы `invites`
--

CREATE TABLE `invites` (
  `id` int NOT NULL,
  `user_id_from` int DEFAULT NULL,
  `user_id_to` int DEFAULT NULL,
  `status` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `likes`
--

CREATE TABLE `likes` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `post_id` int NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `post_id`, `status`) VALUES
(6, 1, 5, 1),
(7, 1, 4, 1),
(8, 1, 11, 1),
(9, 1, 12, 1),
(10, 1, 1, 1),
(11, 1, 13, 1),
(12, 1, 19, 0),
(13, 1, 20, 1),
(14, 1, 21, 1),
(15, 1, 22, 1),
(16, 1, 26, 1),
(17, 1, 30, 1),
(18, 1, 31, 1),
(19, 1, 37, 1),
(20, 1, 39, 0),
(21, 1, 38, 1),
(22, 1, 51, 1),
(23, 1, 50, 1),
(24, 1, 58, 1),
(25, 1, 64, 0),
(26, 1, 63, 1),
(27, 1, 66, 1),
(28, 1, 70, 1),
(29, 1, 68, 0),
(30, 1, 65, 1),
(31, 1, 73, 1),
(32, 1, 74, 1),
(33, 1, 79, 0),
(34, 13, 79, 1),
(35, 13, 65, 1),
(36, 12, 79, 1),
(37, 12, 65, 1),
(38, 12, 76, 1),
(39, 13, 76, 1),
(40, 12, 74, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `dialog_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `text` text,
  `created_at` datetime DEFAULT NULL,
  `status` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `messages`
--

INSERT INTO `messages` (`id`, `dialog_id`, `user_id`, `text`, `created_at`, `status`) VALUES
(1, 16, 12, 'Hello', '2021-07-03 23:29:09', 1),
(2, 15, 12, 'Hi!', '2021-07-03 23:29:22', 1),
(3, 14, 12, 'aaa', '2021-07-03 23:29:28', 1),
(11, 15, 13, 'I\'m Here', '2021-07-13 00:13:37', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int NOT NULL,
  `migration` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `created_at`) VALUES
(8, 'm0001_initial.php', '2021-05-17 12:17:09'),
(9, 'm0002_test.php', '2021-05-17 12:17:09');

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE `posts` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `likes_count` int NOT NULL,
  `dislikes_count` int NOT NULL,
  `user_id` int NOT NULL,
  `author_id` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `posts`
--

INSERT INTO `posts` (`id`, `title`, `text`, `likes_count`, `dislikes_count`, `user_id`, `author_id`, `created_at`, `updated_at`) VALUES
(1, 'New Post', '1 Пост', 7, 6, 1, 2, '2021-05-18 01:04:13', NULL),
(5, 'Some Title', 'Здесь был косой', 3, 1, 1, 1, '2021-05-19 03:15:47', NULL),
(46, 'Some Title', 'Запись!', 0, 0, 7, 1, '2021-06-04 19:58:29', NULL),
(47, 'Some Title', 'New Zapis', 0, 0, 3, 1, '2021-06-04 19:59:56', NULL),
(48, 'Some Title', 'Новый пост', 0, 0, 5, 1, '2021-06-04 20:13:49', NULL),
(50, 'Some Title', 'Don Drakon', 1, 0, 2, 1, '2021-06-04 20:46:01', NULL),
(56, 'Some Title', 'Ачх', 0, 0, 4, 1, '2021-06-04 22:34:24', NULL),
(59, 'Some Title', 'Мой новый пост', 0, 0, 1, 2, '2021-06-14 02:59:54', NULL),
(60, 'Some Title', 'Ничего нового!', 0, 0, 1, 2, '2021-06-16 03:49:04', NULL),
(65, 'Some Title', 'Директор работает с любым экземпляром строителя, который передаётся ему\n     * клиентским кодом. Таким образом, клиентский код может изменить конечный\n     * тип вновь собираемого продукта.', 3, 0, 12, 12, '2021-06-16 05:30:03', NULL),
(73, 'Some Title', '1123456', 2, 0, 2, 12, '2021-06-16 08:26:58', NULL),
(74, 'Some Title', 'Ну че?', 2, 0, 2, 12, '2021-06-16 08:28:14', NULL),
(75, 'Some Title', 'Log post', 0, 0, 13, 13, '2021-06-17 05:03:17', NULL),
(76, 'Some Title', 'Это пост', 2, 0, 4, 12, '2021-06-21 00:39:42', NULL),
(78, 'Some Title', 'Сообщение', 1, 0, 13, 13, '2021-06-26 20:26:18', NULL),
(83, 'Some Title', 'Hello', 1, 0, 13, 12, '2021-07-02 01:43:55', NULL),
(91, 'Some Title', 'Дым', 0, 0, 6, 12, '2021-07-07 02:50:56', NULL),
(92, 'Some Title', 'Post 1', 0, 0, 12, 12, '2021-07-08 02:37:09', '2021-07-08 02:41:51'),
(93, 'Some Title', 'Post2', 0, 0, 12, 12, '2021-07-08 02:40:09', '2021-07-08 02:41:57'),
(94, 'Some Title', 'Post 3', 0, 0, 12, 12, '2021-07-08 02:40:15', '2021-07-08 02:42:06'),
(95, 'Some Title', 'Post 4', 0, 0, 12, 12, '2021-07-08 02:40:22', '2021-07-08 02:42:14'),
(96, 'Some Title', ' Post 5', 0, 0, 12, 12, '2021-07-08 02:40:45', '2021-07-08 02:42:20'),
(97, 'Some Title', 'Russia', 1, 0, 14, 12, '2021-07-10 23:48:34', NULL),
(98, 'Some Title', 'Post 6', 0, 0, 12, 12, '2021-07-10 23:49:05', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_registered` date NOT NULL,
  `gender` int NOT NULL,
  `about_me` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `date_birth` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `lastname`, `email`, `password`, `date_registered`, `gender`, `about_me`, `image`, `created_at`, `updated_at`, `date_birth`, `status`) VALUES
(1, 'Sem', 'Fisher', 'examle@mail.com', '12345678', '2021-05-11', 1, 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia', '/images/user_gallery/unnamed__7f3092a0.jpg', '2021-05-12 17:15:25', '2021-05-18 17:15:25', '2021-05-12', 0),
(2, 'Don', 'Drakon', 'name@mail.ru', '12345678', '2021-05-11', 0, 'Yf[', '/images/user_gallery/unnamed__7f3092a0.jpg', '2021-05-17 01:00:25', '2021-05-25 01:00:25', '2021-05-03', 0),
(3, 'Max', 'Power', 'nabe@mail.com', '12345678', '2021-05-12', 1, 'Name', '/images/user_gallery/unnamed__7f3092a0.jpg', '2021-05-21 07:40:07', '2021-05-21 07:40:10', '2021-05-19', 0),
(4, 'Lol', 'This', 'mail@mail.com', '12345678', '2021-05-10', 0, 'thhioj', '/images/user_gallery/unnamed__7f3092a0.jpg', '2021-05-21 09:30:20', '2021-03-21 09:30:25', '2013-05-22', 0),
(5, 'sMike', 'Mike', 'Tison@mail.com', '12315', '2021-05-12', 1, 'sasa', '/images/user_gallery/unnamed__7f3092a0.jpg', '2021-05-21 10:03:52', '2021-05-21 10:03:57', '2015-05-13', 0),
(6, 'Jem', 'NoFish', 'gshj@hajuh.com', '12345678', '2021-06-11', 0, 'ewewdcwe', '/images/user_gallery/unnamed__7f3092a0.jpg', '2021-06-01 15:45:19', '2021-06-01 15:45:23', '2021-06-01', 0),
(7, 'SeM', 'EAlook', 'acfs@hasuj.com', '11321321', '2021-06-08', 1, 'dffddf', '/images/user_gallery/unnamed__7f3092a0.jpg', '2021-06-01 20:08:01', '2021-06-01 20:08:03', '2021-06-01', 0),
(12, 'Ivan', 'Ivan', 'mail-mail@yandex.ru', '$2y$10$V46UC951lJBcrAa/YnhjSu2.wtuHaVYNaDXu58pCNXjlRnMf5bMem', '2021-06-15', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur', '/images/user_gallery/ava__e5a45075.jpg', '2021-06-15 22:15:00', '2021-07-14 22:58:07', '1991-10-18', 1),
(13, 'Jane', 'Doe', 'mail@gmail.com', '$2y$10$rIQotGx0YNjeaabrMxNxguXIPgGZlV/8i1o5ygr7qYG7AydIIlGxe', '2021-06-16', 0, 'About me', '/images/user_gallery/mult-ava-instagram-58__bd18adec.jpg', '2021-06-16 09:16:11', '2021-07-14 22:59:40', '1959-07-10', 1),
(14, 'laravel', 'Symfony', 'Fedor@mail.com', '$2y$10$mckwoIljknM.jivsq7BPjudNHj7cfUw8ygBkFw0Nbyf2QTfIyCGgu', '2021-07-07', 1, 'null', '/images/user_gallery/57__7903c9a8.jpg', '2021-07-07 02:58:44', '2021-07-07 03:01:19', '1965-06-18', 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD UNIQUE KEY `comments_id_uindex` (`id`),
  ADD KEY `comments_posts_id_fk` (`post_id`),
  ADD KEY `comments_users_id_fk` (`author_id`);

--
-- Индексы таблицы `comments_image`
--
ALTER TABLE `comments_image`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `dialogs`
--
ALTER TABLE `dialogs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `images_users_id_fk` (`user_id`);

--
-- Индексы таблицы `invites`
--
ALTER TABLE `invites`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_dialogs_id_fk` (`dialog_id`),
  ADD KEY `messages_users_id_fk` (`user_id`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posts_users__fk` (`user_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `albums`
--
ALTER TABLE `albums`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT для таблицы `comments_image`
--
ALTER TABLE `comments_image`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT для таблицы `dialogs`
--
ALTER TABLE `dialogs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT для таблицы `images`
--
ALTER TABLE `images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT для таблицы `invites`
--
ALTER TABLE `invites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT для таблицы `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_posts_id_fk` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `comments_users_id_fk` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_dialogs_id_fk` FOREIGN KEY (`dialog_id`) REFERENCES `dialogs` (`id`),
  ADD CONSTRAINT `messages_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_users__fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
