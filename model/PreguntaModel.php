<?php
class PreguntaModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getDbConnection() {
        return $this->db->getConnection();
    }

    public function esCorrecta($id_respuesta) {
        $result = $this->db->getConnection()->query("
        SELECT es_correcta FROM respuesta WHERE id_respuesta = $id_respuesta
        ")->fetch_assoc();

        return $result['es_correcta'];
    }

    public function getPreguntaPorCategoria($categoria,$idUsuario) {
        $conn = $this->db->getConnection();

        $stmt = $conn->prepare("
        SELECT p.id_pregunta, p.pregunta, c.categoria, c.color
        FROM pregunta p
        JOIN categoria c ON p.id_categoria = c.id_categoria
        WHERE c.categoria = ? AND  p.activo = 1
        /*que no se repita*/
        AND NOT exists(
            SELECT 1
            FROM pregunta_usuarios pu 
            WHERE pu.id_pregunta = p.id_pregunta AND pu.id_usuario = ?
        )
        ORDER BY RAND()
        LIMIT 1
    ");
        $stmt->bind_param("si", $categoria,$idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $pregunta = $resultado->fetch_assoc();
        $stmt->close();

        if (!$pregunta) {
            return ['status' => 'no-preguntas-disponibles'];
        }

        $cantidad = $this->cantidadDeVecesRespondidaPorPregunta($pregunta['id_pregunta']);
        if ($cantidad > 10) {
            return ['status' => 'repetida-muchas-veces'];
        }

        $resStmt = $conn->prepare("
        SELECT id_respuesta, respuesta 
        FROM respuesta 
        WHERE id_pregunta = ?
        ORDER BY RAND()
    ");
        $resStmt->bind_param("i", $pregunta['id_pregunta']);
        $resStmt->execute();
        $respuestas = $resStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $resStmt->close();

        //Mando colorcito a la vista!
        $consulta = $conn->query("SELECT color FROM categoria WHERE categoria = '$categoria'");
        $color = $consulta->fetch_assoc();
        $pregunta['color'] = $color['color'];


        $pregunta['respuestas'] = $respuestas;

        return [
            'status' => 'ok',
            'pregunta' => $pregunta,
        ];
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

    public function borrarTodasPreguntasqueYaVioElUsuario($idUsuario)
    {   $db = $this->db->getConnection();
        $sql = "DELETE FROM pregunta_usuarios WHERE id_usuario = ? ";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();

    }

    public function guardarPreguntasQueYaVioElUsuario($idUsuario,$idPregunta )
    {   $db = $this->db->getConnection();
        $sql = "INSERT INTO pregunta_usuarios (id_usuario, id_pregunta) VALUES (?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $idUsuario, $idPregunta);
        $stmt->execute();
    }

    public function guardarPreguntasQueElUsuarioContesto($idUsuario,$pregunta,$es_correcta)
    {  $db = $this->db->getConnection();
        $sql = "INSERT INTO preguntas_usuarios_respuestas (id_usuario, id_preguntas, respuesta_correcta) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iii", $idUsuario,$pregunta,$es_correcta);
        $stmt->execute();

    }

    /*-----------------------------------------------------------------------------*/


    public function traerPreguntaClasificadaSegunLaDificultadUsuarioYCategoria($categoria,$idUsuario){
        $conn = $this->db->getConnection();
        $dificultadUsuario = $this->traerEltipoDificultadDelUsuario($idUsuario);
        $stmt = $conn->prepare(" 
        SELECT p.id_pregunta, p.pregunta, c.categoria, c.color, dificultad.dificultad_real 
        FROM pregunta p JOIN categoria c ON p.id_categoria = c.id_categoria 
            LEFT JOIN ( SELECT id_preguntas, 
                        CASE WHEN COUNT(*) = 0 THEN 'normal' 
                        WHEN SUM(respuesta_correcta = 1)/COUNT(*) > 0.7 THEN 'facil' 
                        WHEN SUM(respuesta_correcta = 1)/COUNT(*) < 0.3 THEN 'dificil' 
                        ELSE 'normal' END AS dificultad_real 
                        FROM preguntas_usuarios_respuestas 
                        GROUP BY id_preguntas ) AS dificultad ON dificultad.id_preguntas = p.id_pregunta 
        WHERE c.categoria = ? 
             AND COALESCE(dificultad.dificultad_real, 'normal') = ?
             AND NOT EXISTS ( SELECT 1 
                              FROM pregunta_usuarios pu 
                              WHERE pu.id_pregunta = p.id_pregunta AND pu.id_usuario = ?)
        ORDER BY RAND()
        LIMIT 1");

        $stmt->bind_param("ssi", $categoria,$dificultadUsuario,$idUsuario);
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

    public function nuevaCategoriaDisponible($idUsuario)
    {   $db = $this->db->getConnection();
        $categoria = null;
        $sql = "SELECT c.categoria 
                FROM categoria c join pregunta p on c.id_categoria=p.id_categoria 
                WHERE NOT EXISTS ( SELECT 1 
                                   FROM pregunta_usuarios pu 
                                   WHERE pu.id_pregunta = p.id_pregunta AND pu.id_usuario = ?)
                ORDER BY RAND()
                LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();

        $stmt->bind_result($categoria);
        $stmt->fetch();
        $stmt->close();
        return $categoria;

    }

    private function traerEltipoDificultadDelUsuario($idUsuario)
    {
        $totalRespondidas = $this->cuantasContestoEnTotal($idUsuario);
        $totalRespondidasBien = $this->cuantasPreguntasRespondioBienElUsuario($idUsuario);

        $nivelUsuario = 'normal';
        if ($totalRespondidas > 0){
            $porcentaje = $totalRespondidasBien / $totalRespondidas ;
            if ($porcentaje > 0.7){
                $nivelUsuario = 'dificil';
            }else if ($porcentaje < 0.3){
                $nivelUsuario = 'facil';
            }
        }
        return $nivelUsuario;
    }
    private function cuantasContestoEnTotal($idUsuario)
    {  $db = $this->db->getConnection();
        $total = 0;
        $sql = "SELECT COUNT(*) FROM preguntas_usuarios_respuestas WHERE id_usuario = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();

        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();
        return $total;
    }

    private function cuantasPreguntasRespondioBienElUsuario($idUsuario)
    {   $db = $this->db->getConnection();
        $total = 0;

        $sql = "SELECT COUNT(*) FROM preguntas_usuarios_respuestas WHERE id_usuario = ? AND respuesta_correcta = 1";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();

        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();
        return $total;
    }

    private function cantidadDeVecesRespondidaPorPregunta($id_pregunta)
    {  $db = $this->db->getConnection();
        $total = 0;

        $sql = "SELECT COUNT(*) FROM pregunta_usuarios pu WHERE pu.id_pregunta = ? ";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_pregunta);
        $stmt->execute();

        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();
        return $total;
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

    //lautaro preguntas propuestas
    // Devuelve todas las preguntas propuestas con categoría y usuario
    public function getPreguntasPropuestas() {
        $sql = "SELECT pp.*, c.categoria, u.nombre_usuario 
            FROM preguntas_propuestas pp
            JOIN categoria c ON pp.id_categoria = c.id_categoria
            JOIN usuarios u ON pp.id_usuario = u.id_usuario
            WHERE pp.estado = 'pendiente'";

        $result = $this->db->getConnection()->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

// Devuelve respuestas asociadas a una pregunta propuesta
    public function getRespuestasPropuestasPorPregunta($id_pregunta_propuesta) {
        $sql = "SELECT * FROM respuestas_propuestas WHERE id_pregunta_propuesta = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bind_param("i", $id_pregunta_propuesta);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

//preguntas propuestas
    public function getPreguntaPropuestaById($id) {
        $sql = "SELECT pp.*, c.categoria, u.nombre_usuario 
            FROM preguntas_propuestas pp
            JOIN categoria c ON pp.id_categoria = c.id_categoria
            JOIN usuarios u ON pp.id_usuario = u.id_usuario
            WHERE pp.id_pregunta_propuesta = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
}


// Cambia estado de propuesta
    public function actualizarEstadoPropuesta($id, $estado) {
        $sql = "UPDATE preguntas_propuestas SET estado = ? WHERE id_pregunta_propuesta = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bind_param("si", $estado, $id);
        $stmt->execute();
    }

    public function actualizarEstadoRespuestasPropuestas($id_pregunta_propuesta, $estado) {
        $sql = "UPDATE respuestas_propuestas SET estado = ? WHERE id_pregunta_propuesta = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bind_param("si", $estado, $id_pregunta_propuesta);
        $stmt->execute();
    }


    public function insertarPreguntaFinal($pregunta, $idCategoria) {
        $sql = "INSERT INTO pregunta (pregunta, id_categoria, activo) VALUES (?, ?, 1)";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bind_param("si", $pregunta, $idCategoria);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function insertarRespuestaFinal($idPregunta, $respuesta, $esCorrecta) {
        $sql = "INSERT INTO respuesta (id_pregunta, respuesta, es_correcta) VALUES (?, ?, ?)";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bind_param("isi", $idPregunta, $respuesta, $esCorrecta);
        $stmt->execute();
    }

}