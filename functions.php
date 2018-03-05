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
    $content = "";
    if (file_exists($template)) {
        ob_start();
        extract($data);
        require_once($template);
        $content = ob_get_clean();  
    }
    return $content;
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
            "image" => $row["image"],
            "completed" => $row["completed"],
        ]];
    }
    return $arrayTasks;
}

function selectTasksOnFilter($db, $sql) {
    $result = mysqli_query($db, $sql);
    if (!$result) {
        die("Ошибка в SQL при извлечении данных: " . mysqli_error($db));
    }
    $tasks = createArrayTasks($result);
    return $tasks;
}
