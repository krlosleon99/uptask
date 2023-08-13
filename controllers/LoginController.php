<?php

namespace Controllers;

use MVC\Router;
use Model\Usuario;
use Classes\Email;

class LoginController {

    public static function login(Router $router) {
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarLogin();

            if( empty($alertas) ) {
                $usuario = Usuario::where('email', $usuario->email);
                
                if( empty($usuario) || $usuario->token ) {
                    Usuario::setAlerta('error','Usuario no encontrado o el usuario no está confirmado');
                } else {
                    //comprobar el usuario

                    if( password_verify($_POST['password'], $usuario->password) ) {
                        // Iniciar sesión usuario
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //Redireccionar
                        Header('Location: /dashboard');

                    } else {
                        Usuario::setAlerta('error','El password es incorrecto');
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();

        // Render a la vista
        $router->render('auth/login', [
            'titulo' => 'Iniciar sesión',
            'alertas' => $alertas
        ]);
    }

    public static function logout() {
        session_start();
        $_SESSION = [];
        header('Location: /');
    }

    public static function crear(Router $router) {

        $usuario = new Usuario;
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarCuentaNueva();

            if( empty($alertas) ) {
                $usuarioExiste = Usuario::where('email', $usuario->email);

                if( $usuarioExiste ) {
                    Usuario::setAlerta('error', 'El usuario ya existe');
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hashear el password
                    $usuario->hashPassword();

                    // Eliminar password2
                    unset($usuario->password2);

                    //Generar token único
                    $usuario->crearToken();

                    // Crear nuevo usuario
                    $resultado = $usuario->guardar();

                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);

                    $email->enviarConfirmacion();

                    if( $resultado ) {
                        Header('Location: /mensaje');
                    }

                }
            }

        }

        $router->render('auth/crear', [
            'titulo' => 'Crear una cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
    
    public static function olvide(Router $router) {

        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();
            
            if( empty($alertas) ) {
                $usuario = Usuario::where('email', $usuario->email);
                
                if( $usuario ) {
                    // El usuario existe
                    if( $usuario->confirmado ) {
                        // El usuario está confirmado

                        // Generar token y eliminar atributo password2
                        $usuario->crearToken();
                        unset($usuario->password2);

                        // Enviar email
                        $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                        $email->enviarInstrucciones();

                        // Actualizar usuario
                        $usuario->guardar();

                        // Imprimir alerta
                        Usuario::setAlerta('exito', 'Instrucciones enviadas correctamente al correo');

                    } else {
                        // El usuario no está confirmado
                        Usuario::setAlerta('error', 'El usuario no está confirmado');
                    }
                } else {
                    // El usuario no existe
                    Usuario::setAlerta('error','El usuario no existe');
                }
            }
        }
        
        $alertas = Usuario::getAlertas();
        
        $router->render('auth/olvide', [
            'titulo' => 'Olvidé mi contraseña',
            'alertas' => $alertas
        ]);
    }
    
    public static function reestablecer(Router $router) {
        $token = s($_GET['token']);
        $alertas = [];
        $conf = true;

        if( !$token ) Header('Location: /');

        $usuario = Usuario::where('token', $token);

        if( empty($usuario) ) {
            Usuario::setAlerta('error', 'El token no es válido');
            $conf = false;
        }
        
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPassword();

            if( empty($alertas) ) {
                // HASHEAR PASSWORD
                $usuario->hashPassword();
                unset($usuario->password2);
                $usuario->token = '';
                $resultado = $usuario->guardar();

                if( $resultado ) {
                    Header('Location: /');
                }
            }
        }
        
        $alertas = Usuario::getAlertas();

        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer contraseña',
            'alertas' => $alertas,
            'conf' => $conf
        ]);
    }

    public static function mensaje(Router $router) {

        $router->render('auth/mensaje', [
            'titulo' => 'Instrucciones enviadas'
        ]);
    }

    public static function confirmar(Router $router) {

        $alertas = [];

        $token = s($_GET['token']);

        if( !$token ) header('Location: /');

        $usuario = Usuario::where('token', $token);

        if( empty($usuario) ) {
            //Validando token
            Usuario::setAlerta('error', 'Token inválido');
        } else {
            // Confirmando cuenta
            unset($usuario->password2);
            $usuario->token = '';
            $usuario->confirmado = 1;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta confirmada correctamente');
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar', [
            'titulo' => 'Confirma tu cuenta UpTask',
            'alertas' => $alertas
        ]);
    }
}
