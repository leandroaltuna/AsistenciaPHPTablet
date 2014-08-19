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
class directorModel extends Model {

    public function getFields() {
        $devuelve = array(
            array('codigo', 'type' => 'INTEGER', 'primary_key' => TRUE, 'render' => TRUE),
            array( 'estatus','ins_numdoc','s_cartilla','s_aula','s_ficha','cant_ficha', 'type' => 'INTEGER', 'render' => TRUE),
            array('sede', 'aula', 'local_aplicacion', 'apepat', 'apemat', 'nombres', 'nro_local', 'direccion', 'type' => 'TEXT', 'render' => TRUE),
            array('f_aula','f_cartilla','f_ficha','codFicha','codCartilla','new_aula','new_local','tipo', 'type' => 'TEXT', 'render' => TRUE),
        );
        return $devuelve;
    }

    public function getRules() {
       return array(
            array('ins_numdoc','required'=>TRUE,'minlength'=>8,'maxlength'=>11),
        );
         
    }

    public function tableName() {
        return 'postulantes2014';
    }

    public function actualizafecha($codigo,$local) {
        ini_set('date.timezone', 'America/Lima'); 
        $where = array('codigo' => $codigo,
            'nro_local' =>$local);
        $data = array('fecha_registro' => date('Y-m-d H:i:s'));
        $this->_db->update($this->tableName(), $data, $where);
        return $this->_db->affected_rows();
    }
    
    
    public function fecha($codigo) {
        ini_set('date.timezone', 'America/Lima'); 
        $where = array('codigo' => $codigo );
        $data = array('fecha_registro' => date('Y-m-d H:i:s'));
        $this->_db->update($this->tableName(), $data, $where);
        return $this->_db->affected_rows();
    }
    
    
     /* * Exito
     * ingreso al local de aplicacion   
     */
    public function exito($codigo,$local) {
      
        $where = array(
            'codigo' => $codigo,
            'nro_local' =>$local
            
            );       
        $data = array('estatus' => '1' );
        $this->_db->update($this->tableName(), $data, $where);
        return $this->_db->affected_rows();
    }
    /* * enviado
     * Sincronizo correctamente con central  
     */
    public function enviado($codigo,$local) {
        $where = array('ins_numdoc' => $codigo,
            'nro_local' => $local);
        $data = array('estatus' => '3');
        $this->_db->update($this->tableName(), $data, $where);
        return $this->_db->affected_rows();
    }


/* * enviado trunco ingreso al aula
     * Sincronizo correctamente con central  
     */
    public function trunco_aula($codigo) {
        $where = array('ins_numdoc' => $codigo);
        $data = array('s_aula' => '2');
        $this->_db->update($this->tableName(), $data, $where);
        return $this->_db->affected_rows();
    }

    /* * AULA
     * Ingreso al aula correcta
     */
    public function aula($codigo,$local,$aula) {
        
        ini_set('date.timezone', 'America/Lima'); 
        $where = array('codigo' => $codigo);
        $data = array(
            'new_local'=> $local,
            'new_aula'=>$aula,
            'f_aula' => date('Y-m-d H:i:s'),
            's_aula' => '1');
        $this->_db->update($this->tableName(), $data, $where);
        return $this->_db->affected_rows();
    }
    /* * FICHA
     * ingreso en la ficha optica 
     */
    public function ficha($codigo,$cantFicha) {
        $cantFicha=$cantFicha+1;
        ini_set('date.timezone', 'America/Lima'); 
        $where = array('codFicha' => $codigo);
        $data = array(
            'f_ficha' => date('Y-m-d H:i:s'),
            's_ficha' => '1',
            'cant_ficha'=>$cantFicha
           );
        $this->_db->update($this->tableName(), $data, $where);
        return $this->_db->affected_rows();
    }
    
    /* * Cartilla
     * ingreso en la cartilla optica 
     */
    public function cartilla($codigo) {
        
        ini_set('date.timezone', 'America/Lima'); 
        $where = array('codCartilla' => $codigo);
        $data = array(
            'f_cartilla' => date('Y-m-d H:i:s'),
            's_cartilla' => '1'
           );
        $this->_db->update($this->tableName(), $data, $where);
        return $this->_db->affected_rows();
    }
    
