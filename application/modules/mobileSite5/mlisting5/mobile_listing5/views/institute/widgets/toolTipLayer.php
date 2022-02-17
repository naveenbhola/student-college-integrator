<?php
	$toolTipData            = $this->config->item("instituteToolTipData");
	$inlineData             = $topCardData['inlineData'];
	$instituteImportantData = $topCardData['instituteImportantData'];
?>
<div class="popup_layer hid" id="helpTextSection" data-role="dialog" data-transition="none" data-enhance="false">

	<div class="hlp-popup nopadng">
	    <a href="javascript:void(0);" class="hlp-rmv" onclick="closeHelpText();">&times;</a>
	    <div class="glry-div amen-div">
	        <div class="hlp-info">
	        <div class="loc-list-col">
	        	<div class="prm-lst">
				<?php
					foreach ($toolTipData as $key => $value) {
						if(array_key_exists($key, $inlineData) || array_key_exists($key, $instituteImportantData)){
				?>
					<div id="help_<?php echo $key;?>" class="amen-box">
                        <strong><?php echo $value['name'];?></strong>
                        <p class="para-L3"><?php echo $value['helptext'];?></p>
						                                    
                    </div>
                <?php
            			}
					}
				?>
				</div>
			</div>
	        </div>
	    </div>
	</div>      

</div>