<?php

    /**
     * Se conecta a la base de datos.
     */
    function conexionBD(){
        
        $conexionBD = mysqli_connect("localhost", "root", "", "setas")
        or die("Error al conectar a la base de datos.");

        return $conexionBD;
    }

    /**
     * Obtiene toda la información de la base de datos
     * en un único array.
     */
    function obtenTodo(){        
        $resultado = array();
        $bd = conexionBD();

        $sql = "SELECT * FROM SETAS";

        $consulta = mysqli_query($bd, $sql);

        while ($fila = $consulta->fetch_assoc()){
            $resultado[] = $fila;
        }
        return $resultado;
    }
    
    /**
     * Obtiene toda la información de la base de datos
     * en un único array.
     */
    function obtenTodoFav(){        
        $resultado = array();
        $bd = conexionBD();

        $sql = "SELECT * FROM SETAS WHERE FAVORITO = 1";

        $consulta = mysqli_query($bd, $sql);

        while ($fila = $consulta->fetch_assoc()){
            $resultado[] = $fila;
        }
        return $resultado;
    }
    
    /**
     * Obtiene un unico elemento según un identificador.
     */
    function obten($id) {
        $bd = conexionBD();
        $sql = "SELECT * FROM SETAS WHERE ID = ".$id;

        $consulta = mysqli_query($bd, $sql);

        return $resultado = $consulta -> fetch_assoc();
    }

    /**
     * Inserta un dato a raíz de un array.
     */
    function insertar($datos) {
        $nombre = $datos['nombre'];
        $desc = $datos['descripcion'];
        $nombre_com = $datos['nombre_comun'];
        $comestible = $datos['comestible'];
        $favorito = $datos['favorito'];
        if (isset($datos['url'])) {
            $url = $datos['url'];
        } 
        if (isset($datos['imagen'])){
            $imagen = $datos['imagen'];
        }

        if (count($datos) == 5){
            $sql = "INSERT INTO SETAS (NOMBRE, DESCRIPCION, NOMBRE_COMUN, COMESTIBLE, FAVORITO) VALUES ('".$nombre."', '".$desc."', '".$nombre_com."', '".$comestible."', '".$favorito."')";
        } else if (isset($datos['url']) && !isset($datos['imagen'])){
            $sql = "INSERT INTO SETAS (NOMBRE, DESCRIPCION, NOMBRE_COMUN, URL, COMESTIBLE, FAVORITO) VALUES ('".$nombre."', '".$desc."', '".$nombre_com."', '".$url."','".$comestible."', '".$favorito."')";
        } else if (isset($datos['imagen']) && !isset($datos['url'])){
            $sql = "INSERT INTO SETAS (NOMBRE, DESCRIPCION, NOMBRE_COMUN, COMESTIBLE, FAVORITO, IMAGEN) VALUES ('".$nombre."', '".$desc."', '".$nombre_com."', '".$comestible."', '".$favorito."', '".$imagen."')";
        } else if (count($datos) == 7) {
            $sql = "INSERT INTO SETAS (NOMBRE, DESCRIPCION, NOMBRE_COMUN, URL, COMESTIBLE, FAVORITO, IMAGEN) VALUES ('".$nombre."', '".$desc."', '".$nombre_com."', '".$url."','".$comestible."', '".$favorito."', '".$imagen."')";
        }

        $bd = conexionBD();

        if ($bd -> query($sql) == TRUE){
            return "ok";
        } else {
            return $bd -> error;
        }

    }

    /**
     * Actualiza un dato dado un identificador.
     */
    function actualizar($datos, $id) {
        $nombre = $datos['nombre'];
        $desc = $datos['descripcion'];
        $nombre_com = $datos['nombre_comun'];
        $comestible = $datos['comestible'];
        $favorito = $datos['favorito'];
        if (isset($datos['url'])) {
            $url = $datos['url'];
        } 
        if (isset($datos['imagen'])){
            $imagen = $datos['imagen'];
        }

        if (count($datos) == 5){
            $sql = "UPDATE SETAS SET 
                    NOMBRE = '".$nombre."',
                    DESCRIPCION = '".$desc."', 
                    NOMBRE_COMUN = '".$nombre_com."', 
                    COMESTIBLE = '".$comestible."', 
                    FAVORITO = '".$favorito."'
                    WHERE ID = ".$id;
        } else if (isset($datos['url'])){
            $sql = "UPDATE SETAS SET 
                    NOMBRE = '".$nombre."',
                    DESCRIPCION = '".$desc."', 
                    NOMBRE_COMUN = '".$nombre_com."',
                    URL = '".$url."', 
                    COMESTIBLE = '".$comestible."', 
                    FAVORITO = '".$favorito."'
                    WHERE ID = ".$id;
        } else if (isset($datos['imagen'])){
            $sql = "UPDATE SETAS SET 
                    NOMBRE = '".$nombre."',
                    DESCRIPCION = '".$desc."', 
                    NOMBRE_COMUN = '".$nombre_com."', 
                    COMESTIBLE = '".$comestible."', 
                    FAVORITO = '".$favorito."',
                    IMAGEN = '".$imagen."',
                    WHERE ID = ".$id;
        } else if (count($datos) == 7) {
            $sql = "UPDATE SETAS SET 
                    NOMBRE = '".$nombre."',
                    DESCRIPCION = '".$desc."', 
                    NOMBRE_COMUN = '".$nombre_com."',
                    URL = '".$url."', 
                    COMESTIBLE = '".$comestible."', 
                    FAVORITO = '".$favorito."',
                    IMAGEN = '".$imagen."'
                    WHERE ID = ".$id;
        }

        $bd = conexionBD();

        if ($bd -> query($sql) == TRUE){
            return "ok";
        } else {
            return $bd -> error;
        }

    }

    /**
     * Cambia el estado que se le pase al contrario, es decir:
     * Si una seta tiene por valor FAVORITO 0, se cambiará a 1,
     * y viceversa. 
     * 
     */
    function cambiarFavoritos($datos, $id) {
        $favorito = $datos['favorito'];
        $valNuevoFav = 0;
        if ($favorito == 1) {
            $valNuevoFav = 1;
        } else if ($favorito == 0){
            $valNuevoFav = 0;
        }
        $sql = "UPDATE SETAS SET
                FAVORITO = '".$valNuevoFav."'
                WHERE ID = ".$id;

        $bd = conexionBD();

        if ($bd -> query($sql) == TRUE) {
            return "ok";
        } else {
            return $bd -> error;
        }
        
    }

    /**
     * Elimina un dato de la base de datos dado el id.
     */
    function eliminar($id) {
        $sql = "DELETE FROM SETAS WHERE ID = ".$id;

        $bd = conexionBD();

        if ($bd -> query($sql) == TRUE){
            return "ok";
        } else {
            return $bd -> error;
        }
    }

    /**
     * Verifica si el array obtenido cumple los requisitos para poder
     * insertar los datos en la base de datos.
     */
    function validaDatosIns($item) {
        return
                is_array($item) &&
                count($item) >= 5 &&
                isset($item['nombre']) &&
                isset($item['descripcion']) &&
                isset($item['nombre_comun']) &&
                isset($item['comestible']) &&
                isset($item['favorito']) &&
                $item['nombre'] != '' &&
                $item['descripcion'] != '' &&
                $item['nombre_comun'] != '' &&
                $item['comestible'] != '' &&
                $item['favorito'] != '';
    }

    /**
     * Función que dada un array con un nombre y un string
     * en base64, escribe una imagen al disco.
     */
    function subirImagen($imagen){
        $nombre = $imagen['imagen'];
        $b64 = base64_decode($imagen['base64']);
        $fp = fopen('/img/'.$nombre, 'w');
        fwrite($fp, $b64);
        
        return fclose($fp);
    }
?>