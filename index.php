<?php
include_once("includes/mysqlconnect.php");
include_once("includes/shopify.php");

$shopify = new Shopify();
$parameters = $_GET;

$query = "SELECT * FROM shops WHERE shop_url='" . $parameters['shop'] . "' LIMIT 1";
$result = $mysql->query($query);

if($result->num_rows < 1){
    header("Location: install.php?shop=" . $_GET['shop']);
    exit();
}

$store_data = $result->fetch_assoc();

$shopify->set_url($parameters['shop']);
$shopify->set_token($store_data['access_token']);

echo $shopify->get_url();
echo '<br />';
echo $shopify->get_token();

$products = $shopify->rest_api('admin/api/2021-07/products.json', array(), 'GET');
echo print_r($products);