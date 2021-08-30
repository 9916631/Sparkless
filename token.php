<?php
include_once("includes/mysqlconnect.php");
$api_key = 'fa6e425aa8160aabd484c4693cc8b3bc';
$secret_key = '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';
$parameters = $_GET;
$shop_url = $parameters['shop'];
$hmac = $parameters['hmac'];
$parameters = array_diff_key($parameters, array('hmac' =>  ''));

ksort($parameters);

$new_hmac = hash_hmac('sha256', http_build_query($parameters), $secret_key);

if( hash_equals($hmac, $new_hmac)){
    $access_token_endpoint = 'https://' . $shop_url . '/admin/oauth/access_token';

    $var = array("client_id" => $api_key, "client_secret" => $secret_key, "code" => $parameters['code']);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $access_token_endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, count($var));
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($var));
    $response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response, true);
    echo print_r($response);

    $query = "INSERT INTO shopss (shop_url, access_token, install_date) VALUES ('" . $shop_url . "','" . $response['access_token'] . "',NOW()) ON DUPLICATE KEY UPDATE access_token='" . $response['access_token'] . "'";
    if($mysql->query($query)){
        header("Location: https://" . $shop_url . '/admin/apps');
        exit();
    }
}
else{
    echo 'this not safe';
}