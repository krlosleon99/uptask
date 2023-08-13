<div class="contenedor reestablecer">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <?php if($conf) { ?>

            <p class="descripcion-pagina">Coloca tu nuevo password</p>

            <form method="POST" class="formulario">

                <div class="campo">
                    <label for="password">Nuevo password:</label>

                    <input 
                        type="password"
                        id="password"
                        placeholder="Tu password"    
                        name="password"
                    >
                </div>

                <input type="submit" class="boton" value="Guardar Password">

            </form>
            
        <?php }?>

        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Crea una cuenta</a>
            <a href="/olvide">Olvidé mi password</a>
        </div>
    </div> <!-- FIN .contenedor-sm -->
</div>