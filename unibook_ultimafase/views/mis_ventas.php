<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$misVentas = $misVentas ?? [];

?>

<?php include "views/partials/navbar.php"; ?>


<div class="container py-5">

    <div class="mb-5">

        <span class="badge rounded-pill text-bg-light border px-3 py-2">
            💼 Mi actividad
        </span>

        <h1 class="fw-bold mt-3 mb-2">
            Mis ventas
        </h1>

        <p class="text-muted mb-0">
            Consulta los libros que has vendido a otros estudiantes.
        </p>

    </div>


    <?php if(empty($misVentas)): ?>


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
                💼
            </div>

            <h3 class="fw-bold">
                Todavía no tienes ventas
            </h3>

            <p class="text-muted mb-4">
                Publica los libros que ya no necesitas y dales una
                segunda oportunidad dentro de UniBook.
            </p>

            <a
                href="index.php?accion=publicar_libro"
                class="btn btn-primary rounded-pill px-4"
            >
                Publicar un libro
            </a>

        </div>


    <?php else: ?>


        <div class="d-flex justify-content-between align-items-center mb-4">

            <p class="text-muted mb-0">

                <strong>
                    <?= count($misVentas) ?>
                </strong>

                <?= count($misVentas) === 1
                    ? 'venta registrada'
                    : 'ventas registradas'
                ?>

            </p>

        </div>


        <div class="row g-4">

            <?php foreach($misVentas as $venta): ?>

                <?php

                $imagen = !empty($venta['imagen'])
                    ? $venta['imagen']
                    : 'assets/img/libro-placeholder.png';


                $estado = strtolower(
                    $venta['estado'] ?? 'pendiente'
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
                            $venta['metodo_pago'] ?? ''
                        )
                    ]
                    ?? ucfirst(
                        $venta['metodo_pago'] ?? ''
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
                                        alt="Portada de <?= htmlspecialchars($venta['titulo']) ?>"
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
                                            #<?= (int)$venta['id'] ?>
                                        </small>

                                    </div>


                                    <h4 class="fw-bold mt-3 mb-1">

                                        <?= htmlspecialchars(
                                            $venta['titulo']
                                        ) ?>

                                    </h4>


                                    <?php if(!empty($venta['autor'])): ?>

                                        <p class="text-muted mb-3">

                                            <?= htmlspecialchars(
                                                $venta['autor']
                                            ) ?>

                                        </p>

                                    <?php endif; ?>


                                    <div class="mb-3">

                                        <span
                                            class="fs-4 fw-bold text-success"
                                        >
                                            $
                                            <?= number_format(
                                                (float)$venta['precio'],
                                                0,
                                                ',',
                                                '.'
                                            ) ?>
                                        </span>

                                    </div>


                                    <div class="small text-muted">

                                        <div class="mb-2">
                                            👤
                                            <strong>Comprador:</strong>

                                            <?= htmlspecialchars(
                                                trim(
                                                    ($venta['comprador_nombre'] ?? '')
                                                    . ' '
                                                    . ($venta['comprador_apellido'] ?? '')
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

                                            <?= !empty($venta['fecha_compra'])
                                                ? date(
                                                    'd/m/Y H:i',
                                                    strtotime(
                                                        $venta['fecha_compra']
                                                    )
                                                )
                                                : 'No disponible'
                                            ?>
                                        </div>

                                    </div>


                                    <div class="mt-4">

                                        <a
                                            href="index.php?accion=detalle_compra&id=<?= (int)$venta['id'] ?>"
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