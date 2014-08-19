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
 * Model Class
 * Representa una vista dentro del patron mvc. establece los recursos estaticos
 * a utlizarse al renderizar la vista para ser mostrada en el browser.
 *
 * @package		Microinei
 * @subpackage	core
 * @category	Core
 * @author		holivares
 */
abstract class Model {
    /**
     *
     * @var CI_DB_driver
     */
    protected $_db;
    private $_primary_keys = array();

    public function __construct() {
        require_once BASEPATH . 'libs' . DS . 'database' . DS . 'DB.php';
        $this->_db = new Database(array());
        //$this->_db = new Database();
    }

    /**
     * Debe retornar el nombre de la tabla.
     *
     * @return string
     */
    public abstract function tableName();

    /**
     * Debe retornar un array con los nombres de los campos y sus atributos en
     * comun, tales como tipo de dato, cantidad de caracteres, tipo de llave y
     * el tipo de control HTML que se utilizara por default en el Formulario.
     * <b>Atributos validos para los campos del modelo</b>
     * <ul>
     * <li>render => bool Si el campo se mostrara en formulario</li>
     * <li>type => string Establece el tipo de dato</li>
     * <li>unique => bool Establece que los campos son unique </li>
     * <li>primary_key => bool Establece que los campos son primary_key </li>
     * <li>dropdown => bool Establece que los campos se pintaran con un control <b>select</b> HTML </li>
     * <li>checkbox => bool Establece que los campos se pintaran con un control <b>checkbox</b> HTML </li>
     * <li>radio => bool Establece que los campos se pintaran con un control <b>radio</b> HTML</li>
     * <li>options => array Establece las opciones si se utiliza dropdown, checkbox o radio, caso contrario no tiene efecto</li>
     *<b>NOTA</b> Las llaves dropdown, checkbox y radio no se pueden utilizar a la vez.
     *
     * </ul>
     * <b>Valores validos para la llave 'type'</b>
     * INTEGER
     * TEXT
     * REAL
     * MATRIX
     *<b>Valores validos para la llave 'unique'</b>
     * bool TRUE o FALSE
     *<b>Valores validos para la llave 'primary_key'</b>
     * bool TRUE o FALSE
     *<b>Valores validos para la llave 'dropdown'</b>
     * bool TRUE o FALSE
     *<b>Valores validos para la llave 'checkbox'</b>
     * bool TRUE o FALSE
     *<b>Valores validos para la llave 'radio'</b>
     * bool TRUE o FALSE
     *<b>options</b>
     * array Un array con la lista de valores a utilizar en los campos. Si se utiliza con
     * checkbox => TRUE, entonces la cantidad de campos debe coincidir con la cantidad
     * de valores en <b>options</b>.
     *
     * @return array Retorna un array multidimensional con la informacion de los campos
     * del modelo.
     */
    public abstract function getFields();

    /**
     * Debe retornar un array con los nombres de los campos y sus respectivas
     * reglas de validacion las cuales tambien puede ser utilizadas por
     * el formulario.
     *
     * <b>Reglas validas</b>
     *
     * Todas las reglas incluidas en la libreria jquery.validator
     *
     * <ul>
     * <li>required => string Establece el tipo de dato</li>
     *
     * </ul>
     * @return array Array multidimensional
     */
    public abstract function getRules();
    /**
     * Devuelve una nueva instancia de Formulario segun los parametros
     * proporcionados.
     * <b>Parametros validos para $params</b>
     * <ul>
     * <li>model => string Nombre del modelo</li>
     * <li>prefix => string Nombre del formulario HTML Opcional Default frm_modelo</li>
     * <li>attrs => array asociativo con los atributos para la etiqueta html del form Opcional Default array</li>
     * <li>isAjaxSumitHandler => bool Si el formulario se enviara por ajax via una funcion javascript Opcional Default False</li>
     * <li>isAjaxErrorHandler => bool Si los errores del formulario se controlara desde javascript Opcional Default False</li>
     * <li>data => array Array asociativo con los valores para cada campo del formulario Opcional Default array</li>
     * </ul>
     *
     * @param array $params Array asociativo de parametros
     * @param bool $matrix
     * @return \Form
     */
    public static function createForm($params, $matrix = FALSE) {
        require_once BASEPATH . 'core' . DS . 'FormBase.php';
        if ($matrix) {
            $form = new FormMatrix($params);
        } else {
            $form = new Form($params);
        }
        return $form;
    }

