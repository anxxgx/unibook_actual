<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php?accion=login");
    exit();
}

$usuarioActualChat =
    (int)$_SESSION['id_usuario'];

$otroUsuarioChatId =
    (int)($otroUsuarioChatId ?? 0);

$nombreOtroUsuario =
    'Usuario';

if (!empty($vendedorChat)) {
    $nombreOtroUsuario =
        $vendedorChat['nombre'] ?? 'Usuario';
}

/*
 * Si existe información del otro usuario desde el controlador,
 * la usamos para mostrar correctamente la conversación.
 */
if (
    isset($otroUsuarioChat) &&
    is_array($otroUsuarioChat)
) {
    $nombreOtroUsuario =
        $otroUsuarioChat['nombre']
        ?? $nombreOtroUsuario;
}

$libroChatId =
    (int)($libroChat['id'] ?? 0);

$tituloLibro =
    $libroChat['titulo'] ?? 'Libro';

$imagenLibro =
    $libroChat['imagen'] ?? '';

?>

<?php include "views/partials/navbar.php"; ?>


<style>

.chat-page {
    background: #f5f6f8;
    min-height: calc(100vh - 80px);
    padding: 40px 20px;
}

.chat-container {
    max-width: 900px;
    margin: 0 auto;
}

.chat-header {
    background: #ffffff;
    border: 1px solid #e4e6eb;
    border-radius: 22px 22px 0 0;
    padding: 20px 24px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.chat-back {
    text-decoration: none;
    font-size: 24px;
    color: #172033;
    margin-right: 5px;
}

.chat-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #eef0f3;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    overflow: hidden;
}

.chat-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.chat-header-info {
    flex: 1;
}

.chat-header-name {
    font-size: 20px;
    font-weight: 700;
    color: #172033;
    margin: 0;
}

.chat-header-book {
    color: #687386;
    font-size: 14px;
    margin-top: 3px;
}

.chat-messages {
    background: #eef0f4;
    border-left: 1px solid #e4e6eb;
    border-right: 1px solid #e4e6eb;
    padding: 25px;
    min-height: 430px;
    max-height: 520px;
    overflow-y: auto;
}

.chat-empty {
    height: 380px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: #788294;
}

.chat-empty-inner {
    max-width: 400px;
}

.chat-empty-icon {
    font-size: 45px;
    margin-bottom: 12px;
}

.chat-message-row {
    display: flex;
    margin-bottom: 14px;
}

.chat-message-row.mine {
    justify-content: flex-end;
}

.chat-message-row.theirs {
    justify-content: flex-start;
}

.chat-bubble {
    max-width: 70%;
    padding: 11px 15px;
    border-radius: 18px;
    font-size: 15px;
    line-height: 1.45;
    word-wrap: break-word;
}

.chat-message-row.mine .chat-bubble {
    background: #1769ff;
    color: #ffffff;
    border-bottom-right-radius: 5px;
}

.chat-message-row.theirs .chat-bubble {
    background: #ffffff;
    color: #172033;
    border-bottom-left-radius: 5px;
    border: 1px solid #e0e3e8;
}

.chat-date {
    font-size: 11px;
    margin-top: 5px;
    opacity: .65;
}

.chat-message-row.mine .chat-date {
    text-align: right;
}

.chat-form {
    background: #ffffff;
    border: 1px solid #e4e6eb;
    border-radius: 0 0 22px 22px;
    padding: 18px;
}

.chat-form-inner {
    display: flex;
    gap: 10px;
    align-items: flex-end;
}

.chat-input {
    flex: 1;
    resize: none;
    border: 1px solid #d8dce3;
    border-radius: 18px;
    padding: 12px 16px;
    min-height: 48px;
    max-height: 120px;
    outline: none;
}

.chat-input:focus {
    border-color: #1769ff;
    box-shadow: 0 0 0 3px rgba(23,105,255,.10);
}

.chat-send {
    border: none;
    background: #1769ff;
    color: white;
    border-radius: 18px;
    padding: 12px 20px;
    font-weight: 700;
    min-height: 48px;
}

.chat-send:hover {
    opacity: .9;
}

.chat-book {
    margin-top: 18px;
    background: #ffffff;
    border: 1px solid #e4e6eb;
    border-radius: 18px;
    padding: 15px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
}

.chat-book-image {
    width: 55px;
    height: 70px;
    border-radius: 9px;
    overflow: hidden;
    background: #eef0f3;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.chat-book-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.chat-book-title {
    font-weight: 700;
    color: #172033;
}

.chat-book-text {
    color: #7a8494;
    font-size: 13px;
}

