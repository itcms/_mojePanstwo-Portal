<?php

class SejmometrController extends SejmometrAppController
{
	
	public $helpers = array('Dane.dataobjectsSlider', 'Dane.Dataobject');
	public $components = array('RequestHandler');
	
    public function index()
    {
		
		$API = $this->API->Dane();
		
		
		
		// OSTATNIE POSIEDZENIE
		
		$posiedzenie = $API->searchDataset('sejm_posiedzenia', array(
			'order' => 'data_stop desc',
			'limit' => 1,
		));
		
		$posiedzenie = $API->getObjects();
		$posiedzenie = $posiedzenie[0];
		$related = $posiedzenie->loadRelated();
				
		$this->set('posiedzenie', $posiedzenie);
		

    }
    
    public function posiedzenia_timeline()
    {
	    
	    $output = array(
            'timeline' => array(
                'headline' => 'Posiedzenia Sejmu RP',
                'type' => 'default',
                'date' => array(),
            ),
        );
        
        
	    $API = $this->API->Dane();
		$API->searchDataset('sejm_posiedzenia', array(
			'order' => 'data_stop desc',
			'limit' => 100,
		));
		
		foreach( $API->getObjects() as $object )
		{
	    		    	
	    	$startDate = $object->getData('data_start');
            $dateParts = explode('-', $startDate);
            $startDate = $dateParts[0] . ',' . $dateParts[1] . ',' . $dateParts[2];
            
            $stopDate = $object->getData('data_stop');
            $dateParts = explode('-', $stopDate);
            $stopDate = $dateParts[0] . ',' . $dateParts[1] . ',' . $dateParts[2];
						
	        $output['timeline']['date'][] = array(
	            'startDate' => $startDate,
	            'endDate' => $stopDate,
	            'headline' => '#' . $object->getData('numer'),
	            'text' => '<p>Poseł wziął udział w debacie Sejmowej</p>',
	            'classname' => 'klasa',
	            'asset' => array(
	                'media' => 'http://resources.sejmometr.pl/sejm_komunikaty/img/' . $object->getData('komunikat_id') . '-0.jpg',
	                'thumbnail' => 'http://resources.sejmometr.pl/sejm_komunikaty/img/' . $object->getData('komunikat_id') . '-1.jpg',
	                'credit' => '® Kancelaria Sejmu',
	            ),
	        );
        
        }
        
        $this->set('data', $output);
        $this->set('_serialize', 'data');
	    
    }

}