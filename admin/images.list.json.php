<?php
require("../settings.php");
$items = scandir(MEDIA_DIR);
$result = array();
foreach( $items as $i) {
    if(substr($i, 0, 1) != ".") {
        $result[] = array("name" => $i, "thumb" => PUBLIC_MEDIA_DIR.$i."/size_thumb.jpg", "full" => PUBLIC_MEDIA_DIR.$i."/size_large.jpg");
    }
}
header("Content-Type: application/json");
echo json_encode($result);
?>