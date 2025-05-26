<?php
class LobbyController {
private $view;

public function __construct($view) {
$this->view = $view;
}

public function show() {
$this->view->render("lobby");
}
}