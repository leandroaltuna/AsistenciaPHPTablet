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

define('BASE_URL', 'http://localhost/evaluacion/');
define('DEFAULT_CONTROLLER', 'index');
define('DEFAULT_LAYOUT', 'default');

define('DB_HOST', '');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_NAME', BASEPATH . 'db'. DIRECTORY_SEPARATOR . 'mydatabase.sqlite');
define('DB_ENGINE','sqlite3');
define('DB_CHAR', 'utf8');
//define('TRANSFER_HOST', 'http://webinei.inei.gob.pe/beca18/WSDirectores/WSDirectores.php');
//define('TRANSFER_HOST', 'http://190.223.32.196/beca18/WSDirectores/WSDirectores.php'); externo
//define('TRANSFER_HOST', 'http://192.168.201.114:8080/beca18/WSDirectores/WSDirectores.php');
define('TRANSFER_HOST', 'http://190.223.32.196/beca18/WSDirectores/WSDirectores_1.php');

define('TRANSFER_HOST_MASIVO', 'http://190.223.32.196/beca18/WSDirectores/WSDirectoresMasivo_1.php');

?>
