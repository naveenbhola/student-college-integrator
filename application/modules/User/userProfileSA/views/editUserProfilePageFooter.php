<?php
$footerComponents = array(  'nonAsyncJSBundle' => 'sa-edit-profile',
                            //'nonAsyncJSBundleMobile' => 'sa-edit-profile-mobile',
                            'hideHTML'			=> "true"
                    );
$this->load->view('studyAbroadCommon/saFooter', $footerComponents);
?>
<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('jquery.auto-complete');?>">
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('jquery.auto-complete');?>"></script>
<?php foreach(array("commonSA","registrationSA","userProfilePageSA") as $jsFile) { ?>
<script src="//<?php echo JSURL; ?>/public/mobileSA/js/<?php echo getJSWithVersion($jsFile,'abroadMobile');?>"></script>    
<?php } ?>
<script id="jsInit"></script>
<?php
$jsInit = str_replace('<script>','<noscript id="nsJSInit">',$this->load->view('registration/common/jsInitialization',null,true));
$jsInit = str_replace('</script>','</noscript>',$jsInit);
echo $jsInit; 
$this->load->view('studyAbroadCommon/imageUploader/imageUploaderHtml');
?>
<script>
    var isEditProfileDesktop=true;
	$j(window).on('load',function() {
		initializeEditForm();
	});
</script>