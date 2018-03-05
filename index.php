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
$projectId = "";

session_start();

if (isset($_SESSION["user"])) {
    require_once("modules/for_users.php");
} elseif (isset($_GET["login"])) {
    $content = renderTemplate("templates/auth_form.php", []);
    $className = "overlay";
} elseif (isset($_GET["registration"])) {
    $content = renderTemplate("templates/registr.php", []);
} elseif (isset($_POST["login"])) {
    require_once("modules/auth.php");
} elseif (isset($_POST["registration"])) {
    require_once("modules/registr.php");
} else {
    $content = renderTemplate("templates/guest.php", []);
}

print(renderTemplate("templates/layout.php", ["className" => $className, "content" => $content, "title" => $siteName]));
