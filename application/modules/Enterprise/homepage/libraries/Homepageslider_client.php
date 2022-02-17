<?php
/**
 * This is the client, makes server call for all types of services
 *
 * @author     Aditya <aditya.roshan@shiksha.com>
 * @version
 */
class Homepageslider_client
{
	var $CI = '';
	var $cacheLib;
	static $instance;
	/**
	 *
	 * Return new instance if already not exists
	 *
	 * @access	public
	 * @return	object
	 */
	public static function  getInstance () {
		if(!self::$instance) {
			self::$instance = new Homepageslider_client();
		}
		return self::$instance;
	}
	/**
	 * this method sets the server url and other parameters required for xml rpc call
	 *
	 * @param none
	 * @return void
	 */
	private function _init()
	{
		$this->CI = & get_instance();
		$this->CI->load->helper('url');
		$this->CI->load->library('xmlrpc');
		$this->CI->load->library('cacheLib');
		$this->cacheLib = new cacheLib();
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server(HOMEPAGE_CAROUSEL_SERVER_URL, HOMEPAGE_CAROUSEL_SERVER_PORT);
	}
	/**
	 *
	 * make server calls
	 *
	 * @access	private
	 * @return	array
	 */
	private function _makeServerCall($key,$request,$methodName,$cache=0,$ttl=0) {
		if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
			$this->CI->xmlrpc->method($methodName);
			$this->CI->xmlrpc->request($request);
			if ( ! $this->CI->xmlrpc->send_request()){
				return  $this->CI->xmlrpc->display_error();
			} else {
				$response=$this->CI->xmlrpc->display_response();
				if($cache == 1)
				{
					$this->cacheLib->store($key,$response,$ttl);
				}
				return $response;
			}
		}else {
			return  $this->cacheLib->get($key);
		}
	}
	/**
	 * Add contents to the carousel widget
	 *
	 * @param
	 * @return array
	 */
	public function addContentToCarouselWidget($carousel_title,$carousel__destination_url,$carousel_photo_url,$carousel_description,$carousel_open_new_window) {
		$this->_init();
                $this->deleteHomepageCacheHTMLFile(true);
		$key=md5('addContentToCarouselWidget');
		$request = array ($carousel_title,$carousel__destination_url,$carousel_photo_url,$carousel_description,$carousel_open_new_window);
		return $this->_makeServerCall($key,$request,'addContentToCarouselWidget');
	}
	/**
	 * Add contents to the carousel widget
	 *
	 * @param
	 * @return array
	 */
	public function renderCarouselDeatils($carousle_id ='') {
		$this->_init();
		$key=md5('renderCarouselDeatils');
		$request = array($carousle_id);
		return $this->_makeServerCall($key,$request,'renderCarouselDeatils');
	}
	/**
	 * Add contents to the carousel widget
	 *
	 * @param
	 * @return array
	 */
	public function updateCarouselDeatils($array) {
		$this->_init();
                $this->deleteHomepageCacheHTMLFile(true);
		$key=md5('updateCarouselDeatils');
		$request = array(json_encode($array));
		return $this->_makeServerCall($key,$request,'updateCarouselDeatils');
	}
	/**
	 * Add contents to the carousel widget
	 *
	 * @param
	 * @return array
	 */
	public function deleteCarousel($carousel_id,$carousel_order) {
		$this->_init();
                $this->deleteHomepageCacheHTMLFile(true);
		$key=md5('deleteCarousel');
		$request = array(json_encode($carousel_id),json_encode($carousel_order));
		return $this->_makeServerCall($key,$request,'deleteCarousel');
	}
	/**
	 * Add contents to the carousel widget
	 *
	 * @param
	 * @return array
	 */
	public function reorderCarousel($array) {
		$this->_init();
                $this->deleteHomepageCacheHTMLFile(true);
		$key=md5('reorderCarousel');
		$request = array(json_encode($array));
		return $this->_makeServerCall($key,$request,'reorderCarousel');
	}
	/**
	 * Add contents to the carousel widget
	 *
	 * @param
	 * @return array
	 */
	public function getDataForHomepageCafeWidget($array) {
		$this->_init();
		$key=md5('getDataForHomepageCafeWidget');
		$request = array(json_encode($array));
		return $this->_makeServerCall($key,$request,'getDataForHomepageCafeWidget',1,21600);
	}
        /**
	 * deletes HTML cache
	 *
	 * @param
	 * @return array
	 */
        function deleteHomepageCacheHTMLFile($makeCurl=true)  {
	//In case of course type, only delete the Overview file
	    $overviewFile = "HomePageRedesignCache/middlepanel.html";
	    if(file_exists($overviewFile))
	      unlink($overviewFile);
	//After deleting the HTML files from the local server, also delete this file from other Frontend servers
	//The variable makeCurl is used so that we don't get in the loop of calling other Front end servers. This will be true only when this function is called from Local server.
        $makeCurl = false;
	if($makeCurl){
	      global $shikshaRestFrontEndBoxes;
	      $c = curl_init();
	      for($i= 0  ;$i < count($shikshaRestFrontEndBoxes) ; $i++){
		  $url =  "http://".$shikshaRestFrontEndBoxes[$i]."/homepage/HomepageSlideshowWidget/deleteHomepageCacheHTMLFile";
		  curl_setopt($c, CURLOPT_URL,$url);
		  curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		  curl_exec($c);
	      }
	      curl_close($c);
	}
    }
    function deleteHomepageCoverBannerCache() {
    	$this->CI = & get_instance();
    	$this->CI->load->library('cacheLib');
		$this->cacheLib = new cacheLib();
		if($this->cacheLib) {
			$this->cacheLib->clearCacheForKey('newHomepage_banner');
			$this->cacheLib->clearCacheForKey('newHomepage_featured');
			return true;
		}
    }

    function getHomePageCmsData($key = 'banner', $disableCache = false) {
    	$cacheKey = "newHomepage_".$key;
    	$this->CI = & get_instance();
    	$this->CI->load->library('cacheLib');
		$this->cacheLib = new cacheLib();
		$homepageCoverBannerData = array();
		if($this->cacheLib && !$disableCache) {
			$homepageCoverBannerData = $this->cacheLib->get($cacheKey);
			$processedHomepageCoverBannerData = $homepageCoverBannerData;
		}

		//If not present in cache
		if(empty($homepageCoverBannerData) || $homepageCoverBannerData == "ERROR_READING_CACHE") {
			$this->homepagecmsmodel = $this->CI->load->model("home/homepagecmsmodel");
			$homepageCoverBannerData = $this->homepagecmsmodel->getHomePageCoverBannerData($key);
			$processedHomepageCoverBannerData = array();
			$i=0;
			$j=0;
			foreach($homepageCoverBannerData as $coverBannerData) {
				if($coverBannerData['is_default']) {
					$processedHomepageCoverBannerData['default'][$i] = $coverBannerData;
					$processedHomepageCoverBannerData['default'][$i]['target_url'] = $this->getTrackCtrUrl($coverBannerData['banner_id'], $coverBannerData['target_url']);
$i++;
				}
				else {
					$processedHomepageCoverBannerData['paid'][$j] = $coverBannerData;
					$processedHomepageCoverBannerData['paid'][$j]['target_url'] = $this->getTrackCtrUrl($coverBannerData['banner_id'], $coverBannerData['target_url']);
					$j++;
				}
			}
			$this->cacheLib->store($cacheKey, $processedHomepageCoverBannerData, 86400);
		}
		return $processedHomepageCoverBannerData;
    }

    function getTrackCtrUrl($id, $url) {
    	return SHIKSHA_HOME.'/trackCtr/'.$id.'?url='.urlencode($url);
    }
}
?>
