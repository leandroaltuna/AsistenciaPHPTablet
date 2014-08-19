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
abstract class FormBase {

    protected $_modelClass;
    protected $_model;
    protected $_data = array();
    protected $_fields = array();
    protected $_valueFields = array();
    public $attrs = NULL;
    public $_action = '';
    protected $_prefix = '';
    protected $_ajaxSubmitHandler = 'save';
    protected $_ajaxErrorHandler = 'error';
    protected $_isAjaxSubmitHandler = FALSE;
    protected $_isAjaxErrorHandler = FALSE;
    protected $_scenario = FALSE;

    public function get_scenario() {
        return $this->_scenario;
    }

    public function set_scenario($_scenario) {
        $this->_scenario = $_scenario;
    }

    public function isAjaxErrorHandler() {
        return $this->_isAjaxErrorHandler;
    }

    public function set_isAjaxErrorHandler($_isAjaxErrorHandler) {
        $this->_isAjaxErrorHandler = $_isAjaxErrorHandler;
    }

    public function isAjaxSubmitHandler() {
        return $this->_isAjaxSubmitHandler;
    }

    public function set_isAjaxSubmitHandler($_isAjaxSubmitHandler) {
        $this->_isAjaxSubmitHandler = $_isAjaxSubmitHandler;
    }

    public function get_ajaxSubmitHandler() {
        return $this->_ajaxSubmitHandler;
    }

    public function get_ajaxErrorHandler() {
        return $this->_ajaxErrorHandler;
    }

    /**
     *
     * @param string $_ajaxSumitHandler
     */
    public function set_ajaxSumitHandler($_ajaxSumitHandler) {
        $this->_ajaxSubmitHandler = $_ajaxSumitHandler;
    }

    /**
     *
     * @param string $_ajaxErrorHandler
     */
    public function set_ajaxErrorHandler($_ajaxErrorHandler) {
        $this->_ajaxErrorHandler = $_ajaxErrorHandler;
    }

    public function __construct($params) {
        if (is_array($params)) {
            if (!array_key_exists('model', $params)) {
                throw new Exception('Debe especificar el modelo');
            }
            $this->_modelClass = $params['model'];
            $this->_prefix = array_key_exists('prefix', $params) ? $params['prefix'] : 'frm' . $params['model'];
            $this->default_attrs();
            if (array_key_exists('attrs', $params)) {
                $this->set_attrs($params);
            }
            if (array_key_exists('isAjaxSumitHandler', $params)) {
                $this->_isAjaxSubmitHandler = $params['isAjaxSumitHandler'];
            }
            if (array_key_exists('scenario', $params)) {
                $this->_scenario = $params['scenario'];
            }
            if (array_key_exists('isAjaxErrorHandler', $params)) {
                $this->_isAjaxErrorHandler = $params['isAjaxErrorHandler'];
            }
            if (array_key_exists('data', $params)) {
                $data = $params['data'];
                $this->_data = array_key_exists($this->_prefix, $data) ? $data[$this->_prefix] : $data;
            }
        }
        $this->loadModel();
        $this->populateFields();
    }

    public function getData() {
        return $this->_data;
    }

    public function setData($data) {
       if(is_object($data)){
            $data = (array) $data;
        }
        if(!is_array($data)){
            return;
        }
        $this->_data = array_key_exists($this->_prefix, $data) ? $data[$this->_prefix] : $data;
    }

//    protected function updateValues(){
//        fore
//    }
    /**
     *
     * @param type $attrs
     */
    public function set_attrs($attrs) {
        foreach ($attrs as $key => $value) {
            if ($key === 'action') {
                $this->_action = $value;
            } else {
                $this->attrs->$key = $value;
            }
        }
    }

    public function fieldExists($field){
        return array_key_exists($field, $this->_fields);
    }

    public function setFieldAttrs($field, $attrs) {
        if($this->fieldExists($field)){
            foreach ($attrs as $key => $value) {
                $f = $this->_fields[$field];
                $f->$key = $value;
            }
        }
    }

    public function setFieldsAttrs($attrs) {
        foreach ($attrs as $field => $values) {
            $this->setFieldAttrs($field, $values);
        }
    }
    /**
     * Devuelve la etiqueta de apertura HTML para el fomulario
     *
     * @return string
     */
    public function open_tag() {
        return form_open($this->_action, $this->attrs);
    }

