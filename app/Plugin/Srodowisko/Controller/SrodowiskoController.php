<?php

App::uses('ApplicationsController', 'Controller');

class SrodowiskoController extends ApplicationsController
{

	public $components = array('RequestHandler');
	
    public $settings = array(
        'id' => 'srodowisko',
        'title' => 'Środowisko',
    );    
	
	public $params = array(
		'C6H6' => 'Benzen', 
		'CO' => 'Czad', 
		'NO2' => 'Dwutlenek azotu', 
		'O3' => 'Ozon', 
		'PM10' => 'Pył PM10', 
		'PM2.5' => 'Pył PM2.5',
		'SO2' => 'Dwutlenek siarki',
	);
	
	public function dane()
	{
		
		$data = array();
		
		if(
			isset( $this->request->query['param'] ) && 
			in_array($this->request->query['param'], array_keys($this->params))
		) {
		
			$data = $this->Srodowisko->getData( $this->request->query['param'] );
		
		}
		
		$this->set('data', $data);
		$this->set('_serialize', 'data');
		
	}

	public function chart()
	{
		$this->set('data', $this->Srodowisko->getChartData($this->request->query));
		$this->set('_serialize', 'data');
	}
	
    public function view()
    {

        $datasets = $this->getDatasets('srodowisko');

        $options = array(
            'searchTitle' => 'Szukaj stacji pomiarowych...',
            'autocompletion' => array(
                'dataset' => implode(',', array_keys($datasets)),
            ),
            'conditions' => array(
                'dataset' => array_keys($datasets)
            ),
            'cover' => array(
                'view' => array(
                    'plugin' => 'Srodowisko',
                    'element' => 'cover',
                ),
                'aggs' => array(
	            	'stacje' => array(
		            	'filter' => array(
			            	'term' => array(
				            	'dataset' => 'srodowisko_stacje_pomiarowe',
			            	),
		            	),
		            	'scope' => 'global',
		            	'aggs' => array(
			            	'top' => array(
				            	'top_hits' => array(
					            	'size' => 500,
					            	'_source' => array('data'),
				            	),
			            	),
		            	),
	            	),
                ),
            ),
            'aggs' => array(
                'dataset' => array(
                    'terms' => array(
                        'field' => 'dataset',
                    ),
                    'visual' => array(
                        'skin' => 'datasets',
                        'class' => 'special',
                        'field' => 'dataset',
                        'dictionary' => $datasets,
                    ),
                ),
            ),
            'apps' => true,
        );

        $this->Components->load('Dane.DataBrowser', $options);
        $this->render('Dane.Elements/DataBrowser/browser-from-app');

    }
    
    public function stacje_pomiarowe()
    {
	    $this->title = 'Stacje pomiarowe | Środowisko';
        $this->loadDatasetBrowser('srodowisko_stacje_pomiarowe', array());
    }
    
    public function getChapters() {
		$items = array();
		foreach($this->params as $short => $name) {
			$items[] = array(
				'id' => 'id',
				'label' => $name,
				'href' => '#' . $short,
				'icon' => 'dot',
			);
		}
		
		return array(
			'items' => $items
		);
	    
    }

}