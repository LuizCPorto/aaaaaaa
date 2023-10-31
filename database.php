<?php

$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'api';

$connection = mysqli_connect($hostname, $username, $password, $database);
if (mysqli_connect_errno()){
    echo "Error na conexão" . mysqli_connect_error();
    exit;
}

