<?php
    if (isset($_GET["show_completed"])) {
        if (isset($_COOKIE["showcompl"])) {
            $showCompleted = ($_COOKIE["showcompl"]) == 1 ? 0 : 1;
        }
        setcookie("showcompl", $showCompleted);
        if (isset($_COOKIE["projectId"])) {
            header("location: /?project_id=" . $_COOKIE["projectId"]);
        } else {
            header("Location: /");
        }
    }
    
    if (isset($_GET["project_id"])) {
        $projectId = $_GET["project_id"];
        if (isset($_COOKIE["projectId"])) {
            $projectId = ($_COOKIE["projectId"]) == $_GET["project_id"] ? "" : $_GET["project_id"];
        }
        setcookie("projectId", $projectId);
    }
    
    $completed = (isset($_COOKIE["showcompl"])) ? $_COOKIE["showcompl"] : "0";
    $projectId = (isset($_COOKIE["projectId"])) ? $_COOKIE["projectId"] : "";
    
    // Выбор проектов пользователя, "Входящие" - общая для всех
    $query = "SELECT id, project_name 
        FROM projects 
        WHERE author_id = (SELECT id FROM users WHERE email = '" . $_SESSION["user"]["email"] . "')
        OR project_name = 'Входящие'";
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
        if (isset($_COOKIE["projectId"])) {
            $queryTasks = $queryTasks . " AND project_id = '" . $_COOKIE["projectId"] ."'";
        }
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
        if (isset($_COOKIE["projectId"])) {
            header("location: /?project_id=" . $_COOKIE["projectId"]);
        } else {
            header("Location: /");
        }
        
    } elseif (isset($_GET["project_id"])) {
        $categoryId = $_GET["project_id"];
        $checkQuery = "SELECT * FROM projects WHERE id = '$categoryId'";
        $resultCheck = mysqli_query($dbc, $checkQuery);
        if (mysqli_num_rows($resultCheck) === 0) {
            http_response_code(404);
            $content = "Категория не найдена";
        } else {
            $queryTasks = $queryTasks . " AND project_id = '$categoryId'";
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
        require_once("modules/add_task.php");
    } elseif (isset($_POST["add_project"])) {
        require_once("modules/add_project.php");
    } else {
        $userTasks = mysqli_query($dbc, $queryTasks);
        $tasks = createArrayTasks($userTasks);
        $content = renderTemplate("templates/index.php", ["completed" => $completed, "date" => $currentDate, "tasks" => $tasks]);
    }
    
    print(renderTemplate("templates/layout.php", ["projectId" => $projectId, "className" => $className, "categories" => $categories, "tasks" => $allTasks, "content" => $content, "title" => $siteName]));
