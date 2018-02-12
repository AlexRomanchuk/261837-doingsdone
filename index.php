<?php
// показывать или нет выполненные задачи

// Нужно ли удалить эту переменную? 
$show_complete_tasks = rand(0, 1);

define("SECONS_IN_DAY", 86400);

$siteName = "Дела в порядке";
$userName = "Константин";
$currentDate = date("d.n.Y");
$filteredTask = null;

$categories = ["Все", "Входящие", "Учеба", "Работа", "Домашние дела", "Авто"];
$tasks = [
    [
     "task" => "Собеседование в IT компании",
     "date" => "01.06.2018",
     "category" => "Работа",
     "completed" => false,
    ],
    [
     "task" => "Выполнить тестовое задание",
     "date" => "25.05.2018",
     "category" => "Работа",
     "completed" => false,
    ],
    [
     "task" => "Сделать задание первого раздела",
     "date" => "21.04.2018",
     "category" => "Учеба",
     "completed" => true,
    ],
    [
     "task" => "Встреча с другом",
     "date" => "08.02.2018",
     "category" => "Входящие",
     "completed" => false,
    ],
    [
     "task" => "Купить корм для кота",
     "date" => "",
     "category" => "Домашние дела",
     "completed" => false,
    ],
    [
     "task" => "Заказать пиццу",
     "date" => "",
     "category" => "Домашние дела",
     "completed" => false,
    ],
];

require_once("functions.php");

if (isset($_GET["add"])) {
    $addForm = renderTemplate ("templates/addtask.php", []);
    print (renderTemplate ("templates/layout.php", ["categories" => $categories, "tasks" => $tasks, "content" => $addForm, "title" => $siteName, "userName" => $userName]));
}

if (isset($_GET["category_id"])) {
    $categoryId = $_GET["category_id"];
    $tasksInCategory = [];
    if (array_key_exists($categoryId, $categories)) {
        foreach ($tasks as $task) {
            if ($task["category"] === $categories[$categoryId]) {
                $filteredTask = $task;
                array_push($tasksInCategory, $filteredTask);
            }
        }
        $content = renderTemplate ("templates/index.php", ["date" => $currentDate, "tasks" => $tasksInCategory, "show_complete_tasks" => $show_complete_tasks]);
    } else {
        http_response_code(404);
        $content = "Категория не найдена";
    }
} else {
    $content = renderTemplate ("templates/index.php", ["date" => $currentDate, "tasks" => $tasks, "show_complete_tasks" => $show_complete_tasks]);
}

print (renderTemplate ("templates/layout.php", ["categories" => $categories, "tasks" => $tasks, "content" => $content, "title" => $siteName, "userName" => $userName]));

?>
