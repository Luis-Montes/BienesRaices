<?php
    require 'includes/app.php';
    $db = conectarDB();
    $errores = [];

    // Autenticar el usuario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // var_dump($_POST);
        $email = mysqli_real_escape_string($db, filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL));
        $password = $_POST['password'];

        if (!$email) {
            $errores[] = "Email es obligario o esta incorrecto";
        }

        if (!$password) {
            $errores[] = "Falta el password";
        }

        if (empty($errores)) {
            // Revisar si usuario existe
            $query = "SELECT * FROM usuarios WHERE email = '{$email}'";
            $resultado = mysqli_query($db, $query);
            // var_dump($resultado);

            if ($resultado->num_rows) {
                $usuario = mysqli_fetch_assoc($resultado);

                $auth = password_verify($password, $usuario['password']);
                if ($auth) {
                    session_start();

                    $_SESSION['usuario'] = $usuario['email'];
                    $_SESSION['login'] = true;

                    header('Location: /admin');


                }else {
                    $errores[] = "Contraseña incorrecta";
                }
            }else {
                $errores[] = "Usuario no existe";
            }
        }
    }



    
    incluirTemplate('header');
    // include './includes/templates/header.php';
?>


    <main class="contenedor seccion">
        <h1>Iniciar Sesión</h1>

        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>

        <form method="post" action="" class="formulario">
            <fieldset>
                    <legend>Email y Password</legend>


                    <label for="email">E-mail</label>
                    <input name="email" type="email" placeholder="Tu Email" id="email" require>

                    <label for="password">Teléfono</label>
                    <input name="password" type="password" placeholder="Tu password" id="password" require>
                </fieldset>
                <input type="submit" value="Iniciar Sesión" class="boton boton-verde">
        </form>
    </main>

    <?php

    // require 'includes/funciones.php';

    
    // incluirTemplate('header', $inicio = true);
    incluirTemplate('footer');
?>