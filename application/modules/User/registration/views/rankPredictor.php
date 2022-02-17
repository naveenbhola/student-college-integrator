<?php
$formCustomData['regFormId']  = $regFormId;
$formCustomData['rpExamName'] = $rpExamName;
$formCustomData['trackingPageKeyId']=$trackingPageKeyId;
$this->load->config('RP/RankPredictorConfig',TRUE);
$rpConfig = $this->config->item('settings','RankPredictorConfig');
$formCustomData['rpConfig'] = $rpConfig;
$formCustomData['showPassword'] = true;
?>
<div class="predictor-rank-box">
    <div class="predictor-rank-form flLt">
	<h2><?php echo $rpConfig[$rpExamName]['formHeading'];?></h2>
	<?php echo Modules::run('registration/Forms/LDB',NULL,'rankPredictor',$formCustomData); ?>
    </div>
    <div class="clearFix"></div>
</div>