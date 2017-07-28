<?php

require '../../config.php';
require 'UsuariosRepository.php';


$pdo = new PDO("mysql:host=" . $config['db']['host'] . ";dbname=" . $config['db']['dbname'],
        $config['db']['user'], $config['db']['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$o = new \APITurnos\Repository\UsuariosRepository($pdo);


var_dump($o->getUserData("javonte81", "3894"));