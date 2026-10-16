<?php

$mensajeIntercambio = $_GET['intercambio'] ?? '';

?>

<style>

    .intercambios-page {
        min-height: 80vh;
        background: #f7faff;
    }

    .intercambios-header {
        background: linear-gradient(135deg, #0d6efd, #4f8df7);
        color: white;
        border-radius: 24px;
        padding: 35px;
        margin-bottom: 30px;
        box-shadow: 0 15px 35px rgba(13, 110, 253, 0.18);
    }

    .intercambios-header h1 {
        font-size: 2rem;
        margin-bottom: 8px;
    }

    .intercambios-header p {
        margin: 0;
        opacity: 0.9;
    }

    .solicitud-card {
        border: none;
        border-radius: 22px;
        background: white;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
        transition: all 0.25s ease;
        height: 100%;
    }

    .solicitud-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.11);
    }

    .solicitud-top {
        padding: 20px 22px;
        border-bottom: 1px solid #edf0f5;
    }

    .usuario-avatar {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: linear-gradient(135deg, #dceaff, #edf5ff);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        flex-shrink: 0;
    }

    .usuario-nombre {
        font-weight: 700;
        color: #1f2937;
        font-size: 16px;
    }

    .fecha-solicitud {
        font-size: 12px;
        color: #8a94a6;
    }

    .estado-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
    }

    .estado-pendiente {
        background: #fff3cd;
        color: #856404;
    }

    .estado-aceptado {
        background: #d1e7dd;
        color: #0f5132;
    }

    .estado-rechazado {
        background: #f8d7da;
        color: #842029;
    }

    .estado-otro {
        background: #e9ecef;
        color: #495057;
    }

    .solicitud-body {
        padding: 22px;
    }

    .intercambio-titulo {
        font-size: 13px;
        font-weight: 700;
        color: #8a94a6;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }

    .libro-box {
        border: 1px solid #e8edf5;
        border-radius: 15px;
        padding: 15px;
        margin-bottom: 12px;
        background: #fbfcff;
    }

    .libro-box.ofrecido {
        background: #f3f9ff;
        border-color: #d9eaff;
    }

    .libro-label {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 12px;
        color: #7b8494;
        margin-bottom: 5px;
    }

    .libro-nombre {
        font-size: 15px;
        font-weight: 700;
        color: #202938;
        word-break: break-word;
    }

    .intercambio-flecha {
        text-align: center;
        color: #0d6efd;
        font-size: 20px;
        margin: -3px 0;
    }

    .acciones-intercambio {
        margin-top: 20px;
        display: grid;
        gap: 9px;
    }

    .acciones-intercambio .btn {
        border-radius: 12px;
        padding: 11px;
        font-weight: 600;
    }

    .mensaje-alerta {
        border: none;
        border-radius: 15px;
        padding: 15px 18px;
        margin-bottom: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .estado-final {
        margin-top: 18px;
        padding: 12px;
        border-radius: 12px;
        text-align: center;
        font-size: 13px;
        font-weight: 600;
    }

    .estado-final.aceptado {
        background: #edf9f2;
        color: #18794e;
    }

    .estado-final.rechazado {
        background: #fff1f2;
        color: #b4232c;
    }

    .sin-solicitudes {
        background: white;
        border-radius: 24px;
        padding: 65px 25px;
        text-align: center;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
    }

    .sin-solicitudes-icono {
        width: 85px;
        height: 85px;
        margin: 0 auto 20px;
        border-radius: 50%;
        background: #edf5ff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
    }

    @media (max-width: 576px) {

        .intercambios-header {
            padding: 25px;
            border-radius: 20px;
        }

        .intercambios-header h1 {
            font-size: 1.6rem;
        }

    }

</style>


<div class="intercambios-page">

    <div class="container py-5">


        <!-- ================================================= -->
        <!-- CABECERA -->
        <!-- ================================================= -->

        <div class="intercambios-header">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

                <div>

                    <div style="font-size: 30px; margin-bottom: 8px;">
                        🔄
                    </div>

                    <h1 class="fw-bold">
                        Solicitudes recibidas
                    </h1>

                    <p>
                        Revisa las propuestas de intercambio que otros estudiantes
                        han enviado por tus libros.
                    </p>

                </div>


                <a
                    href="index.php?accion=catalogo"
                    class="btn btn-light fw-semibold px-4 py-2"
                    style="border-radius: 12px;"
                >
                    📚 Ver catálogo
                </a>

            </div>

        </div>


        <!-- ================================================= -->
        <!-- MENSAJES -->
        <!-- ================================================= -->

        <?php if ($mensajeIntercambio === 'aceptado'): ?>

            <div class="alert alert-success mensaje-alerta">

                <strong>✅ ¡Intercambio aceptado!</strong>

                <br>

                La solicitud fue aceptada correctamente.

            </div>

        <?php elseif ($mensajeIntercambio === 'rechazado'): ?>

            <div class="alert alert-warning mensaje-alerta">

                <strong>↩️ Solicitud rechazada</strong>

                <br>

                La solicitud de intercambio fue rechazada.

            </div>

        <?php elseif ($mensajeIntercambio === 'error'): ?>

            <div class="alert alert-danger mensaje-alerta">

                <strong>❌ No se pudo procesar la solicitud.</strong>

                <br>

                Es posible que la solicitud ya haya sido procesada.

            </div>

        <?php elseif ($mensajeIntercambio === 'creado'): ?>

            <div class="alert alert-success mensaje-alerta">

                <strong>✅ Solicitud enviada correctamente.</strong>

            </div>

        <?php endif; ?>


        <!-- ================================================= -->
        <!-- SOLICITUDES -->
        <!-- ================================================= -->

        <?php if (empty($intercambiosRecibidos)): ?>


            <div class="sin-solicitudes">

                <div class="sin-solicitudes-icono">
                    📚
                </div>

                <h3 class="fw-bold mb-2">
                    No tienes solicitudes todavía
                </h3>

                <p class="text-muted mb-4">
                    Cuando otro estudiante quiera intercambiar
                    uno de tus libros, su propuesta aparecerá aquí.
                </p>

                <a
                    href="index.php?accion=catalogo"
                    class="btn btn-primary px-4"
                    style="border-radius: 12px;"
                >
                    Explorar libros
                </a>

            </div>


        <?php else: ?>


            <div class="row g-4">


                <?php foreach ($intercambiosRecibidos as $intercambio): ?>


                    <?php

                    $estado = strtolower(
                        trim(
                            $intercambio['estado'] ?? 'pendiente'
                        )
                    );

                    $nombreSolicitante =
                        trim(
                            ($intercambio['nombre'] ?? '')
                            . ' '
                            . ($intercambio['apellido'] ?? '')
                        );

                    ?>


                    <div class="col-md-6 col-xl-4">


                        <div class="solicitud-card">


                            <!-- ================================= -->
                            <!-- PARTE SUPERIOR -->
                            <!-- ================================= -->

                            <div class="solicitud-top">

                                <div class="d-flex justify-content-between align-items-start gap-2">


                                    <div class="d-flex align-items-center gap-3">

                                        <div class="usuario-avatar">
                                            👤
                                        </div>

                                        <div>

                                            <div class="usuario-nombre">
                                                <?= htmlspecialchars(
                                                    $nombreSolicitante
                                                ) ?>
                                            </div>

                                            <div class="fecha-solicitud">

                                                <?=
                                                    isset(
                                                        $intercambio['fecha_creacion']
                                                    )
                                                        ? htmlspecialchars(
                                                            $intercambio['fecha_creacion']
                                                        )
                                                        : ''
                                                ?>

                                            </div>

                                        </div>

                                    </div>


                                    <?php if ($estado === 'pendiente'): ?>

                                        <span class="estado-badge estado-pendiente">
                                            🕐 Pendiente
                                        </span>

                                    <?php elseif ($estado === 'aceptado'): ?>

                                        <span class="estado-badge estado-aceptado">
                                            ✓ Aceptado
                                        </span>

                                    <?php elseif ($estado === 'rechazado'): ?>

                                        <span class="estado-badge estado-rechazado">
                                            ✕ Rechazado
                                        </span>

                                    <?php else: ?>

                                        <span class="estado-badge estado-otro">
                                            <?= htmlspecialchars(
                                                ucfirst($estado)
                                            ) ?>
                                        </span>

                                    <?php endif; ?>


                                </div>

                            </div>


                            <!-- ================================= -->
                            <!-- CUERPO -->
                            <!-- ================================= -->

                            <div class="solicitud-body">


                                <div class="intercambio-titulo">
                                    Propuesta de intercambio
                                </div>


                                <!-- TU LIBRO -->

                                <div class="libro-box">

                                    <div class="libro-label">
                                        📕 Tu libro
                                    </div>

                                    <div class="libro-nombre">

                                        <?= htmlspecialchars(
                                            $intercambio['libro_solicitado']
                                            ?? 'Libro no disponible'
                                        ) ?>

                                    </div>

                                </div>


                                <div class="intercambio-flecha">
                                    ⇅
                                </div>


                                <!-- LIBRO OFRECIDO -->

                                <div class="libro-box ofrecido">

                                    <div class="libro-label">
                                        📗 Te ofrece
                                    </div>

                                    <div class="libro-nombre">

                                        <?= htmlspecialchars(
                                            $intercambio['libro_ofrecido']
                                            ?? 'Libro no disponible'
                                        ) ?>

                                    </div>

                                </div>


                                <!-- ================================= -->
                                <!-- ACCIONES -->
                                <!-- ================================= -->

                                <?php if ($estado === 'pendiente'): ?>


                                    <div class="acciones-intercambio">


                                        <form
                                            method="POST"
                                            action="index.php"
                                            onsubmit="return confirm('¿Seguro que quieres aceptar este intercambio?');"
                                        >

                                            <input
                                                type="hidden"
                                                name="intercambio_id"
                                                value="<?= (int)$intercambio['id'] ?>"
                                            >

                                            <button
                                                type="submit"
                                                name="aceptar_intercambio"
                                                class="btn btn-success w-100"
                                            >
                                                ✓ Aceptar intercambio
                                            </button>

                                        </form>


                                        <form
                                            method="POST"
                                            action="index.php"
                                            onsubmit="return confirm('¿Seguro que quieres rechazar este intercambio?');"
                                        >

                                            <input
                                                type="hidden"
                                                name="intercambio_id"
                                                value="<?= (int)$intercambio['id'] ?>"
                                            >

                                            <button
                                                type="submit"
                                                name="rechazar_intercambio"
                                                class="btn btn-outline-danger w-100"
                                            >
                                                ✕ Rechazar solicitud
                                            </button>

                                        </form>


                                    </div>


                                <?php elseif ($estado === 'aceptado'): ?>


                                    <div class="estado-final aceptado">

                                        ✓
                                        Este intercambio fue aceptado.

                                    </div>


                                <?php elseif ($estado === 'rechazado'): ?>


                                    <div class="estado-final rechazado">

                                        ✕
                                        Esta solicitud fue rechazada.

                                    </div>


                                <?php endif; ?>


                            </div>

                        </div>

                    </div>


                <?php endforeach; ?>


            </div>


        <?php endif; ?>


    </div>

</div>