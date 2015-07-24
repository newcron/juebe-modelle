<?php
require("../settings.php");
require("../mysql-connector.php");
header("Content-Type: application/json; charset=UTF-8");

$res = $mysqli->query("select id, title, content from jube_pages");
$jsonArr = array();
while ($row = $res->fetch_assoc()) {
    $jsonArr[]=$row;
}
echo json_encode($jsonArr);
?>