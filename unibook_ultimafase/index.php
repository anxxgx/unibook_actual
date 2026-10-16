<?php

session_start();

require_once "config/conexion.php";

require_once "controllers/UsuarioController.php";
require_once "controllers/LibroController.php";
require_once "controllers/CompraController.php";
require_once "controllers/NotificacionController.php";
require_once "controllers/IntercambioController.php";
require_once "controllers/MensajeController.php";


$usuarioController =
    new UsuarioController($conexion);

$libroController =
    new LibroController($conexion);

$compraController =
    new CompraController($conexion);

$notificacionController =
    new NotificacionController($conexion);

$intercambioController =
    new IntercambioController($conexion);

$mensajeController =
    new MensajeController($conexion);


$accion =
    $_GET['accion'] ?? 'registro';


// ============================================================
// REGISTRO
// ============================================================

if (isset($_POST['registrar'])) {

    $usuarioController->registrar();

    $mensajeRegistro = "
        <div class='container mt-4'>
            <div class='ub-alert ub-alert-success'>
                ✅ Cuenta creada correctamente. Ya puedes iniciar sesión.
            </div>
        </div>
    ";
}


// ============================================================
// LOGIN
// ============================================================

if (isset($_POST['login'])) {

    $usuarioController->login();
}


// ============================================================
// PUBLICAR LIBRO
// ============================================================

if (isset($_POST['publicar_libro'])) {

    $libroController->publicar();

    $mensajePublicar = "
        <div class='container mt-4'>
            <div class='ub-alert ub-alert-success'>
                ✅ Libro publicado correctamente.
            </div>
        </div>
    ";
}


// ============================================================
// ELIMINAR LIBRO
// ============================================================

if (isset($_POST['eliminar_libro'])) {

    $id =
        $_POST['id'] ?? 0;

    $eliminado =
        $libroController->eliminar($id);

    if ($eliminado) {

        header(
            "Location: index.php?accion=perfil&eliminado=1"
        );

        exit();

    } else {

        header(
            "Location: index.php?accion=perfil&error=1"
        );

        exit();
    }
}


// ============================================================
// CONFIRMAR COMPRA
// FASE 3.2
// ============================================================

if (isset($_POST['confirmar_compra'])) {

    $resultado =
        $compraController->confirmar();

    if ($resultado['motivo'] === 'sesion') {

        header(
            "Location: index.php?accion=login"
        );

        exit();
    }

    if ($resultado['ok']) {

        header(
            "Location: index.php?accion=compra_confirmada&id="
            . $resultado['compra_id']
        );

        exit();

    } else {

        header(
            "Location: index.php?accion=detalle_libro&id="
            . $resultado['libro_id']
            . "&compra="
            . $resultado['motivo']
        );

        exit();
    }
}


// ============================================================
// ACEPTAR VENTA
// FASE 3.5
// ============================================================

if (isset($_POST['aceptar_venta'])) {

    $id =
        $_POST['compra_id'] ?? 0;

    $resultado =
        $compraController->aceptarVenta($id);

    if ($resultado['ok']) {

        header(
            "Location: index.php?accion=detalle_compra&id="
            . $resultado['compra_id']
            . "&venta=confirmada"
        );

        exit();

    } else {

        header(
            "Location: index.php?accion=detalle_compra&id="
            . $id
            . "&venta="
            . $resultado['motivo']
        );

        exit();
    }
}


// ============================================================
// RECHAZAR VENTA
// FASE 3.5
// ============================================================

if (isset($_POST['rechazar_venta'])) {

    $id =
        $_POST['compra_id'] ?? 0;

    $resultado =
        $compraController->rechazarVenta($id);

    if ($resultado['ok']) {

        header(
            "Location: index.php?accion=detalle_compra&id="
            . $resultado['compra_id']
            . "&venta=cancelada"
        );

        exit();

    } else {

        header(
            "Location: index.php?accion=detalle_compra&id="
            . $id
            . "&venta="
            . $resultado['motivo']
        );

        exit();
    }
}