    public function close_tag() {
        return '</form>';
    }

    public function get_action() {
        return $this->_action;
    }

    public function set_action($_action) {
        $this->_action = BASE_URL . 'index.php/' . $_action;
    }

    protected function default_attrs() {
        $this->attrs = new stdClass;
        $this->attrs->name = $this->_prefix;
        $this->attrs->id = $this->_prefix . '_id';
        $this->attrs->method = 'POST';
        $this->_action = BASE_URL;
    }

    /**
     *
     * @return Crea una instancia del modelo asociado al formulario
     * @throws Exception
     */
    private function loadModel() {
        $path = '';
        $modelo = $this->_modelClass;
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
            $this->_model = $modelo;
        } else {
            throw new Exception('Error de modelo');
        }
    }

    public abstract function asHtml();

    public abstract function save();

    public function __get($name) {
        if (array_key_exists($name, $this->_fields)) {
            return $this->_fields[$name];
        }
        return NULL;
    }

    public function __set($name, $value) {
        //if(in_array($name, $this->_model->get_db_fields())){
        $this->_fields[$name] = $value;
        /* }else{
          throw new Exception('El campo es invalido');
          } */
    }

    protected abstract function getNameField($input);

    protected abstract function getIdField($input);

    protected function getHtmlNumberField($inputs, $length) {
        /* aqui buscar todos los campos para setearlos individualmente */
        if ($this->debeRenderizar($inputs)) {
            $data = array('data-length' => $length);
            foreach ($inputs as $key => $input) {
                if (is_numeric($key)) {
                    $data['name'] = $this->getNameField($input);
                    $data['id'] = $this->getIdField($input);
                    $data['class'] = 'form-control';
                    $data['value'] = array_key_exists($input, $this->_data) ? $this->_data[$input] : '';
                    $data['type'] = 'number';
                    $this->$input = (object) $data;
                }
            }
        }
    }

    protected function getHtmlTextField($inputs, $length) {
        if ($this->debeRenderizar($inputs)) {
            $data = array('maxlength' => $length);
            foreach ($inputs as $key => $input) {
                if (is_numeric($key)) {
                    $data['name'] = $this->getNameField($input);
                    $data['id'] = $this->getIdField($input);
                    $data['class'] = 'form-control';
                    $data['value'] = array_key_exists($input, $this->_data) ? $this->_data[$input] : '';
                    $data['type'] = 'text';
                    $this->$input = (object) $data;
                }
            }
        }
    }

    protected function getHtmlHiddenField($inputs) {
        if ($this->debeRenderizar($inputs)) {
            $data = array();
            foreach ($inputs as $key => $input) {
                if (is_numeric($key)) {
                    $data['name'] = $this->getNameField($input);
                    $data['id'] = $this->getIdField($input);
                    $data['value'] = array_key_exists($input, $this->_data) ? $this->_data[$input] : '';
                    $this->$input = (object) $data;
                }
            }
        }
    }

    protected function getDropdownField($inputs, $options, $type) {
        if ($this->debeRenderizar($inputs)) {
            $data = array();
            foreach ($inputs as $key => $input) {
                if (is_numeric($key)) {
                    $data['name'] = $this->getNameField($input);
                    $data['id'] = $this->getIdField($input);
                    $data['class'] = 'etitle form-control';
                    $data['value'] = array_key_exists($input, $this->_data) ? $this->_data[$input] : '';
                    $data['type'] = $type;
                    $data['options'] = $options;
                    $this->$input = (object) $data;
                }
            }
        }
    }

    protected function isMultiRow() {
        $count1 = count($this->_data);
        $count2 = count($this->_data, COUNT_RECURSIVE);
        return $count2 > $count1;
    }

    protected function getCheckboxField($inputs, $options) {
        if ($this->debeRenderizar($inputs)) {
            $data = array();
            $_value = NULL;
            $keys = array_keys($options);
            $values = array_values($options);
            foreach ($inputs as $key => $input) {
                if (is_numeric($key)) {
                    $data['name'] = $this->getNameField($input);
                    $data['id'] = $this->getIdField($input);
                    $data['class'] = 'checkbox-inline boton25';
                    $_value = array_key_exists($input, $this->_data) ? $this->_data[$input] : '';
                    $data['value'] = count($keys)>1 ? $keys[$key]:  current($keys);
                    $data['label'] = count($keys)>1 ? $values[$key]: current($values);
                    $data['type'] = 'checkbox';
                    $this->$input = (object) $data;
                    $this->_valueFields[$input] = NULL;
                }
            }
        }
    }

    protected function getRadioField($inputs, $options) {
        if ($this->debeRenderizar($inputs)) {
            $data = array();
            foreach ($inputs as $key => $input) {
                if (is_numeric($key)) {
                    $data['name'] = $this->getNameField($input);
                    $data['id'] = $this->getIdField($input);
                    $data['class'] = 'radio-inline boton25';
                    $_value = array_key_exists($input, $this->_data) ? $this->_data[$input] : '';
                    $data['value'] = $_value;
                    $data['options'] = $options;
                    //$data['checked'] = $value===$_value;
                    $data['type'] = 'radio';
                    $this->$input = (object) $data;
                }
            }
        }
    }

    /*     * ***************************************************** */

    protected function isCheckbox($inputs) {
        $rpta = FALSE;
        if (array_key_exists('checkbox', $inputs)) {
            if (is_bool($inputs['checkbox'])) {
                $rpta = $inputs['checkbox'];
            }
        }
        return $rpta;
    }

    protected function isPrimarykey($inputs) {
        $rpta = FALSE;
        if (array_key_exists('primary_key', $inputs)) {
            if (is_bool($inputs['primary_key'])) {
                $rpta = $inputs['primary_key'];
            }
        }
        return $rpta;
    }

    protected function isRadio($inputs) {
        $rpta = FALSE;
        if (array_key_exists('radio', $inputs)) {
            if (is_bool($inputs['radio'])) {
                $rpta = $inputs['radio'];
            }
        }
        return $rpta;
    }

    protected function isDropdown($inputs) {
        $rpta = FALSE;
        if (array_key_exists('dropdown', $inputs)) {
            if (is_bool($inputs['dropdown'])) {
                $rpta = $inputs['dropdown'];
            }
        }
        return $rpta;
    }

    protected function getScenarioData($inputs) {
        $rpta = FALSE;
        if (array_key_exists('scenario', $inputs)) {
            $rpta = $inputs['scenario'];
        }
        return $rpta;
    }

    protected function getOptionsData($inputs) {
        $options = array();
        if (array_key_exists('options', $inputs)) {
            if (is_array($inputs['options'])) {
                $options = $inputs['options'];
            }
        }
        return $options;
    }

    /*     * ******************************************************* */

    protected function getTextField($inputs, $length) {
        if ($this->isPrimarykey($inputs)) {
            $this->getHtmlHiddenField($inputs);
        } else if ($this->isDropdown($inputs)) {
            $options = $this->getOptionsData($inputs);
            $this->getDropdownField($inputs, $options, 'text');
        } else if ($this->isCheckbox($inputs)) {
            $options = $this->getOptionsData($inputs);
            $this->getCheckboxField($inputs, $options);
        } else if ($this->isRadio($inputs)) {
            $options = $this->getOptionsData($inputs);
            $this->getRadioField($inputs, $options);
        } else {
            $this->getHtmlTextField($inputs, $length);
        }
    }

    protected function getNumberField($inputs, $length) {
        if ($this->isPrimarykey($inputs)) {
            $this->getHtmlHiddenField($inputs);
        } else if ($this->isDropdown($inputs)) {
            $options = $inputs['options'];
            $this->getDropdownField($inputs, $options, 'number');
        } else if ($this->isCheckbox($inputs)) {
            $options = $this->getOptionsData($inputs);
            $this->getCheckboxField($inputs, $options);
        } else if ($this->isRadio($inputs)) {
            $options = $this->getOptionsData($inputs);
            $this->getRadioField($inputs, $options);
        } else {
            $this->getHtmlNumberField($inputs, $length);
        }
    }

    protected function debeRenderizar($fields) {
        if (array_key_exists('render', $fields)) {
            return $fields['render'];
        }
        return FALSE;
    }

    protected abstract function populateFields();

    protected abstract function setValue($field, $value);

    protected function renderControl($type, $field, $label = FALSE) {
        $control = '';
        switch ($type) {
            case 'INPUT': {
                    $control = form_input($this->$field);
                }break;
            case 'RADIO': {
                    $control = form_radio_multiple($this->$field);
                    $label = FALSE;
                }break;
            case 'CHECKBOX': {
                    $control = form_checkbox($this->$field);
                }break;
            case 'HIDDEN': {
                    $control = form_hidden($this->$field->name, $this->$field->value, $this->$field->id);
                    $label = FALSE;
                }break;
            case 'DROPDOWN': {
                    $values = !is_array($this->$field->value) ? array($this->$field->value) : $this->$field->value;
                    $control = form_dropdown($this->$field->name, $this->$field->options, $values);
                }break;
            case 'READONLY': {
                    $control = $this->$field->value;
                }break;
            default : {
                    $control = $this->$field->value;
                }
        }
        if ($label === TRUE) {
            return form_label($control, '', array('id' => 'la_' . $this->$field->id));
        }
        return $control;
    }

    protected function populateJsonValidate() {
        $_rules = $this->_model->getRules();
        $rules = array();
        foreach ($_rules as $rule) {
            $_fields = array_filter(array_keys($rule), create_function('$item', 'return is_numeric($item)===TRUE;'));
            $_keys = array_filter(array_keys($rule), create_function('$item', 'return is_numeric($item)===FALSE;'));
            $attrs = array_intersect_key($rule, array_flip($_keys));
            $fields = array_intersect_key($rule, array_flip($_fields));
            foreach ($fields as $field) {
                $rules[$this->getNameField($field)] = $attrs;
            }
        }
        return $rules;
    }

    public function getJsonValidate() {
        $rules = $this->populateJsonValidate();
        $json = array('rules' => $rules);
        if ($this->isAjaxSubmitHandler() === TRUE) {
            $json['submitHandler'] = $this->get_ajaxSubmitHandler();
        }
        if ($this->isAjaxErrorHandler() === TRUE) {
            $json['invalidHandler'] = $this->get_ajaxErrorHandler();
        }
        return json_encode($json);
    }

    protected function getDefaultValuesByScenario(){
        if(!$this->get_scenario()){
            return FALSE;
        }
        $fields = array_keys($this->_fields);
        return array_combine($fields, array_fill(0, count($fields), NULL));
    }

    protected function getFieldsByScenario($scenario, $fields_or_attr){
        if(!$scenario){
            return $fields_or_attr;
        }
        $scenarioData = $this->getScenarioData($fields_or_attr);
        if(is_array($scenarioData)){
            if(in_array($scenario, $scenarioData)){
                return $fields_or_attr;
            }
        }else if($scenario === $scenarioData){
            return $fields_or_attr;
        }
        return FALSE;
    }

