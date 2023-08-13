<div class="contenedor login">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar sesión</p>

        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <form action="/" method="POST" class="formulario">

            <div class="campo">
                <label for="email">Email:</label>

                <input 
                    type="email"
                    id="email"
                    placeholder="Tu email"    
                    name="email"
                >
            </div>

            <div class="campo">
                <label for="password">Password:</label>

                <input 
                    type="password"
                    id="password"
                    placeholder="Tu password"    
                    name="password"
                >
            </div>

            <input type="submit" class="boton" value="Iniciar sesión">

        </form>

        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Crea una cuenta</a>
            <a href="/olvide">Olvidé mi password</a>
        </div>
    </div> <!-- FIN .contenedor-sm -->
</div>