<?php if(strpos($widget,"request_callback")!==FALSE) {
    if($in_working_hours){
        $message = "Thank you for your request. A Shiksha counselor will contact you on your mobile.";
    }
    else{
        $message = "A Shiksha counselor will contact you during the week (Monday to Friday) between 9 AM and 6:30 PM.";
    }
?>
    <div class="abroad-layer abroad-layer-tupple" id = "downloadLinkContainer" style="width:600px !important; background:#f8f8f8 !important">
        <div class="abroad-layer-head clearfix">
            <div class="abroad-layer-logo flLt"><i class="layer-logo"></i></div>
            <a id="close-two-step-layer" href="javascript:void(0);" onclick="registrationOverlayComponent.hideOverlay(); return false;" class="common-sprite close-icon flRt"></a>
        </div>
        <div class="abroad-layer-content clearfix" style="padding:0">
            <div class="abroad-step-title" style="padding:0 10px;background: none !important;">
                <p>Thank you for your interest. </p>
            </div>
            <div class="counselor-details">
                <p><?=($message)?></p>
                <a class="button-style big-button" style="padding:10px 40px; font-size:18px; margin-top:15px;" href="Javascript:void(0);" onclick = "$j('#close-two-step-layer').trigger('click');">OK</a>
            </div>
        </div>
    </div>
<?php }else{
    $makePlural = $brochureURL != "" && $universityBrochureURL != ""?'s':'';
    ?>

<div class="abroad-layer abroad-layer-tupple" id = "downloadLinkContainer" style="background:#f8f8f8 !important">
        <div class="abroad-layer-head clearfix">
            <div class="abroad-layer-logo flLt"><i class="layer-logo"></i></div>
            <a id="close-two-step-layer" href="javascript:void(0);" onclick="registrationOverlayComponent.hideOverlay(); return false;" class="common-sprite close-icon flRt"></a>
        </div>
        <div class="abroad-layer-content clearfix">
            <div class="confirm-msg clearfix">
                <i class="common-sprite <?=($brochureURL != "" || $universityBrochureURL != ""?"confirm":"exclamation")?>-icon"></i>
                <div class="msg-details">
                    <?php if($brochureURL != "" || $universityBrochureURL != ""){ ?>
                    <strong>Brochure<?=($makePlural)?> sent to email: <em><?=$email?></em></strong>
                    <p><div style="font-size:16px;margin-bottom:5px;">Direct download brochure<?=($makePlural)?> from the below link<?=($makePlural)?></div>
                        <?php if($brochureURL != "") {
                                $courseBrochure = array('brochureURL'=>base64_encode($brochureURL),
                                                        'listingType'=>'course',
                                                        'listingTypeId'=>$listingTypeId,
                                                        'courseId'     =>$listingTypeId, //course id is required in both cases because when we update download tracking column in emailtracking we do it using course id as response is generated on course only
                                                        'downloadedFrom'=>$downloadedFrom,
                                                        'tempLmsTableId'=>$tempLmsTableId ? $tempLmsTableId:0,
                                                        'brochureEmailInsertId'=>$brochureEmailInsertId?$brochureEmailInsertId:0,
                                                            
                                                        );
                                ?> 
                            <div style= "margin-bottom:5px;">
                                <a style= "margin-left:15px;" href="javascript:void(0)" onclick="startAbroadListingsBrochureDownload('<?=base64_encode(json_encode($courseBrochure))?>')">Click here to download </a> course brochure (brochure size: <?=$brochureSize?>)
                            </div>
                        <?php } ?>
                        <?php if($universityBrochureURL != "") {
                                $universityBrochure = array('brochureURL'=>base64_encode($universityBrochureURL),
                                                            'listingType'=>'university',
                                                            'listingTypeId'=>$universityId,
                                                            'courseId'     =>$listingTypeId, //course id is required in both cases because when we update download tracking column in emailtracking we do it using course id as response is generated on course only
                                                            'downloadedFrom'=>$downloadedFrom,
                                                            'tempLmsTableId'=>$tempLmsTableId ? $tempLmsTableId:0,
                                                            'brochureEmailInsertId'=>$brochureEmailInsertId?$brochureEmailInsertId:0,
                                                            );
                                ?>
                            <div style= "margin-bottom:5px;">
                                <a style= "margin-left:15px;" href="javascript:void(0)" onclick="startAbroadListingsBrochureDownload('<?=base64_encode(json_encode($universityBrochure))?>')">Click here to download </a> university brochure (brochure size: <?=$universityBrochureSize?>)
                            </div>
                        <?php } ?>
                    </p>
                    <?php } else { ?>
                    <strong style="display:block; padding-top:3px;">Sorry, E- Brochure of <?=htmlentities($name)?> is currently unavailable.</strong>
                    <?php } ?>
                </div>
            </div>
            <div id="alsoViewedRecommendationLayer" style="width:765px">
                <?php if($alsoViewedRecommendationHTML != ''){
                    echo $alsoViewedRecommendationHTML;
                }
                ?>
            </div>
        </div>
</div>
<?php } ?>
<script>
    $j("#close-two-step-layer").bind("click",function(event){
            if(typeof(skipReloadFlag)=='undefined' || skipReloadFlag !='rmcSuccessPageSkipReload')
                {
                    window.location.reload();
                }
		    });
    var intervalId = setInterval(realignDownloadLinkOverlay,100);
    hideFullPageProgressLayer();
    window.onbeforeunload = false;
</script>