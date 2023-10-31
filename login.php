<?php 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/jwtHandler.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $data = json_decode(file_get_contents('php://input'));

    if (!isset($data->email) || !isset($data->senha) || empty(trim($data->email)) || empty(trim($data->senha))){
        sendJson(422, 'Por favor preencha todos os campos obrigatórios.');
    }


    $email = mysqli_real_escape_string($connection, trim($data->email));
    $senha = trim($data->senha);

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        sendJson(422, 'Email invalido');
    }

    $sql = "SELECT * FROM `users` WHERE `email`='$email'";
    $query = mysqli_query($connection, $sql);
    $row = mysqli_fetch_array($query, MYSQLI_ASSOC);
    if ($row == null) {
        sendJson(404, 'Usuario não encontrado, verifique o email.');
    }
    if (password_verify($senha, $row['senha'])) {
        sendJson(401, 'Senha incorreta');
    }
    sendJson(200, '', ['token'=>encodeToken($row['id'])]);

}

sendJson(405, 'Método de solicitação inválido. O método HTTP deve ser POST');