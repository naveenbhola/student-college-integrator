<?php

/*

   Copyright 2007 Info Edge India Ltd

   $Rev::               $:  Revision of last commit
   $Author: manishz $:  Author of last commit
   $Date: 2010-01-06 07:38:53 $:  Date of last commit

   alert_feed_client.php makes call to server's using XML RPC calls.

   $Id: alert_feed_client.php,v 1.14 2010-01-06 07:38:53 manishz Exp $: 

 */
class Alert_feed_client extends MX_Controller {


	function init(){
		$this->load->helper('url');
		$this->load->library('xmlrpc');
		
		$this->load->library('alertconfig'); 
	}

	function getEventsFeeds($startDate,$endDate){
		$this->init();
		$server_url=site_url('/events/event_cal_server');	
		$this->xmlrpc->server($server_url, 80);	
		$this->xmlrpc->method('getEventsFeeds');
		$request = array($startDate,$endDate); 
		$this->xmlrpc->request($request);
		if ( ! $this->xmlrpc->send_request()){
			echo $this->xmlrpc->display_error();
		}else{
			$appID=1;
			$response= $this->xmlrpc->display_response();
			$dbConfig = array( 'hostname'=>'localhost');
			$this->alertconfig->getDbConfig($appID,$dbConfig);	
			$dbHandle = $this->load->database($dbConfig,TRUE);
			if($dbHandle == ''){
				log_message('error','EventFeed can not create db handle');
			}
			foreach($response as $row){
				$dbHandle->insert('alertEventTable', $row); 
			}
		}
	}

	function getBlogsFeeds($startDate,$endDate){
		$this->init();
		$server_url=site_url('/blogs/blog_server');	
		$this->xmlrpc->server($server_url, 80);	
		$this->xmlrpc->method('getBlogsFeeds');
		$request = array($startDate,$endDate); 
		$this->xmlrpc->request($request);
		if ( ! $this->xmlrpc->send_request()){
			echo $this->xmlrpc->display_error();
		}else{
			$appID=1;
			$response= $this->xmlrpc->display_response();
			$dbConfig = array( 'hostname'=>'localhost');
			$this->alertconfig->getDbConfig($appID,$dbConfig);	
			$dbHandle = $this->load->database($dbConfig,TRUE);
			if($dbHandle == ''){
				log_message('error','BlogsFeed can not create db handle');
			}
			foreach($response as $row){
				$dbHandle->insert('alertBlogTable', $row); 
			}
		}
	}

	function getMessageBoardFeeds($startDate,$endDate){
		$this->init();
		$server_url=site_url('/messageBoard/message_board_server');
		$this->xmlrpc->server($server_url, 80);	
		$this->xmlrpc->method('getMessageBoardFeeds');
		$request = array($startDate,$endDate); 
		$this->xmlrpc->request($request);
		if ( ! $this->xmlrpc->send_request()){
			echo $this->xmlrpc->display_error();
		}else{
			$appID=1;
			$response= $this->xmlrpc->display_response();
			$dbConfig = array( 'hostname'=>'localhost');
			$this->alertconfig->getDbConfig($appID,$dbConfig);	
			$dbHandle = $this->load->database($dbConfig,TRUE);
			if($dbHandle == ''){
				log_message('error','getMessageBoardFeeds can not create db handle');
			}
			foreach($response as $row){
				$dbHandle->insert('alertMessageBoardTable', $row); 				
			}
		}
	}

	function getMessageBoardCommentFeeds($startDate,$endDate){
		$this->init();
		$server_url=site_url('/messageBoard/message_board_server');
		$this->xmlrpc->server($server_url, 80);	
		$this->xmlrpc->method('getMessageBoardCommentFeeds');
		$request = array($startDate,$endDate); 
		$this->xmlrpc->request($request);
		if ( ! $this->xmlrpc->send_request()){
			echo $this->xmlrpc->display_error();
		}else{
			$appID=1;
			$response= $this->xmlrpc->display_response();
			$dbConfig = array( 'hostname'=>'localhost');
			$this->alertconfig->getDbConfig($appID,$dbConfig);	
			$dbHandle = $this->load->database($dbConfig,TRUE);
			if($dbHandle == ''){
				log_message('error','getMessageBoardCommentFeeds can not create db handle');
			}
			foreach($response as $row){
				$dbHandle->insert('alertMessageBoardCommentTable', $row); 				
			}
		}
	}

	function getRatingFeeds($startDate,$endDate){
		$this->init();
		/*$server_url=site_url('/rating/review_server');
		$this->xmlrpc->server($server_url, 80);	
		$this->xmlrpc->method('getRatingFeeds');
		$request = array($startDate,$endDate); 
		$this->xmlrpc->request($request);
		if ( ! $this->xmlrpc->send_request()){
			echo $this->xmlrpc->display_error();
		}else{
			$appID=1;
			$response= $this->xmlrpc->display_response();
			$dbConfig = array( 'hostname'=>'localhost');
			$this->alertconfig->getDbConfig($appID,$dbConfig);	
			$dbHandle = $this->load->database($dbConfig,TRUE);
			if($dbHandle == ''){
				log_message('error','getRatingFeeds can not create db handle');
			}
			foreach($response as $row){
				$dbHandle->insert('alertRatingTable', $row); 
			}
		}*/
	}


