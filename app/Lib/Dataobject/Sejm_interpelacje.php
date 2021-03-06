<?

namespace MP\Lib;
require_once('DocDataObject.php');

class Sejm_interpelacje extends DocDataObject
{
	
	protected $tiny_label = 'Interpelacja';
	protected $schema = array(
		array('poslowie_str', 'Od'),
		array('adresaci_str', 'Do'),
		array('data_wplywu', 'Data wpływu', 'date')
	);
    protected $routes = array(
        'title' => 'tytul',
        'shortTitle' => 'tytul_skrocony',
        'date' => 'data_wplywu',
    );
	protected $hl_fields = array(
		'poslowie_str', 'adresaci_str'
	);

    public function __construct($params = array())
    {
        parent::__construct($params);

        if ($this->data['poslowie_str'])
            $this->data['poslowie_str'] = preg_replace('/href\=\"\/(.*?)\/([0-9]+)\"/i', 'href="/dane/$1/$2"', $this->data['poslowie_str']);

    }
	
    public function getLabel()
    {
        return '<strong>Interpelacja</strong> nr ' . $this->getData('numer');
    }
    
    public function getHighlightsFields()
    {
	    
	    return array(
	    	'poslowie_str' => array(
	    		'label' => 'Od',
	    		'img' => 'http://resources.sejmometr.pl/mowcy/a/3/' . $this->getData('mowca_id') . '.jpg',
	    	),
	    	'adresaci_str' => 'Do',
	    );
	    	    
    }
    
    public function getMetaDescriptionParts($preset = false)
	{
							
		$output = array(
			dataSlownie( $this->getDate() ),
		);
		
		if( $this->getData('sejm_interpelacje.adresaci_str') )
			$output[] = 'Do: ' . $this->getData('sejm_interpelacje.adresaci_str');
							
		return $output;
		
	}

    public function getUrl() {

        return '/dane/instytucje/3214/interpelacje/' . $this->getId() . ',' . $this->getSlug();

    }

    public function getBreadcrumbs()
    {

        return array(
            array(
                'id' => '/dane/instytucje/3214,sejm-rzeczypospolitej-polskiej/interpelacje',
                'label' => 'Interpelacje',
            ),
        );

    }

}