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
function __autoload($class){
	if (!file_exists($file = dirname(__FILE__).'/'. $class .'.php')) return;

	require_once($file);
}

?>