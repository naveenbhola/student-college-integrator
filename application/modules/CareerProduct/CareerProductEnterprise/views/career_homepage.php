<?php if($validateuser[0]['usergroup'] == "cms"){?>
<title>CMS Control Page</title>
<?php } ?>
<?php if($validateuser[0]['usergroup'] == "enterprise"){ ?>
<title>Enterprise Control Page</title>
<?php } ?>

<?php
$middlePanelView = '-1';
$paramArray = array();
$js = array('imageUpload','common','careerEnterprise','ajax-api');
$jsFooter = array('footer');
$dontShowStartingFormBorder = 0;
$js = array_merge(array('lazyload'),$js);
$headerComponents = array(
'css'	=>	array('headerCms','raised_all','mainStyle','articles','common_new','careers_cms'),
'js'	=> $js,
'jsFooter' => $jsFooter,
'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
'tabName'	=>	'',
'taburl' => site_url('enterprise/Enterprise'),
'metaKeywords'	=>''
);
$this->load->view('enterprise/headerCMS', $headerComponents);
?>
  <script>var careerObj = new CareerEnterprise();</script>
<?php
 if($careerPageHeader['careerType'] == 1)
    $middlePanelView = 'CareerProductEnterprise/addEditCareer';
else if($careerPageHeader['careerType'] == 2)
    $middlePanelView = 'CareerProductEnterprise/careersDetails';
else if($careerPageHeader['careerType'] == 3)
   $middlePanelView = 'CareerProductEnterprise/howDoIGetThereMapping';
else if($careerPageHeader['careerType'] == 4)
   $middlePanelView = 'CareerProductEnterprise/interlinkingLDBCourseToCareers';
 else 
  $middlePanelView = 'CareerProductEnterprise/featureColleges';

$paramArray = $careerPageHeader;
$dontShowStartingFormBorder = 1;
?>
 
<!--Start_Center-->

<SCRIPT>
   var SITE_URL = '<?php echo base_url() ."/";?>';
   var userGroup = '<?php echo $usergroup; ?>';
   var prodId = '<?php echo $prodId; ?>';
</SCRIPT>


<div class="mar_full_10p">
        <?php $this->load->view('enterprise/cmsTabs'); ?>
	<?php
	if($middlePanelView != '-1') { 
		$this->load->view($middlePanelView,$paramArray); 
	} 
	?>
</div>
<?php $this->load->view('enterprise/footer'); ?>
<?php if($careerPageHeader['careerType'] == 1){ ?>
<script>
window.onload=function(){careerObj.initializeClickEventsForStreamsOptionBox()};
</script>
<?php } ?>
