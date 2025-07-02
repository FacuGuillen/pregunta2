<?php

class RankingController{
    private $model;
    private $view;

    public function __construct($model,$view){
        $this->model = $model;
        $this->view = $view;
    }

    public function show(){

        $username = $_SESSION["user"]["nombre_usuario"] ?? null;

        if (isset($_GET['error'])) {
            if ($_GET['error'] === 'null') {
                echo "<script>alert('Ingrese un usuario a buscar');</script>";
            } elseif ($_GET['error'] === 'invalido') {
                echo "<script>alert('Usuario inexistente');</script>";
            }
        }

        $data = [ "usuarios" => $this->model->getRanking()];


        $context = array_merge($data, ['username' => $username]);

        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->view->render('ranking', $context);
    }

}