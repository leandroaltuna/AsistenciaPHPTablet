<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
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
 * Controller Class
 * Representa a un controlador dentro del patron mvc posee una instancia a vista
 * mediante el atributo _view, posse una referencia a la peticion que lo invoco
 * mediante el atributo _request, posee una referencia a la clase format para
 * realizar conversion de datos para enviar como contenido de respuesta, pueden
 * ser todos los formatos soportados por el atributo _supported_formats
 *
 * @package		Microinei
 * @subpackage	core
 * @category	Core
 * @author		holivares
 */
abstract class Controller {

    protected $_view;
    protected $_format;
    protected $_request;
    protected $_supported_formats = array(
        'xml' => 'application/xml',
        'json' => 'application/json',
        'jsonp' => 'application/javascript',
        'serialized' => 'application/vnd.php.serialized',
        'php' => 'text/plain',
        'html' => 'text/html',
        'csv' => 'application/csv'
    );
    protected $_allowed_http_methods = array('get', 'delete', 'post', 'put', 'options', 'patch', 'head');
    protected $_response = null;
    protected $_zlib_oc = null;
    protected $_useSession = FALSE;
    private $_isCrossDomain = null;
    private $_crossDomain = null;

    public function __construct() {
        $request = Request::getInstance();
        $this->_request = $request;
        $this->_view = new View($request);
        $this->_response = new stdClass();
        $this->_zlib_oc = @ini_get('zlib.output_compression');
        $this->_response->format = $this->_detect_input_format();
        if($this->_useSession){
            $this->session_start();
        }
    }
    /**
     * Carga la vista inicial del controlador. Siempre debe existir. Es la
     * vista por defecto.
     */
    abstract public function index();
    /**
     * Carga un modelo mediante su nombre corto y devuelve una nueva instancia de
     * dicho modelo. Si el modelo se encuentra en un subdirectorio se puede usar
     * un slash / entre el nombre de directorio y el nombre del modelo.
     * Ejemplo, se tienen dos modelos uno en la carpeta models y otro bajo el
     * directorio models/auth
     * models/usuarioModel
     * models/auth/securityModel
     * <pre><code>
     * <?php
     * $userModel = $this->loadModel('usuario');
     * $securitytModel = $this->loadModel('security');
     *
     * </code></pre>
     * @param string $modelo Nombre del modelo
     * @return \Model
     * @throws Exception Si no se puede encuentra la clase model solicitada
     */
    protected function loadModel($modelo) {
        $path = '';
        //esta en un subdirectorio??
        if (($last_slash = strrpos($modelo, '/')) !== FALSE) {
            // directorio
            $path = substr($modelo, 0, $last_slash + 1) . DS;
            // modelo
            $modelo = substr($modelo, $last_slash + 1);
        }
        $modelo = $modelo . 'Model';
        $rutaModelo = APP_PATH . 'models' . DS . $path . $modelo . '.php';

        if (is_readable($rutaModelo)) {
            require_once $rutaModelo;
            $modelo = new $modelo;
            return $modelo;
        } else {
            throw new Exception('Error de modelo');
        }
    }
    /**
     * Carga un formulario mediante su nombre corto y devuelve una nueva instancia de
     * dicho formulario. Si el formulario se encuentra en un subdirectorio se puede usar
     * un slash / entre el nombre de directorio y el nombre del modelo.
     * Ejemplo, se tienen dos modelos uno en la carpeta models y otro bajo el
     * directorio forms/auth
     * forms/usuarioForm
     * forms/auth/securityForm
     * <pre><code>
     * <?php
     * $userForm = $this->loadForm('usuario');
     * $securitytForm = $this->loadForm('security');
     *
     * </code></pre>
     * @param string $modelo Nombre del modelo
     * @return \Model
     * @throws Exception Si no se puede encuentra la clase formulario solicitada
     */
    protected function loadForm($form) {
        $path = '';
        //esta en un subdirectorio??
        if (($last_slash = strrpos($form, '/')) !== FALSE) {
            // directorio
            $path = substr($form, 0, $last_slash + 1) . DS;
            // modelo
            $form = substr($form, $last_slash + 1);
        }
        $form = $form . 'Form';
        $rutaModelo = APP_PATH . 'forms' . DS . $path . $form . '.php';

        if (is_readable($rutaModelo)) {
            require_once $rutaModelo;
            $form = new $form;
            return $form;
        } else {
            throw new Exception('Error de formulario');
        }
    }
    /**
     * Carga una determinada libreria desde el directorio system/libs mediante
     * un require_once. Despues de la llamada las funciones y/o clases incluidas
     * en la libreria cargada estaran disponibles a nivel del codigo del
     * controlador.
     *
     * @param string $libreria
     * @throws Exception Si no se puede encontrar la libreria solicitada
     */
    protected function getLibrary($libreria) {
        $rutaLibreria = BASEPATH . 'libs' . DS . $libreria . '.php';

        if (is_readable($rutaLibreria)) {
            require_once $rutaLibreria;
        } else {
            throw new Exception('Error de libreria');
        }
    }

