<?php

function updateLastModificationDate() {
    date_default_timezone_set("Europe/Berlin");
    file_put_contents(MEDIA_DIR."lastmod", date("d.m.Y  H:i"));
}

function getLastModificationDate() {
    return file_get_contents(MEDIA_DIR."lastmod");
}
?>