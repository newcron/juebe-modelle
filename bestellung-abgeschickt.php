<?php
require("settings.php");
require("mysql-connector.php");
require(LIB_DIR . "/core/template.php");

$content = json_decode($_POST["order"]);
$content->{"standalone"} = true;
$headers = "Content-Type: text/html; charset=utf-8\n";

$mailContent = renderTemplateToString("purchase-order", $content);

$name =$content->{"name"};
$time = $content->{"orderDate"};
$subject = "Bestellung von $name um $time";
mail(ORDER_MAIL_TO, $subject, $mailContent, $headers);
mail($content->{"mail"}, "Kopie ihrer $subject", $mailContent, $headers);

$article = getPageContents(10);
$article["siteBase"] = SITE_BASE;

renderTemplate("page-base", $article);
?>