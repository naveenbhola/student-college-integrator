<?php
// if we need to add other elements having data-role = page it can be passed as below
$footerComponents = array(
    'commonJSV2'=>true,
    'trackingPageKeyIdForReg' => 901,
    'js'    => array('applyHomePageSA','vendor/jquery.lazy.min'),
    'pages'=>array('widgets/examScoreWarningMsgLayer', 'widgets/examScoreUploadLayer','widgets/uploadThankYouLayer','widgets/removeFileConfirmation')
);
$this->load->view('commonModule/footerV2',$footerComponents);
?>
<script>
var pageIdentifier = 'applyHomePage';
</script>