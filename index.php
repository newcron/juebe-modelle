<?php
require("settings.php");
require("mysql-connector.php");
require(LIB_DIR."/core/template.php");
require("admin/moddate.php");

$requestMapping = array(
    "home" => 1,
    "produkte" => 2,
    "bestellung" => 3,
    "projekte" => 4,
    "links" => 5,
    "kontakt" => 6,
    "impressum" => 7,
    "agb" => 8
);

$base = isset($base) ? $base : "home";
$id = $requestMapping[$base];
$res = getPageContents($id);


$viewModel = array(
    "siteBase" => SITE_BASE,
    "title" => $res["title"],
    "content" => $res["content"],
    "lastModification" => getLastModificationDate()
);

if($base == "produkte") {
    $viewModel["onProductPage"] = true;
    $viewModel["items"] = loadProducts();
} else if($base == "bestellung") {
    $viewModel["onOrderPage"] = true;
    $viewModel["items"] = loadProducts();

}



renderTemplate("page-base", $viewModel);

function loadProducts(){
    global $mysqli;
    $p= array();
    $res = $mysqli->query("select id, title, description, category, price, image from jube_items order by id");
    while ($row = $res->fetch_assoc()) {
        if(!$p[$row["category"]]) {
            $p[$row["category"]] = array();
        }
        $item = array(
            "id" => $row["id"],
            "title" => $row["title"],
            "description" => $row["description"],
            "price" => formatPrice($row["price"]),
            "category" => $row["category"],

        );
        if(!empty($row["image"])) {
            $item["thumb"] = PUBLIC_MEDIA_DIR.$row["image"]."/size_thumb.jpg";
            $item["large"] = PUBLIC_MEDIA_DIR.$row["image"]."/size_large.jpg";

        }
        $p[$row["category"]][]= $item;
    }
    $res = array();
    $catIndex = 0;
    while(list($key, $val) = each($p)) {

        $res[] = array("category" => $key, "items" => $val, "catIndex" => $catIndex++);
    }

    return $res;

}



?>