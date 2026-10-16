<?php

if (session_status() === PHP_SESSION_NONE) {

    session_start();

}

/*
|--------------------------------------------------------------------------
| Contador de notificaciones
|--------------------------------------------------------------------------
*/

$notificacionesNoLeidasNavbar = 0;

if (isset($notificacionController) && isset($_SESSION['id_usuario'])) {

    $notificacionesNoLeidasNavbar =
        $notificacionController->contarNoLeidas();

}

?>

<nav class="navbar navbar-expand-lg navbar-dark ub-nav">

    <div class="container">

        <a class="navbar-brand" href="index.php?accion=dashboard">

            <span class="mark">📚</span> UniBook

        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#menu"
        >

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="menu">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="index.php?accion=catalogo"
                    >

                        📚 Catálogo

                    </a>

                </li>


                <!-- HISTORIA -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="index.php?accion=historia"
                    >

                        📖 Historia

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="index.php?accion=publicar_libro"
                    >

                        ➕ Publicar

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="index.php?accion=mis_compras"
                    >

                        🛒 Mis compras

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="index.php?accion=mis_ventas"
                    >

                        💰 Mis ventas

                    </a>

                </li>


                <li class="nav-item dropdown">

                    <a
                        class="nav-link dropdown-toggle"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                    >

                        🔄 Intercambios

                    </a>

                    <ul class="dropdown-menu">

                        <li>

                            <a
                                class="dropdown-item"
                                href="index.php?accion=intercambios_enviados"
                            >

                                Solicitudes enviadas

                            </a>

                        </li>

                        <li>

                            <a
                                class="dropdown-item"
                                href="index.php?accion=intercambios_recibidos"
                            >

                                Solicitudes recibidas

                            </a>

                        </li>

                    </ul>

                </li>


                <!-- MENSAJES -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="index.php?accion=mensajes"
                    >

                        💬 Mensajes

                    </a>

                </li>


                <!-- NOTIFICACIONES -->

                <li class="nav-item">

                    <a
                        class="nav-link position-relative"
                        href="index.php?accion=notificaciones"
                    >

                        🔔 Notificaciones

                        <?php if ($notificacionesNoLeidasNavbar > 0): ?>

                            <span
                                style="
                                    position:absolute;
                                    top:2px;
                                    right:-4px;
                                    min-width:18px;
                                    height:18px;
                                    padding:0 5px;
                                    border-radius:999px;
                                    background:#dc2626;
                                    color:white;
                                    font-size:11px;
                                    font-weight:800;
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                    line-height:1;
                                "
                            >

                                <?php echo $notificacionesNoLeidasNavbar; ?>

                            </span>

                        <?php endif; ?>

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="index.php?accion=perfil"
                    >

                        👤 Mi perfil

                    </a>

                </li>


                <li class="nav-item ms-lg-3 mt-2 mt-lg-0">

                    <a
                        href="logout.php"
                        class="btn-ub btn-ub-outline on-dark btn-ub-sm"
                    >

                        Cerrar sesión

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>