<!--<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>-->
 <?php
$headerComponents = array(
        'css'               => array('studyAbroadCommon','studyAbroadSignUp'),
        'canonicalURL'      => $seoUrl,
        'title'	=>$seoDetails['title'],
        'metaDescription' => $seoDetails['description'],
        'hideGNB'           => 'true',
        'hideLoginSignupBar' => 'true',
);

$this->load->view('common/studyAbroadHeader', $headerComponents);
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">