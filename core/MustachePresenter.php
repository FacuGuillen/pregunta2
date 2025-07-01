<?php

class MustachePresenter{
    private $mustache;
    private $partialsPathLoader;

    public function __construct($partialsPathLoader){
        Mustache_Autoloader::register();
        $this->mustache = new Mustache_Engine(
            array(
                'partials_loader' => new Mustache_Loader_FilesystemLoader( $partialsPathLoader )
            ));
        $this->partialsPathLoader = $partialsPathLoader;
    }

    public function render($contentFile, $data = array()) {
        if (!isset($data["css"])) {
            $data["css"] = $contentFile . ".css";
        }

        // Agregamos el usuario global si está logueado
        if (isset($_SESSION["user"])) {
            $user = $_SESSION["user"];
            $data["username"] = $user["nombre_usuario"] ?? null;
            $data["tipo_usuario"] = $user["tipo_usuario"] ?? null;

            // Agregamos banderas según tipo
            $data["isJugador"] = $user["tipo_usuario"] == 1;
            $data["isEditor"] = $user["tipo_usuario"] == 2;
            $data["isAdmin"] = $user["tipo_usuario"] == 3;
        }

        echo $this->generateHtml($this->partialsPathLoader . '/' . $contentFile . "View.mustache", $data);
    }


    public function generateHtml($contentFile, $data = array()) {
        $contentAsString = file_get_contents(  $this->partialsPathLoader .'/header.mustache');
        $contentAsString .= file_get_contents( $contentFile );
        $contentAsString .= file_get_contents($this->partialsPathLoader . '/footer.mustache');
        return $this->mustache->render($contentAsString, $data);
    }


}