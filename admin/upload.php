<?php
	define("TARGET_DIRECTORY", realpath("../../../../../../../")."/uploaded-images"); 
	define("TARGET_DIRECTORY_PUBLIC_URL", "/juebe/uploaded-images/"); 
	define("MAX_THUMB_WIDTH", 300); 
?>
<html>
<head>
<body>
<?php 
	if($_FILES["file"]): 
		handleFileupload(); 
	else: 
?>


<form action="upload.php" method="post"
enctype="multipart/form-data">

	<input type="file" name="file" id="file"><br>
	<input type="submit" name="submit" value="Hochladen">
</form>
<hr/>
Bilder speichern in <?php echo TARGET_DIRECTORY; ?>

<?php endif; ?>
</body>
</html>
<?php
function handleFileupload() {
	$allowedExts = array("jpg", "jpeg", "gif", "png");
	$upload = $_FILES["file"]; 
	$filename = $upload["name"];
	$extension = strtolower(end(explode(".", $filename)));
	if (!in_array($extension, $allowedExts)) {
		die("ACHTUNG: Scheint keine Bilddatei zu sein: $filename"); 
	}
	if ($upload["error"] > 0)
    {
	    die ("Return Code: " . $upload["error"]); 
	}
	$prefix = time()."-";
	$destination = TARGET_DIRECTORY."/".$prefix.$filename; 
	$from = $upload["tmp_name"];
	move_uploaded_file($from, $destination);
	createThumb($destination, TARGET_DIRECTORY."/".$prefix."thumb-".$filename); 
}

function createThumb($from, $to) {

	$extension = strtolower(end(explode(".", $from))); 
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
	$percent = MAX_THUMB_WIDTH / $width; 
	$new_width = MAX_THUMB_WIDTH;
	$new_height = $height * $percent;

	$image_p = imagecreatetruecolor($new_width, $new_height);

	imagecopyresampled($image_p, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

	// Output
	imagejpeg($image_p, $to, 80);
}

?>