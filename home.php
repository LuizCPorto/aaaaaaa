<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: access');
header('Access-Control-Allow-Methods: GET');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/jwtHandler.php';

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $headers = getallheaders();
    if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\+S)/', $headers['Authorization'], $matches)){
        $data = decodeToken($matches[1]);
        $userId = (int) $data;
        if (is_numeric($data)){
            sendJson(401, 'Usuario invalido');
        }       
        $sql = "SELECT `id`,`nome`,`email` FROM `users` WHERE `id`='$userId'";
        $query = mysqli_query($connection, $sql);
        $row = mysqli_fetch_array($query, MYSQL_ASSOC);
        if ($row == null){
            sendJson(404, 'usuario não encontrado');
        }
        $sql = "SELECT `id`,`nome`,`email` FROM `users`";
        $query = mysqli_query($connection, $sql);
        $row = mysqli_fetch_array($query, MYSQL_ASSOC);

    }
    sendJson(403, 'O token de autorização está ausente!');
}

sendJson(405, 'Invalid Request Method. HTTP method should be GET');