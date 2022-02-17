<?php //if($newSAOverlay === true){ // goin forward we use this ?>
<div class="cmn-popup" id="newOverlayContainer" style="display: none;">
	<a href="Javascript:void(0);" class="cmn-hd" id="newOverLayCrossButton" onclick="hideNewAbroadOverlay();" title="close">&times;</a>
	<?php if($hideTrackingFields!==true){?>
		<input type="hidden" id="tracking_page_key_new_layer" name="tracking_page_key_new_layer"/>
		<input type="hidden" id="page_referrer_new_layer" name="page_referrer_new_layer"/>
	<?php } ?>
	<div class="cmn-det">
		<strong id="newOverlayTitle">Title</strong>
		<div id="newOverlayContent"></div>
	</div>
    <div style="position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; opacity: 0.7; background: url('//<?php echo IMGURL;?>/public/images/loader.gif') no-repeat scroll 50% 50% rgb(254, 255, 254); z-index: 999999; display: none;" id="AbroadAjaxLoaderNewLayer"></div>
    <script type="text/javascript"> var isSANewOverlayActive = false;</script>
</div>
<?php //} ?>
<!-- Below HTML kept for old overlay -->
<div class="abroad-layer cmn__ovrly" id="overlayContainer" style="display: none;">
	<div class="abroad-layer-head clearfix">
    	<div class="abroad-layer-logo flLt"><i alt="shiksha.com" class="layer-logo"></i></div>
        <a href="JavaScript:void(0);" id="overLayCrossButton" onclick="hideAbroadOverlay();" title="close" class="common-sprite close-icon flRt"></a>
        <?php if($hideTrackingFields!==true){?>
            <input type="hidden" id="tracking_page_key" name="tracking_page_key"/>
            <input type="hidden" id="page_referrer" name="page_referrer"/>
            <input type="hidden" id="saABTracking" name="saABTracking" value="yes"/>
        <?php } ?>
    </div>
    
    <div class="abroad-layer-content clearfix">
    	<div class="abroad-layer-title" id="overlayTitle"></div>
		<div id="overlayContent"></div>
    </div>
    <div style="position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; opacity: 0.7; background: url('//<?php echo IMGURL;?>/public/images/loader.gif') no-repeat scroll 50% 50% rgb(254, 255, 254); z-index: 999999; display: none;" id="AbroadAjaxLoader"></div>
    <script type="text/javascript"> var isSAOverlayActive = false;</script>
</div>