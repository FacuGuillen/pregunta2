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
        $username = $_SESSION["user"]["nombre_usuario"] ?? null;

        $data = [ "usuario" => $this->model->traerLasPartidasDeUnUsuario($username)] ;

        $context = array_merge($data, ['username' => $username]);

        $this->view->render("listaPartida", $context);

    }

}