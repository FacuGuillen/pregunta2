<?php
require_once("core/Database.php");
require_once("core/FilePresenter.php");
require_once("core/MustachePresenter.php");
require_once("core/Router.php");
require_once ("configuration/constants.php");

require_once("controller/HomeController.php");
require_once("controller/GroupController.php");
require_once("controller/SongController.php");
require_once("controller/TourController.php");
require_once("controller/RegisterController.php");
require_once("controller/LoginController.php");
require_once("controller/IndexController.php");
require_once("controller/ProfileController.php");


require_once("model/GroupModel.php");
require_once("model/SongModel.php");
require_once("model/TourModel.php");
require_once("model/RegisterModel.php");
require_once("model/LoginModel.php");
require_once("model/UserModel.php");

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

    public function getSongController()
    {
        return new SongController(new SongModel($this->getDatabase()), $this->getViewer());
    }

    public function getTourController()
    {
        return new TourController(new TourModel($this->getDatabase()), $this->getViewer());
    }

    public function getHomeController()
    {
        return new HomeController($this->getViewer());
    }

    public function getIndexController()
    {
        return new IndexController($this->getViewer());
    }

    public function getRegisterController()
    {
        return new RegisterController(new RegisterModel($this->getDatabase()),$this->getViewer());
    }

    public function getLoginController() {
        return new LoginController(new LoginModel($this->getDatabase()), $this->getViewer()
        );
    }

    public function getLobbyController() {
        return new LobbyController($this->getViewer());
    }


    public function getGroupController()
    {
        return new GroupController(new GroupModel($this->getDatabase()), $this->getViewer());
    }

    public function getProfileController(){
        return new ProfileController(new UserModel($this->getDatabase()),
            $this->getViewer()
        );
    }

    public function getRouter()
    {
        return new Router("getHomeController", "show", $this);
    }

    public function getViewer()
    {
        //return new FileView();
        return new MustachePresenter("view");
    }
}