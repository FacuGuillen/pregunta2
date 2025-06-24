<?php
class PreguntaModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getDbConnection() {
        return $this->db->getConnection();
    }

    // Verifica si una respuesta es correcta
    public function esCorrecta($id_respuesta) {
        $result = $this->db->getConnection()->query("
        SELECT es_correcta FROM respuesta WHERE id_respuesta = $id_respuesta
        ")->fetch_assoc();

        return $result['es_correcta'];
    }

    public function getPreguntaPorCategoria($categoria) {
        $conn = $this->db->getConnection();

        $stmt = $conn->prepare("
        SELECT p.id_pregunta, p.pregunta, c.categoria, c.color
        FROM pregunta p
        JOIN categoria c ON p.id_categoria = c.id_categoria
        WHERE c.categoria = ? and p.activo = 1
        ORDER BY RAND()
        LIMIT 1
    ");
        $stmt->bind_param("s", $categoria);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $pregunta = $resultado->fetch_assoc();
        $stmt->close();

        if (!$pregunta) return null;

        $id_pregunta = $pregunta['id_pregunta'];

        $resStmt = $conn->prepare("
        SELECT id_respuesta, respuesta 
        FROM respuesta 
        WHERE id_pregunta = ?
        ORDER BY RAND()
    ");
        $resStmt->bind_param("i", $id_pregunta);
        $resStmt->execute();
        $respuestas = $resStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $resStmt->close();

        $pregunta['respuestas'] = $respuestas;

        return $pregunta;
    }

    public function guardarPartida($puntaje){
        $db = $this->db->getConnection();

        $sql = "INSERT INTO partidas (puntaje) VALUES ('$puntaje')";

        if (!$db->query($sql)) {
            return $db->error;
        }

        $idPartida = $db->insert_id;

        return $idPartida;
    }

    public function guardarPartidaUsuario($idUsuario, $idPartida){
        $db = $this->db->getConnection();

        $stmt = $db->prepare("INSERT INTO partidas_usuarios (id_usuario, id_partidas) VALUES (?, ?)");

        if (!$stmt) {
            return $db->error;
        }

        $stmt->bind_param("ii", $idUsuario, $idPartida);

        if (!$stmt->execute()) {
            return $stmt->error;
        }

        $stmt->close();

        return true;
    }

    public function getAllQuestions() {
        $sql = "SELECT * FROM pregunta";
        $result = $this->db->getConnection()->query($sql);

        if (!$result) {
            die("Error en la consulta: " . $this->db->getConnection()->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPreguntasPorIdCategoria($idCategoria) {
        $stmt = $this->db->getConnection()->prepare("
        SELECT id_pregunta, pregunta, activo
        FROM pregunta
        WHERE id_categoria = ?
    ");
        $stmt->bind_param("i", $idCategoria);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPregunta($id_pregunta) {
        $stmt = $this->db->getConnection()->prepare("
        select * from pregunta where id_pregunta = ?");
        $stmt->bind_param("i", $id_pregunta);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getRespuestasPorPregunta($id_pregunta) {
        $stmt = $this->db->getConnection()->prepare("
        SELECT * FROM respuesta WHERE id_pregunta = ?
    ");
        $stmt->bind_param("i", $id_pregunta);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function actualizarPregunta($idPregunta, $texto) {
        $stmt = $this->db->getConnection()->prepare("
        UPDATE pregunta 
        SET pregunta = ? 
        WHERE id_pregunta = ?
    ");
        $stmt->bind_param("si", $texto, $idPregunta);
        $stmt->execute();
        $stmt->close();
    }

    public function actualizarRespuesta($idRespuesta, $texto, $esCorrecta) {
        $stmt = $this->db->getConnection()->prepare("
        UPDATE respuesta 
        SET respuesta = ?, es_correcta = ? 
        WHERE id_respuesta = ?
    ");
        $stmt->bind_param("sii", $texto, $esCorrecta, $idRespuesta);
        $stmt->execute();
        $stmt->close();
    }

    public function eliminarPregunta($idPregunta) {
        $conn = $this->db->getConnection();

        // Primero borrar las respuestas relacionadas
        $stmt = $conn->prepare("DELETE FROM respuesta WHERE id_pregunta = ?");
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();
        $stmt->close();

        // Luego borrar la pregunta
        $stmt2 = $conn->prepare("DELETE FROM pregunta WHERE id_pregunta = ?");
        $stmt2->bind_param("i", $idPregunta);
        $stmt2->execute();
        $stmt2->close();

        return true;
    }


    public function pausarPregunta($idPregunta) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("
        UPDATE pregunta
        SET activo = CASE WHEN activo = 1 THEN 0 ELSE 1 END
        WHERE id_pregunta = ?");
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();
        $stmt->close();
        return true;
    }




}