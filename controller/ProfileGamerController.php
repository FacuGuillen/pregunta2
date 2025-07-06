<?php
include_once 'codigo_qr/phpqrcode/qrlib.php';
class ProfileGamerController{

    public $view;
    private $model;

    public function __construct($model,$view){
        $this->model = $model;
        $this->view = $view;
    }

    public function show($nombreJugador = null){

        $username = $_SESSION["user"]["username"] ?? null;

        if ($nombreJugador == null) {
            header('location: /ranking/show?error=null');
            exit();
        }

        $jugador = $this->model->traerLosdatosDelUsuarioYSuRanking($nombreJugador);

        if (empty($jugador) || empty($jugador[0])) {
            header('location: /ranking/show?error=invalido');
            exit();
        }

        $data = ["jugador" => $jugador[0]] ;

        // genero qr
        $idUsuario = $data["jugador"]["id_usuario"];
        $qr_url = "http://localhost:8080/profile/show/$idUsuario";

        $context = array_merge($data, [
            'username' => $username,
            'qr_url' =>$qr_url
        ]);

        $this->view->render("profileGamer", $context);
    }

    public function generarQr($idUsuario){
        $qr_url = "http://localhost:8080/profile/show/$idUsuario";
        QRcode::png($qr_url, false, QR_ECLEVEL_L, 8);
    }
}