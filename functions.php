<?php
function countTasks ($listTasks, $nameTask) {
    $i = 0;
    foreach ($listTasks as $task) {
        if ($task["project"] === $nameTask) {
            $i++;
        }
    }
    if ($nameTask === "Все") {
        $i = count($listTasks);
    }
    return $i;
}

function renderTemplate ($template, $data) {
    if (file_exists($template)) {
        ob_start();
        extract($data);
        require_once($template);
        return ob_get_clean();  
    } else {
        return "";
    }
}

function countDays ($currentDate, $nextDate) {
    return floor((strtotime($nextDate) - strtotime($currentDate)) / SECONS_IN_DAY);
}

function searchByEmail($email, $users) {
    $result = "";
    foreach ($users as $user) {
        if ($user["email"] == $email) {
            $result = $user;
            break;
        }
    }
    return $result;
}
