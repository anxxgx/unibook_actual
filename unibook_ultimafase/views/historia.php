<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "views/partials/navbar.php";

?>

<section class="historia-bookshelf">

    <div class="container">

        <!-- ENCABEZADO -->
        <div class="historia-heading">

            <span class="historia-eyebrow">
                381.46 — Historia
            </span>

            <h1>
                Libros que ya encontraron
                <em>nuevo dueño.</em>
            </h1>

            <p>
                Una pequeña colección de libros que ya fueron vendidos
                o intercambiados dentro de UniBook.
            </p>

        </div>


        <?php if (empty($historial)) { ?>

            <!-- SIN HISTORIAL -->

            <div class="historia-empty">

                <div class="historia-empty__icon">
                    📚
                </div>

                <h5>
                    Todavía no hay libros en el historial
                </h5>

                <p>
                    Cuando un libro sea vendido o intercambiado,
                    aparecerá aquí.
                </p>

            </div>

        <?php } else { ?>


            <!-- ESTANTERÍA -->

            <div class="bookshelf-wrapper">

                <div
                    class="bookshelf"
                    id="historiaBookshelf"
                >

                    <?php foreach ($historial as $index => $libro) { ?>

                        <?php

                        $estado = strtolower(
                            trim(
                                $libro['estado_disponibilidad'] ?? ''
                            )
                        );

                        $esIntercambio =
                            $estado === 'intercambiado';

                        $titulo =
                            $libro['titulo'] ?? 'Sin título';

                        $autor =
                            $libro['autor'] ?? 'Autor desconocido';

                        $imagen =
                            $libro['imagen'] ?? '';

                        ?>

                        <article
                            class="
                                history-book
                                <?php echo $index === 0 ? 'active' : ''; ?>
                            "
                            data-index="<?php echo $index; ?>"
                            tabindex="0"
                        >

                            <!-- PORTADA -->

                            <div class="history-book__cover">

                                <?php if (!empty($imagen)) { ?>

                                    <img
                                        src="<?php echo htmlspecialchars($imagen); ?>"
                                        alt="<?php echo htmlspecialchars($titulo); ?>"
                                        loading="lazy"
                                    >

                                <?php } else { ?>

                                    <div class="history-book__no-image">
                                        <span>📚</span>
                                        <small>
                                            Sin portada
                                        </small>
                                    </div>

                                <?php } ?>


                                <!-- OSCURECIMIENTO -->

                                <div class="history-book__shade"></div>


                                <!-- INFORMACIÓN DEL LIBRO -->

                                <div class="history-book__info">

                                    <span
                                        class="
                                            history-book__status
                                            <?php
                                            echo $esIntercambio
                                                ? 'is-exchange'
                                                : 'is-sold';
                                            ?>
                                        "
                                    >
                                        <?php
                                        echo $esIntercambio
                                            ? 'Intercambiado'
                                            : 'Vendido';
                                        ?>
                                    </span>


                                    <h2>
                                        <?php
                                        echo htmlspecialchars($titulo);
                                        ?>
                                    </h2>


                                    <p>
                                        <?php
                                        echo htmlspecialchars($autor);
                                        ?>
                                    </p>


                                    <div class="history-book__hint">
                                        <span>↗</span>
                                        Libro de la historia
                                    </div>

                                </div>


                                <!-- NÚMERO CUANDO ESTÁ CERRADO -->

                                <div class="history-book__number">

                                    <?php
                                    echo str_pad(
                                        $index + 1,
                                        2,
                                        '0',
                                        STR_PAD_LEFT
                                    );
                                    ?>

                                </div>

                            </div>

                        </article>

                    <?php } ?>

                </div>


                <!-- BASE DE LA ESTANTERÍA -->

                <div class="bookshelf-base">
                    <span></span>
                </div>

            </div>


            <!-- CONTROLES -->

            <?php if (count($historial) > 1) { ?>

                <div class="history-controls">

                    <button
                        type="button"
                        id="historyPrev"
                        aria-label="Libro anterior"
                    >
                        ←
                    </button>

                    <span>
                        Mueve el cursor sobre un libro
                    </span>

                    <button
                        type="button"
                        id="historyNext"
                        aria-label="Libro siguiente"
                    >
                        →
                    </button>

                </div>

            <?php } ?>


        <?php } ?>

    </div>

</section>


<?php include "views/partials/footer.php"; ?>


<style>

