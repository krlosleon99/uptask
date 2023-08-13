<?php include_once __DIR__ . '/headerDashboard.php'; ?>

<div class="contenedor-sm">

    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <a href="/perfil" class="enlace">Volver al perfil</a>

    <form method="POST" class="formulario" action="/cambiar-password">

        <div class="campo">
            <label for="password_actual">Password actual:</label>
            <input 
                type="password"
                name="password_actual"
                id="password_actual"
                placeholder="Coloca tu actual password"
            />
        </div>
        
        <div class="campo">
            <label for="nuevo_password">Nuevo password:</label>
            <input 
                type="password"
                name="nuevo_password"
                id="nuevo_password"
                placeholder="Coloca tu nuevo password"
            />
        </div>

        <input type="submit" value="Guardar Cambios">

    </form>

</div>

<?php include_once __DIR__ . '/footerDashboard.php'; 