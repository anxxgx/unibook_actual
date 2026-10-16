<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "views/partials/navbar.php";

$mapaEstado = [
    [
        'titulo' => 'Base de datos',
        'detalle' => 'Estructuras de usuarios, libros, compras, intercambios, mensajes y notificaciones.',
        'estado' => 'Revisada',
        'clase' => 'revisada',
        'icono' => 'bi-database-check',
    ],
    [
        'titulo' => 'Compras y disponibilidad',
        'detalle' => 'Hay estados de compra vacíos que requieren revisión antes de corregirlos.',
        'estado' => 'Pendiente',
        'clase' => 'pendiente',
        'icono' => 'bi-cart-x',
    ],
    [
        'titulo' => 'Controladores y modelos PHP',
        'detalle' => 'Todavía no he podido inspeccionar el contenido de la carpeta del código desde esta herramienta.',
        'estado' => 'Sin verificar',
        'clase' => 'sin-verificar',
        'icono' => 'bi-code-square',
    ],
    [
        'titulo' => 'Prueba integral y entrega',
        'detalle' => 'Falta comprobar el recorrido completo de las funciones y detectar errores.',
        'estado' => 'Por hacer',
        'clase' => 'por-hacer',
        'icono' => 'bi-clipboard2-check',
    ],
];

?>

<section class="state-map">
    <div class="container">
        <header class="state-map__heading">
            <span class="state-map__eyebrow">381.46 — Seguimiento técnico</span>
            <h1>Mapa de estado — <em>UniBook Medellín</em></h1>
            <p>Una vista de seguimiento para conocer rápidamente el estado de cada parte del proyecto.</p>
        </header>

        <div class="state-stream" aria-label="Mapa de estado del proyecto">
            <div class="state-stream__glow" aria-hidden="true"></div>
            <div class="state-stream__rail state-stream__rail--left" aria-hidden="true">
                <?php foreach ($mapaEstado as $indice => $item): ?>
                    <div class="state-stream__ghost state-stream__ghost--<?php echo $indice; ?>">
                        <i class="bi <?php echo $item['icono']; ?>" aria-hidden="true"></i>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="state-stream__rail state-stream__rail--right" aria-hidden="true">
                <?php foreach ($mapaEstado as $indice => $item): ?>
                    <div class="state-stream__ghost state-stream__ghost--<?php echo $indice; ?>">
                        <i class="bi <?php echo $item['icono']; ?>" aria-hidden="true"></i>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</section>

<?php include "views/partials/footer.php"; ?>

<style>
    .state-map { overflow:hidden; padding:76px 0 92px; background:linear-gradient(180deg,#f8f5ef 0%,#eef2f7 100%); }
    .state-map__heading { max-width:760px; margin:0 auto 46px; text-align:center; }
    .state-map__eyebrow { color:#7a6540; font:700 11px/1 var(--font-mono,monospace); letter-spacing:.16em; text-transform:uppercase; }
    .state-map__heading h1 { margin:13px 0 0; color:#172033; font-size:clamp(34px,5vw,62px); letter-spacing:-.055em; line-height:.96; }
    .state-map__heading h1 em { color:#9c7b3d; font-style:normal; }
    .state-map__heading p { max-width:590px; margin:19px auto 0; color:#687386; font-size:16px; line-height:1.65; }
    .state-stream { position:relative; isolation:isolate; height:405px; padding:0 20px 45px; container-type:inline-size; perspective:900px; }
    .state-stream__glow { position:absolute; z-index:-1; top:0; left:50%; width:min(58%,640px); aspect-ratio:1; transform:translateX(-50%); background:radial-gradient(circle,rgba(197,161,91,.27),transparent 68%); filter:blur(18px); }
    .state-stream__rail { position:absolute; top:0; right:0; left:0; height:375px; pointer-events:none; transform-style:preserve-3d; }
    .state-stream__ghost { position:absolute; top:48%; left:50%; display:grid; width:14cqw; height:18cqw; min-width:116px; min-height:148px; place-items:center; border:1px solid rgba(28,39,57,.12); border-radius:18px; background:rgba(255,255,255,.42); box-shadow:0 18px 45px rgba(28,39,57,.1); color:#aa8750; font-size:clamp(28px,4cqw,52px); backface-visibility:hidden; animation:state-corridor 15s linear infinite; animation-delay:calc(var(--card-order) * -3.75s); }
    .state-stream__rail--left .state-stream__ghost { --side:-1; }
    .state-stream__rail--right .state-stream__ghost { --side:1; }
    .state-stream__ghost--0 { --card-order:0; }.state-stream__ghost--1 { --card-order:1; }.state-stream__ghost--2 { --card-order:2; }.state-stream__ghost--3 { --card-order:3; }
    @keyframes state-corridor { 0%{transform:translate3d(calc(var(--side) * -9cqw),0,-480px) scale(.2) rotateY(calc(var(--side) * -14deg));opacity:0} 12%{opacity:.5} 58%{opacity:.38} 100%{transform:translate3d(calc(var(--side) * 48cqw),0,330px) scale(1.4) rotateY(calc(var(--side) * 24deg));opacity:0} }
    @media (max-width:700px) { .state-stream{height:0;padding:0}.state-stream__rail{display:none}.state-stream__ghost{display:none}.state-map{padding:55px 0 65px} }
    @media (prefers-reduced-motion:reduce) { .state-stream__ghost{animation:none}.state-card,.state-card__icon{transition:none} }
</style>