//    protected function getFieldsName($inputs){
//        $fields = array();
//        foreach ($inputs as $key => $input) {
//            if (is_numeric($key)) {
//                $fields[] = $input;
//            }
//        }
//        return $fields;
//    }
//
//    protected function getFieldsNameByScenario($scenario, $fields_or_attr){
//        if(!$scenario){
//            return FALSE;
//        }
//        $scenarioData = $this->getScenarioData($fields_or_attr);
//        if(is_array($scenarioData)){
//            if(in_array($scenario, $scenarioData)){
//                return $this->getFieldsName($fields_or_attr);
//            }
//        }else if($scenario === $scenarioData){
//            return $this->getFieldsName($fields_or_attr);
//        }
//        return FALSE;
//    }

    public function isValid() {
        return TRUE;
    }

    /**
     * necesita renderizarse
     * necesita dibujar los campos
     * necesita validar los campos
     * necesita guardar los campos
     * necesita actualizar los campos
     * necesita pintar los valores
     */
}

class Form extends FormBase {

    protected function getNameField($input) {
        $_name = sprintf('%s[%s]', $this->_prefix, $input);
        return $_name;
    }

    protected function getIdField($input) {
        $_id = sprintf('%s_%s', $this->_prefix, $input);
        return $_id;
    }

