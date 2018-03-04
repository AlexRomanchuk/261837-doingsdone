<?php
require_once("connectdb.php");
require_once("functions.php");

define("SECONS_IN_DAY", 86400);

$siteName = "Дела в порядке";
$currentDate = date("Y-m-d");
$filteredTask = null;
$className = "";
$addErrors = [];
$loginErrors = [
  "email" => [],
  "password" => [],
];
$registrErrors = [
  "name" => [],
  "password" => [],
  "email" => [],
];
$projectErrors = [];
$categories = [];
$tasks = [];

$showCompleted = 1;

session_start();

if (isset($_SESSION["user"])) {
    require_once("modules/for_users.php");
} elseif (isset($_GET["login"])) {
    $content = renderTemplate("templates/auth_form.php", []);
    $className = "overlay";
} elseif (isset($_GET["registration"])) {
    $content = renderTemplate("templates/registr.php", []);
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
    } else {
        $query = "SELECT * FROM users WHERE email = '" . $guest["email"]  . "'";
        $result = mysqli_query($dbc, $query);
        if (mysqli_num_rows($result) === 0) {
            $loginErrors["email"] += ["unknown_user" => "Такого пользователя не существует"];
        } else {
            $user = mysqli_fetch_array($result);
            if (password_verify($guest["password"], $user["password"])) {
                $_SESSION["user"] = $user;
            } else {
                $loginErrors["password"] += ["invalid_password" => "Неверный пароль"];
            }
        }
    }
    if (count($loginErrors["email"]) || count($loginErrors["password"])) {
        $content = renderTemplate("templates/auth_form.php", ["loginErrors" => $loginErrors]);
        $className = "overlay";
	} else {
        header("Location: /");
    }
} elseif (isset($_POST["registration"])) {
    require_once("modules/registr.php");
} else {
    $content = renderTemplate("templates/guest.php", []);
}

print(renderTemplate("templates/layout.php", ["className" => $className, "content" => $content, "title" => $siteName]));