    /**
     * Carga un determinado helper desde el directorio system/helpers mediante
     * un require_once. Despues de la llamada las funciones y/o clases incluidas
     * en la libreria cargada estaran disponibles a nivel del codigo del
     * controlador.
     *
     * @param string $helper
     * @throws Exception Si no se puede encontrar el helper solicitado
     */
    protected function getHelper($helper) {
        $rutaLibreria = BASEPATH . 'helpers' . DS . $helper . '.php';

        if (is_readable($rutaLibreria)) {
            require_once $rutaLibreria;
        } else {
            throw new Exception('Error de helper');
        }
    }
    /**
     * Redirecciona a la ruta especificada. Puede ser una ruta fuera de este
     * controlador.
     *
     * @param string $ruta
     */
    protected function redireccionar($ruta = false) {
        if ($ruta) {
            header('location:' . BASE_URL . 'index.php/' . $ruta);
            exit;
        } else {
            header('location:' . BASE_URL);
            exit;
        }
    }

    /**
     * Envia una respuesta http desde el controlador.
     *
     * @param mixed $data El contenido de la respuesta
     * @param int $http_code El estado http de la respuesta
     * @param bool $continue Envia la respuesta y termina el script?
     */
    public function response($data = null, $http_code = null, $continue = false) {

        // If data is null and not code provide, error and bail
        if ($data === null && $http_code === null) {
            $http_code = 404;

            // create the output variable here in the case of $this->response(array());
            $output = null;
        }

        // If data is null but http code provided, keep the output empty
        else if ($data === null && is_numeric($http_code)) {
            $output = null;
        }

        // Otherwise (if no data but 200 provided) or some data, carry on camping!
        else {
            // Is compression requested?
            if (extension_loaded('zlib')) {
                if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) and strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {
                    ob_start('ob_gzhandler');
                }
            }


            is_numeric($http_code) or $http_code = 200;

            // @deprecated the following statement can be deleted.
            // If the format method exists, call and return the output in that format
            if (method_exists($this, '_format_' . $this->_response->format)) {
                // Set the correct format header
                header('Content-Type: ' . $this->_supported_formats[$this->_response->format] . '; charset=' . strtolower(DB_CHAR));

                $output = $this->{'_format_' . $this->_response->format}($data);
            }

            // If the format method exists, call and return the output in that format
            elseif (method_exists($this->_format, 'to_' . $this->_response->format)) {
                // Set the correct format header
                header('Content-Type: ' . $this->_supported_formats[$this->_response->format] . '; charset=' . strtolower(DB_CHAR));

                $output = $this->_format->factory($data)->{'to_' . $this->_response->format}();
            }

            // Format not supported, output directly
            else {
                $output = $data;
            }
        }

        $this->set_status_header($http_code);

        // If zlib.output_compression is enabled it will compress the output,
        // but it will not modify the content-length header to compensate for
        // the reduction, causing the browser to hang waiting for more data.
        // We'll just skip content-length in those cases.
        if (!$this->_zlib_oc) {
            header('Content-Length: ' . strlen($output));
        }

