<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MobileSiteHome extends ShikshaMobileWebSite_Controller
{
	public $getTabsContentByCategory = array();

	function __construct()
	{
		parent::__construct();
		$this->load->config('mcommon/mobi_config');
	}

	function renderHomePage($type="india")
	{
		$this->load->library('category_list_client');
		$this->getTabsContentByCategory = $this->category_list_client->getTabsContentByCategory();
		$data = array();
		$data['HomePageData'] = $this->getTabsContentByCategory;
		$data['activelink'] = 'home';
		$data['boomr_pageid'] = "home";
		$this->_loadView($type,$data);
	}

	function showSubCategoriesHome($id)
	{
		$this->load->library('category_list_client');
		$this->getTabsContentByCategory = $this->category_list_client->getTabsContentByCategory();
		$data = array();
		$data['key'] = $id;
		$data['HomePageData'] = $this->getTabsContentByCategory;
		$data['HomePageType'] = 'india';
		$this->load->view('showsubcat',$data);
	}

	function _loadView($type,$data)
	{
		if(!empty($_REQUEST['profile_complete_mailer'])) {
			$this->load->view('mailerProfileComplete');
		}
		else if(!empty($_REQUEST['mailerId']) && !empty($_REQUEST['mailId']) && !empty($_REQUEST['mailReportSpam'])) {
			$this->load->view('mailerReportSpam');
		}
		else if(!empty($_REQUEST['encodedMail']) && !empty($_REQUEST['mailerUnsubscribe'])) {
			$objmailerClient = $this->load->library('mailer/MailerClient');
			$result = $objmailerClient->autoLogin(1,$_REQUEST['encodedMail']);
			setcookie('user',$result,0,'/',COOKIEDOMAIN);			
			$redirectUrl = SHIKSHA_HOME."/userprofile/edit?unscr=5";
			$redirectUrl = Modules::run('mailer/Mailer/processRedirectUrl', $redirectUrl,$result,'');
			$redirectUrl = $redirectUrl['redirectUrl'];
			header( "Location: $redirectUrl" );
			exit();
		}
		else {
			switch ($type) {
				case "india":
					$data['HomePageType'] = 'india';
					storeTempUserData("countriesArray","2,");
					$data['mobile_website_home_page_config_1'] = $this->config->item('mobile_website_home_page_config_1');
					$data['mobile_website_home_page_config_2'] = $this->config->item('mobile_website_home_page_config_2');
					$this->load->view('homepage',$data);
					break;
				case "abroad":
					$data['HomePageType'] = 'abroad';
					$this->load->view('homepageabroad',$data);
					break;
				default:
					$data['HomePageType'] = 'india';
					$this->load->view('homepage',$data);
			}
		}
	}

	function Unsubscribe($encodedMail)
	{
		$this->load->library('mailer/MailerClient');
		$objmailerClient = new MailerClient;
		$objmailerClient->unsubscribe($this->appId,$encodedMail);
		$this->load->view('mailerUnsubscribeResponse');
	}

	function recordMailerReportSpam($mailerId,$mailId)
	{
		$validity = $this->checkUserValidation();
		if(!empty($validity[0]['userid'])) {
			$this->load->model('mailer/mailermodel');
			$this->mailermodel->recordMailerReportSpam($validity[0]['userid'],$mailerId,$mailId);
			$this->load->view('mailerReportSpamResponse');
		}
		else {
			header("Location: ".SHIKSHA_HOME);
			exit();
		}
	}

}