/* ============================================================
   HISTORIA — ESTANTERÍA DE LIBROS
   ============================================================ */

.historia-bookshelf {
    padding: 70px 0 90px;
    background:
        radial-gradient(
            circle at top center,
            rgba(0,0,0,.035),
            transparent 45%
        );
}


/* ============================================================
   ENCABEZADO
   ============================================================ */

.historia-heading {
    max-width: 760px;
    margin: 0 auto 55px;
    text-align: center;
}

.historia-eyebrow {
    display: inline-block;
    margin-bottom: 14px;

    font-size: 11px;
    font-weight: 800;
    letter-spacing: .18em;
    text-transform: uppercase;

    opacity: .65;
}

.historia-heading h1 {
    margin: 0;

    font-size: clamp(34px, 5vw, 62px);
    line-height: .98;
    letter-spacing: -.045em;
    font-weight: 900;
}

.historia-heading h1 em {
    font-style: normal;
    opacity: .45;
}

.historia-heading p {
    max-width: 580px;
    margin: 20px auto 0;

    font-size: 15px;
    line-height: 1.7;

    opacity: .65;
}


/* ============================================================
   ESTANTERÍA
   ============================================================ */

.bookshelf-wrapper {
    position: relative;

    max-width: 1180px;
    margin: 0 auto;

    padding: 0 10px 30px;
}

.bookshelf {
    width: 100%;
    height: 530px;

    display: flex;
    align-items: flex-end;
    justify-content: center;

    gap: 9px;

    overflow: hidden;
}


/* ============================================================
   LIBRO
   ============================================================ */

.history-book {
    position: relative;

    flex: 0 0 78px;

    height: 455px;

    min-width: 0;

    border-radius: 5px 8px 8px 5px;

    overflow: hidden;

    cursor: pointer;

    outline: none;

    box-shadow:
        0 16px 30px rgba(0,0,0,.13),
        inset -4px 0 0 rgba(0,0,0,.08);

    transition:
        flex-basis .55s cubic-bezier(.22,1,.36,1),
        height .55s cubic-bezier(.22,1,.36,1),
        transform .55s cubic-bezier(.22,1,.36,1),
        filter .55s ease,
        box-shadow .55s ease;
}


/* LIBRO ACTIVO */

.history-book.active {
    flex: 0 0 355px;

    height: 505px;

    transform: translateY(-10px);

    box-shadow:
        0 30px 55px rgba(0,0,0,.22),
        inset -5px 0 0 rgba(0,0,0,.08);
}


/* LIBROS INACTIVOS */

.history-book:not(.active) {
    filter: brightness(.58);
}

.history-book:not(.active):hover {
    filter: brightness(.82);
    transform: translateY(-5px);
}


/* ============================================================
   PORTADA
   ============================================================ */

.history-book__cover {
    position: absolute;
    inset: 0;

    overflow: hidden;

    background: #171717;
}

.history-book__cover img {
    position: absolute;

    inset: 0;

    width: 100%;
    height: 100%;

    object-fit: cover;

    display: block;

    transition:
        transform .7s ease,
        filter .55s ease;
}


/* Libro cerrado */

.history-book:not(.active) .history-book__cover img {
    transform: scale(1.08);
}


/* Libro abierto */

.history-book.active .history-book__cover img {
    transform: scale(1);
}


/* ============================================================
   OSCURECIMIENTO
   ============================================================ */

.history-book__shade {
    position: absolute;

    inset: 0;

    background:
        linear-gradient(
            to top,
            rgba(0,0,0,.88) 0%,
            rgba(0,0,0,.45) 42%,
            rgba(0,0,0,.02) 78%
        );

    opacity: 0;

    transition: opacity .5s ease;
}

.history-book.active .history-book__shade {
    opacity: 1;
}


/* ============================================================
   SIN PORTADA
   ============================================================ */

.history-book__no-image {
    position: absolute;
    inset: 0;

    display: flex;
    flex-direction: column;

    align-items: center;
    justify-content: center;

    gap: 10px;

    background: #202020;

    color: white;

    text-align: center;
}

.history-book__no-image span {
    font-size: 42px;
}

.history-book__no-image small {
    font-size: 11px;
    opacity: .65;
}


/* ============================================================
   INFORMACIÓN
   ============================================================ */

.history-book__info {
    position: absolute;

    left: 28px;
    right: 28px;
    bottom: 28px;

    z-index: 3;

    color: white;

    opacity: 0;

    transform: translateY(25px);

    transition:
        opacity .4s ease .10s,
        transform .5s cubic-bezier(.22,1,.36,1) .10s;
}

