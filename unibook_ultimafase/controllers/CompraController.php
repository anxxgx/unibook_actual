<?php

require_once "models/Compra.php";
require_once "models/Libro.php";
require_once "models/Usuario.php";
require_once "models/Notificacion.php";


class CompraController
{

    private $modelo;
    private $libroModelo;
    private $usuarioModelo;
    private $notificacionModelo;
    private $conexion;


    public function __construct($conexion)
    {
        $this->conexion = $conexion;

        $this->modelo =
            new Compra($conexion);

        $this->libroModelo =
            new Libro($conexion);

        $this->usuarioModelo =
            new Usuario($conexion);

        $this->notificacionModelo =
            new Notificacion($conexion);
    }


    public function prepararCheckout($id)
    {

        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        $resultado = [
            'valido'   => false,
            'motivo'   => null,
            'libro'    => null,
            'vendedor' => null,
        ];

        if(!isset($_SESSION['id_usuario'])){
            $resultado['motivo'] = 'sesion';
            return $resultado;
        }

        $id = (int)$id;

        if($id <= 0){
            $resultado['motivo'] = 'invalido';
            return $resultado;
        }

        $libro = $this->libroModelo->obtenerPorId($id);

        if(!$libro){
            $resultado['motivo'] = 'invalido';
            return $resultado;
        }

        $usuario_id = (int)$_SESSION['id_usuario'];

        if((int)$libro['usuario_id'] === $usuario_id){
            $resultado['motivo'] = 'propio';
            return $resultado;
        }

        if(strtolower($libro['tipo_publicacion']) !== 'venta'){
            $resultado['motivo'] = 'tipo_no_venta';
            return $resultado;
        }

        $disponibilidad =
            $libro['estado_disponibilidad'] ?? 'disponible';

        if($disponibilidad !== 'disponible'){
            $resultado['motivo'] = 'no_disponible';
            return $resultado;
        }

        $vendedor =
            $this->usuarioModelo->buscarPorId(
                $libro['usuario_id']
            );

        $resultado['valido']   = true;
        $resultado['libro']    = $libro;
        $resultado['vendedor'] = $vendedor;

        return $resultado;
    }


    /*
     * Registrar una compra simulada.
     */
    public function confirmar()
    {

        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if(!isset($_SESSION['id_usuario'])){
            return [
                'ok' => false,
                'motivo' => 'sesion',
                'libro_id' => 0
            ];
        }

        $libro_id =
            (int)($_POST['libro_id'] ?? 0);

        $comprador_id =
            (int)$_SESSION['id_usuario'];

        $metodosValidos = [
            'pse',
            'nequi',
            'bancolombia',
            'daviplata'
        ];

        $metodo_pago =
            $_POST['metodo_pago'] ?? '';

        if(!in_array(
            $metodo_pago,
            $metodosValidos,
            true
        )){
            $metodo_pago = 'pse';
        }

        if($libro_id <= 0){
            return [
                'ok' => false,
                'motivo' => 'invalido',
                'libro_id' => $libro_id
            ];
        }

        $libro =
            $this->libroModelo->obtenerPorId(
                $libro_id
            );

        if(!$libro){
            return [
                'ok' => false,
                'motivo' => 'invalido',
                'libro_id' => $libro_id
            ];
        }

        $comprador =
            $this->usuarioModelo->buscarPorId(
                $comprador_id
            );

        if(!$comprador){
            return [
                'ok' => false,
                'motivo' => 'sesion',
                'libro_id' => $libro_id
            ];
        }

        $vendedor_id =
            (int)$libro['usuario_id'];

        if($vendedor_id === $comprador_id){
            return [
                'ok' => false,
                'motivo' => 'propio',
                'libro_id' => $libro_id
            ];
        }

        $vendedor =
            $this->usuarioModelo->buscarPorId(
                $vendedor_id
            );

        if(!$vendedor){
            return [
                'ok' => false,
                'motivo' => 'error',
                'libro_id' => $libro_id
            ];
        }

        if(
            strtolower($libro['tipo_publicacion'])
            !== 'venta'
        ){
            return [
                'ok' => false,
                'motivo' => 'tipo_no_venta',
                'libro_id' => $libro_id
            ];
        }

        $reservado =
            $this->libroModelo
                ->reservarSiDisponible(
                    $libro_id
                );

        if(!$reservado){
            return [
                'ok' => false,
                'motivo' => 'no_disponible',
                'libro_id' => $libro_id
            ];
        }

        $precio =
            $libro['precio'];

        $compra_id =
            $this->modelo->crear(
                $libro_id,
                $comprador_id,
                $vendedor_id,
                $precio,
                $metodo_pago
            );

        if(!$compra_id){

            $this->libroModelo
                ->marcarDisponible(
                    $libro_id
                );

            return [
                'ok' => false,
                'motivo' => 'error',
                'libro_id' => $libro_id
            ];
        }


        /*
         * 🔔 NOTIFICACIÓN AL VENDEDOR
         *
         * La compra quedó registrada y
         * está esperando su confirmación.
         */
        $nombreComprador =
            trim(
                ($comprador['nombre'] ?? '') . ' ' .
                ($comprador['apellido'] ?? '')
            );

        $tituloLibro =
            $libro['titulo'] ?? 'tu libro';

        $mensajeVendedor =
            $nombreComprador .
            " ha realizado una compra de \"" .
            $tituloLibro .
            "\". Revisa la operación y confirma la venta.";

        $this->notificacionModelo->crear(
            $vendedor_id,
            'nueva_compra',
            'Nueva compra',
            $mensajeVendedor,
            'index.php?accion=detalle_compra&id=' . $compra_id
        );


        return [
            'ok' => true,
            'motivo' => 'reservada',
            'libro_id' => $libro_id,
            'compra_id' => $compra_id
        ];
    }


