<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MobileSiteStatic extends ShikshaMobileWebSite_Controller
{
    private $userStatus = 'false';
    function __construct()
    {
        parent::__construct();
        $this->load->config('mcommon5/mobi_config');
	    $this->load->helper('mcommon/mobile');
    }

    function aboutus()
    {
        $data = array();
        $data['validateuser'] = $this->data['m_loggedin_userDetail'];
    	$data['canonical_url']  = '<link rel="canonical" href="http://aboutus.shiksha.com/" >';
    	$data['boomr_pageid'] = 'aboutUs';
        //below code used for beacon tracking
        $data['trackingpageIdentifier']='aboutUsPage';
        $data['trackingcountryId']=2;

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_AboutUs');
        $data['dfpData']  = $dfpObj->getDFPData($this->data['m_loggedin_userDetail'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');

        //loading library to use store beacon traffic inforamtion
        $this->tracking=$this->load->library('common/trackingpages');
        $this->tracking->_pagetracking($data);

        $this->load->view('aboutUs',$data);
    }

    function viewfullsite()
    {
        setcookie('ci_mobile','',(time() - 864000),'/',COOKIEDOMAIN);
        setcookie('user_force_cookie','YES',time() + 2592000,'/',COOKIEDOMAIN);
        redirect(SHIKSHA_HOME);
    }

    function viewMobileSite()
    {
        setcookie('user_force_cookie','NO',(time() - 864000),'/',COOKIEDOMAIN);
        setcookie('ci_mobile','',(time() - 2592000),'/',COOKIEDOMAIN);
        redirect(SHIKSHA_HOME);
    }

    function privacy()
    {
	
        $data = array();
        $data['validateuser'] = $this->data['m_loggedin_userDetail'];   
        //for tracking purpose
        $data['trackingpageIdentifier']='privacyPolicyPage';
        $data['trackingcountryId']=2;
        //below line is used for storing information in beacon variable for tracking purpose
        $this->tracking=$this->load->library('common/trackingpages');
        $this->tracking->_pagetracking($data);

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_PrivacyPolicy');
        $data['dfpData']  = $dfpObj->getDFPData($this->data['m_loggedin_userDetail'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');

        $this->load->view('privacyPolicy',$data);
    }

    // function for cookie_banner

    function cookie()
    {
        $data = array();
        $data['validateuser'] = $this->data['m_loggedin_userDetail'];   

        //for tracking purpose
        $data['trackingpageIdentifier']='cookiePolicyPage';
        $data['trackingcountryId']=2;
        //below line is used for storing information in beacon variable for tracking purpose
        $this->tracking=$this->load->library('common/trackingpages');
        $this->tracking->_pagetracking($data);

        $this->load->view('cookiePolicy',$data);
    }

    function contactUs()
    {
	
        $data = array();
        $data['validateuser'] = $this->data['m_loggedin_userDetail'];
        //for tracking purpose
        $data['trackingpageIdentifier']='contactUsPage';
        $data['trackingcountryId']=2;
        //below line is used for storing information in beacon variable for tracking purpose
        $this->tracking=$this->load->library('common/trackingpages');
        $this->tracking->_pagetracking($data);
        $data['GA_userLevel'] = $this->data['m_loggedin_userDetail'][0]['userid'] > 0 ? 'Logged In' : 'Non-Logged In';

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_ContactUs');
        $data['dfpData']  = $dfpObj->getDFPData($this->data['m_loggedin_userDetail'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');

        $this->load->view('contactUs',$data);
    }

    function studentHelpLine()
    {
        $data= array();
        $data['validateuser'] = $this->data['m_loggedin_userdetail'];
        //below code used for beacon tracking
        $data['trackingpageIdentifier']='studentHelpLinePage';
        $data['trackingcountryId']=2;

        //loading library to use store beacon traffic inforamtion
        $this->tracking=$this->load->library('common/trackingpages');
        $this->tracking->_pagetracking($data);
        $data['GA_userLevel'] = $this->data['m_loggedin_userDetail'][0]['userid'] > 0 ? 'Logged In' : 'Non-Logged In';
        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_StudentHelpLine');
        $data['dfpData']  = $dfpObj->getDFPData($this->data['m_loggedin_userDetail'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');

	$this->load->view('helpLine',$data);
    }
    
    function terms() 
    {
      $data=array();
      $data['validateuser']=$this->data['m_loggedin_userdetail'];	 
      //below code used for beacon tracking
        $data['trackingpageIdentifier']='termsConditionsPage';
        $data['trackingcountryId']=2;

        //loading library to use store beacon traffic inforamtion
        $this->tracking=$this->load->library('common/trackingpages');
        $this->tracking->_pagetracking($data);

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_Terms');
        $data['dfpData']  = $dfpObj->getDFPData($this->data['m_loggedin_userDetail'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');

      $this->load->view('termsConditions',$data);
    }	

    function mobile404()
    {
    
        $data = array();
        $data['validateuser'] = $this->data['m_loggedin_userDetail'];
        $this->load->helper("mobile");
        $this->load->view('mobile4o4page',$data);
    }
    
    function kumkumProfile()
    {
	
        $data = array();
        $data['validateuser'] = $this->data['m_loggedin_userDetail'];
        $this->load->view('kumkumprofile',$data);
    }
    
    function loadHelpPagesOfApp($contentType,$siteIdentifier="mobileApp"){
	
	    //load static page view
        $displayData['siteIdentifier'] = $siteIdentifier;
        $displayData['boomr_pageid']= $contentType;

	    if($contentType=='communityGuideline'){
            //below code used for beacon tracking
            $displayData['trackingpageIdentifier']='userCommunityGuideLinePage';
            $displayData['trackingcountryId']=2;

            //loading library to use store beacon traffic inforamtion
            $this->tracking=$this->load->library('common/trackingpages');
            $this->tracking->_pagetracking($displayData);

		    $this->load->view('mcommon5/appCommunityGuidelines',$displayData);
	    }else if($contentType=='userPointSystem'){
            //below code used for beacon tracking
            $displayData['trackingpageIdentifier']='userPointSystemPage';
            $displayData['trackingcountryId']=2;

            //loading library to use store beacon traffic inforamtion
            $this->tracking=$this->load->library('common/trackingpages');
            $this->tracking->_pagetracking($displayData);
		    $this->load->view('mcommon5/userPointSystemStaticPage',$displayData);
	    }else if($contentType=='aboutUs'){
		    $this->load->view('mcommon5/aboutUsApp',$displayData);
	    }
	    
    }

}
