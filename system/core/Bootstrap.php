<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Microinei
 *
 * Mini framework para encuestas
 *
 * @package		Microinei
 * @author		holivares
 * @copyright	Copyright (c) 2008 - 2011, inei.
 * @license		http://example.com/license.html
 * @since		Version 0.1
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * View Class
 * Representa una vista dentro del patron mvc. establece los recursos estaticos
 * a utlizarse al renderizar la vista para ser mostrada en el browser.
 *
 * @package		Microinei
 * @subpackage	core
 * @category	Core
 * @author		holivares
 */
class Bootstrap {

    public static function run(Request $peticion) {
        $_controller = $peticion->get_controlador() . 'Controller';   //  ejem:  indexController o postController
        $rutaControlador = APP_PATH . 'controllers' . DS . $_controller . '.php';  // ejem:  .../controllers/indexController.php
        if ($peticion->get_path() !== NULL) {
            $rutaControlador = APP_PATH . 'controllers' . DS . $peticion->get_path() . DS . $_controller . '.php';
        }
        $metodo = $peticion->get_metodo();
        $args = $peticion->get_argumentos();
//        var_dump($rutaControlador);
        if (is_readable($rutaControlador)) {
            require_once $rutaControlador;
            $_controller = new $_controller;

            if (is_callable(array($_controller, $metodo))) {
                $metodo = $peticion->get_metodo();
            } else {
                $metodo = 'index';
            }

            if (isset($args)) {
                call_user_func_array(array($_controller, $metodo), $args);
            } else {
                call_user_func(array($_controller, $metodo));
            }
        } else {
            throw new Exception('no encontrado');
        }
    }

}

?>