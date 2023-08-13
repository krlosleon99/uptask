<?php include_once __DIR__ . '/headerDashboard.php'; ?>

<div class="contenedor-sm">

    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <a href="/cambiar-password" class="enlace">Cambiar Password</a>

    <form method="POST" class="formulario" action="/perfil">

        <div class="campo">
            <label for="nombre">Nombre:</label>
            <input 
                type="text"
                name="nombre"
                value="<?php echo $usuario->nombre; ?>"
                placeholder="Coloca tu nombre"
                id="nombre"
            />
        </div>

        <div class="campo">
            <label for="email">Email:</label>
            <input 
                type="mail"
                name="email"
                value="<?php echo $usuario->email; ?>"
                placeholder="Coloca tu email"
                id="email"
            />
        </div>

        <input type="submit" value="Guardar Cambios">

    </form>

</div>

<?php include_once __DIR__ . '/footerDashboard.php'; 