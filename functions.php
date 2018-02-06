<?php
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
