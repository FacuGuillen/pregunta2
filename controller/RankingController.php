<?php

class RankingController{
    public $model;
    public $view;


    public function __construct($model,$view){
        $this->model = $model;
        $this->view = $view;
    }

    public function show(){

        $username = Security::getUser();

        $data = [
            "usuarios" => $this->model->getRanking()
        ];/*guardo en un array toda la lista que me traiga de ranking*/

        $context = array_merge($data, ['username' => $username]);/*combina dos array : la lista ranking y los datos del saueiro session*/

        $this->view->render('ranking', $context);
    }

}