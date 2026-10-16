<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php?accion=login");
    exit();
}

$conversaciones = $conversaciones ?? [];

?>

<?php include "views/partials/navbar.php"; ?>

<style>
    .mensajes-page {
        padding: 40px 0 70px;
        background: #f7f7f8;
        min-height: 70vh;
    }

    .mensajes-header {
        margin-bottom: 28px;
    }

    .mensajes-header h1 {
        font-weight: 800;
        margin-bottom: 8px;
    }

    .mensajes-header p {
        color: #6b7280;
        margin: 0;
    }

    .conversaciones-lista {
        display: grid;
        gap: 14px;
    }

    .conversacion-card {
        display: flex;
        align-items: center;
        gap: 18px;
        background: #fff;
        border-radius: 18px;
        padding: 16px 18px;
        text-decoration: none;
        color: inherit;
        border: 1px solid #e5e7eb;
        transition: .2s ease;
    }

    .conversacion-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(0,0,0,.07);
        color: inherit;
    }

    .conversacion-imagen {
        width: 72px;
        height: 92px;
        border-radius: 10px;
        overflow: hidden;
        background: #eee;
        flex-shrink: 0;
    }

    .conversacion-imagen img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .sin-imagen {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }

    .conversacion-info {
        flex: 1;
        min-width: 0;
    }

    .conversacion-persona {
        font-weight: 800;
        font-size: 17px;
        margin-bottom: 3px;
    }

    .conversacion-libro {
        font-weight: 700;
        color: #374151;
        margin-bottom: 6px;
    }

    .conversacion-mensaje {
        color: #6b7280;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .conversacion-fecha {
        color: #9ca3af;
        font-size: 13px;
        margin-top: 5px;
    }

    .conversacion-meta {
        text-align: right;
        flex-shrink: 0;
    }

    .badge-no-leidos {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 26px;
        height: 26px;
        padding: 0 8px;
        border-radius: 999px;
        background: #2563eb;
        color: #fff;
        font-size: 12px;
        font-weight: 800;
    }

    .flecha-chat {
        font-size: 24px;
        color: #9ca3af;
        margin-top: 8px;
    }

    .mensajes-vacio {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        padding: 50px 25px;
        text-align: center;
    }

    .mensajes-vacio .icono {
        font-size: 52px;
        margin-bottom: 12px;
    }

    .mensajes-vacio h3 {
        font-weight: 800;
        margin-bottom: 8px;
    }

    .mensajes-vacio p {
        color: #6b7280;
        margin-bottom: 20px;
    }

    @media (max-width: 576px) {

        .conversacion-card {
            gap: 12px;
            padding: 13px;
        }

        .conversacion-imagen {
            width: 58px;
            height: 76px;
        }

        .conversacion-persona {
            font-size: 15px;
        }

        .conversacion-libro {
            font-size: 14px;
        }

        .conversacion-mensaje {
            font-size: 13px;
        }

        .conversacion-meta {
            display: none;
        }
    }
</style>

<section class="mensajes-page">

    <div class="container">

        <div class="mensajes-header">

            <h1>💬 Mensajes</h1>

            <p>
                Tus conversaciones sobre libros de UniBook.
            </p>

        </div>

        <?php if (!empty($conversaciones)): ?>

            <div class="conversaciones-lista">

                <?php foreach ($conversaciones as $conversacion): ?>

                    <?php

                    $imagen = trim($conversacion['imagen'] ?? '');

                    $fecha = !empty($conversacion['fecha_creacion'])
                        ? date(
                            'd/m/Y H:i',
                            strtotime($conversacion['fecha_creacion'])
                        )
                        : '';

                    $otroUsuarioId =
                        (int)($conversacion['otro_usuario_id'] ?? 0);

                    $noLeidos =
                        (int)($conversacion['no_leidos'] ?? 0);

                    ?>

                    <a
                        href="index.php?accion=chat&id=<?php echo (int)$conversacion['libro_id']; ?>&usuario=<?php echo $otroUsuarioId; ?>"
                        class="conversacion-card"
                    >

                        <div class="conversacion-imagen">

                            <?php if ($imagen !== ''): ?>

                                <img
                                    src="<?php echo htmlspecialchars($imagen); ?>"
                                    alt="<?php echo htmlspecialchars($conversacion['titulo'] ?? 'Libro'); ?>"
                                >

                            <?php else: ?>

                                <div class="sin-imagen">
                                    📚
                                </div>

                            <?php endif; ?>

                        </div>

                        <div class="conversacion-info">

                            <div class="conversacion-persona">
                                <?php
                                echo htmlspecialchars(
                                    $conversacion['otro_usuario']
                                    ?? 'Usuario'
                                );
                                ?>
                            </div>

                            <div class="conversacion-libro">

                                📖

                                <?php
                                echo htmlspecialchars(
                                    $conversacion['titulo']
                                    ?? 'Libro'
                                );
                                ?>

                            </div>

                            <div class="conversacion-mensaje">

                                <?php
                                echo htmlspecialchars(
                                    $conversacion['ultimo_mensaje']
                                    ?? 'Sin mensajes'
                                );
                                ?>

                            </div>

                            <div class="conversacion-fecha">
                                <?php echo htmlspecialchars($fecha); ?>
                            </div>

                        </div>

                        <div class="conversacion-meta">

                            <?php if ($noLeidos > 0): ?>

                                <span class="badge-no-leidos">
                                    <?php echo $noLeidos; ?>
                                </span>

                            <?php endif; ?>

                            <div class="flecha-chat">
                                →
                            </div>

                        </div>

                    </a>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="mensajes-vacio">

                <div class="icono">
                    💬
                </div>

                <h3>
                    Aún no tienes conversaciones
                </h3>

                <p>
                    Cuando contactes a un vendedor desde un libro,
                    tu conversación aparecerá aquí.
                </p>

                <a
                    href="index.php?accion=catalogo"
                    class="btn-ub btn-ub-primary"
                >
                    📚 Ir al catálogo
                </a>

            </div>

        <?php endif; ?>

    </div>

</section>

<?php include "views/partials/footer.php"; ?>