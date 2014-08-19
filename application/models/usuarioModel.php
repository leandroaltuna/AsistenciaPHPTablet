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
class usuarioModel extends Model {

    public function getFields() {
        $devuelve = array(
            array('idUsuario', 'type' => 'INTEGER', 'primary_key' => TRUE, 'render' => TRUE),
            array( 'rol','nro_local','naulas','ncontingencia', 'type' => 'INTEGER', 'render' => TRUE),
            array('clave', 'usuario', 'nombreLocal','sede', 'type' => 'TEXT', 'render' => TRUE),
        );
        return $devuelve;
    }

    public function getRules() {
        $data=array(
                  array('clave','required'=>TRUE)
        );
        return $data;
    }

    public function tableName() {
        return 'usuario_local';
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
