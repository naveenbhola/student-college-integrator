<?php

//load page header
$headerComponents = array(
	'css'               => array('studyAbroadCommon','studyAbroadAccountSetting'),
	'canonicalURL'      => '',	
	'title'             => 'Account settings',
	'metaDescription'   => '',
    'metaKeywords'      => '',
	'pgType'	        => 'abraodUserProfilePage'
);

$this->load->view('common/studyAbroadHeader', $headerComponents);

//echo jsb9recordServerTime('SA_CATEGORY_PAGE',1);
$context = 'abroadUserSetting';
  echo Modules::run('registration/Forms/LDB',"studyAbroadRevamped",'abroadUserSetting');



$footerComponents = array(
    'js'   => array('json2','studyAbroadAccountSetting'),
    'nonAsyncJSBundle'=>'sa-user-profile-page',
    'asyncJSBundle'=>'async-sa-profile-page',
  );
$this->load->view('common/studyAbroadFooter',$footerComponents);

?>
<div style="position: fixed;top: 0px; left: 0px; opacity: 0.7; background: url('/public/images/loader.gif') no-repeat scroll 50% 50% rgb(254, 255, 254); z-index: 999999; display: none;" id="AbroadAjaxLoaderFull"></div>
<?php $this->load->view('registration/common/jsInitialization'); ?>


<script>
<?php if($loggedInUserData['abroadSpecialization'] !=''){?>
var specializationId = "<?= $loggedInUserData['abroadSpecialization']?>";
<?php } ?>
$j(document).ready(function($j) {
  
  initializePageOnLoad();
  validatePrefilledExams();
});
</script>