@media (max-width: 700px) {

    .chat-page {
        padding: 20px 10px;
    }

    .chat-messages {
        padding: 15px;
    }

    .chat-bubble {
        max-width: 85%;
    }

    .chat-form-inner {
        align-items: stretch;
    }

    .chat-send {
        padding: 10px 14px;
    }
}

</style>


<div class="chat-page">

    <div class="chat-container">


        <!-- CABECERA DEL CHAT -->

        <div class="chat-header">

            <a
                href="index.php?accion=mensajes"
                class="chat-back"
                title="Volver a mensajes"
            >
                ←
            </a>


            <div class="chat-avatar">

                👤

            </div>


            <div class="chat-header-info">

                <div class="chat-header-name">

                    <?= htmlspecialchars($nombreOtroUsuario) ?>

                </div>

                <div class="chat-header-book">

                    💬 Conversación sobre:
                    <?= htmlspecialchars($tituloLibro) ?>

                </div>

            </div>

        </div>



        <!-- MENSAJES -->

        <div
            class="chat-messages"
            id="chatMessages"
        >

            <?php if (empty($mensajesChat)): ?>

                <div class="chat-empty">

                    <div class="chat-empty-inner">

                        <div class="chat-empty-icon">
                            💬
                        </div>

                        <h5>
                            Inicia la conversación
                        </h5>

                        <p>
                            Escribe un mensaje para contactar
                            sobre este libro.
                        </p>

                    </div>

                </div>

            <?php else: ?>

                <?php foreach ($mensajesChat as $mensaje): ?>

                    <?php

                    $esMio =
                        (int)$mensaje['remitente_id']
                        === $usuarioActualChat;

                    ?>

                    <div
                        class="chat-message-row <?= $esMio ? 'mine' : 'theirs' ?>"
                    >

                        <div class="chat-bubble">

                            <div>
                                <?= nl2br(
                                    htmlspecialchars(
                                        $mensaje['mensaje']
                                    )
                                ) ?>
                            </div>

                            <div class="chat-date">

                                <?= date(
                                    'd/m/Y H:i',
                                    strtotime(
                                        $mensaje['fecha_creacion']
                                    )
                                ) ?>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>



        <!-- FORMULARIO -->

        <div class="chat-form">

            <form
                method="POST"
                action="index.php?accion=chat&id=<?= $libroChatId ?>&usuario=<?= $otroUsuarioChatId ?>"
            >

                <input
                    type="hidden"
                    name="enviar_mensaje"
                    value="1"
                >

                <input
                    type="hidden"
                    name="destinatario_id"
                    value="<?= $otroUsuarioChatId ?>"
                >

                <input
                    type="hidden"
                    name="libro_id"
                    value="<?= $libroChatId ?>"
                >

                <!--
                    Guardamos explícitamente con quién estamos hablando.
                    Así, después de enviar, seguimos exactamente
                    dentro de esta misma conversación.
                -->
                <input
                    type="hidden"
                    name="otro_usuario_id"
                    value="<?= $otroUsuarioChatId ?>"
                >


                <div class="chat-form-inner">

                    <textarea
                        name="mensaje"
                        class="chat-input"
                        placeholder="Escribe un mensaje..."
                        required
                        rows="1"
                    ></textarea>


                    <button
                        type="submit"
                        class="chat-send"
                    >
                        Enviar
                    </button>

                </div>

            </form>

        </div>



        <!-- LIBRO -->

        <div class="chat-book">

            <div class="chat-book-image">

                <?php if (!empty($imagenLibro)): ?>

                    <img
                        src="<?= htmlspecialchars($imagenLibro) ?>"
                        alt="<?= htmlspecialchars($tituloLibro) ?>"
                    >

                <?php else: ?>

                    📚

                <?php endif; ?>

            </div>


            <div>

                <div class="chat-book-title">

                    <?= htmlspecialchars($tituloLibro) ?>

                </div>

                <div class="chat-book-text">

                    Conversación de UniBook

                </div>

            </div>

        </div>


    </div>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const chatMessages =
            document.getElementById('chatMessages');

        if (chatMessages) {

            chatMessages.scrollTop =
                chatMessages.scrollHeight;
        }

        const textarea =
            document.querySelector('.chat-input');

        if (textarea) {

            textarea.focus();

            textarea.addEventListener(
                'keydown',
                function (event) {

                    if (
                        event.key === 'Enter' &&
                        !event.shiftKey
                    ) {

                        event.preventDefault();

                        this.form.submit();
                    }
                }
            );
        }
    }
);

</script>


<?php include "views/partials/footer.php"; ?>