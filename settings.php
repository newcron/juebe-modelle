<?php
#@include_once "production_constants-x.php";

if(!defined("PROD_CONSTANTS_IN_USE")) {
    define("SITE_BASE", "http://localhost/j2/");
    define("DB_HOST", "localhost");
    define("DB_USER", "root");
    define("DB_PASS", "");
    define("DB_DATABASE", "juebe");
}

define("TEMPLATE_DIR", dirname(__FILE__)."/admin/templates/");
define("MEDIA_DIR", dirname(__FILE__)."/media/");
define("PUBLIC_MEDIA_DIR", SITE_BASE."/media/");
define("LIB_DIR", dirname(__FILE__)."/lib/");



define("ORDER_MAIL_TO", "juergenberghaeuser@web.de");

if (get_magic_quotes_gpc()) {
    $process = array(&$_POST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}



function formatPrice($price) {
    $tmp = $price/100;
    $tmp = str_replace(".", ",",$tmp);
    if($price % 100 == 0) {
        $tmp.=",00";
    } else if($price % 10 == 0 ) {
        $tmp.="0";
    }
    return $tmp;
}
?>