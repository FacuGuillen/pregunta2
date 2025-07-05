<?php
class AdministradorModel{
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getDbConnection() {
        return $this->db->getConnection();
    }

    public function cantidadPartidasJugadas() {
        $sql = "SELECT COUNT(*) AS total FROM partidas_usuarios";
        $resultado = $this->getDbConnection()->query($sql);

        if ($resultado) {
            $fila = $resultado->fetch_assoc();
            return (int) $fila['total'];
        }
        return 0;
    }


    public function getJugadoresRegistrados(){
        $sql = "SELECT COUNT(*) AS total FROM usuarios where tipo_usuario = 1";
        $resultado = $this->getDbConnection()->query($sql);

        if ($resultado) {
            $fila = $resultado->fetch_assoc();
            return (int) $fila['total'];
        }
        return 0;

    }


    public function cantidadPreguntasEnElJuego(){
        $sql = "SELECT COUNT(*) AS total FROM pregunta";
        $resultado = $this->getDbConnection()->query($sql);

        if ($resultado) {
            $fila = $resultado->fetch_assoc();
            return (int) $fila['total'];
        }
        return 0;
    }


    public function getUsuariosNuevos(){
        $sql = "SELECT COUNT(*) AS total FROM usuarios WHERE fecha_creacion >= NOW() - INTERVAL 7 DAY;";
        $resultado = $this->getDbConnection()->query($sql);
        if ($resultado) {
            $fila = $resultado->fetch_assoc();
            return (int) $fila['total'];
        }
        return 0;
    }


    public function getPreguntasCreadasPorUsuarios(){
        $sql = "SELECT COUNT(*) AS total FROM preguntas_propuestas where estado = 'aprobada'";
        $resultado = $this->getDbConnection()->query($sql);
        if ($resultado) {
            $fila = $resultado->fetch_assoc();
            return (int) $fila['total'];
        }
        return 0;
    }

    public function getUsuariosPorPais() {
        $db = $this->db->getConnection();

        $sql = "SELECT r.pais, COUNT(*) AS cantidad_usuarios
            FROM usuarios u
            LEFT JOIN residencia r ON u.tipo_residencia = r.id_residencia
            GROUP BY r.pais
            ORDER BY cantidad_usuarios DESC";

        $result = $db->query($sql);

        $datos = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $pais = $row['pais'] ?? 'Desconocido';
                $datos[] = [$pais, (int)$row['cantidad_usuarios']];
            }
        }

        return $datos;
    }

    public function getTopUsuarioPorcentajeCorrectas() {
        $sql = "
        SELECT u.nombre_usuario,
               ROUND((SUM(CASE WHEN pur.respuesta_correcta = 1 THEN 1 ELSE 0 END) * 100.0 / COUNT(*)), 2) AS porcentaje_correctas
        FROM preguntas_usuarios_respuestas pur
        JOIN usuarios u ON pur.id_usuario = u.id_usuario
        GROUP BY u.nombre_usuario
        ORDER BY porcentaje_correctas DESC
        LIMIT 1
    ";
        $result = $this->getDbConnection()->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return $row;
        }
        return null;
    }


    public function getUsuariosPorSexo() {
        $sql = "SELECT sexo, COUNT(*) AS cantidad FROM usuarios GROUP BY sexo";
        $result = $this->getDbConnection()->query($sql);

        $datos = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $datos[] = $row;
            }
        }

        return $datos;
    }

    public function getUsuariosPorEdad() {
        $sql = "
        SELECT 
            CASE 
                WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) < 18 THEN 'Menores'
                WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 18 AND 60 THEN 'Adultos'
                ELSE 'Jubilados'
            END AS grupo,
            COUNT(*) AS cantidad
        FROM usuarios
        GROUP BY grupo
    ";

        $result = $this->getDbConnection()->query($sql);
        $datos = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $datos[] = $row;
            }
        }
        return $datos;
    }



}