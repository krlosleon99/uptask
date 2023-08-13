<?php include_once __DIR__ . '/headerDashboard.php'; ?>

<div class="contenedor-sm">
    
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <form action="crear-proyecto" method="POST" class="formulario" >
        <?php include_once __DIR__ . '/formulario-proyectos.php'; ?>

        <input type="submit" value="Crear proyecto">
    </form>
</div>

<?php include_once __DIR__ . '/footerDashboard.php'; ?>
