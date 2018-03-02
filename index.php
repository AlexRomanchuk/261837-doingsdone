<?php

require_once("functions.php");
require_once("userdata.php");

define("SECONS_IN_DAY", 86400);

$siteName = "Дела в порядке";
$currentDate = date("d.n.Y");
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

if (isset($_GET["show_completed"])) {
    if (isset($_COOKIE["showcompl"])) {
        $showCompleted = ($_COOKIE["showcompl"]) == 1 ? 0 : 1;
    }
    setcookie("showcompl", $showCompleted);
    header("Location: /");
}

$completed = (isset($_COOKIE["showcompl"])) ? $_COOKIE["showcompl"] : "";

session_start();

$dbc = mysqli_connect("localhost", "root", "", "doingsdone");

if (!$dbc) {
    print("Ошибка подключения к БД:" . mysqli_connect_error());
}

if (isset($_SESSION["user"])) {
    $query = "SELECT id, project_name FROM projects WHERE author_id = (SELECT id FROM users WHERE email = '" . $_SESSION["user"]["email"]  . "')";
    $result = mysqli_query($dbc, $query);
    while ($row = mysqli_fetch_array($result)) {
        $categories += [$row["id"] => $row["project_name"]];
    }
    
    $queryTasks = "SELECT id, name, project_id, DATE_FORMAT(date_done, '%d.%m.%Y') AS date_done, completed
        FROM tasks
        WHERE author_id = (SELECT id FROM users WHERE email = '" . $_SESSION["user"]["email"]  . "')";
    $userTasks = mysqli_query($dbc, $queryTasks);
    $tasks = createArrayTasks($userTasks);
    if (isset($_GET["add"])) {
        $content = renderTemplate("templates/addtask.php", ["categories" => $categories]);
        $className = "overlay";
    } elseif (isset($_GET["add_project"])) {
        $content = renderTemplate("templates/addproject.php", []);
        $className = "overlay";
    } elseif (isset($_GET["project_id"])) {
        $categoryId = $_GET["project_id"];
        $checkQuery = "SELECT * FROM tasks WHERE project_id = '$categoryId'";
        $resultCheck = mysqli_query($dbc, $checkQuery);
        if (mysqli_num_rows($resultCheck) === 0) {
            http_response_code(404);
            $content = "Категория не найдена";
        }
        $showQuery = "SELECT id, name, project_id, DATE_FORMAT(date_done, '%d.%m.%Y') AS date_done, completed
            FROM tasks
            WHERE project_id = '$categoryId'
            AND author_id = (SELECT id FROM users WHERE email = '" . $_SESSION["user"]["email"]  . "')";
        $filteredTask = mysqli_query($dbc, $showQuery);
        $tasksInCategory = createArrayTasks($filteredTask);
        $content = renderTemplate("templates/index.php", ["completed" => $completed, "date" => $currentDate, "tasks" => $tasksInCategory]);
    } elseif (isset($_POST["add"])) {
        $task = $_POST;
        if (empty($task["name"])) {
            $addErrors += ["name" => "Заполните это поле"];
        }
        if (empty($task["project"])) {
            $addErrors += ["project" => "Укажите категорию"];
        }
        if (empty($task["date"])) {
            $addErrors += ["date" => "Дату надо указать"];
        }
        if (isset($_FILES["preview"]["name"])) {
            $tmpName = $_FILES["preview"]["tmp_name"];
            $path = $_FILES["preview"]["name"];
            $fileType = $_FILES["preview"]["type"];
            if ($fileType !== "image/png " && $fileType !== "image/jpeg" && $fileType !== "image/gif" && $fileType !== "") {
                $addErrors += ["preview" => "Недопустимый формат файла"];
            }
        }
    
        if (count($addErrors)) {
            $content = renderTemplate("templates/addtask.php", ["errors" => $addErrors, "categories" => $categories]);
            $className = "overlay";
	    } else {
            $imagePath = "" . $path;
            move_uploaded_file($tmpName, $imagePath);
            $email = $_SESSION["user"]["email"];
            $name = mysqli_real_escape_string($dbc, $task["name"]);
            $project = mysqli_real_escape_string($dbc, $task["project"]);
            $date = mysqli_real_escape_string($dbc, $task["date"]);
            $query = "INSERT INTO tasks SET 
                `name` = '$name',
                `date_done` = '$date',
                `project_id` = (SELECT id FROM projects WHERE project_name = '$project'),
                `author_id` = (SELECT id FROM users WHERE email = '$email'),
                `date_created` = NOW(),
                `image` = '$imagePath'";
            $result = mysqli_query($dbc, $query);
            if (!$result) {
                print("Произошла ошибка при добавлении задачи: " . mysqli_error($dbc));
            } else {
                header("Location: /");
            }
        }
    } elseif (isset($_POST["add_project"])) {
        $newProject = $_POST;
        if (empty($newProject["name"])) {
            $projectErrors += ["name" => "Это поле обязательно к заполнению"];
        } else {
            $project = mysqli_real_escape_string($dbc, $newProject["name"]);
            $checkQuery = "SELECT * FROM projects WHERE project_name = '$project'";
            $check = mysqli_query($dbc, $checkQuery);
            if (mysqli_num_rows($check) !== 0) {
                $projectErrors += ["already_exists_name" => "Такая категория уже существует"];
            }
        }
        
        if (count($projectErrors)) {
            $content = renderTemplate("templates/addproject.php", ["projectErrors" => $projectErrors]);
            $className = "overlay";
        } else {
            $addQuery = "INSERT INTO projects SET project_name = '$project', author_id = (SELECT id FROM users WHERE email = '" . $_SESSION["user"]["email"] . "')";
            $result = mysqli_query($dbc, $addQuery);
            if (!$result) {
                print("Произошла ошибка при добавлении проекта: " . mysqli_error($dbc));
            } else {
                header("Location: /");
            }
        }
    } else {
        $content = renderTemplate("templates/index.php", ["completed" => $completed, "date" => $currentDate, "tasks" => $tasks]);
    }
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
    $newUser = $_POST;
    if (empty($newUser["email"])) {
        $registrErrors["email"] += ["missing_email" => "Введите e-mail"];
    }
    if (empty($newUser["password"])) {
        $registrErrors["password"] += ["missing_password" => "Введите пароль"];
    }
    if (empty($newUser["name"])) {
        $registrErrors["name"] += ["missing_name" => "Введите имя"];
    }
    if (!empty($newUser["email"]) && !filter_var($newUser["email"], FILTER_VALIDATE_EMAIL)) {
        $registrErrors["email"] += ["incorrect_email" => "Введен некорректный e-mail"];
    } else {
        $query = "SELECT * FROM users WHERE `email` = '" . $newUser["email"] . "'";
        $result = mysqli_query($dbc, $query);
        if (mysqli_num_rows($result) !== 0) {
            $registrErrors["email"] += ["already_exists_user" => "Такой пользователь уже существует. Введите другой e-mail"];
        }
    }
        
    if (count($registrErrors["email"]) || count($registrErrors["password"]) || count($registrErrors["name"])) {
        $content = renderTemplate("templates/registr.php", ["registrErrors" => $registrErrors]);
	} else {
        $password = password_hash($newUser["password"], PASSWORD_DEFAULT);
        $email = mysqli_real_escape_string($dbc, $newUser["email"]);
        $name = mysqli_real_escape_string($dbc, $newUser["name"]);
        $contacts = mysqli_real_escape_string($dbc, $newUser["contacts"]);
        $query = "INSERT INTO users SET 
            `email` = '$email',
            `nick` = '$name',
            `password` = '$password',
            `date_registr` = NOW(),
            `contacts` = '$contacts'";
        $result = mysqli_query($dbc, $query);
        if (!$result) {
            print("Произошла ошибка при регистрации пользователя: " . mysqli_error($dbc));
        } else {
            header("Location: /");
        }
    }
} else {
    $content = renderTemplate("templates/guest.php", []);
}

print(renderTemplate("templates/layout.php", ["className" => $className, "categories" => $categories, "tasks" => $tasks, "content" => $content, "title" => $siteName, "userName" => $userName]));
