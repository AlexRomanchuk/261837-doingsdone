<?php
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
        $email = mysqli_real_escape_string($dbc, $guest["email"]);
        $checkQuery = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($dbc, $checkQuery);
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
