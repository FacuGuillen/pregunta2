<?php

class Security{

    public static function checkLogin() {

        if (!isset($_SESSION["user"])) {
            header("Location: /login/show");
            exit();
        }

        return $_SESSION["user"];
    }

}