    public function ficha_aula($codigo,$aula,$local,$cantFicha) {
        $cantFicha = $cantFicha +1;
        ini_set('date.timezone', 'America/Lima'); 
        $where = array('codFicha' => $codigo);
        $data = array(
            'f_ficha' => date('Y-m-d H:i:s'),
            's_ficha' => '1',
             'aula' => $aula,
            'new_aula' => $aula,
            'new_local' => $local,
            'cant_ficha'=>$cantFicha
           );
        $this->_db->update($this->tableName(), $data, $where);
        return $this->_db->affected_rows();
    }
    
    /* * Cartilla
     * ingreso en la cartilla optica 
     */
    public function cartilla_aula($codigo,$aula,$local) {
        
        ini_set('date.timezone', 'America/Lima'); 
        $where = array('codCartilla' => $codigo);
        $data = array(
            'f_cartilla' => date('Y-m-d H:i:s'),
            's_cartilla' => '1',
            'aula' => $aula,
            'new_aula' => $aula,
            'new_local' => $local
           );
        $this->_db->update($this->tableName(), $data, $where);
        return $this->_db->affected_rows();
    }

    public function filtro_input($start = NULL, $count = NULL, $type = 'object') {
        if (is_numeric($start) & is_numeric($count)) {
            $this->_db->limit($start, $count);
        }
        $this->_db->where_in('estatus', array(1, 2, 3,4));
        $q = $this->_db->get($this->tableName());
        $result = $q->result($type);
        return $result;
    }
    
    public function count_input() {
        $this->_db->where_in('estatus', array(1, 2, 3,4));
        $count = $this->_db->count_all_results($this->tableName());
        return $count;
    }
    
    public function count_noenv_input() {
        $this->_db->where_in('estatus', array(1,3,4));
        $count = $this->_db->count_all_results($this->tableName());
        return $count;
    }
    
    
    public function filtro_input_ficha($aula = NULL, $type = 'object') {
        if($aula !=="") 
        { $this->_db->where('aula', $aula);}
        $this->_db->where_in('s_ficha', array(1, 2, 3,4));
    	 $q = $this->_db->get($this->tableName());
	
         $result = $q->result($type);
        
        return $result;
        
        
    }
    
    public function count_input_ficha($aula) {
         if($aula!=="") 
        { $this->_db->where('aula', $aula);}
         
        $this->_db->where_in('s_ficha', array(1, 2, 3,4));
        $count = $this->_db->count_all_results($this->tableName());
        return $count;
    }
    
    
    public function count_noenv_ficha($aula) {
        if($aula!=="") 
        { $this->_db->where('aula', $aula);}
        
        $this->_db->where_in('s_ficha', array(1,3,4));
        $count = $this->_db->count_all_results($this->tableName());
        return $count;
    }
    
    
    public function filtro_input_cartilla($aula = NULL, $type = 'object') {
        
        if($aula!=="") 
        { $this->_db->where('aula', $aula);}
        $this->_db->where_in('s_cartilla', array(1, 2,3,4));
        $q = $this->_db->get($this->tableName());
        $result = $q->result($type);
        return $result;
    }
    
    public function count_input_cartilla($aula) {
         if($aula !== "") 
        { $this->_db->where('aula', $aula);}
        $this->_db->where_in('s_cartilla', array(1, 2, 3,4));
        $count = $this->_db->count_all_results($this->tableName());
        return $count;
    }
    
      public function count_noenv_cartilla($aula) {
         if($aula !== "") 
        { $this->_db->where('aula', $aula);}
        $this->_db->where_in('s_cartilla', array(1,3,4));
        $count = $this->_db->count_all_results($this->tableName());
        return $count;
    }
    
    
     public function filtro_input_aula($aula = NULL, $type = 'object') {
        if($aula!=="") 
        { $this->_db->where('new_aula', $aula);}
        
        $this->_db->where_in('s_aula', array(1, 2,3,4));
        $q = $this->_db->get($this->tableName());
        $result = $q->result($type);
        return $result;
    }
    
    public function count_input_aula($aula) {
        
         if($aula !== "") 
        {$this->_db->where('new_aula', $aula);}
        $this->_db->where_in('s_aula', array(1, 2, 3,4));
        $count = $this->_db->count_all_results($this->tableName());
       
        return $count;
    }
    
