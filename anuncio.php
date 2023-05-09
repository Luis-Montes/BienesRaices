<?php

    // Validacion por URL Valido
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if (!$id) {
        header('Location: /admin');
    }
    
    require 'includes/app.php';
    $db = conectarDB();

    // consultar
    $query = "SELECT * FROM propiedades WHERE id = {$id}";

    // obtener resultado
    $resultado = mysqli_query($db, $query);

    if (!$resultado->num_rows) {
        header('location: /');
    }

    $propieda = mysqli_fetch_assoc($resultado);





    
    incluirTemplate('header');
    // include './includes/templates/header.php';
    
?>


    <main class="contenedor seccion contenido-centrado">
        <h1><?php echo $propieda['titulo']; ?></h1>

        <img loading="lazy" src="/imagenes/<?php echo $propieda['imagen'] . ".jpg"; ?>" alt="imagen de la propiedad">

        <div class="resumen-propiedad">
            <p class="precio">$<?php echo $propieda['precio']; ?></p>
            <ul class="iconos-caracteristicas">
                <li>
                    <img class="icono" loading="lazy" src="build/img/icono_wc.svg" alt="icono wc">
                    <p><?php echo $propieda['wc']; ?></p>
                </li>
                <li>
                    <img class="icono" loading="lazy" src="build/img/icono_estacionamiento.svg" alt="icono estacionamiento">
                    <p><?php echo $propieda['estacionamiento']; ?></p>
                </li>
                <li>
                    <img class="icono"  loading="lazy" src="build/img/icono_dormitorio.svg" alt="icono habitaciones">
                    <p><?php echo $propieda['habitaciones']; ?></p>
                </li>
            </ul>

            <p><?php echo $propieda['descripcion']; ?></p>
        </div>
    </main>

    <?php

    // require 'includes/funciones.php';

    
    // incluirTemplate('header', $inicio = true);
    incluirTemplate('footer');
?>