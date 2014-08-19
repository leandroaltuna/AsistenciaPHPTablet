<?php

class cartillaController extends Controller {

    public function __construct() {
        parent::__construct();
         $this->session_start();
        $this->getHelper('form_helper');
        $this->getHelper('string_helper');
        $this->getLibrary('Pagination');
    }
    
    
    
    
    
    

    public function index() {
          $this->_view->titulo = 'SCAI::UDRA - CUADERNILLO'; 
          $this->_view->director  = FALSE; $this->_view->estado =FALSE;
        $local  = $this->_request->session('nro_local');
        $modelA = $this->loadModel('aula');
        $this->_view->aulas = $modelA->get_aula($local);           
         $fdata = $this->_request->get();         
         $model = $this->loadModel('director');
         $director = $model->verificar_aula_cartilla($fdata['ins_numdoc']);  
         $this->_view->estado = '0'; 
         
          
         if ($fdata)
         {   
             $director = $model->verificar_aula_cartilla($fdata['ins_numdoc']);   
             
            if ($director)
                {
                    if($director[0]->s_cartilla == 0)
                    { 
                        if($director[0]->ins_numdoc !== NULL  )
                          { 
                           if($director[0]->tipo !== '2')
                           {
                                if($director[0]->nro_local == $local)
                                {  
                                        if($director[0]->aula === $fdata['paulas'] ){
                                            $this->_view->director = $director[0];              
                                            $model->cartilla($fdata['ins_numdoc']);              
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
                                if($director[0]->nro_local === 18 || $director[0]->nro_local === 12 ){
                                    if($director[0]->aula === $fdata['paulas']){
                                    $this->_view->director = $director[0];              
                                    $model->ficha($fdata['ins_numdoc'],$director[0]->cant_ficha);              
                                    $this->_view->estado = '1';
                                    }  else {
                                        $this->_view->director = $director[0];                                             
                                        $this->_view->estado = '2';
                                    }
                                }else{
                                        $this->_view->director = $director[0];                                             
                                        $this->_view->estado = '5';
                                     }
                            }  
                            ///
                          }else{
                                // Fichas de contingencia
                              $this->_view->director = $director[0];                                       
                              $model->cartilla_aula($fdata['ins_numdoc'],$fdata['paulas'],$local);
                              $this->_view->estado = '7'; 

                        }
                    }else{
                                    $this->_view->director = $director[0];                                             
                                    $this->_view->estado = '3';
                    }

            }else{                              
                $this->_view->estado = '4';                    
            }
         }
         
         
         
         
         
         $this->_view->setJs(array('cartilla'));
        $this->_view->renderizar('ucartilla'); 
           
    }
    


}
