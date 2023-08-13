<?php

namespace Controllers;
use MVC\Router;
use Model\Proyecto;
use Model\Usuario;

class DashboardController {

    public static function index(Router $router) {
        session_start();
        isAuth();

        $id = $_SESSION['id'];

        $proyectos = Proyecto::belongsTo('propietarioId', $id);

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router) {
        session_start();
        isAuth();

        $alertas = [];

        if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            $proyecto = new Proyecto($_POST);
            $alertas = $proyecto->validarProyecto();

            if( empty($alertas) ) {
                $hash = md5(uniqid());
                $proyecto->url = $hash;
                $proyecto->propietarioId = $_SESSION['id'];

                $resultado = $proyecto->guardar();
                
                if($resultado) {
                    header('Location: /proyecto?id='. $proyecto->url);
                }
            }
        }

        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function proyecto(Router $router) {
        session_start();
        isAuth();

        
        $alertas = [];
        $url = s($_GET['id']);
        
        if(!$url) header('Location: /dashboard');
        
        $proyecto = Proyecto::where('url', $url);
        // Revisar que la persona que visita el proyecto, es quien lo creó
        if( $_SESSION['id'] !== $proyecto->propietarioId ) {
            Header('Location: /dashboard');
        }


        // debuguear($proyecto);

        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto,
            'alertas' => $alertas
        ]);
    }

    public static function perfil(Router $router) {
        session_start();
        isAuth();
        $alertas = [];

        $usuario = Usuario::find($_SESSION['id']);

        if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarNombreEmail();

            if( empty($alertas) ) {

                $usuarioExistente = Usuario::where('email', $usuario->email);

                if( $usuarioExistente && $usuario->id !== $usuarioExistente->id ) {
                    // Existe usuario
                    Usuario::setAlerta('error', 'Email ya está registrado');
                    $alertas = Usuario::getAlertas();

                } else {
                    // Guardar registro
                    $usuario->guardar();
    
                    $_SESSION['nombre'] = $usuario->nombre;
    
                    Usuario::setAlerta('exito', 'Cambios guardados correctamente');
    
                    $alertas = Usuario::getAlertas();
                }   
            }
        }

        $router->render('dashboard/perfil',[
            'titulo' => 'Perfil',
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }

    public static function cambiar_password(Router $router) {
        session_start();
        isAuth();
        $alertas = [];

        if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            
            $usuario = Usuario::find($_SESSION['id']);

            $usuario->sincronizar($_POST);

            $alertas = $usuario->validar_password();

            if( empty($alertas) ) {
                
                $password = password_verify($usuario->password_actual, $usuario->password);
                
                if( $password ) {
                    $usuario->password = $usuario->nuevo_password;
                    $usuario->hashPassword();
                    $resultado = $usuario->guardar();

                    if( $resultado ) {
                        Usuario::setAlerta('exito', 'La contraseña se ha actualizado');
                        $alertas = Usuario::getAlertas();

                    }

                } else {
                    Usuario::setAlerta('error', 'Password incorrecto');
                    $alertas = Usuario::getAlertas();
                }

            }

        }

        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar Password',
            'alertas' => $alertas
        ]);
    }

}