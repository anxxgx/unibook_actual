<?php
?>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">
                Mis intercambios
            </h1>

            <p class="text-muted mb-0">
                Aquí puedes ver las solicitudes de intercambio que has enviado.
            </p>
        </div>

        <a
            href="index.php?accion=catalogo"
            class="btn btn-primary"
        >
            Explorar libros
        </a>
    </div>


    <?php if (isset($_GET['intercambio']) && $_GET['intercambio'] === 'creado'): ?>

        <div class="alert alert-success shadow-sm">
            <strong>¡Solicitud enviada!</strong>
            La solicitud de intercambio fue enviada correctamente.
        </div>

    <?php endif; ?>


    <?php if (empty($intercambiosEnviados)): ?>

        <div class="text-center py-5">

            <div style="font-size: 60px;">
                📚
            </div>

            <h3 class="fw-bold mt-3">
                Aún no tienes solicitudes
            </h3>

            <p class="text-muted">
                Cuando solicites un intercambio, aparecerá aquí.
            </p>

            <a
                href="index.php?accion=catalogo"
                class="btn btn-primary mt-2"
            >
                Ver catálogo
            </a>

        </div>

    <?php else: ?>

        <div class="row g-4">

            <?php foreach ($intercambiosEnviados as $intercambio): ?>

                <div class="col-md-6 col-lg-4">

                    <div class="card h-100 shadow-sm border-0">

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-start mb-3">

                                <span class="badge bg-warning text-dark">
                                    <?= htmlspecialchars(
                                        ucfirst(
                                            $intercambio['estado'] ?? 'pendiente'
                                        )
                                    ) ?>
                                </span>

                                <small class="text-muted">
                                    <?= isset($intercambio['fecha_creacion'])
                                        ? htmlspecialchars(
                                            $intercambio['fecha_creacion']
                                        )
                                        : ''
                                    ?>
                                </small>

                            </div>


                            <h5 class="fw-bold">
                                <?= htmlspecialchars(
                                    $intercambio['libro_solicitado']
                                ) ?>
                            </h5>

                            <p class="text-muted mb-3">
                                Libro que deseas recibir
                            </p>


                            <div class="border rounded p-3 mb-3">

                                <small class="text-muted">
                                    Tú ofreciste
                                </small>

                                <div class="fw-semibold mt-1">
                                    <?= htmlspecialchars(
                                        $intercambio['libro_ofrecido']
                                    ) ?>
                                </div>

                            </div>


                            <div class="small text-muted">

                                <div>
                                    <strong>Estado:</strong>

                                    <?= htmlspecialchars(
                                        ucfirst(
                                            $intercambio['estado'] ?? 'pendiente'
                                        )
                                    ) ?>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>