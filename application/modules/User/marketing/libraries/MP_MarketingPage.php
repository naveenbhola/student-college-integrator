<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 * 
 */

interface IMarketingPage
{
    public function getPageName($str);
    public function getPageType($str);
    public function getLeftPanel($str);
    public function getCommunicationBelowLeftPanel($str);
    public function getRegistrationWidget($str);
    public function getHeading($str);
    public function getCommunicationRegistrationWidget($str);
}

class ManagementMarketingPage implements IMarketingPage
{
    /**
    * @var array store config
    */
    var $result = array();
    var $PAGE_NAME = array();
    var $LEFT_PANEL = array();
    var $TEXT_BELOW_LEFT_PANEL = array();
    var $REGISTRATION_WIDGET_FILE = array();
    var $TEXT_HEADING = array();
    var $TEXT_REGISTRATION_WIDGET = array();
    var $POPUP_URL = array();
    /**
     * @var object The main CodeIgniter object.
     */
    var $CI;
        
    /**
     * Constructor.
     */
    function ManagementMarketingPage() {
        $this->CI =& get_instance();
	$this->reset();
        if (!is_null($this->CI)) {
            // Load main configuration
            $this->CI->config->load('MarketingPage', true);
            $this->PAGE_NAME = $this->CI->config->item('PAGE_NAME', 'MarketingPage');
	    $this->LEFT_PANEL = $this->CI->config->item('LEFT_PANEL', 'MarketingPage');
	    $this->TEXT_BELOW_LEFT_PANEL = $this->CI->config->item('TEXT_BELOW_LEFT_PANEL', 'MarketingPage');
	    $this->REGISTRATION_WIDGET_FILE = $this->CI->config->item('REGISTRATION_WIDGET_FILE', 'MarketingPage');
	    $this->TEXT_HEADING = $this->CI->config->item('TEXT_HEADING', 'MarketingPage');
	    $this->TEXT_REGISTRATION_WIDGET = $this->CI->config->item('TEXT_REGISTRATION_WIDGET', 'MarketingPage');
	    $this->REDIRECTION_URL = $this->CI->config->item('REDIRECTION_URL', 'MarketingPage');
	    $this->POPUP_URL = $this->CI->config->item('POPUP_URL','MarketingPage');
        }
    }
    function reset()
    {
    	$this->PAGE_NAME = array();
	$this->LEFT_PANEL = array();
	$this->TEXT_BELOW_LEFT_PANEL = array();
	$this->REGISTRATION_WIDGET_FILE = array();
	$this->TEXT_HEADING = array();
	$this->TEXT_REGISTRATION_WIDGET = array();
	 $this->REDIRECTION_URL = array();
	$this->POPUP_URL = array();
    }
    public function getPageName($str)
    {
	return $this->PAGE_NAME;
    }
    
    public function getPageType($str)
    {
	return $this->PAGE_NAME[$str];
    }
   
    public function getLeftPanel($str)
    {
	return $this->LEFT_PANEL[$str];
    }
    
    public function getCommunicationBelowLeftPanel($str)
    {
	return $this->TEXT_BELOW_LEFT_PANEL[$str];
    }
    
    public function getRegistrationWidget($str)
    {
	return $this->REGISTRATION_WIDGET_FILE[$str];
    }
   
    public function getHeading($str)
    {
	return $this->TEXT_HEADING[$str];
    }
    
    public function getCommunicationRegistrationWidget($str)
    {
	return $this->TEXT_REGISTRATION_WIDGET[$str];
    }
    public function getRedirectionUrl($str)
    {
	return  $this->REDIRECTION_URL[$str];
    }
    public function getPopupUrl($str)
    {
        return  $this->POPUP_URL[$str];
    }
    public function getAllPageinfo($str)
    {	
	$this->result['PAGE_TYPE'] = $this->getPageType($str);
	$this->result['LEFT_PANEL'] = $this->getLeftPanel($str);
	$this->result['TEXT_BELOW_LEFT_PANEL'] = $this->getCommunicationBelowLeftPanel($str);
	$this->result['REGISTRATION_WIDGET_FILE'] = $this->getRegistrationWidget($str);
	$this->result['TEXT_HEADING'] = $this->getHeading($str);
	$this->result['TEXT_REGISTRATION_WIDGET'] = $this->getCommunicationRegistrationWidget($str);
	$this->result['REDIRECTION_URL'] = $this->getRedirectionUrl($str);
	$this->result['POPUP_URL'] = $this->getPopupUrl($str);
	return $this->result;
    }
}

class MP_MarketingPage
{
    public static function INIT ( $s_class )
    {
	if ( class_exists ( $s_class ) )
	{
	  log_message('debug', "Class $s_class is Loaded.");
	  return new $s_class;
	} else {
	  log_message('debug', "Class $s_class is not Loaded.");
	  throw new Exception( $s_class . ': Requsted class is not exist.');
	}
    }
}

?>
