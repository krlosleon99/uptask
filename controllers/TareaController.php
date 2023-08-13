<?php

namespace Controllers;
use Model\Proyecto;
use Model\Tarea;

class TareaController {
    public static function index() {

        $proyectoId = $_GET['id'];

        $proyecto = Proyecto::where('url', $proyectoId);

        session_start();

        if( !$proyecto || $proyecto->propietarioId !== $_SESSION['id'] ) header('Location: /404');

        $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);

        echo json_encode(['tareas' => $tareas]);

    }

    public static function crear() {
        if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

            session_start();

            $proyectoId = $_POST['proyectoId'];

            $proyecto = Proyecto::where('url', $proyectoId);

            if( !$proyecto || $proyecto->propietarioId !== $_SESSION['id'] ) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al guardar la tarea'
                ];
                echo json_encode($respuesta);
            } 

            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            $respuesta = [
                'tipo' => 'exito',
                'id' => $resultado['id'],
                'mensaje' => 'Tarea guardada correctamente',
                'proyectoId' => $proyecto->id
            ];
            echo json_encode($respuesta);
        }
    }

    public static function actualizar() {
        if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

            $proyecto = Proyecto::where('url', $_POST['proyectoId']);
            
            session_start();

            if( !$proyecto || $proyecto->propietarioId !== $_SESSION['id'] ) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al actualizar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }

            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            if( $resultado ) {
                $resultado = [
                    'tipo' => 'exito',
                    'mensaje' => 'Tarea actualizada correctamente',
                    'id' => $tarea->id,
                    'proyectoId' => $proyecto->id
                ];
            }
            echo json_encode($resultado);
        }
    }

    public static function eliminar() {
        if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);
            
            session_start();

            if( !$proyecto || $proyecto->propietarioId !== $_SESSION['id'] ) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al actualizar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }

            $tarea = new Tarea($_POST);
            $resultado = $tarea->eliminar();

            if( $resultado ) {
                $resultado = [
                    'resultado' => $resultado,
                    'tipo' => 'exito',
                    'mensaje' => 'Tarea eliminada correctamente'
                ];
            }
            echo json_encode($resultado);
        }
    }
}