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
 * RestController Class
 * Controlador que implementa el modelo RESTful webservices
 *
 * @package		Microinei
 * @subpackage	core
 * @category	Core
 * @author		holivares
 */
abstract class RestController extends Controller {

    private $_data = NULL;
    private $_conditions = NULL;
    protected $_model = NULL;
    protected $_fields = '*';
    protected $_default_format = 'json';
    /**
     * @return string Debe retornar el nombre del modelo
     */
    protected abstract function getModel();

    public function index() {
    }

    public function __construct() {
        parent::__construct();
        $this->getHelper('string_helper');
        $this->getLibrary('Format');
        $this->_model = $this->loadModel($this->getModel());
        $this->_format = new Format;
    }

    /**
     * Obtiene los datos via <b>POST</b> y ejecuta un <b>INSERT INTO</b> a la base de datos
     * utilizando el modelo especificado.
     * Debe invocarse mediante una peticion <b>POST</b>.
     * El tipo de contenido (<b>CONTENT-TYPE</b>) aceptado y de respuesta es el
     * tipo de contenido especificado en la cabecera Content-type.
     * El tipo de contenido de respuesta (response) se puede modificar
     * acceciendo a la propiedad format del objeto _response del controlador.
     * Los tipos soportados son los mismos tipos soportados por la clase Format.
     * Por default se espera que los datos enviados sean en formato json.
     *
     * <pre>
     * <?php
     * //tipo de contenido de respuesta es application/xml
     * $this->_response->format = 'xml';
     * </pre>
     *
     * @access public
     */
    public function create() {
        $data = $this->parseRequestPost();
        $this->set_data($data);
        $rpta = $this->_model->create($this->get_data());
        $this->setFormatResponse();
        $this->response(array($rpta!==0), 200);
    }

    /**
     * Obtiene los datos via <b>PUT</b> y ejecuta un <b>UPDATE</b> a la base de datos
     * utilizando el modelo especificado.
     * Debe invocarse mediante una peticion <b>PUT</b>. El tipo de
     * contenido (<b>CONTENT-TYPE</b>) aceptado y de respuesta es el tipo de contenido
     * especificado en la cabecera Content-type.
     * El tipo de contenido de respuesta (response) se puede modificar
     * acceciendo a la propiedad format del objeto _response del controlador.
     * Los tipos soportados son los mismos tipos soportados por la clase Format.
     * Por default se espera que los datos enviados sean en formato json.
     *
     * <pre>
     * <?php
     * //tipo de contenido de respuesta es application/json
     * $this->_response->format = 'json';
     * </pre>
     *
     * @access public
     */
    public function edit() {
        $data = $this->parseRequestPut();
        $this->set_data($data);
        $rpta = $this->_model->edit($this->get_data());
        $this->setFormatResponse();
        $this->response(array($rpta!==0), 200);
    }

    /**
     * Obtiene los datos via <b>DELETE</b> y ejecuta un <b>DELETE</b> a la base de datos
     * utilizando el modelo especificado.
     * Debe invocarse mediante una peticion <b>DELETE</b>. El tipo de
     * contenido (<b>CONTENT-TYPE</b>) aceptado y de respuesta es el tipo de contenido
     * especificado en la cabecera Content-type.
     * El tipo de contenido de respuesta (response) se puede modificar
     * acceciendo a la propiedad format del objeto _response del controlador.
     * Los tipos soportados son los mismos tipos soportados por la clase Format.
     * Por default se espera que los datos enviados sean en formato json.
     *
     * <pre>
     * <?php
     * //tipo de contenido de respuesta es application/json
     * $this->_response->format = 'json';
     * </pre>
     *
     * @access public
     */
    public function remove() {
        $data = $this->parseRequestDelete();
        $this->set_conditions($data);
        $rpta = $this->_model->delete($this->get_conditions());//_data es un where
        $this->setFormatResponse();
        $this->response(array($rpta!==0), 200);
    }

    /**
     * Obtiene los datos via <b>GET</b> y ejecuta un <b>SELECT</b> a la base de datos
     * utilizando el modelo especificado.
     * Debe invocarse mediante una peticion <b>GET</b>. El tipo de
     * contenido (<b>CONTENT-TYPE</b>) aceptado y de respuesta es el tipo de contenido
     * especificado en la cabecera Content-type. Los parametros enviados por GET
     * se usaran como condicion para obtener la primera fila del conjunto de resultados.
     * El tipo de contenido de respuesta (response) se puede modificar
     * acceciendo a la propiedad format del objeto _response del controlador.
     * Los tipos soportados son los mismos tipos soportados por la clase Format.
     * Por default se espera que los datos enviados sean en formato json.
     *
     * <pre>
     * <?php
     * //tipo de contenido de respuesta es application/json
     * $this->_response->format = 'json';
     * </pre>
     *
     * @access public
     */
    public function find() {
        $data = $this->parseRequestGet();
        $this->set_conditions($data);
        $result = $this->_model->get($this->get_conditions(), $this->get_fields(), 'array');
        $this->setFormatResponse();
        $this->response($result, 200);
    }

