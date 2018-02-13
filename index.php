<?php
// показывать или нет выполненные задачи 
require_once("functions.php");
$show_complete_tasks = rand(0, 1);

define("SECONS_IN_DAY", 86400);

$siteName = "Дела в порядке";
$userName = "Константин";
$currentDate = date("d.n.Y");
$filteredTask = null;
$className = "";
$errors = [];

$categories = ["Все", "Входящие", "Учеба", "Работа", "Домашние дела", "Авто"];
$tasks = [
    [
     "name" => "Собеседование в IT компании",
     "date" => "01.06.2018",
     "project" => "Работа",
     "completed" => false,
    ],
    [
     "name" => "Выполнить тестовое задание",
     "date" => "25.05.2018",
     "project" => "Работа",
     "completed" => false,
    ],
    [
     "name" => "Сделать задание первого раздела",
     "date" => "21.04.2018",
     "project" => "Учеба",
     "completed" => true,
    ],
    [
     "name" => "Встреча с другом",
     "date" => "08.02.2018",
     "project" => "Входящие",
     "completed" => false,
    ],
    [
     "name" => "Купить корм для кота",
     "date" => "",
     "project" => "Домашние дела",
     "completed" => false,
    ],
    [
     "name" => "Заказать пиццу",
     "date" => "",
     "project" => "Домашние дела",
     "completed" => false,
    ],
];



if (isset($_GET["add"])) {
    $content = renderTemplate ("templates/addtask.php", ["categories" => $categories]);
    $className = "overlay";
} elseif (isset($_GET["project_id"])) {
    $categoryId = $_GET["project_id"];
    $tasksInCategory = [];
    if (array_key_exists($categoryId, $categories)) {
        foreach ($tasks as $task) {
            if ($task["project"] === $categories[$categoryId]) {
                $filteredTask = $task;
                array_push($tasksInCategory, $filteredTask);
            }
        }
        $content = renderTemplate ("templates/index.php", ["date" => $currentDate, "tasks" => $tasksInCategory, "show_complete_tasks" => $show_complete_tasks]);
    } else {
        http_response_code(404);
        $content = "Категория не найдена";
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    $task = $_POST;
    if (empty($task["name"])) {
        $errors += ["name" => "Заполните это поле"];
    }
    if (empty($task["project"])) {
        $errors += ["project" => "Укажите категорию"];
    }
    if (empty($task["date"])) {
        $errors += ["date" => "Дату надо указать"];
    }
    
    if (isset($_FILES["preview"]["name"])) {
        $tmpName = $_FILES["preview"]["tmp_name"];
        $path = $_FILES["preview"]["name"];
        move_uploaded_file($tmpName, "" . $path);
        $task["preview"] = $path;
    }
    
    if (count($errors)) {
        $content = renderTemplate ("templates/addtask.php", ["errors" => $errors, "categories" => $categories]);
        $className = "overlay";
	} else {
        array_unshift($tasks, $task);
        $content = renderTemplate ("templates/index.php", ["date" => $currentDate, "tasks" => $tasks, "show_complete_tasks" => $show_complete_tasks]);
    }
} else {
    $content = renderTemplate ("templates/index.php", ["date" => $currentDate, "tasks" => $tasks, "show_complete_tasks" => $show_complete_tasks]);
}

print (renderTemplate ("templates/layout.php", ["className" => $className, "categories" => $categories, "tasks" => $tasks, "content" => $content, "title" => $siteName, "userName" => $userName]));
?>
