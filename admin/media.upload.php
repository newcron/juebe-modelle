<?php
require("../settings.php");
define("MAX_THUMB_WIDTH", 300);

handleFileupload();

function handleFileupload() {
    $allowedExts = array("jpg", "jpeg", "gif", "png");
    $upload = $_FILES["qqfile"];
    $filename = $upload["name"];
    $extension = strtolower(end(explode(".", $filename)));
    if (!in_array($extension, $allowedExts)) {
        die("ACHTUNG: Scheint keine Bilddatei zu sein: $filename");
    }
    if ($upload["error"] > 0)
    {
        die ("Return Code: " . $upload["error"]);
    }
    $targetFolder = targetPath($filename);
    $from = $upload["tmp_name"];
    $ext = extension($filename);
    $originalDestination = $targetFolder . "original." . $ext;

    $success = move_uploaded_file($from, $originalDestination);
    chmod($originalDestination, 0777);
    createThumbs($targetFolder, $originalDestination);
    // createThumb($destination, MEDIA_DIR."/".$prefix."thumb-".$filename);

    header("Content-Type: application/json");
    echo json_encode(array("success" => $success, "target"=> $originalDestination,"data" => $upload));
}

function targetPath($name) {
    $name = str_replace(".".extension($name), "", $name);
    $name = str_replace(" ", "-", $name);
    // $name = preg_replace(" +", "-", $name);
    $counter = 1;
    $finalName = $name;
    $destination = MEDIA_DIR."/".$finalName;
    while(file_exists($destination)) {
        $counter++;
        $finalName = $counter."-".$name;
        $destination = MEDIA_DIR."/".$finalName;
    }
    mkdir($destination, 0777);
    chmod($destination, 0777);
    return $destination."/";
}

function extension($from){
    return strtolower(end(explode(".", $from)));
}

function createThumbs($path, $originalImage) {
    createThumb($originalImage, $path."/size_thumb.jpg", 180, 100);
    createThumb($originalImage, $path."/size_large.jpg", 800, 800);
}



function createThumb($from, $to, $maxWidth, $maxHeight) {

    $extension = extension($from);
    switch($extension) {
        case "jpg":
        case "jpeg":
            $source = imagecreatefromjpeg($from);
            break;
        case "png":
            $source = imagecreatefrompng($from);
            break;
        case "gif":
            $source = imagecreatefromgif($from);
            break;
    }


    list($width, $height) = getimagesize($from);
    $percent = $maxWidth / $width;



    $new_width = $maxWidth;
    $new_height = $height * $percent;

    if($new_height > $maxHeight) {
        $percent = $maxHeight / $height;
        $new_height = $maxHeight;
        $new_width = $width * $percent;
    }


    if($new_width > $width || $new_height > $height) {
        $new_width = $width;
        $new_height = $height;
    }

    $image_p = imagecreatetruecolor($new_width, $new_height);


    imagecopyresampled($image_p, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    // Output
    imagejpeg($image_p, $to, 80);

    chmod($to, 0777);
}

?>