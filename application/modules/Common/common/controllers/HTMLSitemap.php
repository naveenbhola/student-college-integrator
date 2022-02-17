<?php
/**
 * HTMLSitemap Controller.
 * Controller for creating HTML sitemap of shiksha
 * @date    2016-04-12
 * @author  Romil Goel
 * @todo    none
*/
ini_set('memory_limit', '1800M');
class HTMLSitemap extends MX_Controller
{

	function __construct(){
		parent::__construct();
		$this->load->library('common/HTMLSitemapLibrary');
		$this->sitemapLibrary = new HTMLSitemapLibrary();
		$this->aliasMapping = array('s'	=>'stream_id',
								 	'sb'=>'substream_id',
		 							'sp'=>'specialization_id',
            						'b' =>'base_course_id',
            						'c' =>'credential'
           );
	}

	/**
	 * Method to serve the homepage or the first page of the Shiksha's HTML sitemap
	 *
	 * @author Romil Goel <romil.goel@shiksha.com> / Ankur Kumar <ankur.k@shiksha.com>
	 * @date   2016-04-12 / 2016-11-04
	 */
	function sitemapHome(){
		$data                  		   = array();
		$data['validateuser'] 		   = $this->checkUserValidation();
		$data['sitemapPageType'] 	   = 'home';
		$data['seoTitle']			   = "Sitemap | Shiksha.com";
		$data['seoDesc']			   = "Check out Shiksha's sitemap to find colleges, courses, exams, careers, and other useful resources on the website quickly.";
		$data['otherCategoriesData']   = $this->sitemapLibrary->getAllStreamData();
		$data['otherCategoriesData'][] = array("text"=>"Colleges by location >>", "link" => SHIKSHA_HOME."/sitemap/browse-colleges-by-location");
		
		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_SiteMap');
        $data['dfpData']  = $dfpObj->getDFPData($data['validateuser'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		if(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile')){
			$this->load->view("common/sitemap/MobileHTMLsitemap", $data);
		}
		else{
			$this->load->view("common/sitemap/HTMLsitemapHome", $data);
		}
	}

	/**
	 * Method to serve Location sitemap for a particular subcategory. This is the page 2 of the HTML sitemap.
	 * @author   Romil Goel <romil.goel@shiksha.com>
	 * @date     2016-04-12
	 *
	 * @param string $categoryText The URL name of the stream
	 */
	function locationSitemap($text,$entityTypeAlias,$entityId) {	
		$data                   = array();
		$data 					= $this->sitemapLibrary->locationSitemap($entityId,$this->aliasMapping[$entityTypeAlias]);
		$data['validateuser']   = $this->checkUserValidation();

		// show the mobile sitemap in case of mobile otherwise show desktop sitemap
		if (($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile')) {
			$this->load->view("common/sitemap/MobileHTMLsitemap", $data);
		} else {
			$this->load->view("common/sitemap/HTMLsitemapLocation", $data);
		}
	}
}
