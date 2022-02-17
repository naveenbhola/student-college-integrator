<?php
/**
 * File for UserRegistrationSource entity
 */ 
namespace user\libraries\DataPopulators;

/**
 * Populator class for UserRegistrationSource entity
 */ 
class UserRegistrationSource extends AbstractPopulator
{
	/**
     * Constructor
     *
     * @param string $mode create|update
     */
    function __construct($mode = 'create')
    {
        parent::__construct($mode);
    }
    
    /**
     * Populate data into UserRegistrationSource entity
     *
     * @param object $registrationSource \user\Entities\UserRegistrationSource
     * @param array $data Data to be populated in
     */
    public function populate(\user\Entities\UserRegistrationSource $registrationSource,$data = array())
    {
        $this->setData($data);
        
        $source = $data['registrationSource'];
        
        $keyQuery = $this->_getKeyQuery($source);
        $keyId = $this->_getKeyId($source);
        $registrationSource->setKeyId($keyId);
        $registrationSource->setKeyQuery($keyQuery);
        $registrationSource->setCoordinates($this->getValue('coordinates'));

        if($data['isFBCall'] == 1){
            $registrationSource->setReferer($this->getValue('referrer',$data['registrationSource']));
        }else{
            $registrationSource->setReferer($this->getValue('referrer',$_SERVER['HTTP_REFERER']));    
        }
        
        $registrationSource->setType('registration');
        $registrationSource->setTime(new \DateTime);
        $registrationSource->setResolution($this->getValue('resolution'));
        $registrationSource->setLandingPage($this->getValue('landingPage'));
        $registrationSource->setBrowser($_SERVER['HTTP_USER_AGENT']);
        $registrationSource->setTrackingKeyid($this->getValue('tracking_keyid'));
        $registrationSource->setVisitorSessionId(getVisitorSessionId());
    }
    
    /**
     * Get page key id from source name
     *
     * @param string $sourceName
     * @return integer
     */ 
    private function _getKeyId($sourceName)
    {
		$query = $this->_getKeyQuery($sourceName);
		$query = $this->dbHandle->query($query);
		$result = $query->row_array();
		return $result['keyid'];
	}
    
    /**
     * Get query to get page key id from source name
     *
     * @param string $sourceName
     * @return string
     */ 
    private function _getKeyQuery($sourceName)
    {
        return "SELECT keyid FROM tkeyTable WHERE keyname = ".$this->dbHandle->escape($sourceName);
    }
}