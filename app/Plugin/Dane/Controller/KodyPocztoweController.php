<?php

App::uses('DataobjectsController', 'Dane.Controller');

class KodyPocztoweController extends DataobjectsController
{
    public $menu = array();
    // public $initLayers = array('struktura');
    
    public function view() {
	    
	    $this->_prepareView();
	    return $this->redirect( $this->object->getUrl() );
	    
    }

} 