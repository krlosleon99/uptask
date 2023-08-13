<?php include_once __DIR__ . '/headerDashboard.php'; ?>

    <div class="contenedor-sm">
        <div class="contenedor-nueva-tarea">
            <button
                class="agregar-tarea"
                type="button"
                id="agregar-tarea"
            >&#43; Agregar Tarea</button>
        </div>

        <div class="filtros" id="filtros">
            <div class="filtros-inputs">
                
                <h2>Filtros</h2>
                
                <div class="campo">
                    <label for="todas">Todas</label>
                    <input 
                        type="radio" 
                        id="todas"
                        name="filtro" 
                        value=""
                        checked
                    />
                </div>

                <div class="campo">
                    <label for="completadas">Completadas</label>
                    <input 
                        type="radio" 
                        id="completadas"
                        name="filtro" 
                        value="1"
                    />
                </div>

                <div class="campo">
                    <label for="pendientes">pendientes</label>
                    <input 
                        type="radio" 
                        id="pendientes"
                        name="filtro" 
                        value="0"
                    />
                </div>

            </div>
        </div>

        <ul class="listado-tareas" id="listado-tareas"></ul>
    </div>

<?php include_once __DIR__ . '/footerDashboard.php'; 

    $script .= '
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="build/js/tareas.js"></script>
    ';

?>

