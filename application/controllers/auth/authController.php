<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of authController
 *
 * @author holivares
 */
class authController extends Controller{

    public function __construct() {
        parent::__construct();
        $this->session_start();
        $this->getHelper('form_helper');
        $this->getHelper('string_helper');
    }

    public function index() {
    }

    public  function cerrar()
    {

        $this->session_destroy();
         $this->_view->redireccionar('index/index');

    }


    public function validar(){
        $model = $this->loadModel('usuario');
         $fdata = $this->_request->post('frmlogin');

           $usuario = FALSE;
        if ($fdata) {
            $usuario = $model->get($fdata);
           
            if ($usuario) {

                $this->_request->session_add('nro_local',$usuario->nro_local);
                $this->_request->session_add('nlocal',$usuario->nombreLocal);
                $this->_request->session_add('aula',$usuario->naulas);
                $this->_request->session_add('rol',$usuario->rol);
                $this->_request->session_add('usuario',$usuario->usuario);
                $this->_request->session_add('sede',strtoupper($usuario->sede));
                $this->_request->session_add('contingencia',$usuario->ncontingencia);
                $this->_view->redireccionar('index/local');

            }else
            {
            $usuario->_view->usuario = $fdata;
            $this->_view->redireccionar('index/index');
            }

        }else{

          $usuario->_view->usuario = $fdata;
          $this->_view->redireccionar('index/index');

         }

    }

//put your code here
}
