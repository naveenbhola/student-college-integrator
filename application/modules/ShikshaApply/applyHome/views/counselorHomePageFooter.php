<img id = 'beacon_img' width=1 height=1 >
<?php
	$footerComponents = array(
		'nonAsyncJSBundle'=>'sa-counselor-review-page',
		'asyncJSBundle'=>'async-sa-counselor-review-page',
		);
	$this->load->view('studyAbroadCommon/saFooter',$footerComponents);
    $lastReview = end($reviewInfo);
?>
<script>
var lastReviewId = '<?php echo $lastReview['id'];?>';
var totalReview =   <?php echo ($totalReviewCount>0)?$totalReviewCount:0;?>;
var currentOffet=   <?php echo ($reviewPerPageLimit>0)?$reviewPerPageLimit:0;?>;
var perPageLimit= 	<?php echo ($reviewPerPageLimit>0)?$reviewPerPageLimit:0;?>;
var counselorId = 	'<?php echo base64_encode(($counselorId>0)?$counselorId:0);?>';   
var thkCnlMobile=   '<?php echo base64_encode($counselorInfo['mobile']);?>';
var thkCnlEmail =   '<?php echo base64_encode($counselorInfo['email']);?>';


	$j(window).on('load',function(){
		initilizePagination();
//		bindProfileEvalCalls();
		bindDeleteComment();
		// bind write  a review
		bindWriteReviewClick();
		<?php if($triggerReviewCheck === true){ ?>
		triggerReviewCheck();
		<?php } ?>
		<?php if(!is_null($regnData) && count($regnData)>0){ ?>
		//completeUploadPostRegister('<?php //echo json_encode($regnData); ?>');
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