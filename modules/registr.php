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
