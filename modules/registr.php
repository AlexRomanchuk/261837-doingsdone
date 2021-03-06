<?php
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
        $checkEmail = mysqli_real_escape_string($dbc, $newUser["email"]);
        $query = "SELECT * FROM users WHERE `email` = '$checkEmail'";
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
        // Регистрируем пользователя и добавляем ему проект "Входящие" как первый проект, 
        // Иначе он не сможет сразу добавить задачи (ему потребуется добавить проект), 
        // так как в поле "Проект" окна "Добавить задачу" не будет никаких проектов.
        mysqli_query($dbc, "START TRANSACTION");
        $registr = mysqli_query($dbc, "INSERT INTO users SET
            `email` = '$email',
            `nick` = '$name',
            `password` = '$password',
            `date_registr` = NOW(),
            `contacts` = '$contacts'");
        $addProject = mysqli_query($dbc, "INSERT INTO projects SET 
            `project_name` = 'Входящие',
            `author_id` = (SELECT id FROM users WHERE email = '$email')");
        if ($registr && $addProject) {
            mysqli_query($dbc, "COMMIT");
            header("Location: /?login");
        } else {
            mysqli_query($dbc, "ROLLBACK");
            $content = "Произошла ошибка при регистрации пользователя";
        }
    }
