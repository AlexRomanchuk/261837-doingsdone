<?php
// показывать или нет выполненные задачи

// Нужно ли удалить эту переменную? 
$show_complete_tasks = rand(0, 1);

$siteName = "Дела в порядке";
$userName = "Константин";

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
     "date" => "22.04.2018",
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

function countTasks ($listTasks, $nameTask) {
    $i = 0;
    foreach ($listTasks as $task) {
        if ($task["category"] === $nameTask) {
            $i++;
        }
    }
    if ($nameTask === "Все") {
        $i = count($listTasks);
    }
    return $i;
}

require_once("functions.php");

$content = renderTemplate ("templates/index.php", ["categories" => $categories, "tasks" => $tasks, "show_complete_tasks" => $show_complete_tasks]);

print (renderTemplate ("templates/layout.php", ["categories" => $categories, "tasks" => $tasks, "content" => $content, "title" => $siteName, "userName" => $userName]));

?>
