<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$misCompras = $misCompras ?? [];

?>

<?php include "views/partials/navbar.php"; ?>


<div class="container py-5">

    <div class="mb-5">

        <span class="badge rounded-pill text-bg-light border px-3 py-2">
            📚 Mi actividad
        </span>

        <h1 class="fw-bold mt-3 mb-2">
            Mis compras
        </h1>

        <p class="text-muted mb-0">
            Aquí encontrarás los libros que has comprado en UniBook.
        </p>

    </div>


    <?php if(empty($misCompras)): ?>

        <div class="card border-0 shadow-sm rounded-4 text-center p-5">

            <div
                class="mx-auto mb-4 d-flex align-items-center justify-content-center rounded-circle"
                style="
                    width:80px;
                    height:80px;
                    background:#eef6ff;
                    font-size:36px;
                "
            >
                📚
            </div>

            <h3 class="fw-bold">
                Todavía no tienes compras
            </h3>

            <p class="text-muted mb-4">
                Explora el catálogo y encuentra los libros que necesitas
                para tu carrera.
            </p>

            <a
                href="index.php?accion=catalogo"
                class="btn btn-primary rounded-pill px-4"
            >
                Explorar catálogo
            </a>

        </div>


    <?php else: ?>


        <div class="d-flex justify-content-between align-items-center mb-4">

            <p class="text-muted mb-0">

                <strong>
                    <?= count($misCompras) ?>
                </strong>

                <?= count($misCompras) === 1
                    ? 'compra realizada'
                    : 'compras realizadas'
                ?>

            </p>

        </div>


        <div class="row g-4">

            <?php foreach($misCompras as $compra): ?>

                <?php

                $imagen = !empty($compra['imagen'])
                    ? $compra['imagen']
                    : 'assets/img/libro-placeholder.png';


                $estado = strtolower(
                    $compra['estado'] ?? 'pendiente'
                );


                switch($estado){

                    case 'pendiente':

                        $estadoTexto = 'Pendiente';
                        $estadoClase = 'warning';

                    break;

                    case 'confirmada':
                    case 'completada':

                        $estadoTexto = 'Completada';
                        $estadoClase = 'success';

                    break;

                    case 'cancelada':
                    case 'rechazada':

                        $estadoTexto = 'Cancelada';
                        $estadoClase = 'danger';

                    break;

                    default:

                        $estadoTexto = ucfirst($estado);
                        $estadoClase = 'secondary';

                    break;
                }


                $metodos = [
                    'pse' => 'PSE',
                    'nequi' => 'Nequi',
                    'bancolombia' => 'Bancolombia',
                    'daviplata' => 'Daviplata'
                ];


                $metodo =
                    $metodos[
                        strtolower(
                            $compra['metodo_pago'] ?? ''
                        )
                    ]
                    ?? ucfirst(
                        $compra['metodo_pago'] ?? ''
                    );

                ?>

                <div class="col-12 col-lg-6">

                    <div
                        class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden"
                    >

                        <div class="row g-0 h-100">

                            <div class="col-4">

                                <div
                                    class="h-100 d-flex align-items-center justify-content-center"
                                    style="
                                        min-height:230px;
                                        background:#f4f7fb;
                                        padding:18px;
                                    "
                                >

                                    <img
                                        src="<?= htmlspecialchars($imagen) ?>"
                                        alt="Portada de <?= htmlspecialchars($compra['titulo']) ?>"
                                        class="img-fluid rounded-3"
                                        style="
                                            max-height:195px;
                                            object-fit:cover;
                                        "
                                    >

                                </div>

                            </div>


                            <div class="col-8">

                                <div class="card-body p-4">

                                    <div
                                        class="d-flex justify-content-between align-items-start gap-2"
                                    >

                                        <span
                                            class="badge text-bg-<?= $estadoClase ?> rounded-pill"
                                        >
                                            <?= htmlspecialchars($estadoTexto) ?>
                                        </span>

                                        <small class="text-muted">
                                            #<?= (int)$compra['id'] ?>
                                        </small>

                                    </div>


                                    <h4 class="fw-bold mt-3 mb-1">

                                        <?= htmlspecialchars(
                                            $compra['titulo']
                                        ) ?>

                                    </h4>


                                    <?php if(!empty($compra['autor'])): ?>

                                        <p class="text-muted mb-3">
                                            <?= htmlspecialchars(
                                                $compra['autor']
                                            ) ?>
                                        </p>

                                    <?php endif; ?>


                                    <div class="mb-3">

                                        <span
                                            class="fs-4 fw-bold text-primary"
                                        >
                                            $
                                            <?= number_format(
                                                (float)$compra['precio'],
                                                0,
                                                ',',
                                                '.'
                                            ) ?>
                                        </span>

                                    </div>


                                    <div class="small text-muted">

                                        <div class="mb-2">
                                            👤
                                            <strong>Vendedor:</strong>

                                            <?= htmlspecialchars(
                                                trim(
                                                    ($compra['vendedor_nombre'] ?? '')
                                                    . ' '
                                                    . ($compra['vendedor_apellido'] ?? '')
                                                )
                                            ) ?>
                                        </div>


                                        <div class="mb-2">
                                            💳
                                            <strong>Pago:</strong>

                                            <?= htmlspecialchars($metodo) ?>
                                        </div>


                                        <div>
                                            📅
                                            <strong>Fecha:</strong>

                                            <?= !empty($compra['fecha_compra'])
                                                ? date(
                                                    'd/m/Y H:i',
                                                    strtotime(
                                                        $compra['fecha_compra']
                                                    )
                                                )
                                                : 'No disponible'
                                            ?>
                                        </div>

                                    </div>


                                    <div class="mt-4">

                                        <a
                                            href="index.php?accion=detalle_compra&id=<?= (int)$compra['id'] ?>"
                                            class="btn btn-primary rounded-pill px-3"
                                        >
                                            Ver operación
                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>


    <?php endif; ?>

</div>


<?php include "views/partials/footer.php"; ?>