.history-book.active .history-book__info {
    opacity: 1;
    transform: translateY(0);
}


/* ============================================================
   ESTADO
   ============================================================ */

.history-book__status {
    display: inline-flex;

    align-items: center;

    padding: 7px 11px;

    margin-bottom: 12px;

    border-radius: 999px;

    font-size: 10px;
    font-weight: 800;

    letter-spacing: .08em;

    text-transform: uppercase;

    color: white;

    border: 1px solid rgba(255,255,255,.25);

    background: rgba(255,255,255,.12);

    backdrop-filter: blur(8px);
}

.history-book__status.is-sold {
    background: rgba(255,255,255,.12);
}

.history-book__status.is-exchange {
    background: rgba(255,255,255,.12);
}


/* ============================================================
   TÍTULO
   ============================================================ */

.history-book__info h2 {
    margin: 0;

    font-size: clamp(25px, 3vw, 39px);
    line-height: .98;

    font-weight: 900;

    letter-spacing: -.035em;

    color: white;

    text-shadow:
        0 2px 12px rgba(0,0,0,.25);
}


/* ============================================================
   AUTOR
   ============================================================ */

.history-book__info p {
    margin: 11px 0 0;

    font-size: 14px;

    font-weight: 600;

    color: rgba(255,255,255,.82);
}


/* ============================================================
   TEXTO INFERIOR
   ============================================================ */

.history-book__hint {
    display: flex;
    align-items: center;
    gap: 7px;

    margin-top: 22px;

    font-size: 10px;
    font-weight: 800;

    text-transform: uppercase;
    letter-spacing: .12em;

    color: rgba(255,255,255,.62);
}

.history-book__hint span {
    font-size: 16px;
}


/* ============================================================
   NÚMERO DE LIBRO
   ============================================================ */

.history-book__number {
    position: absolute;

    bottom: 17px;
    left: 50%;

    z-index: 4;

    transform: translateX(-50%);

    color: white;

    font-size: 10px;
    font-weight: 800;

    letter-spacing: .08em;

    opacity: .9;

    transition:
        opacity .3s ease,
        transform .4s ease;
}

.history-book.active .history-book__number {
    left: auto;
    right: 18px;

    transform: none;

    opacity: .55;
}


/* ============================================================
   BASE DE ESTANTERÍA
   ============================================================ */

.bookshelf-base {
    position: relative;

    height: 17px;

    margin: -1px auto 0;

    max-width: 1120px;

    border-radius: 3px;

    background:
        linear-gradient(
            to bottom,
            rgba(0,0,0,.28),
            rgba(0,0,0,.08)
        );

    box-shadow:
        0 8px 15px rgba(0,0,0,.12);
}

.bookshelf-base span {
    position: absolute;

    left: 2%;
    right: 2%;
    top: 0;

    height: 2px;

    background: rgba(0,0,0,.22);
}


/* ============================================================
   CONTROLES
   ============================================================ */

.history-controls {
    display: flex;

    align-items: center;
    justify-content: center;

    gap: 20px;

    margin-top: 28px;
}

.history-controls span {
    min-width: 220px;

    text-align: center;

    font-size: 11px;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: .12em;

    opacity: .45;
}

.history-controls button {
    width: 38px;
    height: 38px;

    border: 1px solid rgba(0,0,0,.15);

    border-radius: 50%;

    background: transparent;

    font-size: 18px;

    cursor: pointer;

    transition:
        transform .2s ease,
        background .2s ease;
}

.history-controls button:hover {
    transform: scale(1.08);
    background: rgba(0,0,0,.05);
}


/* ============================================================
   VACÍO
   ============================================================ */

.historia-empty {
    max-width: 520px;

    margin: 40px auto 0;

    padding: 55px 30px;

    text-align: center;

    border: 1px solid rgba(0,0,0,.08);

    border-radius: 22px;

    background: rgba(255,255,255,.55);
}

.historia-empty__icon {
    font-size: 48px;

    margin-bottom: 15px;
}

.historia-empty h5 {
    margin-bottom: 8px;

    font-weight: 800;
}

.historia-empty p {
    margin: 0;

    font-size: 14px;

    opacity: .6;
}


/* ============================================================
   TABLET
   ============================================================ */

