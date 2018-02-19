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
date_done DATETIME NOT NULL,
date_compl DATETIME,
date_created DATETIME NOT NULL,
image VARCHAR(32),
author_id INT NOT NULL,
project_id INT NOT NULL,
completed TINYINT DEFAULT 0
);

CREATE UNIQUE INDEX email ON users(email);
CREATE UNIQUE INDEX password ON users(password);
CREATE UNIQUE INDEX project_name ON projects(project_name);
CREATE INDEX name ON tasks(name);
CREATE INDEX author_id ON tasks(author_id);
CREATE INDEX project_id ON tasks(project_id);
CREATE INDEX date_done ON tasks(date_done);
