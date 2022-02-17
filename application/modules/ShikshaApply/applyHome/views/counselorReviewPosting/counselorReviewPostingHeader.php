<?php
$headerComponents = array(
        'cssBundle'         => 'sa-counselor-review-posting',
        'canonicalURL'      => $seoDetails['canonical'],
        'title' 		    => $seoDetails['title'],
        'metaDescription'   => $seoDetails['description'],
        'hideGNB'           => 'true',
        'hideLoginSignupBar' => 'true',
        'responsiveHtml' => true,
        'firstFoldCssPath' => $firstFoldCssPath,
        'deferCSS' => true
);
$this->load->view('studyAbroadCommon/saHeader', $headerComponents);
?>