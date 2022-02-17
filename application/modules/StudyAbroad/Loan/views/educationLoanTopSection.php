<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 25/10/18
 * Time: 1:06 PM
 */
$otherPropStr = '';
$layerDiv='';
if($isMobile)
{
    $layerDiv = '<a id="applyLoanLayer" tracking_keyid="'.$applyTrackingId.'" href="#register" data-rel="dialog" data-transition="slide" ga-action="educationLoanTop" data-widget="applyLoan" ></a>';
}
else
{
    $otherPropStr .= "gaparams=\"{$beaconTrackData['pageIdentifier']},educationLoanTop,applyLoan\"";
    $classStr = "gaTrack";
}
if($isAlreadyApplied)
{
    $applyBtnStr = "Your application id is under process (Application id: $applicationNumber)";
    $classStr = empty($classStr)?"btn-disable":$classStr.' btn-disable';
}
else
{
    $applyBtnStr = 'Apply for Loan';
    $classStr = empty($classStr)?"":$classStr;
}
$classStr = "class='$classStr'";

?>
<section class="loanCont-widget">
    <h1 class="loan-title">Apply to India's top education loan providers through Shiksha Study Abroad</h1>
    <p>Welcome to Shiksha Study Abroad! We have tied up with India’s leading education loan providers. Their experts will assist you in acquiring education loan for pursuing MS, MBA or any other specialised course abroad.</p>

    <div class="edu-loanWidget">
        <strong>How to apply for education loan?</strong>
        <div class="edu-loanBox">
            <div class="">
                <i class="sgnup-icn"></i>
                <p class="edu-lnDiv">Sign Up on Shiksha</p>
            </div>
            <div class="">
                <i class="sgnup-Licn"></i>
                <p class="edu-lnDiv">Enter Loan Requirements</p>
            </div>
            <div class="">
                <i class="sgnup-Picn"></i>
                <p class="edu-lnDiv">Get Call from Loan Provider</p>
            </div>
        </div>
    </div>

    <div class="loan-btnWrap">
        <a id="applyLoan" href="javascript:void(0);" <?php echo $classStr; ?> tracking_keyid="<?php echo $applyTrackingId; ?>" <?php echo $otherPropStr; ?>><?php echo $applyBtnStr; ?></a>
    </div>
    <?php
     echo $layerDiv;
    ?>
</section>
