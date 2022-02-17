<?php
    $headerComponents = array(
    	'cssBundle'         => 'sa-countryhome-page',
        'canonicalURL'      => $seoData['canonicalUrl'],
        'title'             => ucfirst($seoData['seoTitle']),
        'metaDescription'   => ucfirst($seoData['seoDescription']),
        'pageIdentifier'    => $beaconTrackData['pageIdentifier'],	
        'firstFoldCssPath'  => $firstFoldCssPath,
        'trackingPageKeyId' => 466,
        'deferCSS'			=> true,	
        'skipCompareCode'   => true
			);
    
    // Study Abroad Header file
    $this->load->view('studyAbroadCommon/saHeader', $headerComponents);
    
    echo jsb9recordServerTime('SA_COUNTRY_HOME_PAGE',1);
    
?>
