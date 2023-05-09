<?php 
    require '../../includes/app.php';

    use App\Propiedad;
    use Intervention\Image\ImageManagerStatic as Image;

    estaAutenticado();


    // Base de datos
    $db = conectarDB();

    // Consultar para obtener los vendedores
    $consulta = "SELECT * FROM vendedores";
    $resultado = mysqli_query($db, $consulta);

    // Arreglo con mensajes de errores
    $errores = Propiedad::getErrores();


    // Ejecutar el código después de que el usuario envia el formulario
    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        //Instancia de la propiedad
        $propiedad = new Propiedad($_POST);

        // CREACION DE CARPETA
        // $carpetaImagenes = '../../imagenes/';
        // if (!is_dir($carpetaImagenes)) {
        //     mkdir($carpetaImagenes);
        // }


        // // GENERAR NOMBRE UNICO
        $nombreImagen = md5(uniqid(rand(), true) );

        //Set la imagen
        // //Subir la imagen
         // Cambio de tamaño con intervention
        if($_FILES['imagen']['tmp_name']){
            $image = Image::make($_FILES['imagen']['tmp_name'])->fit(800,600);
            $propiedad->setImagen($nombreImagen);
        }




        $errores = $propiedad->validar();



        // $errores = $propiedad->validar();

        // $titulo = mysqli_real_escape_string( $db,  $_POST['titulo'] );
        // $precio = mysqli_real_escape_string( $db,  $_POST['precio'] );
        // $descripcion = mysqli_real_escape_string( $db,  $_POST['descripcion'] );
        // $habitaciones = mysqli_real_escape_string( $db,  $_POST['habitaciones'] );
        // $wc = mysqli_real_escape_string( $db,  $_POST['wc'] );
        // $estacionamiento = mysqli_real_escape_string( $db,  $_POST['estacionamiento'] );
        // $vendedores_id = mysqli_real_escape_string( $db,  $_POST['vendedores_id'] );
        // $creado = date('Y/m/d');

        // Asignar files hacia una variable



        // echo "<pre>";
        // var_dump($errores);
        // echo "</pre>";


        // Revisar que el array de errores este vacio

        if(empty($errores)) {

            // CREACION DE CARPETA
            if (!is_dir(CARPETA_IMAGENES)) {
                mkdir(CARPETA_IMAGENES);
            }

             //Guarda la imagen en el servidor
            $image->save(CARPETA_IMAGENES . $nombreImagen . ".jpg");


            $resultado = $propiedad->guardar();

            if($resultado) {
                // Redireccionar al usuario.
                header('Location: /admin?resultado=1');
            }
        }
    }
    incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Crear</h1>

        

        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php foreach($errores as $error): ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
        <?php endforeach; ?>

        <form class="formulario" method="POST" action="/admin/propiedades/crear.php" enctype="multipart/form-data">
            <?php include '../../includes/templates/formulario_propiedades.php'; ?>

            <input type="submit" value="Crear Propiedad" class="boton boton-verde">
        </form>
        
    </main>

<?php 
    incluirTemplate('footer');
?> 