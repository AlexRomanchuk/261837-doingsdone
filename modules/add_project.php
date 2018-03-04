<?php
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
