<?php
$headerComponents = array(
    'cssBundleMobile' => 'sa-shortlist-mobile',
    'canonicalURL'    => SHIKSHA_STUDYABROAD_HOME."/shortlisted-courses-page",
    'title'           => 'Study Abroad – Colleges, Courses, Exams – Shiksha.com',
    'metaDescription' => 'User shortlisted courses - Shiksha.com',
    'metaKeywords'    => '',
    'firstFoldCssPath'    => 'shortlistPage/css/shortlistFirstFoldCss',
    'deferCSS' => true
);
$this->load->view('commonModule/headerV2',$headerComponents);
echo jsb9recordServerTime('SA_MOB_SHORTLISTPAGE',1);
$this->load->view("shortlistPage/shortlistContent");
$this->load->view("shortlistPage/noResults");
$this->load->view("shortlistPage/shortlistFooter");
?>