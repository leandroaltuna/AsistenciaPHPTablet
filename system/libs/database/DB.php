<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Microinei
 *
 * Mini framework para encuestas
 *
 * @package		Microinei
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com/
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * Initialize the database
 * Adaptacion que elimina las referencias al nucleo de CI
 * @category	Database
 * @package		Microinei
 * @subpackage	database
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/pagination.html
 */
require_once(BASEPATH . 'libs' . DS . 'database' . DS . 'DB_driver.php');
require_once(BASEPATH . 'libs' . DS . 'database' . DS . 'DB_active_rec.php');
if (!class_exists('CI_DB')) {
    eval('class CI_DB extends CI_DB_active_record { }');
}
require_once(BASEPATH . 'libs' . DS . 'database' . DS . 'drivers' . DS . DB_ENGINE . DS . DB_ENGINE . '_driver.php');
// Instantiate the DB adapter
$driver = 'CI_DB_' . DB_ENGINE . '_driver';
$DB = new $driver(array());
if ($DB->autoinit == TRUE) {
    $DB->initialize();
}

/* End of file DB.php */
/* Location: ./system/database/DB.php */