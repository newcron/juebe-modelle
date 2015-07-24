<?php
require("../settings.php");
require("../mysql-connector.php");
require("moddate.php");
header("Content-Type: application/json; charset=UTF-8");
$description = $mysqli->real_escape_string($_POST["description"]);
$title = $mysqli->real_escape_string($_POST["title"]);
$price = $mysqli->real_escape_string($_POST["price"]);
$image = $mysqli->real_escape_string($_POST["image"]);
$category = $mysqli->real_escape_string($_POST["category"]);
$id = $_POST["id"];
$originalId = $_POST["originalId"];
if(empty($originalId)) {
    $query = "insert into jube_items(id, title, category, description, price, image) values('$id', '$title', '$category','$description', '$price', '$image')";
} else {
    $query = "update jube_items set id='$id', title='$title', category='$category', description='$description', price='$price', image='$image' where id='$originalId'";
}
$mysqli->query($query);

updateLastModificationDate();

echo json_encode(array("success"=>true, "query" => $query));


?>