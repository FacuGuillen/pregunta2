<?php
class PreguntaModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getDbConnection() {
        return $this->db->getConnection();
    }

    public function getPreguntaAleatoria($categoria) {
        $conn = $this->db->getConnection();

        // 1. Obtener pregunta aleatoria por categoría (de forma segura)
        $stmt = $conn->prepare("
        SELECT p.id_pregunta, p.pregunta, c.categoria
        FROM pregunta p
        JOIN categoria c ON p.id_categoria = c.id_categoria
        WHERE LOWER(c.categoria) = LOWER(?)
        ORDER BY RAND()
        LIMIT 1
    ");
        $stmt->bind_param("s", $categoria);
        $stmt->execute();
        $result = $stmt->get_result();
        $pregunta = $result->fetch_assoc();
        $stmt->close();

        if (!$pregunta) return null;

        $id_pregunta = $pregunta['id_pregunta'];

        // 2. Obtener respuestas aleatorias para la pregunta
        $stmt2 = $conn->prepare("
        SELECT id_respuesta, respuesta 
        FROM respuesta 
        WHERE id_pregunta = ?
        ORDER BY RAND()
    ");
        $stmt2->bind_param("i", $id_pregunta);
        $stmt2->execute();
        $respuestas = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt2->close();

        $pregunta['respuestas'] = $respuestas;

        return $pregunta;
    }


    // Verifica si una respuesta es correcta
    public function esCorrecta($id_respuesta) {
        $result = $this->db->getConnection()->query("
        SELECT es_correcta FROM respuesta WHERE id_respuesta = $id_respuesta
        ")->fetch_assoc();

        return $result['es_correcta'];
    }

    public function getPreguntaPorCategoria($categoria){
        $pregunta = $this->db->getConnection()->query("
        SELECT categoria FROM categoria WHERE ");
    }
}