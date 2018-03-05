CREATE IF NOT EXISTS DATABASE doingsdone;

USE doingsdone;

CREATE TABLE projects (
id INT AUTO_INCREMENT PRIMARY KEY,
project_name VARCHAR(40) NOT NULL,
author_id INT NOT NULL
);

CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
date_registr DATETIME NOT NULL,
nick VARCHAR(30) NOT NULL,
email VARCHAR(128) NOT NULL,
password VARCHAR(64) NOT NULL,
contacts TEXT
);

CREATE TABLE tasks (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(50) NOT NULL,
date_done CHAR(25),
date_compl DATE,
date_created DATE NOT NULL,
image VARCHAR(40),
author_id INT NOT NULL,
project_id INT NOT NULL,
completed TINYINT DEFAULT 0
);

CREATE UNIQUE INDEX email ON users(email);
CREATE UNIQUE INDEX password ON users(password);
CREATE UNIQUE INDEX project_name ON projects(project_name);
CREATE INDEX auhtor_id ON projects(author_id);
CREATE INDEX name ON tasks(name);
CREATE INDEX author_id ON tasks(author_id);
CREATE INDEX project_id ON tasks(project_id);
CREATE INDEX date_done ON tasks(date_done);