@media (max-width: 991px) {

    .bookshelf {
        height: 490px;
    }

    .history-book {
        flex-basis: 62px;
        height: 410px;
    }

    .history-book.active {
        flex-basis: 310px;
        height: 460px;
    }

}


/* ============================================================
   MÓVIL
   ============================================================ */

@media (max-width: 767px) {

    .historia-bookshelf {
        padding: 45px 0 65px;
    }

    .historia-heading {
        margin-bottom: 35px;
    }

    .historia-heading h1 {
        font-size: 39px;
    }

    .historia-heading p {
        font-size: 14px;
    }


    .bookshelf-wrapper {
        padding: 0;
    }

    .bookshelf {
        height: 470px;

        justify-content: flex-start;

        overflow-x: auto;
        overflow-y: hidden;

        padding:
            15px 18px 20px;

        scroll-snap-type: x mandatory;

        scrollbar-width: none;
    }

    .bookshelf::-webkit-scrollbar {
        display: none;
    }


    .history-book {
        flex: 0 0 70px;

        height: 400px;

        scroll-snap-align: center;
    }

    .history-book.active {
        flex: 0 0 275px;

        height: 445px;
    }


    .history-book__info {
        left: 20px;
        right: 20px;
        bottom: 22px;
    }

    .history-book__info h2 {
        font-size: 26px;
    }

    .history-book__info p {
        font-size: 13px;
    }


    .history-controls span {
        min-width: 0;

        font-size: 9px;
    }

}


/* ============================================================
   REDUCCIÓN DE MOVIMIENTO
   ============================================================ */

@media (prefers-reduced-motion: reduce) {

    .history-book,
    .history-book img,
    .history-book__info,
    .history-book__shade {
        transition: none !important;
    }

}

</style>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const books =
            Array.from(
                document.querySelectorAll(
                    '.history-book'
                )
            );

        if (!books.length) {
            return;
        }


        const prevButton =
            document.getElementById(
                'historyPrev'
            );

        const nextButton =
            document.getElementById(
                'historyNext'
            );


        let activeIndex = 0;


        /* =====================================================
           ACTIVAR LIBRO
           ===================================================== */

        function activateBook(index) {

            if (
                index < 0 ||
                index >= books.length
            ) {
                return;
            }


            activeIndex = index;


            books.forEach(
                function (book, bookIndex) {

                    book.classList.toggle(
                        'active',
                        bookIndex === activeIndex
                    );

                }
            );


            /* En móvil centra el libro seleccionado */

            if (
                window.innerWidth <= 767
            ) {

                books[activeIndex].scrollIntoView({
                    behavior: 'smooth',
                    inline: 'center',
                    block: 'nearest'
                });

            }

        }


        /* =====================================================
           MOUSE
           ===================================================== */

        books.forEach(
            function (book, index) {

                book.addEventListener(
                    'mouseenter',
                    function () {

                        activateBook(index);

                    }
                );


                book.addEventListener(
                    'click',
                    function () {

                        activateBook(index);

                    }
                );


                book.addEventListener(
                    'focus',
                    function () {

                        activateBook(index);

                    }
                );

            }
        );


        /* =====================================================
           TECLADO
           ===================================================== */

        books.forEach(
            function (book) {

                book.addEventListener(
                    'keydown',
                    function (event) {

                        if (
                            event.key === 'ArrowRight'
                        ) {

                            event.preventDefault();

                            activateBook(
                                Math.min(
                                    activeIndex + 1,
                                    books.length - 1
                                )
                            );

                        }


                        if (
                            event.key === 'ArrowLeft'
                        ) {

                            event.preventDefault();

                            activateBook(
                                Math.max(
                                    activeIndex - 1,
                                    0
                                )
                            );

                        }

                    }
                );

            }
        );


        /* =====================================================
           BOTÓN ANTERIOR
           ===================================================== */

        if (prevButton) {

            prevButton.addEventListener(
                'click',
                function () {

                    activateBook(
                        Math.max(
                            activeIndex - 1,
                            0
                        )
                    );

                }
            );

        }


        /* =====================================================
           BOTÓN SIGUIENTE
           ===================================================== */

        if (nextButton) {

            nextButton.addEventListener(
                'click',
                function () {

                    activateBook(
                        Math.min(
                            activeIndex + 1,
                            books.length - 1
                        )
                    );

                }
            );

        }


        /* =====================================================
           ESTADO INICIAL
           ===================================================== */

        activateBook(0);

    }
);

</script>