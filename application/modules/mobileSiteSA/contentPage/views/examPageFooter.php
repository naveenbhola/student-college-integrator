<div style="display:none">
		<img id='beacon_img' src="<?php echo IMGURL_SECURE; ?>/public/images/blankImg.gif" width=1 height=1 >
</div>
<?php
    // if we need to add other elements having data-role = page it can be passed as below
    $footerComponents = array(
    							'pages'=>array('contentUserRegistrationPanel'),
    							'trackingPageKeyIdForReg' => 490
    						);
    $this->load->view('commonModule/footer',$footerComponents);
?>
<script>
$j(document).ready(function($j){
        var img = document.getElementById('beacon_img');
        var randNum = Math.floor(Math.random()*Math.pow(10,16));
        img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0011007/<?=$contentType?>+<?=$contentId?>';
		
		//initializeCommentSection();
        modifiySizeOfVideo('sop-content');
        modifiySizeOfImages('sop-content');
		//we are calling this after some time as well because some images load after document ready
		setTimeout(function(){modifiySizeOfVideo('sop-content');},400);
        setTimeout(function(){modifiySizeOfImages('sop-content')},400); 
		
		$j(window).on( "orientationchange", function( event ) {
            modifiySizeOfVideo('sop-content');
			modifiySizeOfImages('sop-content');
        });	
		if ($j("#emailGuideSticky").length > 0) {
			makeSticky('emailGuideSticky','up');
		}
		initializePageStickies();
		initDownloaderHideEffect();
		initializeCommentSection();
		
        $j(".explore-more-section-exam-page li:last").addClass("last");	
});
$j(window).on("load",function(){checkAndScrollTo();});
</script>
