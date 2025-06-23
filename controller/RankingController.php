<?php

class RankingController{
    private $model;
    private $view;
    private $user;

    public function __construct($model,$view){
        $this->model = $model;
        $this->view = $view;
        $this->user = Security::getUser();
    }

    public function show(){

        $username = $this->user['username'];

        $data = [
            "usuarios" => $this->model->getRanking()
        ];/*guardo en un array toda la lista que me traiga de ranking*/

        $context = array_merge($data, ['username' => $username]);/*combina dos array : la lista ranking y los datos del saueiro session*/

        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->view->render('ranking', $context);
    }

}