<?

namespace MP\Lib;
require_once('DocDataObject.php');

class Zbiorki_publiczne extends DocDataObject
{
	
	protected $tiny_label = 'Zbiórki publiczne';
	
    protected $routes = array(
        'title' => 'prawo.tytul',
        'shortTitle' => 'prawo.tytul',
    );
	
	public function getShortTitle() {
		return $this->getData('nazwa_zbiorki');
	}
	
	public function getTitle() {
		return $this->getShortTitle();
	}

    public function getTitleAddon() {
        return $this->getData('stan_zbiorki');
    }
	
    public function getLabel() {
        return 'Zbiórki publiczne';
    }
		
}