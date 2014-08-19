<?php

class taskController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->session_start();
        $this->getHelper('form_helper');
        $this->getHelper('string_helper');
    }

    public function index() {
        $this->_view->titulo = 'Envio Masivo';
        $this->_view->setJs(array('index'));
        $this->_view->renderizar('index');
    }

    public function transfer($event) {
        $model = $this->loadModel('director');
        $_data = array();
        $_data['directores'] = $model->getDataEnvioMasivo($event);
        $_data['event'] = $event;
       
        $data = json_encode($_data);
        //$ch = curl_init('http://190.223.32.196/beca18/WSEna/mssql.php');
        $ch = curl_init(TRANSFER_HOST_MASIVO);
        //especificamos el POST (tambien podemos hacer peticiones enviando datos por GET
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        //le decimos qu� param�etros enviamos (pares nombre/valor, tambi�n acepta un array)
        curl_setopt($ch, CURLOPT_POSTFIELDS, "data=$data");
        // echo $data;
        //le decimos que queremos recoger una respuesta (si no esperas respuesta, ponlo a false)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //recogemos la respuesta
        $respuesta = curl_exec($ch);
        //o el error, por si falla
        $error = curl_error($ch);
        //y finalmente cerramos curl
        curl_close($ch);
        if (!$error) {
            if ($respuesta) {
                $eventsKCol = array('codigo', 'codigo', 'codFicha', 'codCartilla');
                $rData = json_decode($respuesta);
                $model->setEstadoEnvioMasivo($rData, $eventsKCol[$event-1],  $this->_request->session('nro_local'));
                $json = array('success' => TRUE);
                $this->_view->json = $json;
                $this->_view->renderizarJson();
            }
        }
    }
    
    
    
    public function transfer_u() {
       $dat=$this->_request->get();
        $event=$dat['evento'];
        $model = $this->loadModel('director');
        $_data = array();
        
        $_data['directores'] = $model->getDataEnvio($dat['evento'],$dat['key']);
        $_data['event'] =$event ;
       
        $data = json_encode($_data);
      
        $ch = curl_init(TRANSFER_HOST_MASIVO);
      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        //le decimos qu� param�etros enviamos (pares nombre/valor, tambi�n acepta un array)
        curl_setopt($ch, CURLOPT_POSTFIELDS, "data=$data");
        // echo $data;
        //le decimos que queremos recoger una respuesta (si no esperas respuesta, ponlo a false)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //recogemos la respuesta
        $respuesta = curl_exec($ch);
        //o el error, por si falla
        $error = curl_error($ch);
        //y finalmente cerramos curl
        curl_close($ch);
        if (!$error) {
            if ($respuesta) {
                
                $eventsKCol = array('codigo', 'codigo', 'codFicha', 'codCartilla');
                $rData = json_decode($respuesta);
               
                $model->setEstadoEnvioMasivo($rData, $eventsKCol[$event-1]);
                $json = array('success' => TRUE);
                $this->_view->json = $json;
                $this->_view->renderizarJson();
            }
        }
    }
    
    
    

}
