<img id = 'beacon_img' width=1 height=1 />
<?php 	
	$footerComponents = array(
        'nonAsyncJSBundle' => 'sa-apply-home',
		'asyncJSBundle'    => 'async-sa-apply-home',
		'hideFeedbackHTML' => true
	);
	$this->load->view('studyAbroadCommon/saFooter', $footerComponents);
	$contentId = $contentData['id'];
	$contentType = $contentData['type'];
?>
<script>
	/* whatever changes you do here plz implement same on the rmc success page footer as well because this page is implemented there without footer*/
	var crossBtnFlg;
	$j(window).on('load',function(){
		initializeApplyPageWidgets();
		var fromScrp = getCookie('fromSCRP');
 		if(typeof fromScrp != 'undefined' && fromScrp != ''){
 			var trackingIds = fromScrp.split('|');			
 			$j('a.evaluationCall-btn').attr('gfpectrackingid',trackingIds[0]);			
 			$j('a.evaluationCall-btn').attr('profileevaluationtrackingid',trackingIds[1]);
 			setCookie('fromSCRP','');
 			$j('a.evaluationCall-btn').click();
 		}
		<?php if(!is_null($regnData) && count($regnData)>0){ ?>
		crossBtnFlg = true;
		regnData = '<?php echo json_encode($regnData); ?>';
		loadStudyAbroadForm({'tracking_page_key':<?=$regnData['trackingId']?>},'/applyHome/ApplyHome/getExamDocUploadLayer','examUpload');
		bindUploadFormEvent();
        // bind final done btn and that layer's X btn to reload the page
        bindUploadDoneEvent();
        //bind upload close button
        bindUploadCloseEvent();
		<?php } ?>
	});
</script>