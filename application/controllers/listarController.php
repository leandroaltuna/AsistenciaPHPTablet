<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of listarController
 *
 * @author cdelgadoc
 */
class listarController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->session_start();
        $this->getHelper('form_helper');
        $this->getHelper('string_helper');
        $this->getLibrary('Pagination');
    }

    public function index() {
        $count = 5;

        /* datos para la vista */
        $model = $this->loadModel('director');
        
        $page = $this->_request->get('per_page');
        $query_string_segment = 'per_page';
        $page = is_numeric($page) ? $page : 0;
        $this->pagination = new Pagination();
        $num_rows = $model->count_input();
        $numnoenv_rows = $model->count_noenv_input();
        $this->_view->titulo = 'LISTA DE DOCENTES CONSULTADOS CON DNI <br>Total de [' . $num_rows . '] postulantes ingresados - Total de [' . $numnoenv_rows . '] postulantes registrados no enviados ';
        
        $query_string = $this->_request->server('QUERY_STRING');
        $query_string = preg_replace("/&$query_string_segment=(\d+){0,6}/i", '', $query_string);
        $config = array();
        $config["base_url"] = BASE_URL . 'index.php/listar?' . $query_string;
        $config["total_rows"] = $num_rows;
        $config["per_page"] = $count;
        $config["page_query_string"] = TRUE;
        $config["cur_page"] = $page;
        $config["query_string_segment"] = $query_string_segment;
        $this->pagination->initialize($config);
        $this->_view->paginator = $this->pagination->create_links();
        $this->_view->directores = $model->filtro_input($count, $page);
       $this->_view->setJs(array('task'));
        $this->_view->renderizar('lista');
    }
    
    
    public function ficha() {
        $model = $this->loadModel('director');
        $this->_view->directores = FALSE;
        $local = $this->_request->session('nro_local');
         $modelA = $this->loadModel('aula');
        $this->_view->aulas = $modelA->get_aula($local); 
        $form = $this->_request->get();
        if ($form['paulas']===NULL){
        $num_rows = $model->count_input_ficha('');
        $numnoenv_rows = $model->count_noenv_ficha('');
            
        }else{
                 $num_rows = $model->count_input_ficha($form['paulas']);
                 $numnoenv_rows = $model->count_noenv_ficha($form['paulas']);
                 $this->_view->directores = $model->filtro_input_ficha($form['paulas']);
        }
            $this->_view->titulo = 'LISTADO DE REGISTRO DE FICHA <br>Total de [' . $num_rows . '] fichas registradas  - Total de [' . $numnoenv_rows . '] fichas registradas no enviadas ';
         
        $this->_view->setJs(array('ficha'));
         $this->_view->setJs(array('task'));
        $this->_view->renderizar('listaficha');
    }
    
    public function cartilla() {
       $model = $this->loadModel('director');
        $this->_view->directores = FALSE;
        $local = $this->_request->session('nro_local');
         $modelA = $this->loadModel('aula');
        $this->_view->aulas = $modelA->get_aula($local); 
        $form = $this->_request->get();
        if ($form['paulas']===NULL){
        $num_rows = $model->count_input_cartilla('');
        $numnoenv_rows = $model->count_noenv_cartilla('');
            
        }else{
                 $num_rows = $model->count_input_cartilla($form['paulas']);
                 $numnoenv_rows = $model->count_noenv_cartilla($form['paulas']);
                 $this->_view->directores = $model->filtro_input_cartilla($form['paulas']);
        }
            $this->_view->titulo = 'LISTADO DE REGISTRO DE CUADERNILLOS <br>Total de [' . $num_rows . '] cuadernillos registrados  - Total de [' . $numnoenv_rows . '] cuadernillos registrados no enviados ';
        $this->_view->setJs(array('cartilla'));
        $this->_view->setJs(array('task'));
        $this->_view->renderizar('listacartilla');
    }
    
    
    public function aula() {
       $model = $this->loadModel('director');
        $this->_view->directores = FALSE;
        $local = $this->_request->session('nro_local');
         $modelA = $this->loadModel('aula');
        $this->_view->aulas = $modelA->get_aula($local); 
        $form = $this->_request->get();
        
        if ($form['paulas'] === NULL ){
            
            $num_rows = $model->count_input_aula('');
            $numnoenv_rows = $model->count_noenv_aula('');
            
        }else{
            
                 $num_rows = $model->count_input_aula($form['paulas']);
                 $numnoenv_rows = $model->count_noenv_aula($form['paulas']);
                 $this->_view->directores = $model->filtro_input_aula($form['paulas']);
        }
            $this->_view->titulo = 'LISTADO DE REGISTRO DE ASISTENCIA POR AULA <br>Total de [' . $num_rows . '] postulantes registrados  - Total de [' . $numnoenv_rows . '] postulantes registrados no enviados ';
           
       
        $this->_view->setJs(array('aula'));
        $this->_view->setJs(array('task'));
        $this->_view->renderizar('listaaula');
    }
    
    
    public function reporte() {
      $model = $this->loadModel('director');
        $this->_view->reporte = FALSE;
        $local = $this->_request->session('nro_local');
        
         
            $this->_view->titulo = 'RESUMEN DE CONTROL DE ASISTENCIA';
            
            
           $this->_view->reporte = $model->getResumenTotal($local);
        
      
        $this->_view->renderizar('listareporte');
    }
    
    public function reporteinventario() {
      $model = $this->loadModel('director');
        $this->_view->reporte = FALSE;
        $local = $this->_request->session('nro_local');
        
         
            $this->_view->titulo = 'RESUMEN DE CONTROL DE INVENTARIADO';
            
            
           $this->_view->reporte = $model->getResumenInventario($local);
        
      
        $this->_view->renderizar('listareporteInv');
    }
    
    

}
