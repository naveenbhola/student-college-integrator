<?php
class tabloidLib
{
    private $CI;
    private $sacontentmodel;
    
    // constructor defined here just for the reason to get CI instances for loading different classes in Codeigniter to perform business logic
    public function __construct() {
        $this->CI = & get_instance();
        $this->_setDependencies();
    }
    
    // to get model instance.
    private function _setDependencies(){
        $this->CI->load->model('blogs/sacontentmodel');
        $this->sacontentmodel = new sacontentmodel();
    }

    public function prepareHeaderComponents($content)
    {
		if($content['data']['type'] == 'guide') {
			$seoTitle = 	$content['data']['strip_title'] ;
		}else {
			$seoTitle = 	$content['data']['strip_title'];
		}
	
		if($content['data']['seo_title'] != '') {
			$seoTitle = $content['data']['seo_title'];
		}
	
		$canonicalURL = $content['data']['contentURL'];
		
		if($content['data']['seo_description'] == '') {
			$text = strip_tags($content['data']['summary']);
		}else {
			$text = strip_tags($content['data']['seo_description']);
		}
		
		$text = trim($text);
		
		$text = str_replace('&nbsp;',' ',$text);
		
		if(strlen($text) > 150) {
			$newText = substr($text,150,160);
			$spaceAfter150 = stripos($newText,' ');
			$text = substr($text,0,150+$spaceAfter150);
		}else {
			$text = substr($text, 0, 150);
		}
	
		$metaDescription = $text;
	
		$imageUrl = str_replace('_s','',$content['data']['contentImageURL']);
		$pgType = $content['data']['type'] == 'guide' ? 'guidePage' : 'articlePage';
	
		$robots = 'ALL';
		if($content['data']['content_id']==246){
			$robots = 'NOINDEX';
		}
	
		$headerComponents = array(
				'js'=>array('studyAbroadCategoryPage','studyAbroadGuide',  'studyAbroadHomepage','common','facebook','jquery.royalslider.min','jquery.tinycarouselV2.min'),
                                'asyncJs'=> array('jquery.royalslider.min','jquery.tinycarouselV2.min'),
				'css'=>array('studyAbroadCommon', 'studyAbroadHomePage','studyAbroadGuide'),
				'canonicalURL'      => $canonicalURL,
				'title'             => $seoTitle,
				'metaDescription'   => $metaDescription,
				'hideSeoRevisitFlag' => true,
				'hideSeoRatingFlag' => true,
				'hideSeoPragmaFlag' => true,
				'hideSeoClassificationFlag' => true,
				'articleImage' => $imageUrl,
				'pgType'	        => $pgType,
				'robotsMetaTag' => $robots,
				'pageIdentifier'    => $beaconTrackData['pageIdentifier']
		);
        return $headerComponents;
    }
}
