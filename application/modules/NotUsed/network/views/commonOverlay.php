<?php
if(!isset($commonOverlayDivLoaded)){
    global $commonOverlayDivLoaded;
    $commonOverlayDivLoaded = 1;
?>

<div id="addRequestOverlay" style="position:absolute;display:none;z-index:100000001;">
<input type = "hidden" id = "refreshFlag" value = "0"/>
	<div id="shadow-container">
        <div class="shadow1">
            <div class="shadow2">
                <div class="shadow3">
                    <div class="container" style="width:300px">
							<div>
							<div class="lineSpace_10">&nbsp;</div>
							<div>
								<div class="mar_full_10p">
                                                                    <div id ="div_extraIdForPageRdrt" type ="hidden" value="0"></div>
									  <div class="lineSpace_5">&nbsp;</div>
									  <div class="lineSpace_5">&nbsp;</div>
									  <div class="normaltxt_11p_blk_arial fontSize_12p" id = "responseforadd"></div>
									  <div class="lineSpace_10">&nbsp;</div>
									  <div align="center">
                                                                              <button type="Submit" onclick="javascript:hideaddRequestOverlay(document.getElementById('div_extraIdForPageRdrt').value);" id="commonConfirmationButtonId" class="orange-button">Ok
											</button>
									  </div>
								</div>
							</div>
							<div class="lineSpace_10">&nbsp;</div>
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
}
?>