    /*
     * El vendedor confirma la venta.
     */
    public function aceptarVenta($compra_id)
    {

        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if(!isset($_SESSION['id_usuario'])){
            return [
                'ok' => false,
                'motivo' => 'sesion'
            ];
        }

        $compra_id =
            (int)$compra_id;

        $vendedor_id =
            (int)$_SESSION['id_usuario'];

        if($compra_id <= 0){
            return [
                'ok' => false,
                'motivo' => 'invalido'
            ];
        }

        $detalle =
            $this->modelo
                ->obtenerDetalleParaUsuario(
                    $compra_id,
                    $vendedor_id
                );

        if(!$detalle){
            return [
                'ok' => false,
                'motivo' => 'no_autorizado'
            ];
        }

        if(
            (int)$detalle['vendedor_id']
            !== $vendedor_id
        ){
            return [
                'ok' => false,
                'motivo' => 'no_autorizado'
            ];
        }

        if($detalle['estado'] !== 'pendiente'){
            return [
                'ok' => false,
                'motivo' => 'estado'
            ];
        }

        $this->conexion->begin_transaction();

        $libroActualizado =
            $this->libroModelo
                ->marcarVendidoSiReservado(
                    $detalle['libro_id']
                );

        if(!$libroActualizado){
            $this->conexion->rollback();

            return [
                'ok' => false,
                'motivo' => 'libro'
            ];
        }

        $compraActualizada =
            $this->modelo
                ->confirmarVenta(
                    $compra_id,
                    $vendedor_id
                );

        if(!$compraActualizada){
            $this->conexion->rollback();

            return [
                'ok' => false,
                'motivo' => 'compra'
            ];
        }

        $this->conexion->commit();


        /*
         * 🔔 NOTIFICACIÓN AL COMPRADOR
         *
         * El vendedor aceptó la operación.
         */
        $tituloLibro =
            $detalle['titulo'] ?? 'tu libro';

        $mensajeComprador =
            "El vendedor ha confirmado tu compra de \"" .
            $tituloLibro .
            "\". La operación fue aceptada.";

        $this->notificacionModelo->crear(
            $detalle['comprador_id'],
            'compra_confirmada',
            '¡Compra confirmada!',
            $mensajeComprador,
            'index.php?accion=detalle_compra&id=' . $compra_id
        );


        return [
            'ok' => true,
            'motivo' => 'confirmada',
            'compra_id' => $compra_id
        ];
    }


    /*
     * El vendedor rechaza la operación.
     */
    public function rechazarVenta($compra_id)
    {

        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if(!isset($_SESSION['id_usuario'])){
            return [
                'ok' => false,
                'motivo' => 'sesion'
            ];
        }

        $compra_id =
            (int)$compra_id;

        $vendedor_id =
            (int)$_SESSION['id_usuario'];

        if($compra_id <= 0){
            return [
                'ok' => false,
                'motivo' => 'invalido'
            ];
        }

        $detalle =
            $this->modelo
                ->obtenerDetalleParaUsuario(
                    $compra_id,
                    $vendedor_id
                );

        if(!$detalle){
            return [
                'ok' => false,
                'motivo' => 'no_autorizado'
            ];
        }

        if(
            (int)$detalle['vendedor_id']
            !== $vendedor_id
        ){
            return [
                'ok' => false,
                'motivo' => 'no_autorizado'
            ];
        }

        if($detalle['estado'] !== 'pendiente'){
            return [
                'ok' => false,
                'motivo' => 'estado'
            ];
        }

        $this->conexion->begin_transaction();

        $compraActualizada =
            $this->modelo
                ->cancelarVenta(
                    $compra_id,
                    $vendedor_id
                );

        if(!$compraActualizada){
            $this->conexion->rollback();

            return [
                'ok' => false,
                'motivo' => 'compra'
            ];
        }

        $libroDisponible =
            $this->libroModelo
                ->marcarDisponible(
                    $detalle['libro_id']
                );

        if(!$libroDisponible){
            $this->conexion->rollback();

            return [
                'ok' => false,
                'motivo' => 'libro'
            ];
        }

        $this->conexion->commit();


        /*
         * 🔔 NOTIFICACIÓN AL COMPRADOR
         *
         * El vendedor rechazó la operación.
         */
        $tituloLibro =
            $detalle['titulo'] ?? 'tu libro';

        $mensajeComprador =
            "El vendedor canceló la compra de \"" .
            $tituloLibro .
            "\". El libro volvió a estar disponible.";

        $this->notificacionModelo->crear(
            $detalle['comprador_id'],
            'compra_cancelada',
            'Compra cancelada',
            $mensajeComprador,
            'index.php?accion=detalle_libro&id=' . $detalle['libro_id']
        );


        return [
            'ok' => true,
            'motivo' => 'cancelada',
            'compra_id' => $compra_id
        ];
    }


    public function confirmacion(
        $compra_id,
        $usuario_id
    ){

        $compra_id =
            (int)$compra_id;

        $usuario_id =
            (int)$usuario_id;

        if(
            $compra_id <= 0 ||
            $usuario_id <= 0
        ){
            return null;
        }

        return $this->modelo
            ->obtenerDetalleParaUsuario(
                $compra_id,
                $usuario_id
            );
    }


    public function misCompras()
    {

        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if(!isset($_SESSION['id_usuario'])){
            return null;
        }

        return $this->modelo
            ->obtenerPorComprador(
                (int)$_SESSION['id_usuario']
            );
    }


    public function misVentas()
    {

        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if(!isset($_SESSION['id_usuario'])){
            return null;
        }

        return $this->modelo
            ->obtenerPorVendedor(
                (int)$_SESSION['id_usuario']
            );
    }

}