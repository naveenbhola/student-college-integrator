<?php
$headerComponents = array(
			    'css'               => array('studyAbroadCommon','studyAbroadConsultantProfile','studyAbroadListings'),
			    'canonicalURL'      => $seoData["canonicalUrl"],
			    'title'             => ucfirst($seoData["seoTitle"]),
			    'metaDescription'   => ucfirst($seoData["seoDescription"]),
			    'metaKeywords'      => ucfirst($seoData['seoKeywords']),
			    'pageIdentifier'    => $beaconTrackData['pageIdentifier']
			);
$this->load->view('common/studyAbroadHeader', $headerComponents);
echo jsb9recordServerTime('SA_CONSULTANT_PAGES', 1);
?>