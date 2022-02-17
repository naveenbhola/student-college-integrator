<?php 
$footerComponents = array(
    'js'=>array('userProfilePageSA'),
    'commonJSV2'=>true,
    'trackingPageKeyIdForReg' => $trackingPageKeyIdForReg,
    'pages'=> array('studyAbroadCommon/imageUploader/imageUploaderHtml')
);

$this->load->view('commonModule/footerV2', $footerComponents);
?>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('studyAbroadUserProfile');?>"></script>
<script>
    var isMobile=true;
    var selfProfile = <?php echo ($selfProfile === true?'true':'false'); ?>;
</script>
<?php if($isEditProfile === true){
    echo "<script>var isEditProfile=true;</script>";
?>
<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('jquery.auto-complete');?>">
<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('croppie');?>">
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('jquery.auto-complete');?>"></script>
<script async src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('croppie.min');?>"></script>
<script async src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('shikshaImageUploader');?>"></script>
<script async src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('exif');?>"></script>
<script id="jsInit"></script>
<?php
$jsInit = str_replace('<script>','<noscript id="nsJSInit">',$this->load->view('registration/common/jsInitialization',null,true));
$jsInit = str_replace('</script>','</noscript>',$jsInit);
echo $jsInit; ?>
<?php }
?>
