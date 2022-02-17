<?php
$residenceLocationValues = $fields['preferredStudyLocation']->getValues();
$tier1Cities = $residenceLocationValues['tier1Cities'];
$tier2Cities = $residenceLocationValues['tier2Cities'];
$tier3Cities = $residenceLocationValues['tier3Cities'];
$statesList  = $residenceLocationValues['statesList'];
if(!function_exists('printPreferredStudyLocationInputDesktop')) {
function printPreferredStudyLocationInputDesktop($id,$name,$registrationHelper,$regFormId) {
?>
	<li style="margin-bottom: 5px;">
		<input type="checkbox" name="preferredStudyLocation[]" class="preferredStudyLocation_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('preferredStudyLocation'); ?> value="<?php echo $id; ?>" onclick="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);" />
		<span><?php echo $name; ?></span>
	</li>
<?php
}
}
?>
<div id='preferredStudyLocationLayerIframeContainer_<?php echo $regFormId; ?>' class="regLayerIframe" style='position:relative; display:none; z-index:98;'>
	<iframe scrolling="no" id="preferredStudyLocationLayerIframe_<?php echo $regFormId; ?>" src="about:blank" frameborder="0" style="z-index:109; position:absolute; filter:alpha(opacity:20);opacity: .2; width:615px; top:-1px;"></iframe>
</div>

<div id='preferredStudyLocationLayer_<?php echo $regFormId; ?>' class="regLayer" style='position:relative; display:none; z-index:99; border: none; box-shadow:none;'>
<div id='preferredStudyLocationLayerInner_<?php echo $regFormId; ?>' style='z-index:110; position:absolute;width:615px; background: #fff; border:1px solid #ccc; top:-1px;'>
		
<div class="city-layer-main">
	<div class="city-layer-col">
		<strong>States - Anywhere In:</strong>
        <ul>
        <?php        
        foreach($statesList as $stateId => $stateDetails) {
			printPreferredStudyLocationInputDesktop('S:'.$stateId,$stateDetails['name'],$registrationHelper,$regFormId);
		}
		?>
        </ul>
	</div>

	<div class="city-layer-col" style="width:110px;">
		<strong>Metro Cities</strong>
        <ul>
		<?php
		foreach($tier1Cities as $list) {
			printPreferredStudyLocationInputDesktop('C:'.$list['cityId'],$list['cityName'],$registrationHelper,$regFormId);
        }
		?>
        </ul>
     </div>
	
    <div class="city-layer-col city-layer-col-last">
    	<strong>Other Cities</strong>
        <ul>
        	<?php
			foreach($tier2Cities as $list) {
				printPreferredStudyLocationInputDesktop('C:'.$list['cityId'],$list['cityName'],$registrationHelper,$regFormId);	
            }
			foreach($tier3Cities as $list) {
				printPreferredStudyLocationInputDesktop('C:'.$list['cityId'],$list['cityName'],$registrationHelper,$regFormId);	
            }
			?>                    
		</ul>
    </div>
    
    <div class="spacer10 clearFix"></div>
	<div align="center" class="city-layer-btn">
		<input type="button" class="orange-button" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setPreferredStudyLocations();" value="OK" title="OK" />
	</div>
</div>

</div>	
</div>
