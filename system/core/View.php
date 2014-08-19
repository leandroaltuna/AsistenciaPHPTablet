<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
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
class View {

    private $_controlador;
    private $_rutaVista;
    private $_js;
    private $_css;
    protected $_request;

    /**
     * Una instancia de esta clase es usada por cada instancia de controlador
     * para renderizar las vistas.
     *
     * @param Request $peticion
     */
    public function __construct(Request $peticion) {
        $this->_controlador = $peticion->get_controlador();
        $this->_request = $peticion;
        $this->set_rutaVista($peticion->get_path(), $peticion->get_controlador());
        $this->_js = array();
        $this->_css = array();
        $this->CI = array(
            'base_url' => BASE_URL . 'index.php/'
        );
    }

    /**
     * Devuelve el path logico de la vista a utilizar
     * @return string
     */
    public function get_rutaVista() {
        return $this->_rutaVista;
    }

    /**
     * Establece el path logico de la vista
     * @param string $path
     * @param string $controlador
     */
    private function set_rutaVista($path, $controlador) {
        $this->_rutaVista = $path . DS . $controlador;
        if ($path === NULL) {
            $this->_rutaVista = $controlador;
        }
    }

    public function redireccionar($vista) {
        $vista = BASE_URL . 'index.php/' . $vista;
        header("Location: $vista");
    }

    /**
     * Carga la plantilla solicitada, utilizando el layout por defecto.
     *
     * @param string $vista
     * @throws Exception Si la vista no existe
     */
    public function renderizar($vista, $layout = DEFAULT_LAYOUT) {
        $layout = is_string($layout) ? $layout : '.git';

        if (!is_dir(APP_PATH . 'views' . DS . 'layout' . DS . $layout)) {
            throw new Exception('Error de layout');
        }

        $js = array();
        $css = array();

        if (count($this->_js)) {
            $js = $this->_js;
        }
        if (count($this->_css)) {
            $css = $this->_css;
        }

        $_layoutParams = array(
            'js' => $js,
            'css' => $css
        );

        $rutaView = APP_PATH . 'views' . DS . $this->_rutaVista . DS . $vista . '.phtml';
        if (is_readable($rutaView)) {
            include_once APP_PATH . 'views' . DS . 'layout' . DS . $layout . DS . 'header.php';
            include_once APP_PATH . 'views' . DS . 'layout' . DS . $layout . DS . 'body.php';
            include_once $rutaView;
            include_once APP_PATH . 'views' . DS . 'layout' . DS . $layout . DS . 'footer.php';
        } else {
            throw new Exception('Error de vista');
        }
    }

    /**
     * La vista envia una respuesta json, para ello, se debe establecer los
     * datos a encodear mediante el atributo json de la vista
     * @param bool $auto
     */
    public function renderizarJson($auto = true) {
        header('Content-Type: application/json');
        if ($auto) {
            echo json_encode(array('data' => $this->json));
        } else {
            echo json_encode($this->json);
        }
    }

    /**
     * Construye la ruta a cargar para los recursos js requeridos.
     * @param array $js
     * @throws Exception
     */
    public function setJs(array $js) {
        if (is_array($js) && count($js)) {
            for ($i = 0; $i < count($js); $i++) {
                $this->_js[] = BASE_URL . 'application/views/' . $this->_rutaVista . '/js/' . $js[$i] . '.js';
            }
        } else {
            throw new Exception('Error de js');
        }
    }

    /**
     * Construye la ruta a cargar para los recursos css requeridos.
     * @param array $css
     * @throws Exception
     */
    public function setCss(array $css) {
        if (is_array($css) && count($css)) {
            for ($i = 0; $i < count($css); $i++) {
                $this->_css[] = BASE_URL . 'application/views/' . $this->_rutaVista . '/css/' . $css[$i] . '.css';
            }
        } else {
            throw new Exception('Error de css');
        }
    }

}

?>
