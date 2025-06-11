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
        WHERE c.categoria = ?
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

        // Consulta de respuestas (acá también podrías usar prepared si querés)
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

}