<?php
/**
* Подсчет задач по ID проекта
* @param $listsTasks Массив задач
* @param $idProject ID проекта
* @return $i Int Число проектов для пункта меню
*/
function countTasks ($listTasks, $idProject) {
    $i = 0;
    foreach ($listTasks as $task) {
        if ($task["project"] === "$idProject") {
            $i++;
        }
    }
    return $i;
}
/**
* Создание контента из шаблона
* @param $template Файл шаблона
* @param $data Данные в ассоциативном массиве
* @return $content Контент
*/
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
/**
* Подсчет дней до слудующей даты
* @param $currentDate Текущая дача
* @param $nextDate Следующая дата
* @return Округленное до целого число дней
*/
function countDays ($currentDate, $nextDate) {
    return floor((strtotime($nextDate) - strtotime($currentDate)) / SECONS_IN_DAY);
}
/**
* Создание массива задач по результату запроса
* @param $result Результат запроса к БД
* @return $i Массив задач
*/
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
/**
* Выборка задач по фильтру
* @param $db База данных для подключения
* @param $sql SQL-запрос
* @throws Ошибки в SQL-запросе
* @return $tasks Массив отсортированных задач
*/
function selectTasksOnFilter($db, $sql) {
    $result = mysqli_query($db, $sql);
    if (!$result) {
        die("Ошибка в SQL при извлечении данных: " . mysqli_error($db));
    }
    $tasks = createArrayTasks($result);
    return $tasks;
}
/**
* Проверка куки с существованием projectId, если куки есть, то формирует запрос на выборку по категории
* @param $query SQL-запрос
* @return Запрос на выборку по проекту
*/
function checkCookiesId($query) {
    if (isset($_COOKIE["projectId"])) {
        $query = $query . " AND project_id = '" . $_COOKIE["projectId"] . "'";  
    }
    return $query;
}
