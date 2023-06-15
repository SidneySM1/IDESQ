<?php

$usuario = 'root';
$senha = '124679N@ruto';
$database = 'login2';
$host = 'localhost';

$mysqli = new mysqli($host, $usuario, $senha, $database);

if($mysqli->error) {
    die("Falha ao conectar ao banco de dados: " . $mysqli->error);
}
