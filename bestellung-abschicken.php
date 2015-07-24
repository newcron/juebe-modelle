<?php
require("settings.php");
require("mysql-connector.php");
require(LIB_DIR . "/core/template.php");

$allItems= loadProducts();
$order = array();
$toPay = 0;
while(list($id, $amount) = each($_POST["order"])) {
    if(!empty($amount)) {
        $product = $allItems[$id];
        if(!$product) {
            continue;
        }
        $product["amount"] = $amount;
        $totalPrice = $amount * $product["price"];
        $toPay+=$totalPrice;
        $product["totalPrice"] = formatPrice($totalPrice);
        $product["price"] = formatPrice($product["price"]);
        $order[] =$product;
    }

}
date_default_timezone_set("Europe/Berlin");
$_POST["orderDate"] = date("d.m.Y H:i:s");
$_POST["itemsOrdered"] = $order;
$_POST["toPay"]=formatPrice($toPay);
$purchaseOrder = renderTemplateToString("purchase-order", $_POST);

$article = getPageContents(9);
$article["siteBase"] = SITE_BASE;

renderTemplate("page-base", array(
    "title" => $article["title"],
    "content" => $article["content"],
    "siteBase" => SITE_BASE,
    "orderData" => json_encode($_POST),
    "orderDetails" => $purchaseOrder,
    "onConfirmOrderPage" => true
));

function loadProducts(){
    global $mysqli;
    $p= array();
    $res = $mysqli->query("select id, title, description, category, price from jube_items order by id");
    while ($row = $res->fetch_assoc()) {

        $p[$row["id"]]=array(
            "id" => $row["id"],
            "title" => $row["title"],
            "description" => $row["description"],
            "price" => $row["price"],
            "category" => $row["category"],
        );
    }
    return $p;

}
?>