<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MobileSiteStatic extends ShikshaMobileWebSite_Controller
{
    private $userStatus = 'false';
    function __construct()
    {
        parent::__construct();
        $this->load->config('mcommon/mobi_config');
    }

    function aboutus()
    {
        $data = array();
        $data['validateuser'] = $this->data['m_loggedin_userDetail'];
	$data['canonical_url']  = '<link rel="canonical" href="http://aboutus.shiksha.com/" >';
        //below code used for beacon tracking
        $displayData['trackingpageIdentifier']='aboutUsPage';
        $displayData['trackingcountryId']=2;

        //loading library to use store beacon traffic inforamtion
        $this->tracking=$this->load->library('common/trackingpages');
        $this->tracking->_pagetracking($displayData);
        $this->load->view('aboutUs',$data);
    }

    function viewfullsite()
    {
        setcookie('ci_mobile','',(time() - 864000),'/',COOKIEDOMAIN);
        setcookie('user_force_cookie','YES',0,'/',COOKIEDOMAIN);
        redirect(SHIKSHA_HOME);
    }

    function viewMobileSite()
    {
        setcookie('user_force_cookie','NO',(time() - 864000),'/',COOKIEDOMAIN);
        setcookie('ci_mobile','mobile',0,'/',COOKIEDOMAIN);
        redirect(SHIKSHA_HOME);
    }

    function privacy()
    {
	
        $data = array();
        $data['validateuser'] = $this->data['m_loggedin_userDetail'];	
        $this->load->view('privacyPolicy',$data);
    }

    function contactUs()
    {
        $data = array();
        $data['validateuser'] = $this->data['m_loggedin_userDetail'];
        $this->load->view('contactUs',$data);
    }

    function mobile404()
    {
    
        $data = array();
        $data['validateuser'] = $this->data['m_loggedin_userDetail'];
        $this->load->helper("mobile");
        $this->load->view('mobile4o4page',$data);
    }
    
    function terms()
    {
        $data = array();
        $data['validateuser'] = $this->data['m_loggedin_userDetail'];
        $this->load->view('termsConditions',$data);
    }

    function studentHelpLine()
    {
	$data= array();
	$data['validateuser']= $this->data['m_loggedin_userdetail'];

        //below code used for beacon tracking
        $data['trackingpageIdentifier']='studentHelpLinePage';
        $data['trackingcountryId']=2;

        //loading library to use store beacon traffic inforamtion
        $this->tracking=$this->load->library('common/trackingpages');
        $this->tracking->_pagetracking($data);
	$this->load->view('helpLine',$data);
    }
    
}