        if ($this->isCrossDomain()) {
            header('Access-Control-Allow-Origin: ' . $this->get_crossDomain());
        }

        if ($continue) {
            echo($output);
            ob_end_flush();
            ob_flush();
            flush();
        } else {
            exit($output);
        }
    }
    /**
     * Devuelve verdadero si la peticion es via https, falso en caso contrario.
     * @return bool
     */
    protected function _detect_ssl() {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on");
    }

    /*
     * Detecta el tipo de contenido enviado en la peticion
     *
     */

    protected function _detect_input_format() {
        if ($this->_request->server('CONTENT_TYPE')) {
            // Check all formats against the HTTP_ACCEPT header
            foreach ($this->_supported_formats as $format => $mime) {
                if (strpos($match = $this->_request->server('CONTENT_TYPE'), ';')) {
                    $match = current(explode(';', $match));
                }

                if ($match == $mime) {
                    return $format;
                }
            }
        }
        return null;
    }
    /**
     * Establece la cabecera de codigo de estado http a la respuesta
     * @param int $code
     * @param string $text
     */
    function set_status_header($code = 200, $text = '') {
        $stati = array(
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'
        );

        if ($code == '' OR !is_numeric($code)) {
            show_error('Status codes must be numeric', 500);
        }

        if (isset($stati[$code]) AND $text == '') {
            $text = $stati[$code];
        }

        if ($text == '') {
            show_error('No status text available.  Please check your status code number or supply your own message text.', 500);
        }

        $server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;

        if (substr(php_sapi_name(), 0, 3) == 'cgi') {
            header("Status: {$code} {$text}", TRUE);
        } elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0') {
            header($server_protocol . " {$code} {$text}", TRUE, $code);
        } else {
            header("HTTP/1.1 {$code} {$text}", TRUE, $code);
        }
    }
    /**
     * Verdadero si el controlador va soportar invocaciones desde crossdomain
     * @return bool
     */
    public function isCrossDomain() {
        return $this->_isCrossDomain;
    }
    /**
     * Devuelve el nombre de dominio que actualmente se esta usando para aceptar
     * peticiones crossdomain
     * @return string
     */
    public function get_crossDomain() {
        return $this->_crossDomain;
    }
    /**
     * Establece si el controlador soportara invocaciones desde crossdomain
     * @param bool $_isCrossDomain
     */
    public function set_isCrossDomain($_isCrossDomain) {
        $this->_isCrossDomain = $_isCrossDomain;
    }
    /**
     * Establece el nombre de dominio que sera permitido de realizar peticiones
     * via crossdomain
     * @param string $_crossDomain
     */
    public function set_crossDomain($_crossDomain) {
        $this->_crossDomain = $_crossDomain;
    }
    /**
     * Devuelve un array asociativo con los datos enviados por POST
     * @return array
     */
    protected function parseRequestPost(){
        $data = $this->_request->post();
        if(!$data){
            $data = $this->_request->raw_post();
            if($this->_response->format  === 'json'){
                $data = (array)json_decode($data);
            }
        }
        return $data;
    }
    /**
     * Devuelve un array asociativo con los datos enviados por PUT
     * @return array
     */
    protected function parseRequestPut(){
        $data = $this->_request->put();
        if($data){
            $data = (array)json_decode($data);
        }
        return $data;
    }
    /**
     * Devuelve un array asociativo con los datos enviados por DELETE
     * @return array
     */
    protected function parseRequestDelete(){
        $data = $this->_request->delete();
        if($data){
            $data = (array)json_decode($data);
        }
        return $data;
    }
    /**
     * Devuelve un array asociativo con los datos enviados por GET
     * @return array
     */
    protected function parseRequestGet(){
        $data = $this->_request->get();
        if($this->_response->format  === 'json'){
            if($data & is_array($data)){
                $json = array_keys($data);
                $json = array_shift($json);
                $data = (array)json_decode($json);
            }
        }
        return $data;
    }

    protected function session_start(){
        return session_start();
    }

    protected function session_destroy(){
        return session_destroy();
    }

}

?>
