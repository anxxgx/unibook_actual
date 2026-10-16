<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

include "views/partials/navbar.php";
?>

<div class="ub-section">
    <div class="container" style="max-width: 900px;">

        <!-- ENCABEZADO -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 reveal">

            <div>
                <span class="text-uppercase small fw-bold text-muted">
                    Tu actividad
                </span>

                <h1 class="fw-bold mb-1">
                    Notificaciones 🔔
                </h1>

                <p class="text-muted mb-0">
                    Aquí podrás ver las novedades de tus compras y ventas.
                </p>
            </div>

            <?php if(!empty($notificaciones)){ ?>

                <form method="POST" action="index.php">

                    <input
                        type="hidden"
                        name="marcar_todas_notificaciones"
                        value="1"
                    >

                    <button
                        type="submit"
                        class="btn-ub btn-ub-outline btn-ub-sm"
                    >
                        ✓ Marcar todas como leídas
                    </button>

                </form>

            <?php } ?>

        </div>


        <!-- CONTADOR -->
        <?php if($notificacionesNoLeidas > 0){ ?>

            <div class="notification-count reveal">
                <span>🔔</span>

                Tienes
                <strong>
                    <?php echo $notificacionesNoLeidas; ?>
                </strong>

                notificación<?php echo $notificacionesNoLeidas == 1 ? '' : 'es'; ?>
                pendiente<?php echo $notificacionesNoLeidas == 1 ? '' : 's'; ?>.
            </div>

        <?php } ?>


        <!-- LISTA -->
        <?php if(empty($notificaciones)){ ?>

            <div class="catalog-empty reveal">

                <div class="catalog-empty__icon">
                    🔔
                </div>

                <h5 class="fw-bold mb-1">
                    No tienes notificaciones
                </h5>

                <p class="text-muted mb-4">
                    Cuando haya novedades sobre tus compras o ventas,
                    aparecerán aquí.
                </p>

                <a
                    href="index.php?accion=catalogo"
                    class="btn-ub btn-ub-dark"
                >
                    Explorar catálogo
                </a>

            </div>

        <?php }else{ ?>

            <div class="notifications-list">

                <?php foreach($notificaciones as $notificacion){ ?>

                    <?php

                    $tipo = $notificacion['tipo'];

                    $icono = '🔔';

                    if($tipo === 'nueva_compra'){
                        $icono = '🛍️';
                    }

                    if($tipo === 'compra_confirmada'){
                        $icono = '✅';
                    }

                    if($tipo === 'compra_cancelada'){
                        $icono = '❌';
                    }

                    ?>

                    <div
                        class="notification-card
                        <?php echo $notificacion['leida'] ? 'notification-read' : 'notification-unread'; ?>
                        reveal"
                    >

                        <div class="notification-icon">
                            <?php echo $icono; ?>
                        </div>


                        <div class="notification-content">

                            <div class="d-flex justify-content-between align-items-start gap-3">

                                <div>

                                    <h5 class="notification-title">
                                        <?php
                                        echo htmlspecialchars(
                                            $notificacion['titulo']
                                        );
                                        ?>
                                    </h5>

                                    <p class="notification-message">
                                        <?php
                                        echo htmlspecialchars(
                                            $notificacion['mensaje']
                                        );
                                        ?>
                                    </p>

                                </div>

                                <?php if(!$notificacion['leida']){ ?>

                                    <span class="notification-new">
                                        NUEVA
                                    </span>

                                <?php } ?>

                            </div>


                            <div class="notification-footer">

                                <span class="notification-date">
                                    🕒
                                    <?php
                                    echo date(
                                        'd/m/Y H:i',
                                        strtotime(
                                            $notificacion['fecha_creacion']
                                        )
                                    );
                                    ?>
                                </span>


                                <div class="d-flex gap-2 flex-wrap">

                                    <?php if(!empty($notificacion['enlace'])){ ?>

                                        <a
                                            href="<?php echo htmlspecialchars($notificacion['enlace']); ?>"
                                            class="btn-ub btn-ub-dark btn-ub-sm"
                                        >
                                            Ver detalles →
                                        </a>

                                    <?php } ?>


                                    <?php if(!$notificacion['leida']){ ?>

                                        <form
                                            method="POST"
                                            action="index.php"
                                        >

                                            <input
                                                type="hidden"
                                                name="marcar_notificacion"
                                                value="1"
                                            >

                                            <input
                                                type="hidden"
                                                name="notificacion_id"
                                                value="<?php echo (int)$notificacion['id']; ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="volver"
                                                value="notificaciones"
                                            >

                                            <button
                                                type="submit"
                                                class="btn-ub btn-ub-outline btn-ub-sm"
                                            >
                                                ✓ Marcar leída
                                            </button>

                                        </form>

                                    <?php } ?>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php } ?>

            </div>

        <?php } ?>

    </div>
</div>


<style>

.notification-count{
    background: rgba(30, 64, 175, 0.08);
    border: 1px solid rgba(30, 64, 175, 0.12);
    border-radius: 14px;
    padding: 14px 18px;
    margin-bottom: 22px;
    color: #334155;
}

.notification-count strong{
    color: #1d4ed8;
}

.notifications-list{
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.notification-card{
    display: flex;
    gap: 18px;
    padding: 20px;
    border-radius: 18px;
    border: 1px solid rgba(15, 23, 42, 0.08);
    background: #fff;
    transition: transform .2s ease, box-shadow .2s ease;
}

.notification-card:hover{
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
}

.notification-unread{
    border-left: 4px solid #2563eb;
    background: linear-gradient(
        135deg,
        rgba(37, 99, 235, 0.045),
        #fff
    );
}

.notification-read{
    opacity: .78;
}

.notification-icon{
    min-width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f1f5f9;
    font-size: 22px;
}

.notification-content{
    flex: 1;
    min-width: 0;
}

.notification-title{
    font-size: 17px;
    font-weight: 700;
    margin: 0 0 5px;
    color: #0f172a;
}

.notification-message{
    margin: 0;
    color: #64748b;
    line-height: 1.55;
    font-size: 14.5px;
}

.notification-new{
    flex-shrink: 0;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .08em;
    color: #2563eb;
    background: rgba(37, 99, 235, .10);
    padding: 5px 8px;
    border-radius: 999px;
}

.notification-footer{
    margin-top: 15px;
    padding-top: 13px;
    border-top: 1px solid rgba(15, 23, 42, .07);

    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.notification-date{
    color: #94a3b8;
    font-size: 12.5px;
}


@media(max-width: 576px){

    .notification-card{
        padding: 16px;
        gap: 12px;
    }

    .notification-icon{
        min-width: 42px;
        height: 42px;
        font-size: 19px;
    }

    .notification-title{
        font-size: 15.5px;
    }

    .notification-message{
        font-size: 13.5px;
    }

    .notification-footer{
        align-items: flex-start;
        flex-direction: column;
    }

}

</style>


<?php include "views/partials/footer.php"; ?>