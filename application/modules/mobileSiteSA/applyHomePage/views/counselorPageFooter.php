<?php
// if we need to add other elements having data-role = page it can be passed as below
    $footerComponents = array(
//                                'pages'=>array('applyHomePage/widgets/examScoreUploadLayer','applyHomePage/widgets/uploadThankYouLayer','applyHomePage/widgets/removeFileConfirmation', 'widgets/examScoreWarningMsgLayer'),
                                'js'                => array('applyHomePageSA'),
                                'commonJSV2'=>true,
                                'trackingPageKeyIdForReg' => '916'
                            );
    $this->load->view('commonModule/footerV2',$footerComponents);
    $lastReview = end($reviewInfo);
?>


<script>
var lastReviewId = '<?php echo $lastReview['id'];?>';
var totalReview =   <?php echo ($totalReviewCount>0)?$totalReviewCount:0;?>;
var currentOffet=   <?php echo ($reviewPerPageLimit>0)?$reviewPerPageLimit:0;?>;
var perPageLimit= 	<?php echo ($reviewPerPageLimit>0)?$reviewPerPageLimit:0;?>;
var counselorId = 	'<?php echo base64_encode(($counselorId>0)?$counselorId:0);?>'; 
</script>