// ============================================================
// CREAR SOLICITUD DE INTERCAMBIO
// ============================================================

if (isset($_POST['crear_intercambio'])) {

    $resultado =
        $intercambioController->crearSolicitud();

    if ($resultado) {

        header(
            "Location: index.php?accion=intercambios_enviados&intercambio=creado"
        );

        exit();

    } else {

        $libroSolicitado =
            $_POST['libro_solicitado_id'] ?? 0;

        header(
            "Location: index.php?accion=detalle_libro&id="
            . $libroSolicitado
            . "&intercambio=error"
        );

        exit();
    }
}


// ============================================================
// ACEPTAR INTERCAMBIO
// ============================================================

if (isset($_POST['aceptar_intercambio'])) {

    $resultado =
        $intercambioController->aceptar();

    if ($resultado) {

        header(
            "Location: index.php?accion=intercambios_recibidos&intercambio=aceptado"
        );

        exit();

    } else {

        header(
            "Location: index.php?accion=intercambios_recibidos&intercambio=error"
        );

        exit();
    }
}


// ============================================================
// RECHAZAR INTERCAMBIO
// ============================================================

if (isset($_POST['rechazar_intercambio'])) {

    $resultado =
        $intercambioController->rechazar();

    if ($resultado) {

        header(
            "Location: index.php?accion=intercambios_recibidos&intercambio=rechazado"
        );

        exit();

    } else {

        header(
            "Location: index.php?accion=intercambios_recibidos&intercambio=error"
        );

        exit();
    }
}


// ============================================================
// MARCAR NOTIFICACIÓN COMO LEÍDA
// ============================================================

if (isset($_POST['marcar_notificacion'])) {

    $id =
        (int)($_POST['notificacion_id'] ?? 0);

    $notificacionController->marcarLeida($id);

    $volver =
        $_POST['volver'] ?? 'notificaciones';

    header(
        "Location: index.php?accion="
        . $volver
    );

    exit();
}


// ============================================================
// MARCAR TODAS LAS NOTIFICACIONES COMO LEÍDAS
// ============================================================

if (isset($_POST['marcar_todas_notificaciones'])) {

    $notificacionController->marcarTodasLeidas();

    header(
        "Location: index.php?accion=notificaciones"
    );

    exit();
}


// ============================================================
// ENVIAR MENSAJE
// ============================================================

if (isset($_POST['enviar_mensaje'])) {

    if (!isset($_SESSION['id_usuario'])) {

        header(
            "Location: index.php?accion=login"
        );

        exit();
    }

    $libroId =
        (int)($_POST['libro_id'] ?? 0);

    $destinatarioId =
        (int)($_POST['destinatario_id'] ?? 0);

    $otroUsuarioId =
        (int)($_POST['otro_usuario_id'] ?? 0);

    if ($otroUsuarioId <= 0) {
        $otroUsuarioId = $destinatarioId;
    }

    $mensajeController->enviar();

    /*
     * Volvemos exactamente a la conversación
     * donde estábamos.
     */
    header(
        "Location: index.php?accion=chat&id="
        . $libroId
        . "&usuario="
        . $otroUsuarioId
    );

    exit();
}

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        UniBook Medellín — Estudia más, paga menos.
    </title>

    <link
        rel="icon"
        href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📚</text></svg>"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="assets/css/style.css"
        rel="stylesheet"
    >

</head>


<body>

<?php

