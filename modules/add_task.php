<?php
    $task = $_POST;
    if (empty($task["name"])) {
        $addErrors += ["name" => "Заполните это поле"];
    }
    if (empty($task["date"])) {
        $addErrors += ["date" => "Дата не указана"];
    }
    if (!empty($task["date"]) && strtotime($currentDate) > strtotime($task["date"])) {
        $addErrors += ["date" => "Неверная дата"];
    }
    if (empty($task["project"])) {
        $addErrors += ["project" => "Укажите категорию"];
    } else {
        $project = mysqli_real_escape_string($dbc, $task["project"]);
        $checkQuery = "SELECT * FROM projects p
            JOIN users u ON p.author_id = u.id
            WHERE p.project_name = '$project'
            AND u.email = '" . $_SESSION["user"]["email"] . "'";
        $check = mysqli_query($dbc, $checkQuery);
        if (mysqli_num_rows($check) === 0) {
            $addErrors += ["project" => "Данного проекта не существует"];
        }
    }
    
    $path = "";
    $tmpName = "";
    if (isset($_FILES["preview"]["name"])) {
        $fileInfo = pathinfo($_FILES["preview"]["name"]);
        $tmpName = $_FILES["preview"]["tmp_name"];
        if (isset($fileInfo["filename"]) && isset($fileInfo["extension"])) {
            $path = date("Y-m-d-h-i-s") . "-" . $fileInfo["filename"] . "." . $fileInfo["extension"];
        }
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
        $project = mysqli_real_escape_string($dbc, $task["project"]);
        $queryId = "SELECT id FROM users WHERE email = '$email'";
        $authorId = mysqli_fetch_array(mysqli_query($dbc, $queryId));
        $date = mysqli_real_escape_string($dbc, $task["date"]);
        $name = mysqli_real_escape_string($dbc, $task["name"]);
        $queryProjectId = "SELECT id FROM projects 
            WHERE project_name = '$project' AND author_id = '" . $authorId["id"] . "'";
        $projectId = mysqli_fetch_array(mysqli_query($dbc, $queryProjectId));
        $query = "INSERT INTO tasks SET 
            `name` = '$name',
            `date_done` = '$date',
            `project_id` = '" . $projectId["id"] . "',
            `author_id` = '" . $authorId["id"] . "',
            `date_created` = NOW(),
            `image` = '$imagePath'";
        $result = mysqli_query($dbc, $query);
        if (!$result) {
            $content = "Произошла ошибка при добавлении задачи: " . mysqli_error($dbc);
        } else {
            header("Location: /");
        }
    }
