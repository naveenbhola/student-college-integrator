<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
Desc   	: All article old url will be redirect to 301
@Param 	: array()
@uthor  : akhter
*/
class articleRedirectionLib{

	function __construct($param)
    {
        $this->CI  =  & get_instance();
    }

    //http://www.shiksha.com/news-articles/<pageNo>
    //http://www.shiksha.com/news-<pageNo>
	function redirectDefault($param){
		$this->CI->config->load('blogs/blogsConfig');
		$urlMapping = $this->CI->config->item("redirectUrlMapping");
		if($param['urlStr'] == 'default'){
			$pageNo  = is_numeric($param['pageNo']) ? '-'.$param['pageNo'] : '';
			$url = SHIKSHA_HOME.'/'.$urlMapping[$param['urlStr']].$pageNo;
			$this->redirect($url);
		}
	}

	// OLD URL 1 - like http://media.shiksha.com/mass-communication-news-articles-<pageNo>-coursepage
	// OLD URL 2 - like http://www.shiksha.com/mba/resources/mba-news-articles-<pageNo> 
	// OLD URL 3 - like http://shiksha.com/animation-vfx-gaming-comics-news-articles-<pageNo>
	function redirectFromCoursePage($urlStr,$pageNo){
		$url              = explode('-news-articles', $urlStr);
		$param['pageNo']  = ($pageNo>0) ? $pageNo : substr($url[1],1);
   		$param['urlStr']  =  $url[0];
   		$pageNo           = is_numeric($param['pageNo']) ? '-'.$param['pageNo'] : '';
   		
   		$this->CI->config->load('blogs/blogsConfig');
		$urlMapping = $this->CI->config->item("redirectUrlMapping");
		$mappingArr = $urlMapping[$param['urlStr']];
   		
   		$urlObj = $this->CI->load->library('common/UrlLib');
   		$url    = $urlObj->getAllPageUrl($mappingArr).$pageNo.$mappingArr['queryString'];
   		$this->redirect($url);
	}
	
	function redirect($url){
		header("Location: $url",TRUE,301);exit();
	}

	function redirectToNewArticleUrl($blogObj,$ampViewFlag=false){
		$currentUrl = getCurrentPageURLWithoutQueryParams();

		if(!empty($blogObj)){
			$blogId = $blogObj->getId();
		}

		if(!empty($blogId)) {
			$seo_url   = ($ampViewFlag)?$blogObj->getAmpURL():$blogObj->getUrl();

			$queryParams = array();

            $queryParams = $_GET;

            if(!empty($queryParams) && count($queryParams) > 0)
            {
                $seo_url .= '?'.http_build_query($queryParams);
            }

            $seo_url = addingDomainNameToUrl(array('url' => $seo_url,'domainName' => SHIKSHA_HOME));
            //check if url is different from original url, 301 redirect to main url
            $blogUrl     = ($ampViewFlag)?$blogObj->getAmpURL():$blogObj->getURL();
            $blogUrl = addingDomainNameToUrl(array('url' => $blogUrl,'domainName' => SHIKSHA_HOME));
            if($currentUrl != $blogUrl){     
                header("Location: $seo_url",TRUE,301);
                exit;
            }
		}
	}
}