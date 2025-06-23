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
/*
    public static function isAdmin() {
        $user = self::checkLogin();
        return isset($user['rol']) && $user['rol'] === 'administrador';
    }

    public static function isEditor() {
        $user = self::checkLogin();
        return isset($user['rol']) && $user['rol'] === 'editor';
    }

    public static function isPlayer() {
        $user = self::checkLogin();
        return isset($user['rol']) && $user['rol'] === 'jugador';
    }
*/
}