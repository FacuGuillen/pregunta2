<?php

class Security{

    public static function checkLogin() {

        if (!isset($_SESSION["user"])) {
            header("Location: /login/show");
            exit();
        }

        return $_SESSION["user"];
    }

    public static function getUser() {
        $user = self::checkLogin();
        $user["username"] = $user["nombre_usuario"] ?? null;
        return $user;
    }
}