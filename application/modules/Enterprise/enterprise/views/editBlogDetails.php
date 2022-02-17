<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if($usergroup == "cms"){ ?>
<title>CMS Control Page</title>
<?php } ?>
<?php if($usergroup == "enterprise"){ ?>
<title>Enterprise Control Page</title>
<?php } ?>

<?php
$headerComponents = array(
	'css'	=>	array('headerCms','raised_all','mainStyle','footer','cal_style'),
    'js'	=>	array('common','blog','prototype'),
	'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
	'tabName'	=>	'',
    'taburl' => site_url('enterprise/Enterprise'),
	'metaKeywords'	=>''
);
$this->load->view('enterprise/headerCMS', $headerComponents);
?>
<script>
var completeCategoryTree = eval(<?php echo $categoryForLeftPanel; ?>);
</script>
</head>
<body>
   <div id="dataLoaderPanel" style="position:absolute;display:none">
      <img src="/public/images/loader.gif"/>
   </div>
<!--Start_Center-->
<div class="lineSpace_10">&nbsp;</div>
<div class="mar_full_10p">
	<?php $this->load->view('blogs/createBlog_Form',$blog); ?>
</div>
<div class="lineSpace_10">&nbsp;</div>
<?php $this->load->view('enterprise/footer'); ?>
