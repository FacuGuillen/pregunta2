<?php

class ProponerModel
{
    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function getConnection() {
        return $this->db->getConnection();
    }
    public function getCategorias() {
        $sql = "SELECT id_categoria, categoria FROM categoria";
        $result = $this->db->getConnection()->query($sql);

        if (!$result) {
            die("Error al obtener categorías: " . $this->db->getConnection()->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function guardarPreguntaPropuesta($pregunta, $categoria, $id_usuario) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("INSERT INTO preguntas_propuestas (pregunta, id_categoria, estado, id_usuario) VALUES (?, ?, 'pendiente', ?)");
        $stmt->bind_param("ssi", $pregunta, $categoria, $id_usuario);
        $stmt->execute();
    }

    public function getLastInsertId() {
        return $this->db->getConnection()->insert_id;
    }

    public function guardarRespuestasPropuestas($idPregunta, $texto, $esCorrecta) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("INSERT INTO respuestas_propuestas (id_pregunta_propuesta, respuesta, es_correcta) VALUES (?, ?, ?)");
        $esCorrectaInt = $esCorrecta ? 1 : 0;
        $stmt->bind_param("isi", $idPregunta, $texto, $esCorrectaInt);
        $stmt->execute();
    }

}