<?php
class ListaPartidaController
{
    private $model;
    private $view;


    public function __construct($model, $view){
        $this->model = $model;
        $this->view = $view;
    }

    public function show()
    {
        $nombre = ['nombre_usuario'];

        $data = [
            "usuario" => $this->model->traerLasPartidasDeUnUsuario($nombre)
        ] ;

        $context = array_merge($data, ['username']);

        $this->view->render("listaPartida", $context);

    }

}