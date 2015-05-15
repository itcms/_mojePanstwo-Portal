<?php

App::uses('ApplicationsController', 'Controller');

class MojaGminaController extends ApplicationsController
{
    public $settings = array(
        'id' => 'moja_gmina',
        'menu' => array(
            array(
                'id' => '',
                'label' => 'Start',
                'href' => 'moja_gmina'
            ),
            array(
                'id' => 'gminy',
                'label' => 'Gminy',
                'href' => 'moja_gmina/gminy'
            ),
            array(
                'id' => 'powiaty',
                'label' => 'Powiaty',
                'href' => 'moja_gmina/powiaty'
            ),
            array(
                'id' => 'wojewodztwa',
                'label' => 'Województwa',
                'href' => 'moja_gmina/wojewodztwa'
            ),
            array(
                'id' => 'miejscowosci',
                'label' => 'Miejscowości',
                'href' => 'moja_gmina/miejscowosci'
            ),
            array(
                'id' => 'kody_pocztowe',
                'label' => 'Kody pocztowe',
                'href' => 'moja_gmina/kody_pocztowe'
            ),
            /*
            array(
                'id' => 'radni',
                'label' => 'Radni gmin',
                'href' => 'moja_gmina/radni'
            )
            */
        ),
        'title' => 'Moja gmina',
        // 'subtitle' => 'moja gmina',
		'headerImg' => '/moja_gmina/img/header_moja-gmina.png',
    );


    public $components = array('RequestHandler');


    public function prepareMetaTags() {
        parent::prepareMetaTags();
        $this->setMeta('og:image', FULL_BASE_URL . '/moja_gmina/img/social/mojagmina.jpg');
    }

    public function index()
    {
		
		$datasets = $this->getDatasets('moja_gmina');
			    
        $options  = array(
            'searchTitle' => 'Szukaj w gminach, powiatach, województwach i miejscowościach...',
            'conditions' => array(
	            'dataset' => array_keys($datasets),
            ),
            'cover' => array(
	            'view' => array(
		            'plugin' => 'MojaGmina',
		            'element' => 'cover',
	            ),
            ),
            'aggs' => array(
		        'dataset' => array(
		            'terms' => array(
			            'field' => 'dataset',
		            ),
		            'visual' => array(
			            'label' => 'Zbiory danych',
			            'skin' => 'datasets',
			            'class' => 'special',
		                'field' => 'dataset',
		                'dictionary' => $datasets,
		            ),
		        ),
            ),
        );
        
	    $this->Components->load('Dane.DataBrowser', $options);
        $this->render('Dane.Elements/DataBrowser/browser-from-app');


    }

    public function search()
    {

        $output = array();
		$this->loadModel('Dane.Dataobject');
		
		$gminy = $this->Dataobject->find('all', array(
			'conditions' => array(
				'dataset' => 'gminy',
				'q' => @$this->request->query['q'],
			),
			'limit' => 10,
		));
				
        foreach ($gminy as $gmina) {
            $output[] = array(
                'id' => $gmina->getId(),
                'nazwa' => $gmina->getData('nazwa'),
                'typ' => $gmina->getData('typ_nazwa'),
            );
        }
        
        $this->set('output', $output);
        $this->set('_serialize', 'output');

    }

    public function gminy()
    {
	    $this->title = 'Gminy w Polsce - Moja Gmina';
        $this->loadDatasetBrowser('gminy');
    }

    public function kody_pocztowe()
    {
	    $this->title = 'Kody pocztowe w Polsce- Moja Gmina';
        $this->loadDatasetBrowser('kody_pocztowe');
    }

    public function miejscowosci()
    {
	    $this->title = 'Miejscowości w Polsce - Moja Gmina';
        $this->loadDatasetBrowser('miejscowosci');
    }

    public function powiaty()
    {
	    $this->title = 'Powiaty w Polsce - Moja Gmina';
        $this->loadDatasetBrowser('powiaty');
    }

    public function wojewodztwa()
    {
	    $this->title = 'Województwa w Polsce - Moja Gmina';
        $this->loadDatasetBrowser('wojewodztwa');
    }

    public function radni()
    {
        $this->loadDatasetBrowser('radni_gmin');
    }
} 