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
 * Request Class
 * Representa la peticion actual. clase Singleton. Basado en la clase controller
 * de codeigniter.
 *
 * @package		Microinei
 * @subpackage	core
 * @category	Core
 * @author		holivares
 */
class Request {

    private static $instancia;
    private $_path;
    private $_controlador;
    private $_metodo;
    private $_argumentos;
    private $uri_string;
    private $ip_address = FALSE;
    private $user_agent = FALSE;

    public function __construct() {
        $path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
        if ($path) {
            $this->uri_string = $url = $this->_set_uri_string($path);
            $url = explode('/', $url);
            $url = array_filter($url);  // elimina los elementos vacios
            $this->_validate_request($url);
        }

        if (!$this->_controlador) {
            $this->_controlador = DEFAULT_CONTROLLER;   // index
        }

        if (!$this->_metodo) {
            $this->_metodo = 'index';
        }

        if (!isset($this->_argumentos)) {
            $this->_argumentos = array();
        }
    }
    /**
     * Establece la uri a utilizar
     * @param string $str
     * @return string
     */
    function _set_uri_string($str) {
        return ($str == '/') ? '' : $str;
    }
    /**
     * Obtiene el subdirectorio del controlador, en caso exista.
     *
     * @return string
     */
    public function get_path() {
        return $this->_path;
    }
    /**
     * Establece el subdiretorio del controlador
     *
     * @param string $_path
     */
    protected function set_path($_path) {
        $this->_path = $_path;
    }
    /**
     * Obtiene el nombre del controlador a utilizar que respondera la peticion
     * @return string
     */
    public function get_controlador() {
        return $this->_controlador;
    }
    /**
     * Obtiene el nombre del metodo a utilizar para responder a la peticion
     * @return string
     */
    public function get_metodo() {
        return $this->_metodo;
    }
    /**
     * Obtiene los argumentos a utilizar en el metodo que se utilizara para
     * responder la peticion
     *
     * @return array
     */
    public function get_argumentos() {
        return $this->_argumentos;
    }
    /**
     * Establece el nombre del controlador a utilizar para responder la peticion
     * @param string $_controlador
     */
    protected function set_controlador($_controlador) {
        $this->_controlador = $_controlador;
    }
    /**
     * Establece el nombre del metodo que se ejecutara para responder la peticion
     * @param string $_metodo
     */
    protected function set_metodo($_metodo) {
        $this->_metodo = $_metodo;
    }
    /**
     * Establece los argumentos del metodo que se ejecutara para responder la peticion
     * @param array $_argumentos
     */
    protected function set_argumentos($_argumentos) {
        $this->_argumentos = $_argumentos;
    }
    /**
     * Establece el controlador, metodo y argumentos solicitados en la
     * peticion actual.
     *
     * @param array $segments
     * @return boolean Si se pudo obtener satisfactoriamente la peticion
     */
    protected function _validate_request($segments) {

        if (count($segments) === 0) {
            $this->set_controlador(DEFAULT_CONTROLLER);
        }

        // primero busca en la raiz
        $controller = array_shift($segments);
        if (file_exists(APP_PATH . 'controllers' . DS . $controller . 'Controller.php')) {
            $this->set_controlador($controller);
            $this->set_metodo(array_shift($segments));
            $this->set_argumentos($segments);
            return TRUE;
        }
        // Esta en subcarpeta?
        if (is_dir(APP_PATH . 'controllers' . DS . $controller)) {
            // establece el directorio y lo quita del los segmentos
            $this->set_path($controller);
            $this->set_controlador(DEFAULT_CONTROLLER);
            if (count($segments > 0)) {
                $controller = array_shift($segments);
                if (file_exists(APP_PATH . 'controllers/' . DS . $this->get_path() . DS . $controller . 'Controller.php')) {
                    $this->set_controlador($controller);
                    $this->set_metodo(array_shift($segments));
                    $this->set_argumentos($segments);
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
    /**
     * Obtiene el valor de un array asociativo, esta por implementar la XSS protection
     * @param array $array
     * @param string $index
     * @param bool $xss_clean
     * @return mixed
     */
    function _fetch_from_array(&$array, $index = '', $xss_clean = FALSE) {
        if (!isset($array[$index])) {
            return FALSE;
        }

//        if ($xss_clean === TRUE) {
//            return $this->security->xss_clean($array[$index]);
//        }

        return $array[$index];
    }

    // --------------------------------------------------------------------

    /**
     * Busca un elemento en el array SESSION, si un elemento en especifico no es
     * proporcionado, entonces se devuelve todo el array.
     *
     * @param string $index
     * @param bool $xss_clean
     * @return mixed
     */
    function session($index = NULL, $xss_clean = FALSE) {
        // Check if a field has been provided
        if ($index === NULL AND !empty($_SESSION)) {
            $session = array();

            // loop through the full _GET array
            foreach (array_keys($_SESSION) as $key) {
                $session[$key] = $this->_fetch_from_array($_SESSION, $key, $xss_clean);
            }
            return $session;
        }
        return $this->_fetch_from_array($_SESSION, $index, $xss_clean);
    }

    /**
     * Guarda un valor en el array SESSION
     *
     * @param string $index
     * @param mixed $value
     */
    function session_add($index, $value){
        $_SESSION[$index] = $value;
    }

    /**
     * Busca un elemento en el array GET, si un elemento en especifico no es
     * proporcionado, entonces se devuelve todo el array.
     *
     * @param string $index
     * @param bool $xss_clean
     * @return mixed
     */
    function get($index = NULL, $xss_clean = FALSE) {
        // Check if a field has been provided
        if ($index === NULL AND !empty($_GET)) {
            $get = array();

            // loop through the full _GET array
            foreach (array_keys($_GET) as $key) {
                $get[$key] = $this->_fetch_from_array($_GET, $key, $xss_clean);
            }
            return $get;
        }

        return $this->_fetch_from_array($_GET, $index, $xss_clean);
    }

    // --------------------------------------------------------------------

    /**
     * Busca un elemento en el array POST, si un elemento en especifico no es
     * proporcionado, entonces se devuelve todo el array.
     *
     * @param string $index
     * @param bool $xss_clean
     * @return mixed
     */
    function post($index = NULL, $xss_clean = FALSE) {
        // Check if a field has been provided
        if ($index === NULL AND !empty($_POST)) {
            $post = array();

            // Loop through the full _POST array and return it
            foreach (array_keys($_POST) as $key) {
                $post[$key] = $this->_fetch_from_array($_POST, $key, $xss_clean);
            }
            return $post;
        }

        return $this->_fetch_from_array($_POST, $index, $xss_clean);
    }

    // --------------------------------------------------------------------

    /**
     * Busca un elemento en el array GET o GET, si un elemento en especifico no es
     * proporcionado, entonces se devuelve todo el array.
     *
     * @param string $index
     * @param bool $xss_clean
     * @return mixed
     */
    function get_post($index = '', $xss_clean = FALSE) {
        if (!isset($_POST[$index])) {
            return $this->get($index, $xss_clean);
        } else {
            return $this->post($index, $xss_clean);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Busca un elemento en el array COOKIE, si un elemento en especifico no es
     * proporcionado, entonces se devuelve todo el array.
     *
     * @param string $index
     * @param bool $xss_clean
     * @return mixed
     */
    function cookie($index = '', $xss_clean = FALSE) {
        return $this->_fetch_from_array($_COOKIE, $index, $xss_clean);
    }
    /**
     * Busca un elemento en el array REQUEST, si un elemento en especifico no es
     * proporcionado, entonces se devuelve todo el array.
     *
     * @param type $index
     * @param type $xss_clean
     * @return type
     */
    function request($index = '', $xss_clean = FALSE) {
        return $this->_fetch_from_array($_REQUEST, $index, $xss_clean);
    }
    /**
     * Obtiene los datos enviados por PUT.
     *
     * @return mixed
     */
    function put() {
        $data = file_get_contents('php://input');
        return !$data?array():$data;
    }
    /**
     * Obtiene los datos enviados por DELETE.
     *
     * @return mixed
     */
    function delete($index = '', $xss_clean = FALSE) {
        $data = file_get_contents('php://input');
        return !$data?array():$data;
    }
    /**
     * Obtiene los datos enviados por POST en un formato distinto al POST standard
     *
     * @return type
     */
    function raw_post(){
        return array_key_exists('HTTP_RAW_POST_DATA', $GLOBALS)?$GLOBALS['HTTP_RAW_POST_DATA']:array();
    }

    // ------------------------------------------------------------------------

    /**
     * Set cookie
     *
     * Accepts six parameter, or you can submit an associative
     * array in the first parameter containing all the values.
     *
     * @access	public
     * @param	mixed
     * @param	string	the value of the cookie
     * @param	string	the number of seconds until expiration
     * @param	string	the cookie domain.  Usually:  .yourdomain.com
     * @param	string	the cookie path
     * @param	string	the cookie prefix
     * @param	bool	true makes the cookie secure
     * @return	void
     */
    function set_cookie($name = '', $value = '', $expire = '', $domain = '', $path = '/', $prefix = '', $secure = FALSE) {
        if (is_array($name)) {
            // always leave 'name' in last place, as the loop will break otherwise, due to $$item
            foreach (array('value', 'expire', 'domain', 'path', 'prefix', 'secure', 'name') as $item) {
                if (isset($name[$item])) {
                    $$item = $name[$item];
                }
            }
        }

        if ($prefix == '' AND config_item('cookie_prefix') != '') {
            $prefix = config_item('cookie_prefix');
        }
        if ($domain == '' AND config_item('cookie_domain') != '') {
            $domain = config_item('cookie_domain');
        }
        if ($path == '/' AND config_item('cookie_path') != '/') {
            $path = config_item('cookie_path');
        }
        if ($secure == FALSE AND config_item('cookie_secure') != FALSE) {
            $secure = config_item('cookie_secure');
        }

        if (!is_numeric($expire)) {
            $expire = time() - 86500;
        } else {
            $expire = ($expire > 0) ? time() + $expire : 0;
        }

        setcookie($prefix . $name, $value, $expire, $path, $domain, $secure);
    }

    // --------------------------------------------------------------------

    /**
     * Busca un elemento en el array SERVER
     *
     * @access	public
     * @param	string
     * @param	bool
     * @return	string
     */
    function server($index = '', $xss_clean = FALSE) {
        return $this->_fetch_from_array($_SERVER, $index, $xss_clean);
    }

    // --------------------------------------------------------------------

    /**
     * Busca la IP Address
     *
     * @return	string
     */
    public function ip_address() {
        if ($this->ip_address !== FALSE) {
            return $this->ip_address;
        }

        $proxy_ips = '';
        if (!empty($proxy_ips)) {
            $proxy_ips = explode(',', str_replace(' ', '', $proxy_ips));
            foreach (array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'HTTP_X_CLIENT_IP', 'HTTP_X_CLUSTER_CLIENT_IP') as $header) {
                if (($spoof = $this->server($header)) !== FALSE) {
                    // Some proxies typically list the whole chain of IP
                    // addresses through which the client has reached us.
                    // e.g. client_ip, proxy_ip1, proxy_ip2, etc.
                    if (strpos($spoof, ',') !== FALSE) {
                        $spoof = explode(',', $spoof, 2);
                        $spoof = $spoof[0];
                    }

                    if (!$this->valid_ip($spoof)) {
                        $spoof = FALSE;
                    } else {
                        break;
                    }
                }
            }

            $this->ip_address = ($spoof !== FALSE && in_array($_SERVER['REMOTE_ADDR'], $proxy_ips, TRUE)) ? $spoof : $_SERVER['REMOTE_ADDR'];
        } else {
            $this->ip_address = $_SERVER['REMOTE_ADDR'];
        }

        if (!$this->valid_ip($this->ip_address)) {
            $this->ip_address = '0.0.0.0';
        }

        return $this->ip_address;
    }

    // --------------------------------------------------------------------

    /**
     * Valida IP Address
     *
     * @access	public
     * @param	string
     * @param	string	ipv4 or ipv6
     * @return	bool
     */
    public function valid_ip($ip, $which = '') {
        $which = strtolower($which);

        // First check if filter_var is available
        if (is_callable('filter_var')) {
            switch ($which) {
                case 'ipv4':
                    $flag = FILTER_FLAG_IPV4;
                    break;
                case 'ipv6':
                    $flag = FILTER_FLAG_IPV6;
                    break;
                default:
                    $flag = '';
                    break;
            }

            return (bool) filter_var($ip, FILTER_VALIDATE_IP, $flag);
        }

        if ($which !== 'ipv6' && $which !== 'ipv4') {
            if (strpos($ip, ':') !== FALSE) {
                $which = 'ipv6';
            } elseif (strpos($ip, '.') !== FALSE) {
                $which = 'ipv4';
            } else {
                return FALSE;
            }
        }

        $func = '_valid_' . $which;
        return $this->$func($ip);
    }

    // --------------------------------------------------------------------

    /**
     * Validate IPv4 Address
     *
     * Updated version suggested by Geert De Deckere
     *
     * @access	protected
     * @param	string
     * @return	bool
     */
    protected function _valid_ipv4($ip) {
        $ip_segments = explode('.', $ip);

        // Always 4 segments needed
        if (count($ip_segments) !== 4) {
            return FALSE;
        }
        // IP can not start with 0
        if ($ip_segments[0][0] == '0') {
            return FALSE;
        }

        // Check each segment
        foreach ($ip_segments as $segment) {
            // IP segments must be digits and can not be
            // longer than 3 digits or greater then 255
            if ($segment == '' OR preg_match("/[^0-9]/", $segment) OR $segment > 255 OR strlen($segment) > 3) {
                return FALSE;
            }
        }

        return TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * Validate IPv6 Address
     *
     * @access	protected
     * @param	string
     * @return	bool
     */
    protected function _valid_ipv6($str) {
        // 8 groups, separated by :
        // 0-ffff per group
        // one set of consecutive 0 groups can be collapsed to ::

        $groups = 8;
        $collapsed = FALSE;

        $chunks = array_filter(
                preg_split('/(:{1,2})/', $str, NULL, PREG_SPLIT_DELIM_CAPTURE)
        );

        // Rule out easy nonsense
        if (current($chunks) == ':' OR end($chunks) == ':') {
            return FALSE;
        }

        // PHP supports IPv4-mapped IPv6 addresses, so we'll expect those as well
        if (strpos(end($chunks), '.') !== FALSE) {
            $ipv4 = array_pop($chunks);

            if (!$this->_valid_ipv4($ipv4)) {
                return FALSE;
            }

            $groups--;
        }

        while ($seg = array_pop($chunks)) {
            if ($seg[0] == ':') {
                if (--$groups == 0) {
                    return FALSE; // too many groups
                }

                if (strlen($seg) > 2) {
                    return FALSE; // long separator
                }

                if ($seg == '::') {
                    if ($collapsed) {
                        return FALSE; // multiple collapsed
                    }

                    $collapsed = TRUE;
                }
            } elseif (preg_match("/[^0-9a-f]/i", $seg) OR strlen($seg) > 4) {
                return FALSE; // invalid segment
            }
        }

        return $collapsed OR $groups == 1;
    }

    // --------------------------------------------------------------------

    /**
     * Busca el USER_AGENT de la peticion
     *
     * @access	public
     * @return	string
     */
    function user_agent() {
        if ($this->user_agent !== FALSE) {
            return $this->user_agent;
        }

        $this->user_agent = (!isset($_SERVER['HTTP_USER_AGENT'])) ? FALSE : $_SERVER['HTTP_USER_AGENT'];

        return $this->user_agent;
    }

//    public function xss_clean($str, $is_image = FALSE) {
//        return $str;
//    }

    /**
     * Devuelve el metodo utilizado en la petición
     *
     * @return string
     */
    public function getServerMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Instacia unica
     *
     * @return Request
     */
    public static function getInstance() {
        if (!self::$instancia instanceof self) {
            self::$instancia = new self;
        }
        return self::$instancia;
    }

}

?>