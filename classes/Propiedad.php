<?php

namespace App;

class Propiedad {

    protected static $db;
    protected static $columnasDB = ['id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamiento', 'creado', 'vendedores_id'];

    ////////////////////////////////
    protected static $errores = [];

    public $id;
    public $titulo;
    public $precio;
    public $imagen;
    public $descripcion;
    public $habitaciones;
    public $wc;
    public $estacionamiento;
    public $creado;
    public $vendedores_id;

    //////////////////////////////

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? '';
        $this->titulo = $args['titulo'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->wc = $args['wc'] ?? '';
        $this->estacionamiento = $args['estacionamiento'] ?? '';
        $this->creado = date('Y/m/d');
        $this->vendedores_id = $args['vendedores_id'] ?? 1;
    }

    ///////////////////////////////////////////////////////////////////

    public static function setDB($database){
        self::$db = $database;
    }

    //////////////////////////////////////////////////////////////////////

    public function guardar(){
        //Sanitizaer datos
        // $atributos = $this->sanitizarAtributos();

        // $query = " INSERT INTO propiedades ( ";
        // $query .= join(', ', array_keys($atributos));
        // $query .= " ) VALUE (' ";
        // $query .= join("', '", array_keys($atributos));
        // $query .= " ') ";

        $query = " INSERT IGNORE INTO propiedades (titulo, precio, imagen, descripcion, habitaciones, wc, estacionamiento, creado, vendedores_id) 
        VALUES ('$this->titulo', '$this->precio', '$this->imagen', '$this->descripcion', '$this->habitaciones', '$this->wc', '$this->estacionamiento', '$this->creado', '$this->vendedores_id')";

        $resultado = self::$db->query($query);

        return $resultado;
    }

    
    ///////////////////////////////////////////////////////////////////////


    public function atributos(){
        $atributos = [];
        foreach (self::$columnasDB as $columna) {
            if ($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    ///////////////////////////////////////////////////////////////////////

    public function sanitizarAtributos(){
        $atributos = $this->atributos();
        $sanitizado = [];

        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }

        return $sanitizado;


    }

    /////////////////////////////////////////////////////////////////////////

    public function setImagen($imagen) {
        // Asigna el nombre de la imagen al atributo
        if ($imagen) {
            $this->imagen = &$imagen;
        }
    }


    ////////////////////////////////////////////////////////////////////////


    public static function getErrores(){
        return self::$errores;
    }

    ////////////////////////////////////////////////////////////////////////

    public function validar(){
        
        if(!$this->titulo) {
            self::$errores[] = "Debes añadir un titulo";
        }

        if(!$this->precio) {
            self::$errores[] = 'El Precio es Obligatorio';
        }

        if( strlen( $this->descripcion ) < 50 ) {
            $errores[] = 'La descripción es obligatoria y debe tener al menos 50 caracteres';
        }

        if(!$this->habitaciones) {
            self::$errores[] = 'El Número de habitaciones es obligatorio';
        }
        
        if(!$this->wc) {
            self::$errores[] = 'El Número de Baños es obligatorio';
        }

        if(!$this->estacionamiento) {
            self::$errores[] = 'El Número de lugares de Estacionamiento es obligatorio';
        }
        
        if(!$this->vendedores_id) {
            $errores[] = 'Elige un vendedor';
        }

        if(!$this->imagen) {
            self:: $errores[] = 'La Imagen es Obligatoria';
        }

        // // Validar por tamaño (1mb máximo)
        // $medida = 2 * 1000 * 1000;


        // if($this->imagen['size'] > $medida ) {
        //     $errores[] = 'La Imagen es muy pesada';
        // }

        return self::$errores;
    }

    ////////////////////////////////////////////////////////////////////////

    // Lista de propiedades
    public static function all(){
        $query = "SELECT * FROM propiedades";

        $resultado = self::consultaSQL($query);

        return $resultado;
    }


    //Busca propiedad por id
    public static function find($id){
        $query = "SELECT * FROM propiedades WHERE id = {$id}";

        $resultado = self::consultaSQL($query);

        return array_shift($resultado);
    }

    ////////////////////////////////////////////////////////////////////////

    public static function consultaSQL($query) {
        // Consulta a la base de datos
        $resultado = self::$db->query($query);

        // Iterar los resultados
        $array = [];

        while ($registro = $resultado->fetch_assoc()) {
            $array[] = self::crearObjeto($registro);
        }


        //Liberar memoria
        $resultado->free();

        // Retornar los resultados
        return $array;
    }

    ////////////////////////////////////////////////////////////////////////

    protected static function crearObjeto($registro){
        // instancia dentro de la clase
        $objeto = new self;


        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }
}