<?php

class Compra
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }


    /*
    ============================================================
    CREAR COMPRA
    ============================================================
    */

    public function crear(
        $libro_id,
        $comprador_id,
        $vendedor_id,
        $precio,
        $metodo_pago
    ) {

        $sql = "INSERT INTO compras
                (
                    libro_id,
                    comprador_id,
                    vendedor_id,
                    precio,
                    metodo_pago,
                    estado
                )
                VALUES (?, ?, ?, ?, ?, 'pendiente')";

        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "iiids",
            $libro_id,
            $comprador_id,
            $vendedor_id,
            $precio,
            $metodo_pago
        );

        $resultado = $stmt->execute();

        $id = $this->conexion->insert_id;

        $stmt->close();

        return $resultado ? $id : false;
    }


    /*
    ============================================================
    OBTENER DETALLE DE COMPRA
    ============================================================
    */

    public function obtenerDetalleParaUsuario(
        $compra_id,
        $usuario_id
    ) {

        $sql = "SELECT
                    c.*,

                    l.titulo,
                    l.autor,
                    l.imagen,
                    l.descripcion,
                    l.tipo_publicacion,
                    l.estado_disponibilidad,

                    comprador.nombre AS nombre_comprador,
                    comprador.apellido AS apellido_comprador,
                    comprador.correo AS correo_comprador,

                    vendedor.nombre AS nombre_vendedor,
                    vendedor.apellido AS apellido_vendedor,
                    vendedor.correo AS correo_vendedor

                FROM compras c

                INNER JOIN libros l
                    ON l.id = c.libro_id

                INNER JOIN usuarios comprador
                    ON comprador.id = c.comprador_id

                INNER JOIN usuarios vendedor
                    ON vendedor.id = c.vendedor_id

                WHERE c.id = ?

                AND (
                    c.comprador_id = ?
                    OR c.vendedor_id = ?
                )

                LIMIT 1";

        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param(
            "iii",
            $compra_id,
            $usuario_id,
            $usuario_id
        );

        $stmt->execute();

        $resultado = $stmt->get_result();

        $compra = $resultado->fetch_assoc();

        $stmt->close();

        return $compra;
    }


    /*
    ============================================================
    MIS COMPRAS
    ============================================================
    */

    public function obtenerPorComprador($usuario_id)
    {

        $sql = "SELECT
                    c.*,

                    l.titulo,
                    l.autor,
                    l.imagen,
                    l.tipo_publicacion,
                    l.estado_disponibilidad,

                    vendedor.nombre AS nombre_vendedor,
                    vendedor.apellido AS apellido_vendedor

                FROM compras c

                INNER JOIN libros l
                    ON l.id = c.libro_id

                INNER JOIN usuarios vendedor
                    ON vendedor.id = c.vendedor_id

                WHERE c.comprador_id = ?

                ORDER BY c.fecha_compra DESC";

        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param(
            "i",
            $usuario_id
        );

        $stmt->execute();

        $resultado = $stmt->get_result();

        $compras = [];

        while ($fila = $resultado->fetch_assoc()) {
            $compras[] = $fila;
        }

        $stmt->close();

        return $compras;
    }


    /*
    ============================================================
    MIS VENTAS
    ============================================================
    */

    public function obtenerPorVendedor($usuario_id)
    {

        $sql = "SELECT
                    c.*,

                    l.titulo,
                    l.autor,
                    l.imagen,
                    l.tipo_publicacion,
                    l.estado_disponibilidad,

                    comprador.nombre AS nombre_comprador,
                    comprador.apellido AS apellido_comprador

                FROM compras c

                INNER JOIN libros l
                    ON l.id = c.libro_id

                INNER JOIN usuarios comprador
                    ON comprador.id = c.comprador_id

                WHERE c.vendedor_id = ?

                ORDER BY c.fecha_compra DESC";

        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param(
            "i",
            $usuario_id
        );

        $stmt->execute();

        $resultado = $stmt->get_result();

        $ventas = [];

        while ($fila = $resultado->fetch_assoc()) {
            $ventas[] = $fila;
        }

        $stmt->close();

        return $ventas;
    }


    /*
    ============================================================
    CONFIRMAR VENTA
    ============================================================

    Cuando el vendedor acepta:

    1. La compra pasa a confirmada.
    2. El libro pasa a vendido.
    3. El libro deja de estar disponible en catálogo.
    */

    public function confirmarVenta(
        $compra_id,
        $vendedor_id
    ) {

        $this->conexion->begin_transaction();

        try {

            /*
            --------------------------------------------
            BUSCAR LA COMPRA
            --------------------------------------------
            */

            $sql = "SELECT
                        id,
                        libro_id
                    FROM compras
                    WHERE id = ?
                    AND vendedor_id = ?
                    AND estado = 'pendiente'
                    LIMIT 1";

            $stmt = $this->conexion->prepare($sql);

            if (!$stmt) {
                throw new Exception("Error preparando compra.");
            }

            $stmt->bind_param(
                "ii",
                $compra_id,
                $vendedor_id
            );

            $stmt->execute();

            $resultado = $stmt->get_result();

            $compra = $resultado->fetch_assoc();

            $stmt->close();


            if (!$compra) {

                throw new Exception(
                    "La compra ya no está pendiente."
                );
            }


            $libro_id = (int)$compra['libro_id'];


            /*
            --------------------------------------------
            CAMBIAR LIBRO A VENDIDO
            --------------------------------------------
            */

            $sql = "UPDATE libros
                    SET estado_disponibilidad = 'vendido'
                    WHERE id = ?
                    AND estado_disponibilidad = 'reservado'";

            $stmt = $this->conexion->prepare($sql);

            if (!$stmt) {
                throw new Exception(
                    "Error preparando actualización del libro."
                );
            }

            $stmt->bind_param(
                "i",
                $libro_id
            );

            $stmt->execute();

            if ($stmt->affected_rows <= 0) {

                $stmt->close();

                throw new Exception(
                    "El libro ya no está reservado."
                );
            }

            $stmt->close();


            /*
            --------------------------------------------
            CONFIRMAR COMPRA
            --------------------------------------------
            */

            $sql = "UPDATE compras

                    SET
                        estado = 'confirmada',
                        fecha_actualizacion = NOW()

                    WHERE id = ?
                    AND vendedor_id = ?
                    AND estado = 'pendiente'";

            $stmt = $this->conexion->prepare($sql);

            if (!$stmt) {
                throw new Exception(
                    "Error preparando confirmación."
                );
            }

            $stmt->bind_param(
                "ii",
                $compra_id,
                $vendedor_id
            );

            $stmt->execute();

            if ($stmt->affected_rows <= 0) {

                $stmt->close();

                throw new Exception(
                    "No se pudo confirmar la compra."
                );
            }

            $stmt->close();


            /*
            --------------------------------------------
            CONFIRMAR TODO
            --------------------------------------------
            */

            $this->conexion->commit();

            return true;

        } catch (Exception $e) {

            $this->conexion->rollback();

            return false;
        }
    }


    /*
    ============================================================
    CANCELAR / RECHAZAR VENTA
    ============================================================

    Si el vendedor rechaza:

    1. La compra pasa a cancelada.
    2. El libro vuelve a disponible.
    3. Puede aparecer otra vez en el catálogo.
    */

    public function cancelarVenta(
        $compra_id,
        $vendedor_id
    ) {

        $this->conexion->begin_transaction();

        try {

            /*
            --------------------------------------------
            BUSCAR LA COMPRA
            --------------------------------------------
            */

            $sql = "SELECT
                        id,
                        libro_id
                    FROM compras
                    WHERE id = ?
                    AND vendedor_id = ?
                    AND estado = 'pendiente'
                    LIMIT 1";

            $stmt = $this->conexion->prepare($sql);

            if (!$stmt) {
                throw new Exception(
                    "Error preparando compra."
                );
            }

            $stmt->bind_param(
                "ii",
                $compra_id,
                $vendedor_id
            );

            $stmt->execute();

            $resultado = $stmt->get_result();

            $compra = $resultado->fetch_assoc();

            $stmt->close();


            if (!$compra) {

                throw new Exception(
                    "La compra ya no está pendiente."
                );
            }


            $libro_id = (int)$compra['libro_id'];


            /*
            --------------------------------------------
            DEVOLVER LIBRO A DISPONIBLE
            --------------------------------------------
            */

            $sql = "UPDATE libros
                    SET estado_disponibilidad = 'disponible'
                    WHERE id = ?
                    AND estado_disponibilidad = 'reservado'";

            $stmt = $this->conexion->prepare($sql);

            if (!$stmt) {
                throw new Exception(
                    "Error preparando devolución del libro."
                );
            }

            $stmt->bind_param(
                "i",
                $libro_id
            );

            $stmt->execute();

            $stmt->close();


            /*
            --------------------------------------------
            CANCELAR COMPRA
            --------------------------------------------
            */

            $sql = "UPDATE compras

                    SET
                        estado = 'cancelada',
                        fecha_actualizacion = NOW()

                    WHERE id = ?
                    AND vendedor_id = ?
                    AND estado = 'pendiente'";

            $stmt = $this->conexion->prepare($sql);

            if (!$stmt) {
                throw new Exception(
                    "Error preparando cancelación."
                );
            }

            $stmt->bind_param(
                "ii",
                $compra_id,
                $vendedor_id
            );

            $stmt->execute();

            if ($stmt->affected_rows <= 0) {

                $stmt->close();

                throw new Exception(
                    "No se pudo cancelar la compra."
                );
            }

            $stmt->close();


            /*
            --------------------------------------------
            CONFIRMAR CAMBIOS
            --------------------------------------------
            */

            $this->conexion->commit();

            return true;

        } catch (Exception $e) {

            $this->conexion->rollback();

            return false;
        }
    }
}