    public function count_noenv_aula($aula) {
      
         if($aula !== "") 
        { $this->_db->where('new_aula', $aula);}
        
        $this->_db->where_in('s_aula', array(1, 3,4));
        $count = $this->_db->count_all_results($this->tableName());
        return $count;
    }

    
    
     
    
    
    
    /* * Verificar si es del aula correcta 
     * Sincronizo correctamente con central  
     */
    function verificar_aula($nroLocal = NULL, $aula = NULL ,$dni = NULL, $type = 'object')
    {  
        
        $this->_db->where('nro_local', $nroLocal);    
        $this->_db->where('ins_numdoc', $dni); 
        $this->_db->where('aula', $aula); 
    	 $q = $this->_db->get($this->tableName());
	
         $result = $q->result($type);
        
        return $result;
        
        
    }
    
    function verificar_aula_ficha($codficha = NULL, $type = 'object')
    {  

        $this->_db->where('codFicha', $codficha); 
    	 $q = $this->_db->get($this->tableName());
	
         $result = $q->result($type);
        
        return $result;
        
        
    }
    
    
    function verificar_aula_cartilla($codcartilla = NULL, $type = 'object')
    {  

        $this->_db->where('codCartilla', $codcartilla); 
    	 $q = $this->_db->get($this->tableName());
	
         $result = $q->result($type);
        
        return $result;
        
        
    }
    
    
   function verificar_director($dni = NULL, $type = 'object')
    {  
     
        $this->_db->where('ins_numdoc', $dni); 
        
    	 $q = $this->_db->get($this->tableName());
	
         $result = $q->result($type);
        
        return $result;
        
        
    }
    
    function director_local($dni = NULL,$local = NULL,$type = 'object')
    {  
     
        $this->_db->where('ins_numdoc', $dni); 
        $this->_db->where('nro_local', $local); 
        
    	 $q = $this->_db->get($this->tableName());
	$result = $q->row(0, $type);
         
        
        return $result;
        
        
    }
    ///
    
    
    function getResumenTotal($local= NULL, $type = 'object')
    {
      $sql="select 1 as tipo,'TOTAL' as new_aula,
sum((case estatus when 0 then 0 else 1 END )) as local,
sum((case s_aula when 0 then 0 else 1 END )) as aula,
sum((case s_ficha when 0 then 0 else 1 END )) as ficha,
sum((case s_cartilla when 0 then 0 else 1 END )) as cartilla
 from postulantes2014
where nro_local=".$local."

UNION
select 2 as tipo,new_aula,
sum((case estatus when 0 then 0 else 1 END )) as local,
sum((case s_aula when 0 then 0 else 1 END )) as aula,
sum((case s_ficha when 0 then 0 else 1 END )) as ficha,
sum((case s_cartilla when 0 then 0 else 1 END )) as cartilla
 from postulantes2014
where nro_local=".$local." and new_aula<>0
GROUP BY new_aula
order by tipo,aula";
       $q = $this->_db->query($sql);
       $result = $q->result($type);
        return $result;
}
    
 
    function getResumenInventario($local= NULL, $type = 'object')
    {
      $sql="
select 1 as tipo,'TOTAL' as new_aula,

sum((case s_ficha when 0 then 0 else 1 END )) as ficha,
sum((case s_cartilla when 0 then 0 else 1 END )) as cartilla
 from postulantes2014
where nro_local=".$local."

UNION
select 2 as tipo,aula,

sum((case s_ficha when 0 then 0 else 1 END )) as ficha,
sum((case s_cartilla when 0 then 0 else 1 END )) as cartilla
 from postulantes2014
where nro_local=".$local." and (s_ficha<>0 or s_cartilla<>0)
GROUP BY aula
order by tipo,aula          

";
       $q = $this->_db->query($sql);
       $result = $q->result($type);
        return $result;
}

function limpiar()
    {
      $sql="update postulantes2014 SET
aula_ficha=NULL,
aula_cartilla=NULL,
s_aula='0',
f_aula=NULL,
sf_aula=NULL,
s_ficha='0',
f_ficha=NULL,
sf_ficha=NULL,
s_cartilla='0',
f_cartilla=NULL,
sf_cartilla=NULL,
new_aula='0',
new_local='0',
estatus='0',
cant_ficha='0',
fecha_registro=NULL,
sfecha_registro=NULL ";
      $query = $this->_db->query($sql);
      
}


    
    ////
    
