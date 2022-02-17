<?php 
    switch($profileEvalCTALocation)
    {
        case 'top'   : // covered in headerBannerSection
                break;
        case 'middle':
                $gfpecTrackingId = 921;
                $profileEvaluationTrackingId = ((!$onRMCSuccessFlag)?924:927);
                break;
        case 'bottom':
                $gfpecTrackingId = 922;
                $profileEvaluationTrackingId = ((!$onRMCSuccessFlag)?925:928);
                break;
    }
?>
<div class="prfl-evlCal">
    <a class="gfpec prfl-btn" href="Javascript:void(0);" gfpecTrackingId="<?php echo $gfpecTrackingId; ?>" profileEvaluationTrackingId="<?php echo $profileEvaluationTrackingId; ?>"><?php echo $profEvalCTAText; ?></a>
</div>