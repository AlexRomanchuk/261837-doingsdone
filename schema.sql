CREATE DATABASE IF NOT EXISTS doingsdone;

USE doingsdone;

CREATE TABLE IF NOT EXISTS projects (
id INT AUTO_INCREMENT PRIMARY KEY,
project_name VARCHAR(40) NOT NULL,
author_id INT NOT NULL
);

CREATE TABLE IF NOT EXISTS users (
id INT AUTO_INCREMENT PRIMARY KEY,
date_registr DATETIME NOT NULL,
nick VARCHAR(30) NOT NULL,
email VARCHAR(128) NOT NULL,
password VARCHAR(64) NOT NULL,
contacts TEXT
);

CREATE TABLE IF NOT EXISTS tasks (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(50) NOT NULL,
date_done CHAR(25),
date_compl DATETIME,
date_created DATETIME NOT NULL,
image VARCHAR(40),
author_id INT NOT NULL,
project_id INT NOT NULL,
completed TINYINT DEFAULT 0
);

CREATE UNIQUE INDEX email ON users(email);
CREATE UNIQUE INDEX password ON users(password);
CREATE INDEX author_id ON projects(author_id);
CREATE INDEX name ON tasks(name);
CREATE INDEX author_id ON tasks(author_id);
CREATE INDEX project_id ON tasks(project_id);
ALTER TABLE projects ADD FOREIGN KEY fk_p_author_id (author_id) REFERENCES users (id);
ALTER TABLE tasks ADD FOREIGN KEY fk_project_id (project_id) REFERENCES projects (id);
ALTER TABLE tasks ADD FOREIGN KEY fk_t_author_id (author_id) REFERENCES users (id);