switch ($accion) {


    // ========================================================
    // LOGIN
    // ========================================================

    case 'login':

        include "views/login.php";

    break;


    // ========================================================
    // DASHBOARD
    // ========================================================

    case 'dashboard':

        include "views/dashboard.php";

    break;


    // ========================================================
    // PUBLICAR LIBRO
    // ========================================================

    case 'publicar_libro':

        include "views/publicar_libro.php";

    break;


    // ========================================================
    // CATÁLOGO
    // ========================================================

    case 'catalogo':

        $datos =
            $libroController->listar();

        include "views/catalogo.php";

    break;


    // ========================================================
    // HISTORIA
    // ========================================================

    case 'historia':

        if (!isset($_SESSION['id_usuario'])) {

            header(
                "Location: index.php?accion=login"
            );

            exit();
        }

        $historial =
            $libroController->historial();

        include "views/historia.php";

    break;


    // ========================================================
    // DETALLE DEL LIBRO
    // ========================================================

    case 'detalle_libro':

        $id =
            $_GET['id'] ?? 0;

        $libro =
            $libroController->detalle($id);

        $vendedor = null;

        if ($libro) {

            $vendedor =
                $usuarioController->obtenerPorId(
                    $libro['usuario_id']
                );
        }

        include "views/detalle_libro.php";

    break;


    // ========================================================
    // PERFIL
    // ========================================================

    case 'perfil':

        if (!isset($_SESSION['id_usuario'])) {

            header(
                "Location: index.php?accion=login"
            );

            exit();
        }

        include "views/perfil.php";

    break;


    // ========================================================
    // CHECKOUT
    // ========================================================

    case 'checkout':

        $idCheckout =
            $_GET['id'] ?? 0;

        $checkoutInfo =
            $compraController->prepararCheckout(
                $idCheckout
            );

        include "views/checkout.php";

    break;


    // ========================================================
    // MIS COMPRAS
    // ========================================================

    case 'mis_compras':

        if (!isset($_SESSION['id_usuario'])) {

            header(
                "Location: index.php?accion=login"
            );

            exit();
        }

        $misCompras =
            $compraController->misCompras();

        include "views/mis_compras.php";

    break;


    // ========================================================
    // MIS VENTAS
    // ========================================================

    case 'mis_ventas':

        if (!isset($_SESSION['id_usuario'])) {

            header(
                "Location: index.php?accion=login"
            );

            exit();
        }

        $misVentas =
            $compraController->misVentas();

        include "views/mis_ventas.php";

    break;


    // ========================================================
    // DETALLE COMPRA
    // ========================================================

    case 'detalle_compra':

        if (!isset($_SESSION['id_usuario'])) {

            header(
                "Location: index.php?accion=login"
            );

            exit();
        }

        $idCompra =
            $_GET['id'] ?? 0;

        $detalleCompra =
            $compraController->confirmacion(
                $idCompra,
                $_SESSION['id_usuario']
            );

        include "views/detalle_compra.php";

    break;


    // ========================================================
    // COMPRA CONFIRMADA
    // ========================================================

    case 'compra_confirmada':

        if (!isset($_SESSION['id_usuario'])) {

            header(
                "Location: index.php?accion=login"
            );

            exit();
        }

        $idCompraConfirmada =
            $_GET['id'] ?? 0;

        $compraConfirmada =
            $compraController->confirmacion(
                $idCompraConfirmada,
                $_SESSION['id_usuario']
            );

        include "views/compra_confirmada.php";

    break;


    // ========================================================
    // SOLICITAR INTERCAMBIO
    // ========================================================

    case 'solicitar_intercambio':

        if (!isset($_SESSION['id_usuario'])) {

            header(
                "Location: index.php?accion=login"
            );

            exit();
        }

        $libroSolicitadoId =
            (int)($_GET['id'] ?? 0);

        $libroSolicitado =
            $libroController->detalle(
                $libroSolicitadoId
            );

        if (!$libroSolicitado) {

            header(
                "Location: index.php?accion=catalogo"
            );

            exit();
        }

        $tipoLibro =
            strtolower(
                trim(
                    $libroSolicitado['tipo_publicacion'] ?? ''
                )
            );

        $estadoLibro =
            strtolower(
                trim(
                    $libroSolicitado['estado_disponibilidad']
                    ?? 'disponible'
                )
            );

        if (
            $tipoLibro !== 'intercambio' ||
            $estadoLibro !== 'disponible' ||
            (int)$libroSolicitado['usuario_id']
                === (int)$_SESSION['id_usuario']
        ) {

            header(
                "Location: index.php?accion=detalle_libro&id="
                . $libroSolicitadoId
            );

            exit();
        }

        $misLibros =
            $intercambioController->obtenerMisLibros(
                $_SESSION['id_usuario']
            );

        include "views/solicitar_intercambio.php";

    break;


    // ========================================================
    // INTERCAMBIOS ENVIADOS
    // ========================================================

    case 'intercambios_enviados':

        if (!isset($_SESSION['id_usuario'])) {

            header(
                "Location: index.php?accion=login"
            );

            exit();
        }

        $intercambiosEnviados =
            $intercambioController->enviados();

        include "views/intercambios_enviados.php";

    break;


    // ========================================================
    // INTERCAMBIOS RECIBIDOS
    // ========================================================

    case 'intercambios_recibidos':

        if (!isset($_SESSION['id_usuario'])) {

            header(
                "Location: index.php?accion=login"
            );

            exit();
        }

        $intercambiosRecibidos =
            $intercambioController->recibidos();

        include "views/intercambios_recibidos.php";

    break;


    // ========================================================
    // NOTIFICACIONES
    // ========================================================

    case 'notificaciones':

        if (!isset($_SESSION['id_usuario'])) {

            header(
                "Location: index.php?accion=login"
            );

            exit();
        }

        $notificaciones =
            $notificacionController->listar();

        $notificacionesNoLeidas =
            $notificacionController->contarNoLeidas();

        include "views/notificaciones.php";

    break;


    // ========================================================
    // CHAT
    // ========================================================

    case 'chat':

        if (!isset($_SESSION['id_usuario'])) {

            header(
                "Location: index.php?accion=login"
            );

            exit();
        }

        $libroChatId =
            (int)($_GET['id'] ?? 0);

        /*
         * Usuario con quien se está conversando.
         * Cuando entramos desde Mensajes viene en la URL.
         */
        $otroUsuarioChatId =
            (int)($_GET['usuario'] ?? 0);

        $chat =
            $mensajeController->abrirChat(
                $libroChatId,
                $otroUsuarioChatId
            );

        if (!$chat) {

            header(
                "Location: index.php?accion=mensajes"
            );

            exit();
        }

        $libroChat =
            $chat['libro'];

        $vendedorChatId =
            $chat['vendedor_id'];

        /*
         * Este es el usuario REAL con quien estamos
         * hablando.
         */
        $otroUsuarioChatId =
            $chat['otro_usuario_id'];

        $mensajesChat =
            $chat['mensajes'];

        /*
         * IMPORTANTE:
         * Ya no buscamos solamente al vendedor.
         * Buscamos a la otra persona de la conversación.
         */
        $otroUsuarioChat =
            $usuarioController->obtenerPorId(
                $otroUsuarioChatId
            );

        /*
         * Se mantiene esta variable porque el chat
         * puede necesitar información del vendedor.
         */
        $vendedorChat =
            $usuarioController->obtenerPorId(
                $vendedorChatId
            );

        include "views/chat.php";

    break;


    // ========================================================
    // MENSAJES
    // ========================================================

    case 'mensajes':

        if (!isset($_SESSION['id_usuario'])) {

            header(
                "Location: index.php?accion=login"
            );

            exit();
        }

        $conversaciones =
            $mensajeController->conversaciones();

        $mensajesNoLeidos =
            $mensajeController->contarNoLeidos();

        include "views/mensajes.php";

    break;


    // ========================================================
    // INICIO
    // ========================================================

    default:

        include "views/registro.php";

    break;
}

?>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

<script
    src="assets/js/main.js"
></script>

</body>

</html>