USE doingsdone;
-- Заполнение таблицы projects
INSERT INTO users VALUES (NULL, NOW(), "Игнат", "ignat.v@gmail.com", "$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka", ""),
                         (NULL, NOW(), "Леночка", "kitty_93@li.ru", "$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa", "Телефон: 7775556"),
                         (NULL, NOW(), "Руслан", "warrior07@mail.ru", "$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgW", "");
-- Заполнение таблицы users       
INSERT INTO projects VALUES (NULL, "Входящие", (SELECT id FROM users WHERE email = "warrior07@mail.ru")),
                            (NULL, "Авто", (SELECT id FROM users WHERE email = "ignat.v@gmail.com")),
                            (NULL, "Домашние дела", (SELECT id FROM users WHERE email = "kitty_93@li.ru")),
                            (NULL, "Работа", (SELECT id FROM users WHERE email = "kitty_93@li.ru")),
                            (NULL, "Учеба", (SELECT id FROM users WHERE email = "ignat.v@gmail.com"));
-- Заполнение таблицы tasks
INSERT INTO tasks VALUES (NULL, "Собеседование в IT компании", "2018-06-01", NULL, NOW(), "", (SELECT id FROM users WHERE email = "warrior07@mail.ru"), (SELECT id FROM projects WHERE project_name = "Работа"), 0),
                         (NULL, "Выполнить тестовое задание", "2018-05-25", NULL, NOW(), "", (SELECT id FROM users WHERE email = "ignat.v@gmail.com"), (SELECT id FROM projects WHERE project_name = "Работа"), 0),
                         (NULL, "Сделать задание первого раздела", "2018-05-30", NULL, NOW(), "", (SELECT id FROM users WHERE email = "kitty_93@li.ru"), (SELECT id FROM projects WHERE project_name = "Учеба"), 0),
                         (NULL, "Встреча с другом", "2018-02-08", "2018-03-06", NOW(), "", (SELECT id FROM users WHERE email = "kitty_93@li.ru"), (SELECT id FROM projects WHERE project_name = "Входящие"), 1),
                         (NULL, "Купить корм для кота", "2018-03-01", NULL, NOW(), "", (SELECT id FROM users WHERE email = "ignat.v@gmail.com"), (SELECT id FROM projects WHERE project_name = "Домашние дела"), 0),
                         (NULL, "Заказать пиццу", "2018-03-14", NULL, NOW(), "", (SELECT id FROM users WHERE email = "kitty_93@li.ru"), (SELECT id FROM projects WHERE project_name = "Домашние дела"), 0);
-- Получить список из всех проектов для одного пользователя, id = 2 => "Леночка"
SELECT * FROM tasks t JOIN users u ON t.author_id = u.id WHERE t.author_id = 2;
-- Получить список из всех проектов для одного проекта, id = 3 => "Домашние дела"
SELECT * FROM tasks t JOIN projects p ON t.project_id = p.id WHERE t.project_id = 3;
-- Пометить задачу как выполненную по ID
UPDATE tasks SET completed = 1, date_compl = NOW() WHERE id = 3;
-- Получить задачи до звтрашнего дня
SELECT * FROM tasks WHERE (TIMESTAMP(date_done) - TIMESTAMP(NOW()) <= 86400);
-- Обновить имя задачи по идентификатору
UPDATE tasks SET name = "Найти корм в магазине" WHERE id = 5;
