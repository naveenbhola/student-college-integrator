<?php
$headerComponents = array(
			    'css'               => array('studyAbroadCommon','studyAbroadContentOrg'),
			    'canonicalURL'      => $seoData["canonicalUrl"],
			    'title'             => ucfirst($seoData["seoTitle"]),
			    'metaDescription'   => ucfirst($seoData["seoDescription"]),
			    'metaKeywords'      => ucfirst($seoData['seoKeywords']),
			    'pageIdentifier'    => $beaconTrackData['pageIdentifier'],
                	    'robotsMetaTag'     => 'NOINDEX, NOFOLLOW'
			);
$this->load->view('common/studyAbroadHeader', $headerComponents);
echo jsb9recordServerTime('SA_CONTENT_ORG_PAGES', 1);
?>