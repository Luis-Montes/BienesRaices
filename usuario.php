<?php
    // IMportar la base de datos
    require 'includes/app.php';
    $db = conectarDB();

    //Crear un email y  password
    $email = "correo@correo.com";
    $password = "123456";

    $passwordhash = password_hash($password, PASSWORD_DEFAULT);
    // var_dump($passwordhash);

    // Query para crear al usuario
    $query = "INSERT INTO usuarios (email, password) VALUES ( '{$email}', '{$passwordhash}' )";
    echo $query;

    // Agregarlos a la base de datos
    mysqli_query($db, $query);
?>