    protected function getHtmlMatrixField($inputs) {
        if ($this->debeRenderizar($inputs)) {
            foreach ($inputs as $key => $input) {
                if (is_numeric($key)) {
                    $data = array_key_exists($input, $this->_data) ? $this->_data[$input] : array();
                    $form = Model::createForm(array('model' => $input, 'matrix' => $input, 'data' => $data), TRUE);
                    $this->$input = $form;
                }
            }
        }
    }

    public function asHtml() {

    }

    public function __get($name) {
        if (array_key_exists($name, $this->_fields)) {
            $this->setValue($name, '');
            return $this->_fields[$name];
        }
        return NULL;
    }

    protected function populateFields() {
        $length = NULL;
        $type = NULL;
        foreach ($this->_model->getFields() as $fields_or_attr) {
            $fields_or_attr = $this->getFieldsByScenario($this->get_scenario(), $fields_or_attr);
            if(!$fields_or_attr){
                continue;
            }
            if (array_key_exists('type', $fields_or_attr)) {
                $type = $fields_or_attr['type'];
            } else {
                throw new Exception('Debe especificar el tipo del campo');
            }
            if (array_key_exists('length', $fields_or_attr)) {
                $length = $fields_or_attr['length'];
            }
            if ($type === 'INTEGER' || $type === 'REAL') {
                $this->getNumberField($fields_or_attr, $length);
            } else if ($type === 'TEXT') {
                $this->getTextField($fields_or_attr, $length);
            } else if ($type === 'MATRIX') {
                $this->getHtmlMatrixField($fields_or_attr);
            }
        }
    }

