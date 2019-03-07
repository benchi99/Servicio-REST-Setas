<?php

    // Se requiere para el buen funcionamiento de Slim.
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;
    require 'vendor/autoload.php';

    //Incluye las funciones que manejan la base de datos.
    include __DIR__.'/util/util.php';

    //Se crea una nueva instancia de Slim.
    $config = ['settings' => ['addContentLengthHeader' => false], 'debug' => true];
    $app = new \Slim\App($config);

    /**
     * PETICIÓN GET
     * /setas
     * Devuelve todas las setas en la base de datos.
     * 
     */
    $app -> get('/setas', function(Request $request, Response $response, array $args) {
        return $response -> withJson(obtenTodo());
    });

    /**
     * PETICIÓN GET
     * /setas/favoritos
     * Devuelve todas las setas marcadas como favoritos en la base de datos.
     * 
     */
    $app -> get('/setas/favoritos', function(Request $request, Response $response, array $args) {
        return $response -> withJson(obtenTodoFav());
    });


    /**
     * PETICIÓN GET
     * /setas/{id}
     * Devuelve la seta que tenga el ID especificado, si existe.
     * 
     */
    $app -> get('/setas/{id}', function(Request $request, Response $response, array $args) {
        $item = obten($args['id']);

        if ($item == null){
            return $response -> withStatus(404)-> withJson([
                'error' => 'No existe ningun item con ese id.'
            ]);
        } else {
            return $response -> withJson($item);
        }
    });

    /**
     * PETICIÓN POST
     * /setas
     * Añade una seta a la base de datos, ciñiéndose a un estandar.
     */
    $app -> post('/setas', function (Request $request, Response $response, array $args) {
        $datos_post = $request -> getParsedBody();

        if (validaDatosIns($datos_post)){
            $estado = insertar($datos_post);
            if ($estado != "ok") {
                return $response -> withStatus(418) -> withJson([
                    'error' => 'Hubo un error al insertar en la base de datos.',
                    'mensaje' => $estado,
                    'tetera' => true
                ]); 
            }

        } else {
            return $response -> withStatus(400) -> withJson([
                'error' => 'Sintaxis malformada. Mínimo debe insertarse nombre, descripción, nombre común, si es comestible y si es favorito.']);
        }
        
        return $response -> getBody() -> write(print_r($datos_post));
    });

    /**
     * PETICIÓN POST
     * /setas/subirImagen
     * Sube una imagen al servidor de una seta. Suele ocurrir junto al
     * hacer la petición POST /setas.
     * 
     * DEBIDO A FALTA DE TIEMPO, NO SE UTILIZARÁ EN LA APLICACIÓN DE ANDROID.
     */

    $app -> post('/setas/subirImagen', function(Request $request, Response $response, array $args){
        $imagen = $request -> getParsedBody();

        if (!isset($imagen['imagen']) && !isset($imagen['base64'])) {
            return $response -> withStatus(400) -> withJson([
                    'error' => 'No se ha enviado ninguna imagen para subir.'
            ]);
        } else {
            if (subirImagen($imagen)) {
                return $response -> withStatus(200) -> withJson([
                    'resultado' => true
                ]); 
            } else {
                return $response -> withStatus(400) -> withJson([
                    'resultado' => false
                ]);
            }
        }
    });

    /**
     * PETICIÓN PUT
     * /setas/{id}
     * Modifica una seta en la base de datos, dado el ID.
     */
    $app -> put('/setas/{id}', function (Request $request, Response $response, array $args){
        $datos_put = $request -> getParsedBody();
        print_r($datos_put);

        if (validaDatosIns($datos_put)) {
            $estado = actualizar($datos_put, $args['id']);
            if ($estado != "ok"){
                return $response -> withStatus(418) -> withJson([
                    'error' => 'Hubo un error al actualizar en la base de datos.',
                    'mensaje' => $estado,
                    'tetera' => true
                ]);
            }
        } else {
            return $response -> withStatus(400) -> withJson([
                'error' => 'No se han insertado todos los valores. Para actualizar un ítem, hay que insertar mínimo nombre, descripción, nombre común, si es comestible y si es favorito.'
            ]);
        }
    });

    /**
     * PETICIÓN PUT
     * /setas/favorito/{id}
     * Cambia el estado del valor FAVORITO de la Seta obtenida por ID
     * al valor contrario.
     * 
     */
    $app -> put('/setas/favorito/{id}', function (Request $request, Response $response, array $args){
        $datos_put_fav = $request -> getParsedBody();
        print_r($datos_put_fav);

        if (isset($datos_put_fav['favorito'])) {
            $estado = cambiarFavoritos($datos_put_fav, $args['id']);
            if ($estado != 'ok') {
                return $response -> withStatus(418) -> withJson([
                    'error' => 'Hubo un error al actualizar en la base de datos.',
                    'mensaje' => $estado,
                    'tetera' => true
                ]);
            }
        } else {
            return $response -> withStatus(400) -> withJson([
                'error' => 'No se han insertado los valores necesarios.'
            ]);
        }
    });

    /**
     * PETICIÓN DELETE
     * /setas/{id}
     * Elimina una seta de la base de datos, dado el ID.
     */
    $app -> delete('/setas/{id}', function(Request $request, Response $response, array $args){
        
        $estado = eliminar($args['id']);

        if ($estado != "ok"){
            return $response -> withStatus(400) -> withJson([
                'error' => 'Hubo un error al eliminar el dato en la base de datos.',
                'mensaje' => $estado
            ]);
        }
    });

    //Ejecuta Slim.
    $app -> run();

?>