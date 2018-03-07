<?php
    $newProject = $_POST;
    if (empty($newProject["name"])) {
        $projectErrors += ["name" => "Это поле обязательно к заполнению"];
    } else {
        $project = mysqli_real_escape_string($dbc, $newProject["name"]);
        $checkQuery = "SELECT * FROM projects p 
            JOIN users u ON p.author_id = u.id 
            WHERE p.project_name = '$project' 
            AND u.email = '" . $_SESSION["user"]["email"] . "'";
        $check = mysqli_query($dbc, $checkQuery);
        if (mysqli_num_rows($check) !== 0) {
            $projectErrors += ["already_exists_name" => "Такой проект у вас уже существует"];
        }
    }
        
    if (count($projectErrors)) {
        $content = renderTemplate("templates/addproject.php", ["projectErrors" => $projectErrors]);
        $className = "overlay";
    } else {
        $addQuery = "INSERT INTO projects SET 
            `project_name` = '$project', 
            `author_id` = (SELECT id FROM users WHERE email = '" . $_SESSION["user"]["email"] . "')";
        $result = mysqli_query($dbc, $addQuery);
        if (!$result) {
            $content = "Произошла ошибка при добавлении проекта: " . mysqli_error($dbc);
        } else {
            header("Location: /");
        }
    }
