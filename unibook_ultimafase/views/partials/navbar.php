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

            <span class="mark"><i class="bi bi-bookshelf ub-icon" aria-hidden="true"></i></span> UniBook

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

                        <i class="bi bi-bookshelf ub-icon" aria-hidden="true"></i> Catálogo

                    </a>

                </li>


                <!-- HISTORIA -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="index.php?accion=historia"
                    >

                        <i class="bi bi-clock-history ub-icon" aria-hidden="true"></i> Historia

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="index.php?accion=publicar_libro"
                    >

                        <i class="bi bi-plus-circle ub-icon" aria-hidden="true"></i> Publicar

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="index.php?accion=mis_compras"
                    >

                        <i class="bi bi-bag-check ub-icon" aria-hidden="true"></i> Mis compras

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="index.php?accion=mis_ventas"
                    >

                        <i class="bi bi-cash-stack ub-icon" aria-hidden="true"></i> Mis ventas

                    </a>

                </li>


                <li class="nav-item dropdown">

                    <a
                        class="nav-link dropdown-toggle"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                    >

                        <i class="bi bi-arrow-left-right ub-icon" aria-hidden="true"></i> Intercambios

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

                        <i class="bi bi-chat-dots ub-icon" aria-hidden="true"></i> Mensajes

                    </a>

                </li>


                <!-- NOTIFICACIONES -->

                <li class="nav-item">

                    <a
                        class="nav-link position-relative"
                        href="index.php?accion=notificaciones"
                    >

                        <i class="bi bi-bell ub-icon" aria-hidden="true"></i> Notificaciones

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

                        <i class="bi bi-person-circle ub-icon" aria-hidden="true"></i> Mi perfil

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
