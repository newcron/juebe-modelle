<?php
require("../settings.php");
require("../mysql-connector.php");
require("moddate.php");
header("Content-Type: application/json; charset=UTF-8");
$title = $mysqli->real_escape_string($_POST["title"]);
$content = $mysqli->real_escape_string($_POST["content"]);
$id = $_POST["id"];
$query = "replace jube_pages(id, title, content) values('$id', '$title', '$content')";
$success = $mysqli->query($query);
updateLastModificationDate();
echo json_encode(array("success"=>$success, "query" => $query, "error" => $mysqli->error));
?>