	function getCategoryFeeds($startDate,$endDate){
		$this->init();
		$server_url=site_url('/categoryList/Category_list_server');
		$this->xmlrpc->server($server_url, 80);	
		$this->xmlrpc->method('getCategoryFeeds');
		$request = array($startDate,$endDate); 
		$this->xmlrpc->request($request);
		if ( ! $this->xmlrpc->send_request()){
			echo $this->xmlrpc->display_error();
		}else{
			$appID=1;
			$response= $this->xmlrpc->display_response();
			$dbConfig = array( 'hostname'=>'localhost');
			$this->alertconfig->getDbConfig($appID,$dbConfig);	
			$dbHandle = $this->load->database($dbConfig,TRUE);
			if($dbHandle == ''){
				log_message('error','getCategoryFeeds can not create db handle');
			}
			foreach($response as $row){
				$dbHandle->insert('alertCategoryTable', $row); 
			}
		}	

	}
	function getSaveSearchFeeds($startDate,$endDate){
		$this->init();
		$server_url=site_url('/search/searchCI');
		$this->xmlrpc->server($server_url, 80);
		$this->xmlrpc->method('getSaveSearchFeeds');
		$request = array($startDate,$endDate);
		$this->xmlrpc->request($request);

		$this->alertconfig->getDbConfig($appID,$dbConfig);
		$dbHandle = $this->load->database($dbConfig,TRUE);
		if($dbHandle == ''){
			log_message('error','getMessageBoardFeeds can not create db handle');
		}
		error_log_shiksha("inside savese".print_r($request,true));
		if ( ! $this->xmlrpc->send_request()){
			echo $this->xmlrpc->display_error();
		}else{
			$appID=1;
			$response= $this->xmlrpc->display_response();
			$dbConfig = array( 'hostname'=>'localhost');
			foreach($response as $resp){
				foreach($resp['results'] as $results){
					$data=array('keyword'=>$resp['keyword'],'location'=>$resp['location'],'type_id'=>$results['typeId'],'type'=>$results['type'],'url'=>$results['url'],'title'=>$results['title'],'content'=>$results['shortContent']);
					$queryCmd=$dbHandle->insert_string('alertSaveSearchTable', $data);
					error_log_shiksha("alertSaveSearchTable query string is".$queryCmd);
					$query=$dbHandle->query($queryCmd);
				}
			}
		}
	}
	function temp(){
		error_log_shiksha("inside temp");
		$startDate=date('Y-m-d H:i:s', mktime(0, 0, 0, date('m')-1, 1, date('Y')));
		$endDate=date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('t'), date('Y')));
		$this->getSaveSearchFeeds($startDate,$endDate);
	}
	function cleanStorageArea(){
		$this->init();
		$appID=1;
		$dbConfig = array( 'hostname'=>'localhost');
		$this->alertconfig->getDbConfig($appID,$dbConfig);	
		$dbHandle = $this->load->database($dbConfig,TRUE);
		if($dbHandle == ''){
			log_message('error','cleanStorageArea can not create db handle');
		}
		$dbHandle->query("delete from alertMessageBoardTable where 1=1"); 
		$dbHandle->query("delete from alertMessageBoardCommentTable where 1=1"); 
		$dbHandle->query("delete from alertBlogTable where 1=1"); 
		$dbHandle->query("delete from alertEventTable where 1=1"); 
		$dbHandle->query("delete from alertRatingTable where 1=1"); 
		$dbHandle->query("delete from alertCategoryTable where 1=1"); 
		$dbHandle->query("delete from alertSaveSearchTable where 1=1");
	}

	function index($freq){
		if($freq=='daily'){
			//assuming the cron is run before EOD today
			$startDate=date('Y-m-d H:i:s', mktime(0,0,0));
			$endDate=date('Y-m-d H:i:s', mktime(23,59,59));
		}else if($freq=='weekly'){
			//assuming the cron is run before the end of current week....
			$startDate=date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d')-date('w'), date('Y')));
			$endDate=date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), date('d')+date('w'), date('Y')));
		}else if($freq=='monthly'){
			//assuming the cron is run before the end of current month....
			$startDate=date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), 1, date('Y')));
			$endDate=date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), date('t'), date('Y')));
		}
		
		//get the latest feed of category board table
		$this->cleanStorageArea();
		$this->getCategoryFeeds($startDate,$endDate);

		//get the feeds from various sources XXX should be threaded as well
		$this->getEventsFeeds($startDate,$endDate);
		$this->getMessageBoardFeeds($startDate,$endDate);
		$this->getMessageBoardCommentFeeds($startDate,$endDate);
		$this->getSaveSearchFeeds($startDate,$endDate);
		/*
			$this->getListingFeeds();
			$this->getRatingFeeds($startDate,$endDate);
			$this->getBlogsFeeds($startDate,$endDate);
		*/
	}

	function indexFinal($freq){
		if($freq=='daily'){
			//assuming the cron is run before EOD today
			$startDate=date('Y-m-d H:i:s',  mktime(0,0,0,date('m'),date('d')-1,date('Y')));
			$endDate=date('Y-m-d H:i:s',  mktime(23,59,59,date('m'),date('d')-1,date('Y')));
		}else if($freq=='weekly'){
			//assuming the cron is run before the end of current week....
			$startDate=date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d')-7, date('Y')));
			$endDate=date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), date('d')-1, date('Y')));
		}else if($freq=='monthly'){
			//assuming the cron is run before the end of current month....
			$startDate=date('Y-m-d H:i:s', mktime(0, 0, 0, date('m')-1, 1, date('Y')));
			$endDate=date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), date('d')-date('d'), date('Y')));
		}	

		//get the latest feed of category board table
		$this->cleanStorageArea();

		//get the feeds from various sources XXX should be threaded as well
		$this->getEventsFeeds($startDate,$endDate);
		$this->getMessageBoardFeeds($startDate,$endDate);
		$this->getMessageBoardCommentFeeds($startDate,$endDate);
		$this->getSaveSearchFeeds($startDate,$endDate);
		/*
			$this->getListingFeeds();
			$this->getBlogsFeeds($startDate,$endDate);
			$this->getRatingFeeds($startDate,$endDate);
		*/
	}

}
?>
