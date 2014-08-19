<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of guardarController
 *
 * @author cdelgadoc
 */
class guardarController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->getHelper('form_helper');
        $this->getHelper('string_helper');
    }

    public function index() {
        $this->_view->titulo = 'Agrega un nuevo Docente';
        $atributos = array(
            'model' => 'director',
        );
        $this->_view->form = Model::createForm($atributos);
        $this->_view->form->set_action('guardar/save');
        $this->_view->setJs(array('index'));
        $this->_view->renderizar('create');
    }

    public function save() {
//        $atributos = array(
//            'model' => 'director',
//            'data' => $this->_request->post(),
//        );
//        $form = Model::createForm($atributos);
//        $form->save();
        if ($this->_request->getServerMethod() === 'POST') {
            $model = $this->loadModel('director');
            $model->create($this->_request->post('frmdirector'));
            $this->redireccionar('guardar');
        } else {
            echo 'Enviame data wevonaaaa';
        }
    }

}
