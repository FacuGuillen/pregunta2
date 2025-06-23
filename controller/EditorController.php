<?php

require_once ("configuration/constants.php");

class EditorController{
    private $view;
    private $model;
    private $user;

    public function __construct($model, $view)
    {
        $this->model = $model;
        $this->view = $view;

        $this->user = Security::getUser();
    }

    public function show() {
        $username = $_SESSION["user"]["nameuser"] ?? null;
        $preguntas = $this->model->getAllQuestions();

        var_dump($preguntas); // Agregado para debug
        exit;

        $this->view->render("editor", [
            "username" => $username,
            "preguntas" => $preguntas
        ]);

    }




    private function redirectTo($str)
    {
        header("Location: " . $str);
        exit();
    }

    public function showQuestions() {
        $preguntas = $this->model->getAllQuestions();

        $data = [
            'username' => $this->user['nombre_usuario'] ?? '',
            'preguntas' => $preguntas
        ];

        $this->view->render("editor", $data);

}}