   public function getDataEnvioMasivo($event){
        $data = array();
        switch ($event) {
            case '1':{
                $data = $this->getDataEnvioMasivoEvento1();
            }break;
            case '2':{
                $data = $this->getDataEnvioMasivoEvento2();
            }break;
            case '3':{
                $data = $this->getDataEnvioMasivoEvento3();
            }break;
            case '4':{
                $data = $this->getDataEnvioMasivoEvento4();
            }break;
        }
        return $data;
    }

    function setEstadoEnvioMasivo($data, $key){
        $this->_db->update_batch($this->tableName(), $data, $key);
    }

    /**
     * Local
     * @return array
     */
    function getDataEnvioMasivoEvento1(){
        $this->_db->select('codigo as llave,ins_numdoc,nro_local,cant_ficha');
        $this->_db->where_in('estatus', array(1, 3,4));
        $q = $this->_db->get($this->tableName());
        return $q->result('array');
    }
    /**
     * Aulas
     * @return array
     */
    function getDataEnvioMasivoEvento2(){
        $this->_db->select('codigo as llave, ins_numdoc,nro_local,cant_ficha');
        $this->_db->where_in('s_aula', array(1, 3,4));
        $q = $this->_db->get($this->tableName());
        return $q->result('array');
    }
    /**
     * Ficha electronica
     * @return array
     */
    function getDataEnvioMasivoEvento3(){
        $this->_db->select('codFicha as llave, codFicha as ins_numdoc,nro_local,cant_ficha');
        $this->_db->where_in('s_ficha', array(1, 3,4));
        $q = $this->_db->get($this->tableName());
        return $q->result('array');
    }
    /**
     * Cartilla
     * @return array
     */
    function getDataEnvioMasivoEvento4(){
        $this->_db->select('codCartilla as llave, codCartilla as ins_numdoc,nro_local,cant_ficha');
        $this->_db->where_in('s_cartilla', array(1, 3,4));
        $q = $this->_db->get($this->tableName());
        return $q->result('array');
    }


    //////////////////////////
    
    
    public function getDataEnvio($event,$datokey){
        $data = array();
        switch ($event) {
            case '1':{
                $data = $this->getDataEnvioEvento1($datokey);
            }break;
            case '2':{
                $data = $this->getDataEnvioEvento2($datokey);
            }break;
            case '3':{
                $data = $this->getDataEnvioEvento3($datokey);
            }break;
            case '4':{
                $data = $this->getDataEnvioEvento4($datokey);
            }break;
        }
        return $data;
    }

   

    /**
     * Local
     * @return array
     */
    function getDataEnvioEvento1($datokey){
        $this->_db->select('codigo as llave, ins_numdoc,nro_local,cant_ficha');
        $this->_db->where_in('codigo', $datokey);
        $q = $this->_db->get($this->tableName());
        return $q->result('array');
    }
    /**
     * Aulas
     * @return array
     */
    function getDataEnvioEvento2($datokey){
        $this->_db->select('codigo as llave,ins_numdoc,nro_local,cant_ficha');
        $this->_db->where_in('codigo', $datokey);
        $q = $this->_db->get($this->tableName());
        return $q->result('array');
    }
    /**
     * Ficha electronica
     * @return array
     */
    function getDataEnvioEvento3($datokey){
        $this->_db->select('codFicha as llave,codFicha as ins_numdoc,nro_local,cant_ficha');
        $this->_db->where_in('codFicha', $datokey);
        $q = $this->_db->get($this->tableName());
        return $q->result('array');
    }
    /**
     * Cartilla
     * @return array
     */
    function getDataEnvioEvento4($datokey){
        $this->_db->select('codCartilla as llave, codCartilla as ins_numdoc,nro_local,cant_ficha');
        $this->_db->where_in('codCartilla', $datokey);
        $q = $this->_db->get($this->tableName());
        return $q->result('array');
    }

    
    
    
    

}