    public function save() {
        $defaults = $this->getDefaultValuesByScenario();
        if($defaults){
            $this->_data = array_merge($defaults, $this->_data);
        }
        return $this->_model->save($this->_data, TRUE);
    }

    protected function setValue($field, $value) {
        $type = '';
        if (property_exists($this->_fields[$field], 'type')) {
            $type = $this->_fields[$field]->type;
        }
        $value = array_key_exists($field, $this->_data) ? $this->_data[$field] : '';
        if (!in_array($type, array('checkbox',))) {
            $this->_fields[$field]->value = $value;
        }else{
            if($this->_fields[$field]->value===$value){
                $this->_fields[$field]->checked = 'checked';
            }
        }
    }

}

class FormMatrix extends FormBase implements Iterator {

    private $_matrix = NULL;
    private $_pos = NULL;
    private $_instances = 0;

    public function __construct($params) {
        $this->_matrix = array_key_exists('matrix', $params) ? $params['matrix'] : 'array';
        $this->_pos = array_key_exists('pos', $params) ? $params['pos'] : '__form__';
        $this->_instances = array_key_exists('instances', $params) ? $params['instances'] : 0;
        parent::__construct($params);
        if (array_key_exists($this->_matrix, $this->_data)) {
            $this->_data = $this->_data[$this->_matrix];
        }
        $this->setInitialValues();
    }

    protected function setInitialValues() {
        if (count($this->_data) > 0) {
            return;
        }
        if ($this->_instances > 0) {
            $this->_data = range(0, $this->_instances-1);
        }
    }

    protected function getNameField($input) {
        $_name = sprintf('%s[%s][%s][%s]', $this->_prefix, $this->_matrix, $this->_pos, $input);
        return $_name;
    }

    public function setData($data) {
        parent::setData($data);
        $this->_data = array_key_exists($this->_matrix, $this->_data) ? $this->_data[$this->_matrix] : $this->_data;
    }

    protected function getIdField($input) {
        $_id = sprintf('%s_%s_%s_%s', $this->_prefix, $this->_matrix, $this->_pos, $input);
        return $_id;
    }

    protected function populateFields() {
        $length = NULL;
        $type = NULL;
        foreach ($this->_model->getFields() as $fields_or_attr) {
            $fields_or_attr = $this->getFieldsByScenario($this->get_scenario(), $fields_or_attr);
            if(!$fields_or_attr){
                continue;
            }
            if (array_key_exists('type', $fields_or_attr)) {
                $type = $fields_or_attr['type'];
            } else {
                throw new Exception('Debe especificar el tipo del campo');
            }
            if (array_key_exists('length', $fields_or_attr)) {
                $length = $fields_or_attr['length'];
            }
            if ($type === 'INTEGER' || $type === 'REAL') {
                $this->getNumberField($fields_or_attr, $length);
            } else if ($type === 'TEXT') {
                $this->getTextField($fields_or_attr, $length);
            }
        }
    }

    public function asHtml() {

    }

    private function objectToArrayValues($object){
        if(is_object($object)){
            return (array) $object;
        }
        return $object;
    }

