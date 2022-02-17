<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 24/10/18
 * Time: 11:23 AM
 */
$headerComponents = array(
    'canonicalURL'      => $seoDetails['url'],
    'title'             => $seoDetails['seoTitle'],
    'metaDescription'   => $seoDetails['seoDescription'],
    'metaKeywords'      => $seoDetails['seoMetaKeywords'],
    'firstFoldCssPath'  => 'Loan/css/educationLoanFirstFoldCss',
    'pageIdentifier'   => $beaconTrackData['pageIdentifier'],
    'deferCSS'          => true
);
if($isMobile)
{
    $headerComponents['cssBundleMobile'] = 'sa-education-loan-mobile';
    $headerView = 'commonModule/headerV2';
}
else
{
    $headerComponents['trackingPageKeyId'] = 1913;
    $headerComponents['cssBundle'] = 'sa-education-loan';
    $headerView = 'studyAbroadCommon/saHeader';
}
$this->load->view($headerView, $headerComponents);
?>