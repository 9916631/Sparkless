<?php

$server = 'localhost';
$username = 'root';
$password = '';
$database = 'sparkless_db';

$mysql = mysqli_connect($server, $username, $password, $database);

if(!$mysql){
    die("Error: " . mysql_connect_error());
}