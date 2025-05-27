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

    /*vistas SIN header y footer -> WARNING CONTROLER Y MEDO ME DAN NULL INTENTAR MAS ADELANTE
    public function renderSinHeaderNiFooter($contentFile,$data =array()){
        echo  $this->generateHtmlSinHeaderNiFooter(  $this->partialsPathLoader . '/' . $contentFile . "View.mustache" , $data);
    }

    private function generateHtmlSinHeaderNiFooter($contentFile, $data){
        $contentAsString = file_get_contents( $contentFile );
        return $this->mustache->render($contentAsString,$data);
    }*/



    /*vistas CON header y footer*/
    public function render($contentFile , $data = array() ){
        echo  $this->generateHtml(  $this->partialsPathLoader . '/' . $contentFile . "View.mustache" , $data);
    }

    public function generateHtml($contentFile, $data = array()) {
        $contentAsString = file_get_contents(  $this->partialsPathLoader .'/header.mustache');
        $contentAsString .= file_get_contents( $contentFile );
        $contentAsString .= file_get_contents($this->partialsPathLoader . '/footer.mustache');
        return $this->mustache->render($contentAsString, $data);
    }


}