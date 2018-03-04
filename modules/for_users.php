<?php
    if (isset($_GET["show_completed"])) {
        if (isset($_COOKIE["showcompl"])) {
            $showCompleted = ($_COOKIE["showcompl"]) == 1 ? 0 : 1;
        }
        setcookie("showcompl", $showCompleted);
        header("Location: /");
    }
    
    $completed = (isset($_COOKIE["showcompl"])) ? $_COOKIE["showcompl"] : "0";
    
    $query = "SELECT id, project_name FROM projects";
    $result = mysqli_query($dbc, $query);
    while ($row = mysqli_fetch_array($result)) {
        $categories += [$row["id"] => $row["project_name"]];
    }
    // подготовка запроса
    $queryTasks = "SELECT id, name, project_id, date_done, completed, image
        FROM tasks
        WHERE author_id = (SELECT id FROM users WHERE email = '" . $_SESSION["user"]["email"]  . "')";
    
    // общее кол-во задач у пользователя (для меню проектов)
    $allTasks = createArrayTasks(mysqli_query($dbc, $queryTasks));
    
    if ($completed === "0") {
        $queryTasks = $queryTasks . " AND completed = 0";
    }
    // выборка и сборка запроса
    if (isset($_GET["all"])) {
        $tasks = selectTasksOnFilter($dbc, $queryTasks);
        $content = renderTemplate("templates/index.php", ["completed" => $completed, "date" => $currentDate, "tasks" => $tasks]);
    } elseif (isset($_GET["on_day"])) {
        $tasks = selectTasksOnFilter($dbc, $queryTasks . " AND date_done = '$currentDate'");
        $content = renderTemplate("templates/index.php", ["completed" => $completed, "date" => $currentDate, "tasks" => $tasks]);
    } elseif (isset($_GET["on_tomorrow"])) {
        $tasks = selectTasksOnFilter($dbc, $queryTasks . " AND date_done = '" . date("Y-m-d", strtotime("+1 day")) . "'");
        $content = renderTemplate("templates/index.php", ["completed" => $completed, "date" => $currentDate, "tasks" => $tasks]);
    } elseif (isset($_GET["not_done"])) {
        $tasks = selectTasksOnFilter($dbc, $queryTasks . " AND date_done < '$currentDate'");
        $content = renderTemplate("templates/index.php", ["completed" => $completed, "date" => $currentDate, "tasks" => $tasks]);
    } elseif (isset($_GET["task_id"])) {
        $check = "SELECT completed FROM tasks WHERE id = '" . $_GET["task_id"] . "'";
        $result = mysqli_query($dbc, $check);
        $completed = mysqli_fetch_array($result);
        $query = ($completed["completed"] === '0') ?
            "UPDATE tasks SET completed = 1, date_compl = NOW() WHERE id = '" . $_GET["task_id"] . "'" : 
            "UPDATE tasks SET completed = 0, date_compl = NULL WHERE id = '" . $_GET["task_id"] . "'";
        mysqli_query($dbc, $query);
        header("location: /");
    } elseif (isset($_GET["project_id"])) {
        $categoryId = $_GET["project_id"];
        $checkQuery = $queryTasks . " AND project_id = '$categoryId'";
        $resultCheck = mysqli_query($dbc, $checkQuery);
        if (mysqli_num_rows($resultCheck) === 0) {
            http_response_code(404);
            $content = "Категория не найдена";
        }
        $queryTasks = $queryTasks . " AND project_id = '$categoryId'
            AND author_id = (SELECT id FROM users WHERE email = '" . $_SESSION["user"]["email"]  . "')";
        $tasks = selectTasksOnFilter($dbc, $queryTasks);
        $content = renderTemplate("templates/index.php", ["completed" => $completed, "date" => $currentDate, "tasks" => $tasks]);
    } elseif (isset($_GET["add"])) {
        // модальные окна и добавление данных
        $content = renderTemplate("templates/addtask.php", ["categories" => $categories]);
        $className = "overlay";
    } elseif (isset($_GET["add_project"])) {
        $content = renderTemplate("templates/addproject.php", []);
        $className = "overlay";
    } elseif (isset($_POST["add"])) {
        $task = $_POST;
        if (empty($task["name"])) {
            $addErrors += ["name" => "Заполните это поле"];
        }
        if (!empty($task["date"]) && time() > strtotime($task["date"])) {
            $addErrors += ["date" => "Неверная дата"];
        }
        if (empty($task["project"])) {
            $addErrors += ["project" => "Укажите категорию"];
        } else {
            $project = mysqli_real_escape_string($dbc, $task["project"]);
            $checkQuery = "SELECT * FROM projects WHERE project_name = '$project'";
            $check = mysqli_query($dbc, $checkQuery);
            if (mysqli_num_rows($check) === 0) {
                $errors += ["project" => "Данного проекта не существует"];
            }
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
            $addErrors += ["project" => "Укажите категорию"];
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
                $content = "Произошла ошибка при добавлении задачи: " . mysqli_error($dbc);
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
                $content = "Произошла ошибка при добавлении проекта: " . mysqli_error($dbc);
            } else {
                header("Location: /");
            }
        }
    } else {
        $userTasks = mysqli_query($dbc, $queryTasks);
        $tasks = createArrayTasks($userTasks);
        $content = renderTemplate("templates/index.php", ["completed" => $completed, "date" => $currentDate, "tasks" => $tasks]);
    }
    
    print(renderTemplate("templates/layout.php", ["className" => $className, "categories" => $categories, "tasks" => $allTasks, "content" => $content, "title" => $siteName]));
