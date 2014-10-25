<?php
session_start();
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, HEAD");

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'OPTIONS') {
    exit();
}

http_response_code(400);
?>