    protected function setDefaultValues($key) {
        foreach ($this->_fields as $namefield => $field) {
            //$this->setValue($namefield, $field->value);
            $field->name = str_replace($this->_pos, $key, $this->getNameField($namefield));
            $field->id = str_replace($this->_pos, $key, $this->getIdField($namefield));
        }
    }

    protected function setCurrentValues($key, $values) {
        foreach ($values as $field => $value) {
            if ($this->$field) {
                $this->setValue($field, $value);
                $this->$field->name = str_replace($this->_pos, $key, $this->getNameField($field));
                $this->$field->id = str_replace($this->_pos, $key, $this->getIdField($field));
            }
        }
    }

    public function current() {
        $_values = current($this->_data);
        $key = $this->key();
        $values = $this->objectToArrayValues($_values);
        if (is_array($values)) {
            $this->setCurrentValues($key, $values);
        }else{
            $this->setDefaultValues($key);
        }
        return $this;
    }

    public function key() {
        $key = key($this->_data);
        return $key;
    }

    protected function setValue($field, $value) {
        if (property_exists($this->$field, 'type')) {
            if (in_array($this->$field->type, array('checkbox'))) {
//                echo 'radio';
//                var_dump($value.'---'.$field.'d'.$this->$field->value);
                if ($value === $this->$field->value) {
                    $this->$field->checked = TRUE;
                } else {
                    unset($this->$field->checked);
                }
//                var_dump($this->$field);
            } else {
                $this->$field->value = $value;
            }
        } else {
            $this->$field->value = $value;
        }
    }

    public function next() {
        $_values = next($this->_data);
        $key = $this->key();
        $values = $this->objectToArrayValues($_values);
        if (is_array($values)) {
            $this->setCurrentValues($key, $values);
        }else{
            $this->setDefaultValues($key);
        }
        return $this;
    }

    public function rewind() {
        reset($this->_data);
    }

    public function valid() {
        $key = key($this->_data);
        $var = ($key !== NULL && $key !== FALSE && is_numeric($key) === TRUE);
        return $var;
    }

    public function getIterator() {
        return $this;
    }

    public function add($value) {
        $this->items[$this->count++] = $value;
    }

    private function parseAttr($array) {
        $attr = '';
        if ($array !== NULL) {
            foreach ($array as $key => $val) {
                $attr .= $key . '="' . $val . '" ';
            }
        }
        return $attr;
    }

    /**
     * Tipo de columna debe ser input, select, radio, checkbox, readonly
     * @param type $tr
     * @param type $td
     * @return string
     */

    private function getPrototypeType(&$attrs){
        $type = FALSE;
        if (array_key_exists('type', $attrs)) {
            $type = $attrs['type'];
            unset($attrs['type']);
        }
        return $type;
    }

    private function getPrototypeJoin(&$attrs){
        $join = FALSE;
        if (array_key_exists('joinTo', $attrs)) {
            $join = $attrs['joinTo'];
            unset($attrs['joinTo']);
        }
        return $join;
    }

    public function prototypeHtmlTr($tr = array(), $td = array()) {
        $html = "<tr " . $this->parseAttr($tr) . ">";
        foreach ($td as $field => $attrs) {
            $type = $this->getPrototypeType($attrs);
            $joinField = $this->joinFields($type, $attrs);
            $this->setFieldAttrs($field, $attrs);
            if($type){
                $control = $this->renderControl($type, $field, TRUE) . $joinField;
                $html .= "<td>" . $control . "</td>";
            }
        }
        $html .= '</tr>';
        return $html;
    }

    public function joinFields($type, &$attrs) {
        $joinField = $this->getPrototypeJoin($attrs);
        $control = '';
        if (is_array($joinField)) {
            foreach ($joinField as $_field) {
                $this->setFieldAttrs($_field, $attrs);
                $control .= $this->renderControl($type, $_field, TRUE);
            }
        } else if($joinField){
            $this->setFieldAttrs($joinField, $attrs);
            $this->_fields[$joinField]->class;
            $control .= $this->renderControl($type, $joinField, TRUE);
        }
        return $control;
    }

    public function save($fillFields = FALSE) {
        if ($fillFields === TRUE) {
            $fields = $this->_valueFields;
            array_walk($this->_data, function (&$item) use ($fields) {
                $item = array_merge($fields, $item);
            });
        }
        return $this->_model->save($this->_data, TRUE);
    }

}
