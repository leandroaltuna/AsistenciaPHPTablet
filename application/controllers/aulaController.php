<?php

class aulaController extends Controller {

    public function __construct() {
        parent::__construct();
         $this->session_start();
        $this->getHelper('form_helper');
        $this->getHelper('string_helper');
        $this->getLibrary('Pagination');
    }
   

    public function index() {
         if ($this->_request->session('sede')!='')
        {
        
          $this->_view->titulo = 'ASISTENCIA AL AULA';
          $this->_view->director  = FALSE; $this->_view->estado =FALSE;
        $local  = $this->_request->session('nro_local');
        $modelA = $this->loadModel('aula');
        $this->_view->aulas = $modelA->get_aula($local);
            
         $fdata = $this->_request->get();         
         $model = $this->loadModel('director');
       
         $this->_view->estado = '0';
         
         
         
         if ($fdata) 
         {     
        $director = $model->verificar_director($fdata['ins_numdoc']); 
        $aula =  $modelA->get_selaula($fdata['paulas'],$local);
            if($director){
               if($director[0]->tipo !== '2')
               {
                    if($director[0]->nro_local == $this->_request->session('nro_local') )
                      {  
                
                        if($director[0]->s_aula == 0)
                        {    
                            if($aula[0]->tipo == 0)
                            {
                                $this->_view->director = $director[0];              
                                $model->aula($director[0]->codigo,$local,$fdata['paulas']);              
                                $this->_view->estado = '1';

                            }else
                            {   
                                if ($fdata['paulas'] == $director[0]->aula)
                                {    
                                    $this->_view->director = $director[0];              
                                    $model->aula($director[0]->codigo,$local,$fdata['paulas']);              
                                    $this->_view->estado = '1';

                                }else {
                                    $this->_view->director = $director[0];                                             
                                    $this->_view->estado = '2';
                                }


                            }
                        }else{
                            $this->_view->director = $director[0];  
                            $this->_view->estado = '3';
                        }
                }else  {
                    
                    $this->_view->director = $director[0];  
                    $this->_view->estado = '6';
                }
             }else{
                 ///*****//////
               
                 
                if($director[0]->s_aula == 0)
                 {   
                    if(($director[0]->nro_local === 18 || $director[0]->nro_local === 12) && ($this->_request->session('nro_local') === 18 || $this->_request->session('nro_local') === 12) ){
                      $director1 = $model->director_local($director[0]->ins_numdoc,  $this->_request->session('nro_local'));  
                     if ($fdata['paulas'] == $director[0]->aula)
                     {
                        $this->_view->director = $director[0];              
                        $model->aula($director1->codigo,$local,$fdata['paulas']);              
                        $this->_view->estado = '1';
                     }else{
                         $this->_view->director = $director[0];  
                         $this->_view->estado = '2';
                     }
                         
                   
                }else{
                        $this->_view->director = $director[0];                                             
                        $this->_view->estado = '6';
                     }
             }else{
                        $this->_view->director = $director[0];                                             
                        $this->_view->estado = '3';
             }    
                 /////
             }   
              
            }else
            {                
               
                $this->_view->director = $director[0];  
                   $this->_view->estado = '4'; 
                 
                         
            }
         }
            $this->_view->setJs(array('aula'));
            $this->_view->renderizar('asistencia'); 
        }else
        {
            
            $this->_view->renderizar('index','index');
            
        }      
    }
    
 
}
