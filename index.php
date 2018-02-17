<?php
// показывать или нет выполненные задачи

require_once("functions.php");
require_once("userdata.php");

define("SECONS_IN_DAY", 86400);

$siteName = "Дела в порядке";
$userName = "Константин";
$currentDate = date("d.n.Y");
$filteredTask = null;
$className = "";
$errors = [];
$loginErrors = [
  "email" => [],
  "password" => [],
];

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

$showCompleted = 1;

if (isset($_GET["show_completed"])) {
    if (isset($_COOKIE["showcompl"])) {
        $showCompleted = ($_COOKIE["showcompl"]) == 1 ? 0 : 1;
    }
    setcookie("showcompl", $showCompleted);
    header("Location: /");
}

$completed = (isset($_COOKIE["showcompl"])) ? $_COOKIE["showcompl"] : "";

session_start();

if (isset($_SESSION["user"])) {
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
        $content = renderTemplate("templates/index.php", ["completed" => $completed, "date" => $currentDate, "tasks" => $tasksInCategory]);
        } else {
            http_response_code(404);
            $content = "Категория не найдена";
        }
    } elseif (isset($_POST["add"])) {
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
            $fileType = $_FILES["preview"]["type"];
            if ($fileType !== "image/png " && $fileType !== "image/jpeg" && $fileType !== "image/gif" && $fileType !== "") {
                $errors += ["preview" => "Недопустимый формат файла"];
            }
        }
    
        if (count($errors)) {
            $content = renderTemplate ("templates/addtask.php", ["errors" => $errors, "categories" => $categories]);
            $className = "overlay";
	    } else {
            move_uploaded_file($tmpName, "" . $path);
            $task["preview"] = $path;
            array_unshift($tasks, $task);
            $content = renderTemplate("templates/index.php", ["completed" => $completed, "date" => $currentDate, "tasks" => $tasks]);
        }
    } else {
        $content = renderTemplate("templates/index.php", ["completed" => $completed, "date" => $currentDate, "tasks" => $tasks]);
    }
} elseif (isset($_GET["login"])) {
    $content = renderTemplate ("templates/auth_form.php", []);
    $className = "overlay";
} elseif (isset($_POST["login"])) {
    $guest = $_POST;
    if (empty($guest["email"])) {
        $loginErrors["email"] += ["missing_email" => "Заполните это поле"];
    }
    if (empty($guest["password"])) {
        $loginErrors["password"] += ["missing_password" => "Введите пароль"];
    }
    if (!filter_var($guest["email"], FILTER_VALIDATE_EMAIL)) {
        $loginErrors["email"] += ["incorrect_email" => "Введен некорректный e-mail"];
    }
    if ($user = searchByEmail($guest["email"], $users)) {
        if (password_verify($guest["password"], $user["password"])) {
            $_SESSION["user"] = $user;
        } else {
            $loginErrors["password"] += ["invalid_password" => "Неверный пароль"];
        }
    } else {
        $loginErrors["email"] += ["unknown_user" => "Такого пользователя не существует"];
    }
        
    if (count($loginErrors["email"]) || count($loginErrors["password"])) {
        $content = renderTemplate("templates/auth_form.php", ["loginErrors" => $loginErrors]);
        $className = "overlay";
	} else {
        header("Location: /");
    }
} else {
    $content = renderTemplate ("templates/guest.php", []);
}

print (renderTemplate ("templates/layout.php", ["className" => $className, "categories" => $categories, "tasks" => $tasks, "content" => $content, "title" => $siteName, "userName" => $userName]));
