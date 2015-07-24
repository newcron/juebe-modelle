<?php
try {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);
    $mysqli->set_charset("utf8");

} catch (Exception $e) {
    throw new Exception("Can't connect to host ".DB_HOST." and DB ".DB_DATABASE, 0, $e);
}

function getPageContents($id) {
    global $mysqli;
    $result = $mysqli->query("select title, content from jube_pages where id=$id");
    return $result->fetch_assoc();
}

?>