    /**
     * Obtiene los datos via <b>GET</b> y ejecuta un <b>SELECT</b> a la base de datos
     * utilizando el modelo especificado.
     * Debe invocarse mediante una peticion <b>GET</b>. El tipo de
     * contenido (<b>CONTENT-TYPE</b>) aceptado y de respuesta es el tipo de contenido
     * especificado en la cabecera Content-type. Los parametros enviados por GET
     * se usaran como condicion para obtener las filas a devolver.
     * El tipo de contenido de respuesta (response) se puede modificar
     * acceciendo a la propiedad format del objeto _response del controlador.
     * Los tipos soportados son los mismos tipos soportados por la clase Format.
     * Por default se espera que los datos enviados sean en formato json.
     *
     * <pre>
     * <?php
     * //tipo de contenido de respuesta es application/json
     * $this->_response->format = 'json';
     * </pre>
     *
     * @access public
     */
    public function getAll() {
        $data = $this->parseRequestGet();
        if($data){
            $this->set_conditions($data);
            $result = $this->_model->allBy($this->get_fields(), $this->get_conditions(), NULL, NULL, 'array');
        }else{
            $result = $this->_model->all($this->get_fields(), NULL, NULL, 'array');
        }
        $this->setFormatResponse();
        $this->response($result, 200);
    }

    /**
     * Obtiene los datos via <b>GET</b> y ejecuta un <b>SELECT</b> a la base de datos
     * utilizando el modelo especificado.
     * Debe invocarse mediante una peticion <b>GET</b>. El tipo de
     * contenido (<b>CONTENT-TYPE</b>) aceptado y de respuesta es el tipo de contenido
     * especificado en la cabecera Content-type.  Los parametros enviados por GET
     * se usaran como condicion para obtener las filas a devolver.
     * El tipo de contenido de respuesta (response) se puede modificar
     * acceciendo a la propiedad format del objeto _response del controlador.
     * Los tipos soportados son los mismos tipos soportados por la clase Format.
     * Por default se espera que los datos enviados sean en formato json.
     *
     * <pre>
     * <?php
     * //tipo de contenido de respuesta es application/json
     * $this->_response->format = 'json';
     * </pre>
     *
     * @access public
     * @param int $start Numero de fila inicial
     * @param int $count Cantidad de registros a leer
     */
    public function findRange($start=NULL, $count=NULL) {
        if(!isset($start) | !isset($count) & $count <=0){
            $this->response('Especifique los rangos', 500);
        }else{
            $data = $this->parseRequestGet();
            if($data){
                $this->set_conditions($data);
                $result = $this->_model->allBy($this->get_fields(), $this->get_conditions(),$count, $start-1, 'array');
            }else{
                $result = $this->_model->all($this->get_fields(), $count, $start-1, 'array');
            }
            $this->setFormatResponse();
            $this->response($result, 200);
        }
    }
    /**
     * Obtiene la cantidad de filas que se han leido al invocar a getAll, findRange
     * o find.
     * @return int
     */
    public function count() {
        $count = $this->_model->count($this->get_conditions());
        return $count;
    }
    /**
     * Devuelve un array asociativo que se utilizara para armar la condicion
     * WHERE. Las keys del array deben ser los nombres de los campos a utilizar en la condicion
     * WHERE y los values de array los valores para cada campo a utilizar.
     * @return array
     */
    protected function get_conditions() {
        return $this->_conditions;
    }
    /**
     * Obtiene el nombre de todas los campos del modelo a devolver por cada registro
     * leido por getAll, findRange o find. '*' (todos los campos) por default.
     * @return string
     */
    protected function get_fields() {
        return $this->_fields;
    }
    /**
     * Establece las condiciones para el WHERE
     * @param array $_conditions debe ser un array asociativo
     */
    protected function set_conditions($_conditions) {
        $this->_conditions = $_conditions;
    }
    /**
     * Establece el nombre de los campos
     * @param string $_fields Si es mas de un campo debe separase por comas
     */
    protected function set_fields($_fields) {
        $this->_fields = $_fields;
    }
    /**
     * Devuelve un array asociativo con los datos a ser utilizados por
     * create, edit y remove.
     * @return array
     */
    protected function get_data() {
        return $this->_data;
    }
    /**
     * Establece los datos necesarios para create, edit, remove.
     * @param array $_data Debe ser un array asociativo
     */
    protected function set_data($_data) {
        $this->_data = $_data;
    }
    /**
     * Asigna el formato de respuesta por defecto en caso no se haya especificado.
     */
    private function setFormatResponse(){
        if($this->_response->format === null){
            $this->_response->format = $this->_default_format;
        }
    }


}
