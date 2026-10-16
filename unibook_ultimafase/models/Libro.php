<?php

class Libro
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }


    public function publicar(
        $titulo,
        $autor,
        $editorial,
        $universidad,
        $carrera,
        $semestre,
        $materia,
        $descripcion,
        $precio,
        $estado,
        $tipo_publicacion,
        $imagen,
        $usuario_id
    ){
        $sql = "INSERT INTO libros
        (
            titulo,
            autor,
            editorial,
            universidad,
            carrera,
            semestre,
            materia,
            descripcion,
            precio,
            estado,
            tipo_publicacion,
            imagen,
            usuario_id
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);

        if(!$stmt){
            return false;
        }

        $stmt->bind_param(
            "ssssssssdsssi",
            $titulo,
            $autor,
            $editorial,
            $universidad,
            $carrera,
            $semestre,
            $materia,
            $descripcion,
            $precio,
            $estado,
            $tipo_publicacion,
            $imagen,
            $usuario_id
        );

        $resultado = $stmt->execute();

        $stmt->close();

        return $resultado;
    }


    public function obtenerTodos()
    {
        $sql = "SELECT *
                FROM libros
                ORDER BY fecha_publicacion DESC";

        return $this->conexion->query($sql);
    }


    public function obtenerPorId($id)
    {
        $sql = "SELECT *
                FROM libros
                WHERE id = ?";

        $stmt = $this->conexion->prepare($sql);

        if(!$stmt){
            return null;
        }

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $resultado = $stmt->get_result();

        $libro = $resultado->fetch_assoc();

        $stmt->close();

        return $libro;
    }


    public function obtenerPorUsuario($usuario_id)
    {
        $sql = "SELECT *
                FROM libros
                WHERE usuario_id = ?
                ORDER BY fecha_publicacion DESC";

        $stmt = $this->conexion->prepare($sql);

        if(!$stmt){
            return false;
        }

        $stmt->bind_param("i", $usuario_id);

        $stmt->execute();

        $resultado = $stmt->get_result();

        $stmt->close();

        return $resultado;
    }


    public function eliminar($id, $usuario_id)
    {
        $sql = "DELETE FROM libros
                WHERE id = ?
                AND usuario_id = ?";

        $stmt = $this->conexion->prepare($sql);

        if(!$stmt){
            return false;
        }

        $stmt->bind_param("ii", $id, $usuario_id);

        $resultado = $stmt->execute();

        $stmt->close();

        return $resultado;
    }


    /*
    |--------------------------------------------------------------------------
    | DISPONIBILIDAD
    |--------------------------------------------------------------------------
    */


    public function reservarSiDisponible($id)
    {
        $sql = "UPDATE libros
                SET estado_disponibilidad = 'reservado'
                WHERE id = ?
                AND estado_disponibilidad = 'disponible'";

        $stmt = $this->conexion->prepare($sql);

        if(!$stmt){
            return false;
        }

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $afectadas = $stmt->affected_rows;

        $stmt->close();

        return $afectadas > 0;
    }


    public function marcarDisponible($id)
    {
        $sql = "UPDATE libros
                SET estado_disponibilidad = 'disponible'
                WHERE id = ?";

        $stmt = $this->conexion->prepare($sql);

        if(!$stmt){
            return false;
        }

        $resultado = $stmt->execute();

        $stmt->close();

        return $resultado;
    }


    /*
    |--------------------------------------------------------------------------
    | INTERCAMBIO
    |--------------------------------------------------------------------------
    */


    public function estanReservadosParaIntercambio(
        $libroSolicitadoId,
        $libroOfrecidoId
    ) {
        $sql = "SELECT id, estado_disponibilidad
                FROM libros
                WHERE id IN (?, ?)";

        $stmt = $this->conexion->prepare($sql);

        if(!$stmt){
            return false;
        }

        $stmt->bind_param(
            "ii",
            $libroSolicitadoId,
            $libroOfrecidoId
        );

        $stmt->execute();

        $resultado = $stmt->get_result();

        $libros = [];

        while($libro = $resultado->fetch_assoc()){
            $libros[(int)$libro['id']] =
                $libro['estado_disponibilidad'];
        }

        $stmt->close();

        return (
            isset($libros[$libroSolicitadoId]) &&
            isset($libros[$libroOfrecidoId]) &&
            $libros[$libroSolicitadoId] === 'reservado' &&
            $libros[$libroOfrecidoId] === 'reservado'
        );
    }


    public function marcarIntercambiadoSiReservado($id)
    {
        $sql = "UPDATE libros
                SET estado_disponibilidad = 'intercambiado'
                WHERE id = ?
                AND estado_disponibilidad = 'reservado'";

        $stmt = $this->conexion->prepare($sql);

        if(!$stmt){
            return false;
        }

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $afectadas = $stmt->affected_rows;

        $stmt->close();

        return $afectadas > 0;
    }


    public function marcarDisponibleSiReservado($id)
    {
        $sql = "UPDATE libros
                SET estado_disponibilidad = 'disponible'
                WHERE id = ?
                AND estado_disponibilidad = 'reservado'";

        $stmt = $this->conexion->prepare($sql);

        if(!$stmt){
            return false;
        }

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $afectadas = $stmt->affected_rows;

        $stmt->close();

        return $afectadas > 0;
    }


    /*
    |--------------------------------------------------------------------------
    | VENTA
    |--------------------------------------------------------------------------
    */


    public function marcarVendidoSiReservado($id)
    {
        $sql = "UPDATE libros
                SET estado_disponibilidad = 'vendido'
                WHERE id = ?
                AND estado_disponibilidad = 'reservado'";

        $stmt = $this->conexion->prepare($sql);

        if(!$stmt){
            return false;
        }

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $afectadas = $stmt->affected_rows;

        $stmt->close();

        return $afectadas > 0;
    }


    /*
    |--------------------------------------------------------------------------
    | HISTORIA
    |--------------------------------------------------------------------------
    */


    public function obtenerHistorial()
    {
        $sql = "SELECT *
                FROM libros
                WHERE estado_disponibilidad IN ('vendido', 'intercambiado')
                ORDER BY fecha_publicacion DESC";

        $resultado = $this->conexion->query($sql);

        if(!$resultado){
            return [];
        }

        $historial = [];

        while($libro = $resultado->fetch_assoc()){
            $historial[] = $libro;
        }

        return $historial;
    }
}