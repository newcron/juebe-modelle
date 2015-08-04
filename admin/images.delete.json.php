<?php
require("../settings.php");
$items = scandir(MEDIA_DIR);
$result = array();
$target = $_GET["id"];
foreach ($items as $i) {
    if (substr($i, 0, 1) != ".") {
        if ($i === $target) {
            deleteImage($i);
            break;
        }
    }
}

function deleteImage($imageId)
{
    $imageDir = MEDIA_DIR . DIRECTORY_SEPARATOR . $imageId;
    $files = scandir($imageDir);
    foreach ($files as $i) {
        if (substr($i, 0, 1) === ".") {
            continue;
        }
        unlink($imageDir.DIRECTORY_SEPARATOR.$i);
    }
    rmdir($imageDir);


}

header("Content-Type: application/json");
echo json_encode(["success" => true]);
?>