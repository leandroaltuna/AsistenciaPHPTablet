<?php

class localController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->getHelper('form_helper');
        $this->getHelper('string_helper');
        $this->getLibrary('Pagination');
    }
    
   
    public function index() {
        $this->_view->titulo = 'SCAI:.REG. DEL POSTULANTE - LOCAL';
         $this->_view->renderizar('local');
        $model = $this->loadModel('director');
        $this->_view->director ;
        $fdata = $this->_request->get('frmdirector');
        
        if ($fdata) {
            $director = $model->get($fdata);
            if ($director) {
                //$this->_view->director = $director;
                
                 $this->_view->estado=2;
                if ($director->tipo !== '2') {
                    $this->_view->director = $director;
                    
                    if($director->estatus == 0){
                    
                    $model->exito($director->codigo,$this->_request->session('nro_local'));
                    $this->_view->estado=1;
                        }else{
                         $this->_view->estado=2;
                        }
                }else{
                    if($director->nro_local == 18 || $director->nro_local == 12 ){
                        $director = $model->director_local($fdata,  $this->_request->session('nro_local'));
                       
                        $model->exito($director->codigo, $this->_request->session('nro_local'));
                        $this->_view->estado=1;
                    }else{
                        $this->_view->estado=2;
                    }

                }
                
            }
        }else
        {
            $director = FALSE;
            $this->_view->estado=2;
        }
       // $model->actualizafecha($director->codigo);
        //$model->exito($director->codigo);
        $this->_view->form = Model::createForm(array('model' => 'director'));
        $this->_view->form->set_attrs(array('method' => 'GET'));
        $this->_view->setJs(array('index'));
        $this->_view->renderizar('index');
    }

    public function transfer() {
        $_data = $this->_request->post();
        $data = json_encode($_data);
  
       $ch = curl_init(TRANSFER_HOST);
 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  
        curl_setopt($ch, CURLOPT_POSTFIELDS, "data=$data");
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       $respuesta = curl_exec($ch);

        $error = curl_error($ch);

        curl_close($ch);
        if (!$error) {
            if ($respuesta) {
                $model = $this->loadModel('director');
                $model->enviado($_data['ins_numdoc'],  $this->_request->session('nro_local')); 
                $json = json_decode($respuesta);
                $this->_view->json = $json;
                $this->_view->renderizarJson();
            }
        }else{
            $model = $this->loadModel('director');
            $model->trunco($_data['ins_numdoc']);      
        }
    }

}
