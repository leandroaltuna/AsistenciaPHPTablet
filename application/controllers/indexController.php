<?php

class indexController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->session_start();
        $this->getHelper('form_helper');
        $this->getHelper('string_helper');
    }
    
    public function index() {
        $this->_view->titulo = 'EVALUACION';
         $this->_view->usuario = FALSE;
      
       $this->_view->setJs(array('index'));
        $this->_view->renderizar('index','index');
    }



 public function local() {   
 if ($this->_request->session('sede')!='')
        {
        $this->_view->titulo = 'SCAI::ING. LOCAL';
        $this->_view->director  = FALSE; $this->_view->estado =FALSE;
          $model = $this->loadModel('director');
          $fdata = $this->_request->get('frmdirector');
          $director = FALSE;
                $this->_view->estado = '0';
        if ($fdata){
            $director = $model->get($fdata);
          if ($director){
              if($director->estatus === 0){
                  if($director->tipo !=='2'){
                      //normal
                      if(( $this->_request->session('nro_local') === $director->nro_local) ){
                           $this->_view->director = $director;
                          $model->actualizafecha($director->codigo, $this->_request->session('nro_local'));
                          $model->exito($director->codigo, $this->_request->session('nro_local'));
                          $this->_view->estado = '1';
                      }else{
                          // Otro Local
                           $this->_view->director = $director;
                          $this->_view->estado = '3';
                      }
                  }else{
                      //doble
                      if(($director->nro_local === 18 || $director->nro_local === 12)&&($this->_request->session('nro_local') === 18 || $this->_request->session('nro_local') === 12) ){
                          
                          $director1 = $model->director_local($director->ins_numdoc,  $this->_request->session('nro_local'));   
                     $this->_view->director = $director1;
                      $model->fecha($director->codigo);
                    $model->exito($director->codigo, $this->_request->session('nro_local'));
                      $this->_view->estado = '1';

                          
                        }else{
                            // Otro Local
                             $this->_view->director = $director;    
                       
                            $this->_view->estado = '3';

                        }
                          
                      
                  }
                  
                  
              }else{
                  // Ya tiene Asistencia
                  
                  if($director->tipo !=='2'){
                    $this->_view->director = $director;
                     $this->_view->estado = '4';
                  }else {
                     $director1 = $model->director_local($director->ins_numdoc,  $this->_request->session('nro_local'));   
                     $this->_view->director = $director1;
                     $this->_view->estado = '4';
                    } 
                  
              }
              
              
              
          
              
          }else{
              // No se encuentra en el padron
                 $this->_view->estado = '2';
          }  
            
            
            
            
        }
      
      $this->_view->form = Model::createForm(array('model' => 'director'));
   
        $this->_view->setJs(array('index'));
        $this->_view->renderizar('local');
        
        }else
        {
            
            $this->_view->renderizar('index','index');
            
        }
    }

    
    public function descargaractualizacion()
    {
        
            $this->_view->titulo = 'SCAI:.Descarga de Actualizacion';             
            $this->_view->renderizar("actualizar");
            
    }
    
    
    
      public function resetBDIndex()
    {
       
         $this->_view->titulo = 'Reset del la Base de Datos';   
        $this->_view->setJs(array('limpiar'));    
            $this->_view->renderizar("limpiar");
            
    }
    
    
     public function resetBD()
    {
        $model = $this->loadModel('director');
        $model->limpiar();
         
         $json = array('success' => TRUE);
                $this->_view->json = $json;
                $this->_view->renderizarJson();
        
    }
    
    
}
