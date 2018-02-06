<?php
function countTasks ($listTasks, $nameTask) {
    $i = 0;
    foreach ($listTasks as $task) {
        if ($task["category"] === $nameTask) {
            $i++;
        }
    }
    if ($nameTask === "Все") {
        $i = count($listTasks);
    }
    return $i;
}

function renderTemplate ($template, $data) {
    $page = "";
    if (file_exists($template)) {
        ob_start();
        extract($data);
        require_once($template);
        $page = ob_get_clean();
        return $page;  
    }
    return $page;
}
