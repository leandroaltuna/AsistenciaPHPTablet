<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of directorModel
 *
 * @author cdelgadoc
 */
class aulaModel extends Model {

    public function getFields() {
        $devuelve = array(
            array('codigo', 'type' => 'INTEGER', 'primary_key' => TRUE, 'render' => TRUE),
            array('tipo','nro_local','naula', 'type' => 'INTEGER', 'render' => TRUE),
            
        );
        return $devuelve;
    }

    public function getRules() {
        $data=array(
                  array('naula','required'=>TRUE)
        );
        return $data;
    }

    public function tableName() {
        return 'aulas_local';
    }

    
    function get_aula($nroLocal, $type = 'object' ){
        
	$this->_db->where('nro_local', $nroLocal);    	
    	$this->_db->order_by('naula','asc');
    	 $q = $this->_db->get($this->tableName());	
         $result = $q->result($type);       
        return $result;
    }
    function get_selaula($aula,$nroLocal, $type = 'object' ){
        
	$this->_db->where('nro_local', $nroLocal); 
        $this->_db->where('naula', $aula);    	        
    	
    	 $q = $this->_db->get($this->tableName());	
         $result = $q->result($type);       
        return $result;
    }
    
    
    
    
    
    /*
    public function buscar_usuario($clave = NULL,  $type = 'object') {
       
        $this->_db->where_in('clave', $clave);
        $q = $this->_db->get($this->tableName());
        $result = $q->result($type);
        return $result;
    }
    
*/
}
