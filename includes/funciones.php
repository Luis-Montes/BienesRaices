<?php
define('TEMPLATES_URL', __DIR__ .  '/templates');
define('FUNCIONES_URL', __DIR__ .  'funciones.php');
define('CARPETA_IMAGENES', __DIR__ . '/../imagenes/');

function incluirTemplate (string $nombre, bool $inicio = false ) {
    include TEMPLATES_URL . "/{$nombre}.php";
}

function estaAutenticado() {
    session_start();
    
    if (!$_SESSION['login']) {
        header('Location: /');
    }
}

function debuguear($vairable) {
    echo  "<pre>";
    var_dump($vairable);
    echo  "</pre>";
    exit;
}

// Escapar o sanitizar los datos

function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}