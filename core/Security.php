<?php

class Security{

    public static function checkLogin() {
        session_start();

        if (!isset($_SESSION["user"])) {
            header("Location: /login/show");
            exit();
        }

        return $_SESSION["user"];
    }

}