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
