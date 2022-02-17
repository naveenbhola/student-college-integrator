<?php
    $headerComponents = array(
			    'js'                => array('countryHome'),
			    'css'               => array('studyAbroadCommon', 'countryHome'),
			    'canonicalURL'      => $seoData['canonicalUrl'],
			    'title'             => ucfirst($seoData['seoTitle']),
			    'metaDescription'   => ucfirst($seoData['seoDescription']),
			    'pageIdentifier'    => $beaconTrackData['pageIdentifier']
			
			);
    
    // Study Abroad Header file
    $this->load->view('common/studyAbroadHeader', $headerComponents);
    
    echo jsb9recordServerTime('SA_COUNTRY_HOME_PAGE',1);
    
?>
