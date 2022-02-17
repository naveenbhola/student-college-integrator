<?php if($usergroup == "cms"){ ?>
<title>CMS Control Page</title>
<?php } ?>
<?php if($usergroup == "enterprise"){ ?>
<title>Enterprise Control Page</title>
<?php } ?>

<?php
$middlePanelView = '-1';
$paramArray = array();
$js = array('imageUpload','common','caEnterprise','ajax-api','CalendarPopup');
$jsFooter = array('footer');
$dontShowStartingFormBorder = 0;
$js = array_merge(array('lazyload'),$js);
$headerComponents = array(
'css'	=>	array('headerCms','raised_all','mainStyle','articles','common_new','caenterprise'),
'js'	=> $js,
'jsFooter' => $jsFooter,
'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
'tabName'	=>	'',
'taburl' => site_url('enterprise/Enterprise'),
'metaKeywords'	=>'',
'isOldTinyMceNotRequired' => 1,
'product'=> $product
);
$this->load->view('enterprise/headerCMS', $headerComponents);

if($caPageHeader['subTabType'] == 1)
    $middlePanelView = 'CAEnterprise/ca_details';
elseif($caPageHeader['subTabType'] == 3)
    $middlePanelView = 'CAEnterprise/ca_myTask';
elseif($caPageHeader['subTabType'] == 4)
     $middlePanelView = 'CAEnterprise/mytask_submission.php';
elseif($caPageHeader['subTabType'] == 5)
    $middlePanelView = 'CAEnterprise/cr_wallet';
elseif($caPageHeader['subTabType'] == 6)
    $middlePanelView = 'CAEnterprise/collegeReviewEnterpriseView';
elseif($caPageHeader['subTabType'] == 8)
    $middlePanelView = 'CAEnterprise/chatModeration';
elseif($caPageHeader['subTabType'] == 9)
    $middlePanelView = 'CAEnterprise/cr_bulkPayment';
else 
    $middlePanelView = 'CAEnterprise/ca_discussions';

$paramArray = $caPageHeader;
$dontShowStartingFormBorder = 1;
?>
<!-- Load the javascript file for review moderation-->

<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("campus_ambassador"); ?>"></script>

  <!--Start_Center-->
<SCRIPT>
   var COOKIEDOMAIN = "<?php echo COOKIEDOMAIN; ?>";
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
<?php $this->load->view('enterprise/footer');?>
