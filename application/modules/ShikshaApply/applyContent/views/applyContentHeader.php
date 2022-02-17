<?php
$headerComponents = array(
        'cssBundle'            	=> 'sa-apply-content',
        'canonicalURL'   	    => $canonicalURL,
        'title'			        => $seoDetails['title'],
        'metaDescription' 	    => $seoDetails['description'],
        'metaKeywords' 		    => $seoDetails['keywords'],
        'pageIdentifier'    	=> $beaconTrackData['pageIdentifier'],
        'firstFoldCssPath'  	=> 'applyContent/css/applyContentFirstFoldCss',
        'trackingPageKeyId' 	=> 480,
        'deferCSS'          	=> true
);

//$this->load->view('common/studyAbroadHeader', $headerComponents);
$this->load->view('studyAbroadCommon/saHeader', $headerComponents);
echo jsb9recordServerTime('SA_APPLYCONTENT_PAGES', 1);
?>

