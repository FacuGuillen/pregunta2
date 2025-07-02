<?php

use Couchbase\View;

class CuestionController{

    public $model;
    public $view;

    public function __construct($view){
        $this->view = $view;
    }

    public function show(){
        $this->view->render('cuestionNew');
    }

    public function validarPregunta(){
        $pregunta = $_POST['pregunta'] ?? '';
        $categoria = $_POST['categoria']??'';

        if (empty($pregunta) || empty($categoria)) {
            //$this->redirectTo("/Pregunta2/cuestion/show");
            $this->view->render('cuestionNew',[
                "error" => "campo vacio",
            ]);
            exit();

        }

        $this->redirectTo("/Pregunta2/lobby/show");


    }

    /*----------------------------*/
    private function redirectTo($str)
    {
        header("Location: " . $str);
        exit();
    }
}