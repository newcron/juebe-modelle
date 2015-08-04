<?php
require("../settings.php");
require("../mysql-connector.php");
require("moddate.php");
header("Content-Type: application/json; charset=UTF-8");

$id = $mysqli->real_escape_string($_POST["id"]);
if(empty($id)) {
    echo json_encode(array("success" => false));
} else {
    $query = "delete from jube_items where id='$id' limit 1";

    $mysqli->query($query);

    updateLastModificationDate();

    echo json_encode(array("success" => true, "query" => $query));
}



?>