    /**
     * Permite cambiar a otra cadena de conexion desde el modelo.
     *
     * @param array $con
     */
    public function setConnection($con) {
        if ($this->_db) {
            $this->closeConnection();
        }
        $this->_db = new Database($con);
    }

    /**
     * Devuelve todas las filas de un query. Adicionalmente se puede devolver por
     * paginas.
     *
     * @param string $select Nombre o nombres de campos separados por coma
     * @param int $start Fila desde donde se desean obtener los datos. <b>0 based</b>
     * @param int $count Cantidad de registros a leer desde $start
     * @param string $type object, array o custom
     * @return mixed Devuelve el tipo especificado en el parametro type, Esto puede
     * ser array u object
     */
    public function all($select = '*', $start = NULL, $count = NULL, $type = 'object') {
        if (is_numeric($start) & is_numeric($count)) {
            $this->_db->limit($start, $count);
        }
        $this->_db->select($select);
        $q = $this->_db->get($this->tableName());
        $result = $q->result($type);
        return $result;
    }
    /**
     * Devuelve todas las filas de un query filtrado.
     * Adicionalmente se puede devolver por paginas.
     *
     * @param string $select Nombre o nombres de campos separados por coma
     * @param array $where Un array asociativo que devuelve las filas a crear
     * @param int $start Fila desde donde se desean obtener los datos. <b>0 based</b>
     * @param int $count Cantidad de registros a leer desde $start
     * @param string $type object, array o custom
     * @return mixed Devuelve el tipo especificado en el parametro type, Esto puede
     * ser array u object
     */
    public function allBy($select, $where, $start = NULL, $count = NULL, $type = 'object') {
        if (is_numeric($start) & is_numeric($count)) {
            $this->_db->limit($start, $count);
        }
        $this->_db->select($select);
        if($where){
            $this->_db->where($where);
        }
        $q = $this->_db->get($this->tableName());
        $result = $q->result($type);
        return $result;
    }

    /**
     * Devuelve la primera fila de un query filtrado.
     *
     * @param array $where Un array asociativo que devuelve las filas a crear
     * @param string $type object, array or custom
     * @return mixed Devuelve el tipo especificado en el parametro type, Esto puede
     * ser array u object
     */
    public function get($where, $select = '*', $type = 'object') {
        $this->_db->select($select);
        $this->_db->where($where);
        $q = $this->_db->get($this->tableName());
        $result = $q->row(0, $type);
        return $result;
    }

    /**
     * Devuelve los nombres de los campos de la tabla utilizada por el modelo.
     * @return array
     */
    public function get_db_fields() {
        $q = $this->_db->list_fields($this->tableName());
        return $q;
    }

//    public function columns() {
//        $q = $this->_db->columns($this->tableName());
//        return $q;
//    }
    /**
     * Devuelve los valores para los campos llave primaria obtenidos desde
     * $data.
     *
     * @param array $data
     * @param array $pks
     * @return array
     */
    private function getWherePK($data, $pks) {
        $wherePK = array();
        foreach ($pks as $pk) {
            if (array_key_exists($pk, $data)) {
                $wherePK[$pk] = $data[$pk];
            }
        }
        return $wherePK;
    }
    /**
     * Ejecuta una sentencia insert o update a la tabla especificada en el modelo
     *
     * @param array $data Array asociativo que contiene los datos a utilizarse en la sentencia
     * @param bool $existsDB True si se desea ejecutar una validacion exists a nivel de base de datos
     */
    public function save($data, $existsDB = FALSE) {
        $rpta = TRUE;
        if ($this->is_multidim_array($data)) {
            foreach ($data as $value) {
                $rpta = $rpta & $this->_save($value, $existsDB);
            }
        } else {
            $rpta = $this->_save($data, $existsDB);
        }
        return $rpta;
    }
    /**
     * Ejecuta una sentencia insert o update a la tabla especificada en el modelo
     *
     * @param array $data
     * @param bool $existsDB
     */
    private function _save($data, $existsDB = FALSE) {
        $pks = $this->primary();
        $where = $this->getWherePK($data, $pks);
        $update = FALSE;
        if($where){
            $update = $existsDB === TRUE ? $this->exists($where) : count($where) > 0;
        }
        if ($update === TRUE) {
            return $this->edit($data, $where)>0;
        } else {
            return $this->create($data)>0;
        }
    }
    /**
     * Ejecuta una sentencia insert. Devuelve 0 si no se ha grabado correctamente.
     *
     * @param array $data
     * @return int
     */
    public function create($data) {
        $this->_db->insert($this->tableName(), $data);
        return $this->_db->affected_rows();
    }

