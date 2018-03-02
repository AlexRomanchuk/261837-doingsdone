<?php
function countTasks ($listTasks, $idTask) {
    $i = 0;
    foreach ($listTasks as $task) {
        if ($task["project"] === "$idTask") {
            $i++;
        }
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

function createArrayTasks($result) {
    $arrayTasks = [];
    while ($row = mysqli_fetch_array($result)) {
        $arrayTasks += [$row["id"] => [
            "name" => $row["name"],
            "project" => $row["project_id"],
            "date" => $row["date_done"],
            "completed" => $row["completed"],
        ]];
    }
    return $arrayTasks;
}
