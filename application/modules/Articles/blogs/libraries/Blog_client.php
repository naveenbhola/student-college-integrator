<?php

/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: ashishj $:  Author of last commit
$Date: 2010-06-23 09:26:47 $:  Date of last commit

message_board_client.php makes call to server using XML RPC calls.

$Id: Blog_client.php,v 1.49 2010-06-23 09:26:47 ashishj Exp $: 

*/
class Blog_client
{
	var $CI = '';   
	var $cacheLib;

    function init($what='read')
    {
        $this->CI =& get_instance();
        $this->CI->load->helper('url');
        $this->CI->load->library('xmlrpc');
        $this->CI->load->library('cacheLib');
        $this->cacheLib = new cacheLib();
        $server_url =  BLOG_SERVER;
	$server_port = BLOG_SERVER_PORT;
	if($what=='write'){
	    $server_url = BLOG_WRITE_SERVER;
	    $server_port = BLOG_WRITE_SERVER_PORT;
	}
        error_log($server_url.'checkTitle');
        $this->CI->xmlrpc->set_debug(0);        
        $this->CI->xmlrpc->server($server_url,$server_port);
    }
	
	function initSearch()
	{
		$this->CI =& get_instance();
		$this->CI->load->helper('url');
		$this->CI->load->library('xmlrpc');
		$server_url = BLOG_SEARCH_SERVER;		
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url, BLOG_SEARCH_SERVER_PORT);	
	}

	/*
	The msgbrdid is same as categoryid.
	*/
	function getBlogsForHomePages($AppId,$catId,$countryId,$start,$rows,$keyValue,$parentId,$cache = 1) {
		$this->init();
        	$key=md5('getBlogsForHomePages'.$AppId.$catId.$countryId.$start.$rows);
        	if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
                	$this->CI->xmlrpc->method('getBlogsForHomePages');
                	$request = array (
                        	array($AppId, 'string'),
	                        array($catId, 'string'),
	                        array($start, 'int'),
        	                array($rows, 'int'),
                	        array($countryId, 'string'),
	                        array($keyValue, 'string'),
	                        array($parentId, 'string'),
	                        array($cache, 'int'),
        	                'struct'
                	);
	                $this->CI->xmlrpc->request($request);

                	if ( ! $this->CI->xmlrpc->send_request()){
                        	return  $this->CI->xmlrpc->display_error();
	                }
        	        else{
                	        $response=$this->CI->xmlrpc->display_response();
                            if($cache == 1)
                            {
                                $this->cacheLib->store($key,$response);
                            }
	                        return $response;
        	        }
	        }else{
        	        return  $this->cacheLib->get($key);
	        } 
	} 
	
	function getBlogsForStudyAbroad($AppId,$catId,$countryId,$start,$rows,$keyValue,$parentId,$cache = 1) {
		$this->init();
        	$key=md5('getBlogsForStudyAbroad'.$AppId.$catId.$countryId.$start.$rows);
        	if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
                	$this->CI->xmlrpc->method('getBlogsForStudyAbroad');
                	$request = array (
                        	array($AppId, 'string'),
	                        array($catId, 'string'),
	                        array($start, 'int'),
        	                array($rows, 'int'),
                	        array($countryId, 'string'),
	                        array($keyValue, 'string'),
	                        array($parentId, 'string'),
	                        array($cache, 'int'),
        	                'struct'
                	);
	                $this->CI->xmlrpc->request($request);

                	if ( ! $this->CI->xmlrpc->send_request()){
                        	return  $this->CI->xmlrpc->display_error();
	                }
        	        else{
                	        $response=$this->CI->xmlrpc->display_response();
                            if($cache == 1)
                            {
                                $this->cacheLib->store($key,$response);
                            }
	                        return $response;
        	        }
	        }else{
        	        return  $this->cacheLib->get($key);
	        } 
	}

	function createBlog($AppId,$reqArray)
	{ 
		$this->init('write');
		$this->CI->xmlrpc->method('createBlog');
		$request = array (
		array($AppId, 'string'),
		array(base64_encode(json_encode($reqArray)), 'string')
		);
		$this->CI->xmlrpc->request($request);	
		if ( ! $this->CI->xmlrpc->send_request()){
			$response = $this->CI->xmlrpc->display_error();
			return  $response;
		} else {
			$blogCreationResult = $this->CI->xmlrpc->display_response();
			$blogId = $blogCreationResult['blogId'];
			return $blogId;
		} 
	}
	
	function checkTitle($AppId,$title)
	{
		$this->init();
		$this->CI->xmlrpc->method('checkTitle');	
		$request = array (
		array($AppId, 'string'),
		array($title, 'string'),
		'struct'			
		);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
		 	return  $this->CI->xmlrpc->display_error();
		}
		else
		{
		 	return $this->CI->xmlrpc->display_response();
		} 			
	}
	
	function getBlogTitleComplete($AppId,$partialTitle)
	{ 
		$this->init();
		$this->CI->xmlrpc->method('getBlogTitleComplete');	
		$request = array (
		array($AppId, 'int'),
		array($partialTitle, 'string'),
		'struct'			
		);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
		 	return  $this->CI->xmlrpc->display_error();
		}
		else
		{
		 	return $this->CI->xmlrpc->display_response();
		} 			
	}
	
	
	function indexIt($AppId,$request)
	{
		modules::run('search/Indexer/addToQueue', $request['Id'], 'article');
		return true;
		//$this->initSearch();
		//$this->CI->xmlrpc->method('indexListingRecord');
		//
		//$request = array(array($AppId,'int'), utility_encodeXmlRpcResponse($request));
		//$this->CI->xmlrpc->request($request);
		//if ( ! $this->CI->xmlrpc->send_request()) {
		//	$response = $this->CI->xmlrpc->display_error();
		//	return $response;
		//} else {
		//	$reponse = $this->CI->xmlrpc->display_response();
		//	return $reponse;
		//}
	}
	
	
	function getRecentPostedBlogs($AppId,$catId,$start,$rows,$countryId)
	{ 
		$this->init();
		$this->CI->xmlrpc->method('getRecentPostedBlogs');	
		$request = array (
		array($AppId, 'string'),
		array($catId, 'int'),		
		array($start, 'int'),	
		array($rows, 'int'),
		array($countryId, 'int'),		
		'struct'			
		);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
		 return  $this->CI->xmlrpc->display_error();
		}
		else
		{
		 return $this->CI->xmlrpc->display_response();
		} 			
	}
	
	function getMyBlogs($AppId,$catId,$start,$rows,$countryId,$userId)
	{ 
		$this->init();
		$this->CI->xmlrpc->method('getMyBlogs');	
		$request = array (
		array($AppId, 'string'),
		array($catId, 'int'),		
		array($start, 'int'),	
		array($rows, 'int'),
		array($countryId, 'int'),
		array($userId, 'int'),		
		'struct'			
		);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
		 return  $this->CI->xmlrpc->display_error();
		}
		else
		{
		 return $this->CI->xmlrpc->display_response();
		} 			
	}
	
	function getRelatedBlogs($AppId,$catId,$start,$rows,$countryId,$excludeBlogId = 0)
	{
		$this->init();
		$key=md5('getRelatedBlogs'.$AppId.$catId.$start.$rows.$countryId.$excludeBlogId);
                if($this->cacheLib->get($key)=='ERROR_READING_CACHE' ){
	                $this->CI->xmlrpc->method('getRelatedBlogs');
        	        $request1 = array (
                           array($AppId, 'string'),
                           array($catId, 'int'),
                           array($start, 'int'),
                           array($rows, 'int'),
                           array($countryId, 'int'),
        	        		array($excludeBlogId, 'int'),
                                );
                	$this->CI->xmlrpc->request($request1);

	                if ( ! $this->CI->xmlrpc->send_request())
        	        {
                		  return $this->CI->xmlrpc->display_error();
	                }
        	        else
                	{
		                  $blogs = $this->CI->xmlrpc->display_response();
                		  $this->cacheLib->store($key,$blogs,3600);
		                  return $blogs;
                	}
                }else{
                        return  $this->cacheLib->get($key);
                }
	}	
	
	function getMyBlogCount($AppId,$catId,$countryId,$userId)
	{ 
		$this->init();
		$this->CI->xmlrpc->method('getMyBlogCount');	
		$request = array (
		array($AppId, 'string'),
		array($catId, 'int'),
		array($countryId, 'int'),
		array($userId, 'int'),	
		'struct'			
		);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
		 return  $this->CI->xmlrpc->display_error();
		}
		else
		{
		 return $this->CI->xmlrpc->display_response();
		} 			
	}
	
	
	function getBlogCountForBoards($AppId,$countryId)
	{ 
		$this->init();
		$this->CI->xmlrpc->method('getBlogCountForBoards');	
		$request = array (
		array($AppId, 'string'),
		array($countryId, 'int'),		
		'struct'			
		);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
		 return  $this->CI->xmlrpc->display_error();
		}
		else
		{
		 return $this->CI->xmlrpc->display_response();
		} 			
	}


	function getMostContributingBloggers($AppId,$rows)
	{
		$this->init();
		$this->CI->xmlrpc->method('getMostContributingBloggers');
		$request = array (array($AppId, 'string'),
				  array($rows, 'int'));	 
		$this->CI->xmlrpc->request($request);	
			
		if ( ! $this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else	
		{
			return $this->CI->xmlrpc->display_response();
		}
	}	
	
	function getBlogInfo($AppId,$blogId, $pageNum='' ,$status='')
	{
		$this->init();
		$this->CI->xmlrpc->method('getBlog');
		$request = array ($appId, $blogId, $pageNum, $status);	 
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
		  	return $this->CI->xmlrpc->display_error();
		}
		else
		{
            $response = $this->CI->xmlrpc->display_response();
                $response = json_decode(base64_decode($response), true);
                
		  	return $response;
            }
	}	
	
	function getUserBlogs($AppId,$userId,$start,$rows)
	{
		$this->init();
		$this->CI->xmlrpc->method('getUserBlogs');
		$request = array (
		   	array($AppId, 'string'),
		        array($userId, 'int'),
			array($start, 'int'),
			array($rows, 'int'),
			'struct'
		        );	 
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
		  	return $this->CI->xmlrpc->display_error();
		}
		else
		{
		  	return $this->CI->xmlrpc->display_response();
		}	
	}
	

	function getUserInfo($AppId,$type,$param)
	{
		$this->init();
		$request = array (
		array('How is it going?', 'string'),
		array($type, 'string'),
		array($param, 'string'));
		$this->CI->xmlrpc->method('getUserInfo'); 
		
		
		$this->CI->xmlrpc->request($request);	
		 
		if ( ! $this->CI->xmlrpc->send_request())
		{
		  	return $this->CI->xmlrpc->display_error();
		}
		else
		{
		  	return $this->CI->xmlrpc->display_response();
		} 
	}

	
	
	function getArchives($AppId )
	{
		$this->init();
		$this->CI->xmlrpc->method('getArchives');
		$request = array (array('How is it going?', 'string'));	 
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
		  return $this->CI->xmlrpc->display_error();
		}
		else
		{
		  return $this->CI->xmlrpc->display_response();
		} 
		
	}	

	function getPopularBlogsForCMS($AppId,$catId,$start,$rows,$countryId,$searchType,$searchText,$postedBy,$orderType,$userId,$articleType='none',$articleStatus){ 
		$this->init();
		$this->CI->xmlrpc->method('getPopularBlogsForCMS');	
		$request = array (
			array($AppId, 'string'),
			array($catId, 'int'),				
			array($start, 'int'),	
			array($rows, 'int'),	
			array($countryId, 'int'),
			array($searchType, 'string'),
			array($searchText, 'string'),
			array($postedBy, 'string'),
			array($orderType, 'string'),
			array($userId, 'int'),
			array($articleType, 'string'),
			array($articleStatus, 'string'),
			'struct'
			);
		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
		 	return  $this->CI->xmlrpc->display_error();
		}
		else
		{
			 return $this->CI->xmlrpc->display_response();
		} 			
    	}
    	

	function getBlogForIndex($appId,$threadId){
        	$this->init();
        	$this->CI->xmlrpc->method('getBlogForIndex');
        	$request = array ($appId,$threadId);
        	$this->CI->xmlrpc->request($request);
        	if ( ! $this->CI->xmlrpc->send_request()){
	            return $this->CI->xmlrpc->display_error();
        	}
	        else{
	            return $this->CI->xmlrpc->display_response();
        	}
    	}
    
	//get Chapter Articles
	function getChapterArticles($appID,$chapterNumber,$chapterName,$bookName) {
		$this->init();
        	$this->CI->xmlrpc->method('sGetChapterArticles');
        	$request = array ($appID,$chapterNumber,$chapterName,$bookName);
        	$this->CI->xmlrpc->request($request);
       		if ( ! $this->CI->xmlrpc->send_request()){
            		return $this->CI->xmlrpc->display_error();
        	}
        	else{
            		return $this->CI->xmlrpc->display_response();
        	}
	}
	
	//update blog
	function updateBlog($AppId,$reqArray){ 
		$this->init('write');
		$this->CI->xmlrpc->method('sUpdateBlog');
		$request = array (
		array($AppId, 'string'),
		array(base64_encode(json_encode($reqArray)), 'string')
		);
		$this->CI->xmlrpc->request($request);	
		if ( ! $this->CI->xmlrpc->send_request()){
			$response = $this->CI->xmlrpc->display_error();
			return  $response;
		} else {
			$blogCreationResult = $this->CI->xmlrpc->display_response();
			$blogId = $blogCreationResult['blogId'];
		/*	$blogdesc = $Desc;
			$selectedCategory = $catgory;
			$country = $countryId;
			$AuthorName = "Kumkum";
			$request=array('countryList' => $country,'categoryList' => $selectedCategory,'Id' => $blogId,'title' => $blogTitle,'type' => 'blog','packtype' =>'-5','content'=>"",'authorName'=>$AuthorName);
			$indexResult = $this->indexIt($AppId,$request);*/
			return $blogId;
		} 
	}

	//Get Blogs for home page
	function getPopularBlogsForHomePage($AppId,$count){ 
		$this->init();
	        $key=md5('getPopularBlogsForHomePage'.$AppId.$count);


        	if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            		$this->CI->xmlrpc->method('getPopularBlogsForHomePage');
            		$request = array (
                    		array($AppId, 'string'),
                    		array($count, 'string')
                    	);
            		$this->CI->xmlrpc->request($request);	
            		if ( ! $this->CI->xmlrpc->send_request()){
                		$response = $this->CI->xmlrpc->display_error();
                		return  $response;
            		} else {
                		$blogCreationResult = $this->CI->xmlrpc->display_response();
                		$this->cacheLib->store($key,$blogCreationResult,86400);
                		return $blogCreationResult;
            		} 

        	}else{
            		return  $this->cacheLib->get($key);
        	} 
	}

    function getExams($appId,$status){
        $this->init();
        $key=md5('getExams'.$appId);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $request = array (array($appId, 'string'),$status);
            $this->CI->xmlrpc->request($request);	
            $this->CI->xmlrpc->method('getExam');
            if ( ! $this->CI->xmlrpc->send_request()){
                $response = $this->CI->xmlrpc->display_error();
                return  $response;
            } else {
                $response = $this->CI->xmlrpc->display_response();
                $this->cacheLib->store($key,$response,14000);
                return $response;
            }
        } else {
            return $this->cacheLib->get($key);
        }
    }

    function getExamParents($appId, $category){
		$this->init();
        $request = array ($appId, $category );
        $this->CI->xmlrpc->request($request);	
        $this->CI->xmlrpc->method('getExamParents');
        if ( ! $this->CI->xmlrpc->send_request()){
            $response = $this->CI->xmlrpc->display_error();
            return  $response;
        } else {
            $blogCreationResult = $this->CI->xmlrpc->display_response();
            return $blogCreationResult;
        } 
    }

    function getExamsForParent($appId, $parentId){
		$this->init();
        $request = array ($appId, $parentId);
        $this->CI->xmlrpc->request($request);	
        $this->CI->xmlrpc->method('getExamsForParent');
        if ( ! $this->CI->xmlrpc->send_request()){
            $response = $this->CI->xmlrpc->display_error();
            return  $response;
        } else {
            $blogCreationResult = $this->CI->xmlrpc->display_response();
            return $blogCreationResult;
        } 
    }

    function getExamsParentDetails($appId, $examId){
		$this->init();
        $request = array ($appId, $examId);
        $this->CI->xmlrpc->request($request);	
        $this->CI->xmlrpc->method('getExamParentDetails');
        if ( ! $this->CI->xmlrpc->send_request()){
            $response = $this->CI->xmlrpc->display_error();
            return  $response;
        } else {
            $blogCreationResult = $this->CI->xmlrpc->display_response();
            return $blogCreationResult;
        } 
    }

    function updateBlogsForUrls($appId){
		$this->init('write');
        $request = array (array($appId, 'string') );
        $this->CI->xmlrpc->request($request);	
        $this->CI->xmlrpc->method('updateBlogsForUrls');
        if ( ! $this->CI->xmlrpc->send_request()){
            $response = $this->CI->xmlrpc->display_error();
            return  $response;
        } else {
            $response = $this->CI->xmlrpc->display_response();
            return $response;
        } 
    }

    function deleteArticle($appId, $articleId) {
		$this->init('write');
        $request = array ($appId,$articleId );
        $this->CI->xmlrpc->request($request);	
        $this->CI->xmlrpc->method('deleteBlog');
        if ( ! $this->CI->xmlrpc->send_request()){
            $response = $this->CI->xmlrpc->display_error();
            return  $response;
        } else {
            $response = $this->CI->xmlrpc->display_response();
            return $response;
        }
    }

    function getExamsForProducts($appId){
		$this->init();
        $request = array ($appId);
        $this->CI->xmlrpc->request($request);	
        $this->CI->xmlrpc->method('sgetExamsForProducts');
        if ( ! $this->CI->xmlrpc->send_request()){
            $response = $this->CI->xmlrpc->display_error();
            return  $response;
        } else {
            $response = $this->CI->xmlrpc->display_response();
            return $response;
        }
    }
    
    function getTestPrepInfoForGroups($appId, $examId, $start, $count){
        $this->init();
        $this->CI->xmlrpc->method('getTestPrepInfoForGroups');
        $request = array($appId, $examId, $start, $count); 
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){ 
            error_log_shiksha("getEventsForExams::Failure====> ".$this->CI->xmlrpc->display_error());
        }else{
            $response = ($this->CI->xmlrpc->display_response());
            return $response;
        }
  }

     function getArticlesForCriteria($appId, $criteria, $orderBy, $startOffset, $countOffset){
		$this->init();
        $request = array ($appId, json_encode($criteria), $orderBy, $startOffset, $countOffset);
        $this->CI->xmlrpc->request($request);	
        $this->CI->xmlrpc->method('sShowArticleList');
        if ( ! $this->CI->xmlrpc->send_request()){
            $response = $this->CI->xmlrpc->display_error();
            return  $response;
        } else {
            $response = $this->CI->xmlrpc->display_response();
	    $response = json_decode(gzuncompress(base64_decode($response)),true);
            return $response;
        }
    }
    function getArticlesWithImage($appId, $criteria, $groupBy) {
		$this->init();
        $key=md5('getArticlesWithImage'. $appId . serialize($criteria) . serialize($groupBy));
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $request = array ($appId, array($criteria, 'struct'), $groupBy);
            $this->CI->xmlrpc->request($request);	
            $this->CI->xmlrpc->method('sGetArticlesWithImage');
            if ( ! $this->CI->xmlrpc->send_request()){
                $response = $this->CI->xmlrpc->display_error();
                return  $response;
            } else {
                $response = $this->CI->xmlrpc->display_response();
                $this->cacheLib->store($key,$response);
                return $response;
            }
        } else {
            return $this->cacheLib->get($key);
        }
    }


    function updateImageUrl($appId,$blogId,$url){
		$this->init('write');
        $request = array ($appId,$blogId,$url);
        $this->CI->xmlrpc->request($request);	
        $this->CI->xmlrpc->method('updateImageUrl');
        if ( ! $this->CI->xmlrpc->send_request()){
            $response = $this->CI->xmlrpc->display_error();
            return  $response;
        } else {
            $response = $this->CI->xmlrpc->display_response();
            return $response;
        } 
    }

    //get exams category list based on tier criteria -- SUMS usage
    function getExamsCategory($appID,$parentId = 1,$type = '', $typeId = ''){
        $this->init();
        if(strlen($typeId) <=0 || strlen($type) <= 0){
            return $this->getExamsCategoryAll($appID, $parentId);
        }
        else{
            return $this->getListingsExamsCategories($appID, $parentId, $type, $typeId);
        }
    }

    function getExamsCategoryAll($appID,$parentId = 0){
        $this->init();
        $key = md5('getCategoryList'.$appID.$parentId);
        error_log_shiksha("key for cache is : ".$key);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI->xmlrpc->method('getExamsCategoryAll');
            $request = array($appID,$parentId); 
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                error_log_shiksha("ERROR: CATEGORY CLIENT::getCategoryList: FAIL".$this->CI->xmlrpc->display_error());
                error_log_shiksha("DEBUG: CATEGORY CLIENT::getCategoryList: EXIT FAILURE");
                return $this->CI->xmlrpc->display_error();
            }else{
                $response = $this->CI->xmlrpc->display_response();
                $this->cacheLib->store($key,$response);
                return $response;
            }	
        }
        else{
            error_log_shiksha("DEBUG: CATEGORY CLIENT::getCategoryList: EXIT SUCCESS Reading from cache");
            return $this->cacheLib->get($key);
        }
    }

    function getListingsExamsCategories($appID,$parentId = 0,$type,$typeId){
        $this->init();
        $this->CI->xmlrpc->method('getListingsExamsCategories');
        $request = array($appID,$parentId,$type,$typeId); 
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            error_log_shiksha("ERROR: CATEGORY CLIENT::getCategoryList: FAIL".$this->CI->xmlrpc->display_error());
            error_log_shiksha("DEBUG: CATEGORY CLIENT::getCategoryList: EXIT FAILURE");
            return $this->CI->xmlrpc->display_error();
        }else{
            return $this->CI->xmlrpc->display_response();
        }	
    }

    function updateBlogImages($appID, $blogId, $blogImages, $addStatus) {
        $this->init('write');
        $this->CI->xmlrpc->method('saddImagesToArticle');
        $request = array($appID,$blogId,$blogImages, $addStatus); 
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            return $this->CI->xmlrpc->display_error();
        }else{
            return $this->CI->xmlrpc->display_response();
        }	
    }

    function getBlogImages($appID, $blogId,$status) {
        $this->init();
        $this->CI->xmlrpc->method('sgetBlogImages');
        $request = array($appID,$blogId,$blogImages,$status); 
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            return $this->CI->xmlrpc->display_error();
        }else{
            return $this->CI->xmlrpc->display_response();
        }	
    }

    function getFlavorArticles($appID, $criteria, $orderBy, $startOffset,$countOffset,$type='') {
        $this->init();
        $this->CI->xmlrpc->method('sgetFlavorArticles');
        $key=md5('getFlavorArticles'. $appID . $criteria . $orderBy . $startOffset . $countOffset );
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $request = array($appID, $criteria, $orderBy, $startOffset,$countOffset); 
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                return $this->CI->xmlrpc->display_error();
            }else{
                $response = $this->CI->xmlrpc->display_response();
                if($type != 'homepage') {
                	$this->cacheLib->store($key,$response, 1800);
                }
                return $response;
            }	
        } else{
            error_log_shiksha("DEBUG: BlogClient::getFlavorArticles : EXIT SUCCESS Reading from cache");
            return $this->cacheLib->get($key);
        }
    }
    

    function getBlogPagesIndex($appID, $blogId) {
        $this->init();
        $this->CI->xmlrpc->method('sgetBlogPagesIndex');
        $request = array($appID,$blogId); 
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            return $this->CI->xmlrpc->display_error();
        }else{
            return json_decode($this->CI->xmlrpc->display_response(), true);
        }
    }

   function getLatestUpdatesForHomePage($appId,$startOffSet,$dbHitVar = false,$type=''){
        $this->init();
        $key=md5('getLatestUpdates'.$startOffSet);
	if(($this->cacheLib->get($key)=='ERROR_READING_CACHE')||($dbHitVar != false)){ 
            $request = array ($appId,$startOffSet);
            $this->CI->xmlrpc->request($request);
            $this->CI->xmlrpc->method('sgetLatestUpdatesForHomePage');
            if ( ! $this->CI->xmlrpc->send_request()){
                $response = $this->CI->xmlrpc->display_error();
                return  $response;
            } else {
                $response = $this->CI->xmlrpc->display_response();
                if($type != 'homepage') {
                	$this->cacheLib->store($key,$response);
                }
                return $response;
            }
        } else {
            return $this->cacheLib->get($key);
        }
   }

    function getAllLatestArticles($appID, $startOffset,$countOffset) {
        $this->init();
        $this->CI->xmlrpc->method('sgetAllLatestArticles');
        $key=md5('getLatestArticles'. $appID . $startOffset . $countOffset );
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $request = array($appID, $startOffset,$countOffset); 
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                return $this->CI->xmlrpc->display_error();
            }else{
                $response = $this->CI->xmlrpc->display_response();
                $this->cacheLib->store($key,$response, 86400);
                return $response;
            }	
        } else{
            error_log_shiksha("DEBUG: BlogClient::getAllLatestArticles : EXIT SUCCESS Reading from cache");
            return $this->cacheLib->get($key);
        }
    }

    function getArticleWidgetsData($widget_type_value, $categoryID, $subCatgoryID, $countryID, $regionID)
    {
        $this->init();
        //error_log("AMIT ============================= ".strtoupper($widget_type_value)." ============================= ");
        $keyRegionID = $regionID;
        $keyCountryID = $countryID;
        
        // $key = md5($widget_type_value."_".$regionId."_".$countryId."_".$categoryID);

        if($countryID > 2) {    // Abroad country page..
            $keyRegionID = 0;
            $keyCountryID = $countryID;
        }

        if($countryID == 1 && $regionID > 0) {  // Abroad region page..
            $keyRegionID = $regionID;
            $keyCountryID = 0;
        }

        //error_log("\nAMIT GET KEY = ".$widget_type_value."_".$keyRegionID."_".$keyCountryID."_".$categoryID."_".$subCatgoryID);

        if($subCatgoryID != 1 && $countryID == 2) {
            $key = md5($widget_type_value."_".$keyRegionID."_".$keyCountryID."_".$subCatgoryID);
        } else {
            $key = md5($widget_type_value."_".$keyRegionID."_".$keyCountryID."_".$categoryID);
        }

        $widgetData = $this->cacheLib->get($key);


        if(trim($widgetData) == 'ERROR_READING_CACHE')
        {
            $this->CI->xmlrpc->method('sgetArticleWidgetsData');
            $request = array($widget_type_value, $categoryID, $subCatgoryID, $countryID, $regionID);
            $this->CI->xmlrpc->request($request);
            if (!$this->CI->xmlrpc->send_request())
            {
                return $this->CI->xmlrpc->display_error();
            }
            else
            {
                $response = $this->CI->xmlrpc->display_response();
                $response = json_decode(gzuncompress(base64_decode($response)), true);

               if($response != "")
               $this->cacheLib->store($key, $response, 14400);

               return $response;
            }
        }
        else
        {
            return $widgetData;
        }
    }
	
	function getStudyAbroadStepsWidget($categoryId, $location_id, $location_type){
		$this->init();
                $this->CI->xmlrpc->method('getStudyAbroadStepsWidget');
                $key=md5('getStudyAbroadStepsWidget'. $categoryId . $location_id . $location_type);
                if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
                    $request = array($categoryId, $location_id, $location_type);
                    $this->CI->xmlrpc->request($request);
                    if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                    }else{
                        $response = json_decode(gzuncompress(base64_decode($this->CI->xmlrpc->display_response())),true);
                        $this->cacheLib->store($key,$response, 21600);
                        return $response;
                    }
                }else{
                                $response = $this->CI->xmlrpc->display_response();
                    return $this->cacheLib->get($key);
                }
	}
        
	function getStudyAbroadSnippetWidget($categoryId, $location_id, $location_type){
		$this->init();
                $this->CI->xmlrpc->method('getStudyAbroadSnippetWidget');
                $key=md5('getStudyAbroadSnippetWidget'. $categoryId . $location_id . $location_type);
                if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
                    $request = array($categoryId, $location_id, $location_type);
                    $this->CI->xmlrpc->request($request);
                    if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                    }else{
                        $response = json_decode(gzuncompress(base64_decode($this->CI->xmlrpc->display_response())),true);
                        $this->cacheLib->store($key,$response, 21600);
                        return $response;
                    }
                }else{
                                $response = $this->CI->xmlrpc->display_response();
                    // error_log("DEBUG: BlogClient::getStudyAbroadSnippetWidget : EXIT SUCCESS Reading from cache".$response);
                    return $this->cacheLib->get($key);
                }
	}

	function checkIfFeatured($appId, $blogId){
	        $this->init();
        	$this->CI->xmlrpc->method('scheckIfFeatured');
	        $key=md5('checkIfFeatured'. $appId . $blogId );
        	//if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
        	if(true){
	            $request = array($appID, $blogId);
        	    $this->CI->xmlrpc->request($request);
	            if ( ! $this->CI->xmlrpc->send_request()){
        	        return $this->CI->xmlrpc->display_error();
	            }else{
        	        $response = json_decode($this->CI->xmlrpc->display_response(),true);
                	$this->cacheLib->store($key,$response, 3600);
	                return $response;
        	    }
	        } else{
        	    return $this->cacheLib->get($key);
	        }
	}
        
    /**
     * parser to purify description, change new path and move old images of blogTable
     */
    function  getAllBlogArticle(){
        
		unlink('/tmp/blogArticles.txt');
		$start                   = microtime(true);
		
		$CI                      = & get_instance();
		$CI->load->model('blogs/articlemodel');
		$articlemodel            = new ArticleModel();
		
		$results                 = $articlemodel->getArticleResults('article');
		
		$insertedString4ImageUrl = '';
        foreach($results as $res){
			$newImagepath            = $this->replaceImagePathWithNew($res['blogImageURL']);
			$blogId                  = $res['blogId'];
			//appending the blogId and newImagepath in a string for multi Update
			$insertedString4ImageUrl .="($blogId,'$newImagepath'),"; 
			// moving the images to new folder
			$this->moveImageToAnotherFolder($res['blogImageURL']);
			$insertedString          = '';       
			// purfying the description     
            foreach($res['description'] as $des){
				$newDescription = $this->purifyDescrition($res['blogTitle'],$des['description']);
				$descriptionId  = $des['descriptionId'];
				$insertedString .= "($descriptionId,'$newDescription'),";
            }
			$insertedString =  rtrim($insertedString,',');
			$tableArray     = array('tableName'=>'blogDescriptions','fieldName'=>'descriptionId','fieldName1'=>'description');
			$articlemodel->setBlogUpdateQuery($insertedString,$tableArray);
			error_log('Updated BlogId: '.$res['blogId'].PHP_EOL,3,'/tmp/blogArticles.txt');
        }
		$tableArray1             = array('tableName'=>'blogTable','fieldName'=>'blogId','fieldName1'=>'blogImageURL');
		$insertedString4ImageUrl =  rtrim($insertedString4ImageUrl,',');
		// updating the purify description in table
		$articlemodel->setBlogUpdateQuery($insertedString4ImageUrl,$tableArray1);

		$stop      = microtime(true);
		$seconds   = $stop - $start;
		$resultLog = 'Start:' . $start.PHP_EOL.'Stop:' . $stop.PHP_EOL.'Seconds:' . $seconds.PHP_EOL;
		error_log($resultLog,3,'/tmp/blogArticles.txt');

        return 'true';
    }
        
    /**
     * parser to purify blogSlideshows
     */
    function getAllBlogSlideshow(){

        unlink('/tmp/blogArticles.txt');

		$start                   = microtime(true);
		
		$CI                      = & get_instance();
		$CI->load->model('blogs/articlemodel');
		$articlemodel            = new ArticleModel();
		
		$results                 = $articlemodel->getArticleResults('slideshow');
		
		$insertedString4ImageUrl = '';
        foreach($results as $res){
			$newImagepath            = $this->replaceImagePathWithNew($res['blogImageURL']);
			$blogId                  = $res['blogId'];
			//appending the blogId and newImagepath in a string for multi Update
			$insertedString4ImageUrl .="($blogId,'$newImagepath'),"; 
			// moving the images to new folder
			$this->moveImageToAnotherFolder($res['blogImageURL']);
			$insertedString          = ''; 
			// changes the slideshow image path    
            foreach($res['slideshowImages'] as $des){
				$newSlideshowImagePath = $this->replaceImagePathWithNew($des['image']);
   			    $this->moveImageToAnotherFolder($des['image']);
				$slideshowId           = $des['id'];
				$insertedString        .= "($slideshowId,'$newSlideshowImagePath'),";
            }
			$insertedString =  rtrim($insertedString,',');
			
			$tableArray     = array('tableName'=>'blogSlideShow','fieldName'=>'id','fieldName1'=>'image');
			$articlemodel->setBlogUpdateQuery($insertedString,$tableArray);
			error_log('Updated BlogId: '.$res['blogId'].PHP_EOL,3,'/tmp/blogSlideshow.txt');
		}
        
		$tableArray1             = array('tableName'=>'blogTable','fieldName'=>'blogId','fieldName1'=>'blogImageURL');
		$insertedString4ImageUrl =  rtrim($insertedString4ImageUrl,',');
		// updating the new paths in table
		$articlemodel->setBlogUpdateQuery($insertedString4ImageUrl,$tableArray1);
		
		$stop                    = microtime(true);
		$seconds                 = $stop - $start;
		$resultLog               = 'Start:' . $start.PHP_EOL.'Stop:' . $stop.PHP_EOL.'Seconds:' . $seconds.PHP_EOL;
		error_log($resultLog,3,'/tmp/blogSlideshow.txt');

        return 'true';
	}
        
    /**
     * parser to purify blogQNA
     */    
    function getAllBlogQna(){
		unlink('/tmp/blogQNA.txt');
		
		$start                   = microtime(true);
		
		$CI                      = & get_instance();
		$CI->load->model('blogs/articlemodel');
		$articlemodel            = new ArticleModel();
		
		$results                 = $articlemodel->getArticleResults('qna');
		
		$insertedString4ImageUrl = '';

        foreach($results as $res){
			$newImagepath            = $this->replaceImagePathWithNew($res['blogImageURL']);
			$blogId                  = $res['blogId'];
			
			$insertedString4ImageUrl .="($blogId,'$newImagepath'),"; 
			
			$this->moveImageToAnotherFolder($res['blogImageURL']);
			$insertedString          = '';            
            foreach($res['qna'] as $des){
				$questionDesc   = $this->purifyDescrition($res['blogTitle'],$des['question']);
				$answerDesc     = $this->purifyDescrition($res['blogTitle'],$des['answer']);
				$qnaId          = $des['id'];
				$insertedString .= "($qnaId,'$questionDesc','$answerDesc'),";
            }
			$insertedString =  rtrim($insertedString,',');
			$tableArray     = array('tableName'=>'blogQnA','fieldName'=>'id','fieldName1'=>'question','fieldName2'=>'answer');
			$articlemodel->setBlogUpdateQuery($insertedString,$tableArray);
			error_log('Updated BlogId: '.$res['blogId'].PHP_EOL,3,'/tmp/blogQNA.txt');
        }
		$tableArray1             = array('tableName'=>'blogTable','fieldName'=>'blogId','fieldName1'=>'blogImageURL');
		$insertedString4ImageUrl =  rtrim($insertedString4ImageUrl,',');
		
		$articlemodel->setBlogUpdateQuery($insertedString4ImageUrl,$tableArray1);
		
		$stop                    = microtime(true);
		$seconds                 = $stop - $start;
		$resultLog               = 'Start:' . $start.PHP_EOL.'Stop:' . $stop.PHP_EOL.'Seconds:' . $seconds.PHP_EOL;
		
		error_log($resultLog,3,'/tmp/blogQNA.txt');

        return 'true';
    }
        
    /**
     * parser to purify blogImages
     */      
    function getAllBlogImages(){
		unlink('/tmp/blogImages.txt');
		
		$start                   = microtime(true);
		
		$CI                      = & get_instance();
		$CI->load->model('blogs/articlemodel');
		$articlemodel            = new ArticleModel();
		$results                 = $articlemodel->getArticleResults('images');
		
		$insertedString4ImageUrl = '';
        
        foreach($results as $res){
			$newImagepath            = $this->replaceImagePathWithNew($res['blogImageURL']);
			$blogId                  = $res['blogId'];
			
			$insertedString4ImageUrl .="($blogId,'$newImagepath'),"; 
			
			$this->moveImageToAnotherFolder($res['blogImageURL']);
			$insertedString          = '';            
            foreach($res['images'] as $des){
				$newBlogImagePath = $this->replaceImagePathWithNew($des['imageURL']);
   			    $this->moveImageToAnotherFolder($des['imageURL']);
				$blogImageId      = $des['blogImageId'];
				$insertedString   .= "($blogImageId,'$newBlogImagePath'),";
            }
           
			$insertedString =  rtrim($insertedString,',');
			$tableArray     = array('tableName'=>'blogImages','fieldName'=>'blogImageId','fieldName1'=>'imageURL');
			$articlemodel->setBlogUpdateQuery($insertedString,$tableArray);
			error_log('Updated BlogId: '.$res['blogId'].PHP_EOL,3,'/tmp/blogImages.txt');
        }
		$tableArray1             = array('tableName'=>'blogTable','fieldName'=>'blogId','fieldName1'=>'blogImageURL');
		$insertedString4ImageUrl =  rtrim($insertedString4ImageUrl,',');
		
		$articlemodel->setBlogUpdateQuery($insertedString4ImageUrl,$tableArray1);
		
		$stop                    = microtime(true);
		$seconds                 = $stop - $start;
		$resultLog               = 'Start:' . $start.PHP_EOL.'Stop:' . $stop.PHP_EOL.'Seconds:' . $seconds.PHP_EOL;
		
		error_log($resultLog,3,'/tmp/blogImages.txt');
		
		return 'true';
	}
        
        
    /**
     * parser to purify studyAbroadImages
     */     
    function getStudyAbroadImages(){
		unlink('/tmp/saImages.txt');
		
		$start                   = microtime(true);
		
		$CI                      = & get_instance();
		$CI->load->model('blogs/articlemodel');
		$articlemodel            = new ArticleModel();
		
		$results                 = $articlemodel->getArticleResults('saimages');
		
		$insertedString4ImageUrl = '';
        foreach($results as $res){
			$newImagepath            = $this->replaceImagePathWithNew($res['contentImageURL']);
			$primaryKeyOfSAContent   = $res['id'];
			
			$insertedString4ImageUrl .="($primaryKeyOfSAContent,'$newImagepath'),"; 
			
			$this->moveImageToAnotherFolder($res['contentImageURL']);
			$insertedString          = '';            
            foreach($res['images'] as $des){
				$newBlogImagePath = $this->replaceImagePathWithNew($des['imageURL']);
   			    $this->moveImageToAnotherFolder($des['imageURL']);
				$imageId          = $des['saContentimageId'];
				$insertedString   .= "($imageId,'$newBlogImagePath'),";
             }
            
           
			$insertedString =  rtrim($insertedString,',');
			$tableArray     = array('tableName'=>'sa_content_images','fieldName'=>'content_image_id','fieldName1'=>'image_url');
			$articlemodel->setBlogUpdateQuery($insertedString,$tableArray);
			error_log('Updated BlogId: '.$res['content_id'].PHP_EOL,3,'/tmp/blogImages.txt');

        }
		$tableArray1             = array('tableName'=>'sa_content','fieldName'=>'id','fieldName1'=>'content_image_url');
		$insertedString4ImageUrl =  rtrim($insertedString4ImageUrl,',');
		
		
		$articlemodel->setBlogUpdateQuery($insertedString4ImageUrl,$tableArray1);
		
		$stop                    = microtime(true);
		$seconds                 = $stop - $start;
		$resultLog               = 'Start:' . $start.PHP_EOL.'Stop:' . $stop.PHP_EOL.'Seconds:' . $seconds.PHP_EOL;
		
		error_log($resultLog,3,'/tmp/saImages.txt');
		
		return 'true';
    }
    
    
    /**
     * parser to purify studyAbroadArticle&GuideImages
     */   
    function getStudyAbroadArticleImages(){
		unlink('/tmp/sablogImages.txt');
		
		$start                   = microtime(true);
		
		$CI                      = & get_instance();
		$CI->load->model('blogs/articlemodel');
		$articlemodel            = new ArticleModel();
		
		$results                 = $articlemodel->getArticleResults('sasection');
		
		
		$insertedString4ImageUrl = '';
        foreach($results as $res){
			$newImagepath            = $this->replaceImagePathWithNew($res['contentImageURL']);
			$primaryKeyOfSAContent   = $res['id'];
			
			$insertedString4ImageUrl .="($primaryKeyOfSAContent,'$newImagepath'),"; 
			
			$this->moveImageToAnotherFolder($res['contentImageURL']);
			$insertedString          = '';            
            foreach($res['images'] as $des){
				$details        = $this->purifyDescrition($res['strip_title'],$des['details']);
				$imageId        = $des['id'];
				$insertedString .= "($imageId,'$details'),";
            }
			
			
			$insertedString =  rtrim($insertedString,',');
			
			$tableArray     = array('tableName'=>'sa_content_sections','fieldName'=>'id','fieldName1'=>'details');
			$articlemodel->setBlogUpdateQuery($insertedString,$tableArray);
			error_log('Updated BlogId: '.$res['content_id'].PHP_EOL,3,'/tmp/sablogImages.txt');
        }
		$tableArray1             = array('tableName'=>'sa_content','fieldName'=>'id','fieldName1'=>'content_image_url');
		$insertedString4ImageUrl =  rtrim($insertedString4ImageUrl,',');
		
		
		$articlemodel->setBlogUpdateQuery($insertedString4ImageUrl,$tableArray1);
		
		$stop                    = microtime(true);
		$seconds                 = $stop - $start;
		$resultLog               = 'Start:' . $start.PHP_EOL.'Stop:' . $stop.PHP_EOL.'Seconds:' . $seconds.PHP_EOL;
		
		error_log($resultLog,3,'/tmp/sablogImages.txt');
		
		return 'true';
    }
        
        
       
      
    /**
     * check img tag has alt attribute if not then add title in alt attribute and 
	 * check the image path with the new path for articles
	 * @author Aman Varsheny <aman.varshney@shiksha.com>
     * @param  string $title       
     * @param  string $description 
     * @return string
     */
    function purifyDescrition($title,$description){
        $dom = new DOMDocument;
        $dom->loadHTML($description);
        //$dom->formatOutput=true;
        $anchors = $dom->getElementsByTagName('img');
        $html = '';

		if($anchors->length > 0)
		{
            foreach($anchors as $anchor)
            {
				$rel     = array(); 
				$relAtt1 = $anchor->getAttribute('src');
		        if($relAtt1 != '')
		        {
			        // checking the url contains mediadata/image and replacing with new path
                    $finalImageUrl = $this->replaceImagePathWithNew($relAtt1);
              
                    // code for moving the file to new folder
                    $this->moveImageToAnotherFolder($relAtt1);	

                    // checking the tag has alt attribute or not
                    if ($anchor->hasAttribute('alt') AND ($relAtt = $anchor->getAttribute('alt')) !== '') 
                    {
                            $rel = preg_split('/\s+/', trim($relAtt));
                    }

                    // if alt tag is not their is url then insert  the title 
                    if(count($rel)<=0)
                    {
                            $rel[] = $title;
                    }
                    // set the alt and src atrribute in image tag
                    $anchor->setAttribute('src', $finalImageUrl);    
                    $anchor->setAttribute('alt', implode(' ', $rel));
                }
            }

            foreach($dom->getElementsByTagName('body')->item(0)->childNodes as $element) {
                    $html .= $dom->saveHTML($element);
            }
        }else{
        	$html = $description;
        }
        
  //  $html = $this->DoHTMLEntities($html);
     
        return addslashes($html);
    }
        
        
        function DoHTMLEntities ($string){ 
        $trans_tbl =  get_html_translation_table( HTML_ENTITIES, ENT_QUOTES, "UTF-8" );
        
        // MS Word strangeness.. 
        // smart single/ double quotes: 
        $trans_tbl[chr(145)] = '\''; 
        $trans_tbl[chr(146)] = '\''; 
        $trans_tbl[chr(147)] = '&quot;'; 
        $trans_tbl[chr(148)] = '&quot;'; 

                // Acute 'e' 
        $trans_tbl[chr(142)] = '&eacute;'; 
         
        
        return strtr ($string, $trans_tbl); 
    } 
    
    /**
     * replace image path function
     * @param  string $relAtt1
     * @return string
     */
    function replaceImagePathWithNew($relAtt1)
    {
        // checking the url contains mediadata/image and replacing with new path

        if(preg_match('/mediadata\/images\/articles/', $relAtt1) == 0)
        {
            if (strpos($relAtt1,'mediadata/images/') !== false) 
            {
                    $finalImageUrl = str_replace("mediadata/images/","mediadata/images/articles/",$relAtt1);
            }else
            {
                    $finalImageUrl= $relAtt1;	
            }
        }else{
            $finalImageUrl = $relAtt1;
        }

        return $finalImageUrl;
    }
    
	/**
	 * move image to the new folder 
	 * @param  string $finalImageUrl
	 * @return string
	 */
    function moveImageToAnotherFolder($finalImageUrl){

        $validImageArray= array('_m','_s','_a','_b','_t','_300x200','_172x115','_75x50','_135x90');
        preg_match("/[^\/]+$/", $finalImageUrl, $matches);
        $fileName = $matches[0]; // test
       // $urlParts = explode("/",$finalImageUrl);
      //  $fileName = $urlParts[count($urlParts)-1];

        $ext = pathinfo($fileName, PATHINFO_EXTENSION);


        $strposition = strrpos($fileName, '_');

         if(empty($strposition)){
             $strposition = strrpos($fileName, '.');
           }
         $fileNamePattern =  substr($fileName, 0,$strposition);

        $imageParentFolder = '/var/www/html/shiksha/mediadata/images/';

        $oldFilePathWithOutExtension = $imageParentFolder.$fileNamePattern;
        $newFilePathWithOutExtension = $imageParentFolder.'articles/'.$fileNamePattern;
        $fileExtension         = '.'.$ext;

        if( !(file_exists($imageParentFolder.'articles/') && is_dir($imageParentFolder.'articles/')) ) {
        	@mkdir($imageParentFolder.'articles/', 0777);
    	}

        if(file_exists($oldFilePathWithOutExtension.$fileExtension))
            rename ($oldFilePathWithOutExtension.$fileExtension, $newFilePathWithOutExtension.$fileExtension);
           	error_log($oldFilePathWithOutExtension.$fileExtension."-----".$newFilePathWithOutExtension.$fileExtension.PHP_EOL,3,'/tmp/movedImages.txt');


        foreach($validImageArray as $value){
            if(file_exists($oldFilePathWithOutExtension.$value.$fileExtension))
                    rename ($oldFilePathWithOutExtension.$value.$fileExtension, $newFilePathWithOutExtension.$value.$fileExtension);
                	error_log($oldFilePathWithOutExtension.$value.$fileExtension."-----".$newFilePathWithOutExtension.$value.$fileExtension.PHP_EOL,3,'/tmp/movedImages.txt');

        }


        return ;
    }

    /**
     * backup images to another folder
     * @param  string $imageURL
     * @return true
     */
    function copyImageToAnotherFolder($imageURL){

        $validImageArray= array('_m','_s','_a','_b','_t','_300x200','_172x115','_75x50','_135x90');
        preg_match("/[^\/]+$/", $imageURL, $matches);
        
        $fileName = $matches[0]; // test
       
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);


        $strposition = strrpos($fileName, '_');

         if(empty($strposition)){
             $strposition = strrpos($fileName, '.');
           }
        $fileNamePattern =  substr($fileName, 0,$strposition);

        $imageParentFolder = '/var/www/html/shiksha/mediadata/images/';

        $oldFilePathWithOutExtension = $imageParentFolder.$fileNamePattern;
        $newFilePathWithOutExtension = $imageParentFolder.'articlesbackup/'.$fileNamePattern;
        $fileExtension         = '.'.$ext;

        if( !(file_exists($imageParentFolder.'articlesbackup/') && is_dir($imageParentFolder.'articlesbackup/')) ) {
        	@mkdir($imageParentFolder.'articlesbackup/', 0777);
    	}

        if(file_exists($oldFilePathWithOutExtension.$fileExtension))
            copy ($oldFilePathWithOutExtension.$fileExtension, $newFilePathWithOutExtension.$fileExtension);
           	error_log($oldFilePathWithOutExtension.$fileExtension."-----".$newFilePathWithOutExtension.$fileExtension.PHP_EOL,3,'/tmp/articlesbackupImages.txt');


        foreach($validImageArray as $value){
            if(file_exists($oldFilePathWithOutExtension.$value.$fileExtension))
                    copy ($oldFilePathWithOutExtension.$value.$fileExtension, $newFilePathWithOutExtension.$value.$fileExtension);
                	error_log($oldFilePathWithOutExtension.$value.$fileExtension."-----".$newFilePathWithOutExtension.$value.$fileExtension.PHP_EOL,3,'/tmp/articlesbackupImages.txt');

        }


        return ;
    }

    /**
     * backup description images
     * @param  string $description
     * @return true              
     */
    function toCopyDescriptioImages($description){
        $dom = new DOMDocument;
        $dom->loadHTML($description);
        //$dom->formatOutput=true;
        $anchors = $dom->getElementsByTagName('img');
        $html = '';

		if($anchors->length > 0)
		{
            foreach($anchors as $anchor)
            {
				$rel     = array(); 
				$relAtt1 = $anchor->getAttribute('src');
		        if($relAtt1 != '')
		        {
                    $this->copyImageToAnotherFolder($relAtt1);	
                }
            }
	    }

	    return true;
   
    }



    /**
     * [doArticleBackup4Images description]
     * @return [type] [description]
     */
    function doArticleBackup4Images(){

		$CI           = & get_instance();
		$CI->load->model('blogs/articlemodel');
		$articlemodel = new ArticleModel();
		
		$results      = $articlemodel->getArticleResults('article');
		$this->toIterateResult('article',$results,array('blogImageURL','description','description'));
		
		$results1     = $articlemodel->getArticleResults('slideshow');
		$this->toIterateResult('slideshow',$results1,array('blogImageURL','slideshowImages','image'));
		
		$results2     = $articlemodel->getArticleResults('qna');
		$this->toIterateResult('qna',$results2,array('blogImageURL','qna','question','answer'));
		
		$results3     = $articlemodel->getArticleResults('images');
		$this->toIterateResult('images',$results3,array('blogImageURL','images','imageURL'));
		
		
		$results4     = $articlemodel->getArticleResults('saimages');
		$this->toIterateResult('saimages',$results4,array('contentImageURL','images','imageURL'));
		
		$results5     = $articlemodel->getArticleResults('sasection');
		$this->toIterateResult('sasection',$results5,array('contentImageURL','images','details'));
    }

    /**
     * iterate query results
     * @param  string $type
     * @param  array $result
     * @param  array $fieldsArray
     * @return true;
     */
    function toIterateResult($type,$result,$fieldsArray){
    	
    	foreach($result as $res){
    		   	    $this->copyImageToAnotherFolder($res[$fieldsArray[0]]);
		    foreach($res[$fieldsArray[1]] as $des){
		    	if($type == 'slideshow'){
					$this->copyImageToAnotherFolder($des[$fieldsArray[2]]);
		    	}elseif($type == 'qna'){
		    		$this->toCopyDescriptioImages($des[$fieldsArray[2]]);	
		    		$this->toCopyDescriptioImages($des[$fieldsArray[3]]);	
		    	}elseif($type == 'images'){
		    		$this->copyImageToAnotherFolder($des[$fieldsArray[2]]);
		    	}elseif($type == 'saimages'){
		    		$this->copyImageToAnotherFolder($des[$fieldsArray[2]]);
		    	}else{
		    		$this->toCopyDescriptioImages($des[$fieldsArray[2]]);	
		    	}
				
		    }
		}
    }

    /**
     * to get home page featured articles
     * @author Aman Varshney <varshney.aman@gmail.com>
     * @date   2015-10-22
     * @return array
     */
    function getHomePageFeaturedArticles($disableCache = false){
		
    	$this->CI = & get_instance();
		
		$this->CI->load->library('cacheLib');
		$this->cacheLib = new cacheLib();
		
		$homepageFeaturedArticle = array();
		if($this->cacheLib && !$disableCache) {
			$homepageFeaturedArticle = $this->cacheLib->get('homepageFeaturedArticle');
		}

		if(empty($homepageFeaturedArticle) || $homepageFeaturedArticle == "ERROR_READING_CACHE"){
			$articleModel = $this->CI->load->model('blogs/articlemodel');
			$homepageFeaturedArticle   = $articleModel->getHomePageFeaturedArticles();
			$this->cacheLib->store('homepageFeaturedArticle', $homepageFeaturedArticle, 10800);
		}
		
  		return $homepageFeaturedArticle;

    }


    /**
     * to get homepage news and feature article
     * @author Aman Varshney <varshney.aman@gmail.com>
     * @date   2015-10-22
     * @param  String     $blogType i.e news,feature
     * @param  Integer    $limit   no of items to fetch
     * @return array     data  
     */
    function getHomePageFeatureAndNewsArticle($blogType,$limit,$key,$disableCache = false){
    	$this->CI = & get_instance();
		

		$this->CI->load->library('cacheLib');
		$this->cacheLib = new cacheLib();

		$data = array();
		if($this->cacheLib && !$disableCache) {
			$data = $this->cacheLib->get($key);
		}

		if(empty($data) || $data == "ERROR_READING_CACHE"){
			$articleModel = $this->CI->load->model('blogs/articlemodel');
			$data = $articleModel->getHomePageFeatureAndNewsArticle($blogType,$limit);
			$this->cacheLib->store($key, $data, 10800);
		}

		return $data;
    }
}
?>