    /**
     * Ejecuta una sentencia update. Devuelve 0 si no se ha actualizado correctamente.
     *
     * @param array $data
     * @param array $where
     * @return int
     */
    public function edit($data, $where = FALSE) {
        if (!$where) {
            $pks = $this->primary();
            $where = $this->getWherePK($data, $pks);
        }
        $this->_db->update($this->tableName(), $data, $where);
        return $this->_db->affected_rows();
    }

    /**
     * Ejecuta una sentencia delete. Devuelve 0 si no se ha grabado correctamente.
     *
     * @param array $where
     * @return type
     */
    public function delete($where = array()) {
        $this->_db->delete($this->tableName(), $where);
        return $this->_db->affected_rows();
    }

    /**
     * Devuelve TRUE si se ha grabado exitosamente, FALSE en caso contrario.
     *
     * @param array $where
     * @return bool
     */
    public function exists($where) {
        return $this->count($where) > 0;
    }
    /**
     * Devuelve la cantidad de registros de la tabla segun un filtro dado.
     *
     * @param array $where
     * @return int
     */
    public function count($where = FALSE) {
        if($where){
            $this->_db->where($where);
        }
        $count = $this->_db->count_all_results($this->tableName());
        return $count;
    }
    /**
     * Devuelve los nombres de las llaves primarias
     *
     * @return array
     */
    private function primary() {
        //intento 1
        if (count($this->_primary_keys) === 0) {
            $this->setPrimaryKeys();
        }
        //intento 2
        if (count($this->_primary_keys) === 0) {
            $this->_primary_keys = $this->_db->primary($this->tableName());
        }
        return $this->_primary_keys;
    }
    /**
     * Devuelve un valor
     *
     * @param array $inputs
     * @return bool
     */
    private function isPrimarykey($inputs) {
        $rpta = FALSE;
        if (array_key_exists('primary_key', $inputs)) {
            if (is_bool($inputs['primary_key'])) {
                $rpta = $inputs['primary_key'];
            }
        }
        return $rpta;
    }

    protected function setPrimaryKeys() {
        foreach ($this->getFields() as $fields_or_attr) {
            if ($this->isPrimarykey($fields_or_attr)) {
                $this->_primary_keys = array_merge($this->_primary_keys, $this->_getPrimaryKeys($fields_or_attr));
            }
        }
    }

    private function _getPrimaryKeys($inputs) {
        $_fields = array_filter(array_keys($inputs),create_function('$item', 'return is_numeric($item)===TRUE;'));
        $fields = array_intersect_key($inputs,array_flip($_fields));
        return $fields;
    }

    function is_multidim_array($arr) {
        if (!is_array($arr)) {
            return false;
        }
        foreach ($arr as $elm) {
            if (!is_array($elm)) {
                return false;
            }
        }
        return true;
    }

    public function closeConnection() {
        try {
            $this->_db->close();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        $this->_db = NULL;
    }

//
//    function __destruct() {
//        $this->_db->close();
//        $this->_db = null;
//    }
}

?>
