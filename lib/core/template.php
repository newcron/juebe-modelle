<?php
require(LIB_DIR."/mustache.php/src/Mustache/Autoloader.php");
Mustache_Autoloader::register();


function renderTemplateToString($templateName, $model) {
    $template = file_get_contents(TEMPLATE_DIR."/$templateName.mustache");
    $mustacheEngine = new Mustache_Engine;

    return $mustacheEngine->render($template, $model);
}

function renderTemplate($templateName, $model) {
    header("Content-Type: text/html; charset=UTF-8");
    echo renderTemplateToString($templateName, $model);
}

?>