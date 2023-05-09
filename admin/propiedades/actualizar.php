<?php

    use App\Propiedad;

    require '../../includes/app.php';

    estaAutenticado();

    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if (!$id) {
        header('Location: /admin');
    }

    // Consulta para obtener los datos de la propiedad
    $propiedad = Propiedad::find($id);
    


    // Consultar a los vendedores
    $consulta = "SELECT * FROM vendedores";
    $resultado = mysqli_query($db, $consulta);



    incluirTemplate('header');

    $errores = [];


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // echo "<pre>";
        // var_dump($_POST);
        // echo "</pre>";


        $titulo = mysqli_real_escape_string( $db, $_POST['titulo'] );
        $precio = mysqli_real_escape_string( $db, $_POST['precio'] );
        $descripcion = mysqli_real_escape_string( $db, $_POST['descripcion'] );
        $habitaciones = mysqli_real_escape_string( $db, $_POST['habitaciones'] );
        $wc = mysqli_real_escape_string( $db, $_POST['wc'] );
        $estacionamiento = mysqli_real_escape_string( $db, $_POST['estacionamiento'] );
        $vendedores_id = mysqli_real_escape_string( $db, $_POST['vendedor'] );
        $creado = date('Y/m/d');
        // Asignar files a una variable
        $imagen = $_FILES['imagen'];



        if(!$titulo){
            $errores[] = 'El titulo es obligatorio';
        }
        if(!$precio){
            $errores[] = 'El precio es obligatorio';
        }
        if(strlen($descripcion) < 50){
            $errores[] = 'El campo debe contener al menos 50 caracteres';
        }
        if(!$habitaciones){
            $errores[] = 'El numero de habitaciones es obligatorio';
        }
        if(!$wc){
            $errores[] = 'El numero de baños es obligatorio';
        }
        if(!$estacionamiento){
            $errores[] = 'El numero de estacionamientos es obligatorio';
        }
        if(!$vendedores_id){
            $errores[] = 'Selecciona a un vendedor';
        }


        // Validar por tamaño(100 kb)

        $medida = 1000 * 1000;

        if ($imagen['size'] > $medida) {
            $errores[''] = 'La imagen es muy grande';
        }

        if (empty($errores)) {

            // // CREACION DE CARPETA
            $carpetaImagenes = '../../imagenes/';

            if (!is_dir($carpetaImagenes)) {
                mkdir($carpetaImagenes);
            }
            $nombreImagen = '';

            //** SUBIR ARCHIVOS  Y ELIMINAR EL ANTERIOR **/

            if ($imagen['name']) {
                unlink($carpetaImagenes . $propiedad['imagen']);

                // GENERAR NOMBRE UNICO
                $nombreImagen = md5(uniqid(rand(), true)); 

                 //Subir la imagen
                move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen . ".jpg");
            }else {
                $nombreImagen = $propiedad['imagen'];
            }





            // INSERTAR EN LA BASE DE DATO
            $query = "UPDATE propiedades SET titulo = '{$titulo}', precio = {$precio}, imagen = '{$nombreImagen}', descripcion = '{$descripcion}', habitaciones = {$habitaciones},
            wc = {$wc}, estacionamiento = {$estacionamiento}, vendedores_id = {$vendedores_id} WHERE id = {$id}";


    
            $resultado = mysqli_query($db, $query);

            if ($resultado) {
                header('Location: /admin?resultado=2');
            }
        }

            

        
    }





?>


    <main class="contenedor seccion">
        <h1>Actualizar Propiedad</h1>
        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php foreach($errores as $error): ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
        <?php endforeach; ?>
        
        <form class="formulario" method="POST" enctype="multipart/form-data">

            <?php include '../../includes/templates/formulario_propiedades.php'; ?>

            <input type="submit" value="Guardar Cambios" class="boton boton-verde">
        </form>
    </main>

    <?php

    // require 'includes/funciones.php';

    
    // incluirTemplate('header', $inicio = true);
    incluirTemplate('footer');
?>