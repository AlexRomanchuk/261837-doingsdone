<?php
define("HOST", "localhost");
define("USER", "root");
define("PASSWORD", "");
define("DATABASE", "doingsdone");

$dbc = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

if (!$dbc) {
    die("Ошибка подключения к БД:" . mysqli_connect_error());
}
