USE doingsdone;
-- Заполнение таблицы projects
INSERT INTO users SET 
  date_registr = NOW(), 
  nick = "Игнат", 
  email = "ignat.v@gmail.com", 
  password = "$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka";
INSERT INTO users SET 
  date_registr = NOW(), 
  nick = "Леночка", 
  email = "kitty_93@li.ru", 
  password = "$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa", 
  contacts = "Телефон: 7775556";
INSERT INTO users SET 
  date_registr = NOW(), 
  nick = "Руслан", 
  email = "warrior07@mail.ru", 
  password = "$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW";
-- Заполнение таблицы users       
INSERT INTO projects SET 
  project_name = "Входящие", 
  author_id = (SELECT id FROM users WHERE email = "warrior07@mail.ru");
INSERT INTO projects SET 
  project_name = "Авто", 
  author_id = (SELECT id FROM users WHERE email = "warrior07@mail.ru");
INSERT INTO projects SET 
  project_name = "Авто", 
  author_id = (SELECT id FROM users WHERE email = "ignat.v@gmail.com");
INSERT INTO projects SET 
  project_name = "Домашние дела", 
  author_id = (SELECT id FROM users WHERE email = "kitty_93@li.ru");
INSERT INTO projects SET 
  project_name = "Учеба", 
  author_id = (SELECT id FROM users WHERE email = "kitty_93@li.ru");
INSERT INTO projects SET 
  project_name = "Работа", 
  author_id = (SELECT id FROM users WHERE email = "kitty_93@li.ru");
INSERT INTO projects SET 
  project_name = "Работа", 
  author_id = (SELECT id FROM users WHERE email = "ignat.v@gmail.com");
INSERT INTO projects SET 
  project_name = "Домашние дела", 
  author_id = (SELECT id FROM users WHERE email = "ignat.v@gmail.com");
INSERT INTO projects SET 
  project_name = "Учеба", 
  author_id = (SELECT id FROM users WHERE email = "ignat.v@gmail.com");
INSERT INTO projects SET 
  project_name = "Входящие", 
  author_id = (SELECT id FROM users WHERE email = "ignat.v@gmail.com");
-- Заполнение таблицы tasks
INSERT INTO tasks SET 
  name = "Собеседование в IT компании", 
  date_done = "2018-06-01", 
  date_created = NOW(), 
  author_id = (SELECT id FROM users WHERE email = "warrior07@mail.ru"), 
  project_id = (SELECT id FROM projects WHERE project_name = "Входящие" AND 
    author_id = (SELECT id FROM users WHERE email = "warrior07@mail.ru"));
INSERT INTO tasks SET 
  name = "Выполнить тестовое задание", 
  date_done = "2018-05-25", 
  date_created = NOW(), 
  author_id = (SELECT id FROM users WHERE email = "ignat.v@gmail.com"), 
  project_id = (SELECT id FROM projects WHERE project_name = "Авто" AND 
    author_id = (SELECT id FROM users WHERE email = "ignat.v@gmail.com"));
INSERT INTO tasks SET 
  name = "Сделать задание первого раздела", 
  date_done = "2018-05-30", 
  date_created = NOW(), 
  author_id = (SELECT id FROM users WHERE email = "ignat.v@gmail.com"), 
  project_id = (SELECT id FROM projects WHERE project_name = "Учеба" AND 
    author_id = (SELECT id FROM users WHERE email = "ignat.v@gmail.com"));
INSERT INTO tasks SET 
  name = "Встреча с другом", 
  date_done = "2018-02-08", 
  date_compl = "2018-03-06", 
  date_created = NOW(), 
  author_id = (SELECT id FROM users WHERE email = "kitty_93@li.ru"), 
  project_id = (SELECT id FROM projects WHERE project_name = "Работа" AND 
    author_id = (SELECT id FROM users WHERE email = "kitty_93@li.ru")), 
  completed = 1;
INSERT INTO tasks SET 
  name = "Купить корм для кота", 
  date_done = "2018-03-01", 
  date_created = NOW(), 
  author_id = (SELECT id FROM users WHERE email = "kitty_93@li.ru"), 
  project_id = (SELECT id FROM projects WHERE project_name = "Домашние дела" AND 
    author_id = (SELECT id FROM users WHERE email = "kitty_93@li.ru"));
INSERT INTO tasks SET 
  name = "Заказать пиццу", 
  date_done = "2018-03-14", 
  date_created = NOW(), 
  author_id = (SELECT id FROM users WHERE email = "ignat.v@gmail.com"), 
  project_id = (SELECT id FROM projects WHERE project_name = "Домашние дела" AND 
    author_id = (SELECT id FROM users WHERE email = "ignat.v@gmail.com"));
INSERT INTO tasks SET 
  name = "Заказать пиццу", 
  date_done = "2018-03-14", 
  date_created = NOW(), 
  author_id = (SELECT id FROM users WHERE email = "kitty_93@li.ru"), 
  project_id = (SELECT id FROM projects WHERE project_name = "Домашние дела" AND 
    author_id = (SELECT id FROM users WHERE email = "kitty_93@li.ru"));
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
