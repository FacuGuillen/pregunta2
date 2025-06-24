<?php
require_once("core/Database.php");
require_once("core/FilePresenter.php");
require_once("core/MustachePresenter.php");
require_once("core/Router.php");
require_once ("configuration/constants.php");
require_once ("codigo_qr/phpqrcode/qrlib.php");

require_once("controller/RegisterController.php");
require_once("controller/LoginController.php");
require_once("controller/IndexController.php");
require_once("controller/ProfileController.php");
require_once("controller/JuegoController.php");
require_once("controller/LobbyController.php");
require_once("controller/RuletaController.php");
require_once("controller/RankingController.php");
require_once("controller/ProfileGamerController.php");
require_once("controller/ListaPartidaController.php");
require_once("controller/ProponerController.php");



require_once("model/RegisterModel.php");
require_once("model/LoginModel.php");
require_once("model/UserModel.php");
require_once("model/PreguntaModel.php");
require_once("model/RankingModel.php");
require_once("model/ProfileGamerModel.php");
require_once("model/ProponerModel.php");




include_once('vendor/mustache/src/Mustache/Autoloader.php');

class Configuration
{
    public function getDatabase()
    {
        $config = $this->getIniConfig();

        return new Database(
            $config["database"]["server"],
            $config["database"]["user"],
            $config["database"]["dbname"],
            $config["database"]["pass"]
        );
    }

    public function getIniConfig()
    {
        return parse_ini_file("configuration/config.ini", true);
    }

    public function getLobbyController()
    {
        return new LobbyController($this->getViewer());
    }

    public function getProfileController(){
        return new ProfileController(new UserModel($this->getDatabase()), $this->getViewer()
        );
    }

    public function getRankingController(){
        return new RankingController(new RankingModel($this->getDatabase()),$this->getViewer()
        );
    }
    public function getProfileGamerController(){
        return new ProfileGamerController(new ProfileGamerModel($this->getDatabase()),$this->getViewer()
        );
    }
    public function getListaPartidaController(){
        return new ListaPartidaController(new UserModel($this->getDatabase()),$this->getViewer()
        );
    }

    // Juego
    public function getRuletaController() {
        return new RuletaController($this->getViewer());
    }

    public function getJuegoController() {
        return new JuegoController(new PreguntaModel($this->getDatabase()), $this->getViewer()
        );
    }


    // Sesiones
    public function getRegisterController()
    {
        return new RegisterController(new RegisterModel($this->getDatabase()),$this->getViewer());
    }

    public function getLoginController() {
        return new LoginController(new LoginModel($this->getDatabase()), $this->getViewer()
        );
    }

    public function getIndexController() {
        return new IndexController($this->getViewer());
    }

    public function getProponerController()
    {
        return new ProponerController(new ProponerModel($this->getDatabase()), $this->getViewer());

    }

    public function getRouter()
    {
        return new Router("getIndexController", "show", $this);
    }

    public function getViewer()
    {
        //return new FileView();
        return new MustachePresenter("view");
    }
}