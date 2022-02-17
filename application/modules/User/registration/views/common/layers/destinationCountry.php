<?php
$destinationCountryValues = $fields['destinationCountry']->getValues();
$regions = $destinationCountryValues['regions'];
$countries = $destinationCountryValues['countries'];
?>

<?php
function displayRegionHeading($regionId,$region,$regFormId)
{
?>
	<strong <?php if($regionId == 5) echo "style='width:130px;'"; ?>>
	<span>
		<input type="checkbox" id="region_<?php echo $regionId; ?>_<?php echo $regFormId; ?>" value="<?php echo $regionId; ?>" name="regions[]" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].toggleCountriesInRegion('<?php echo $regionId; ?>');" />
	</span>
	<?php echo $region->getName(); ?>
	</strong>
<?php
}
function listCountries($countries,$regionId,$regFormId,$registrationHelper)
{
	foreach($countries as $country) {
		$countryId = $country->getId();
		$countryName = $country->getName();
?>        
	<li>
	<span>
		<input type="checkbox" name="destinationCountry[]" class="destinationCountry_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('destinationCountry'); ?> value="<?php echo $countryId; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].toggleRegion('<?php echo $regionId; ?>'); registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);" region="<?php echo $regionId; ?>_<?php echo $regFormId; ?>" />
	</span>
	<label><?php echo $countryName; ?></label>
	</li>
<?php            
    }
}
?>
<div id='destinationCountryLayerIframeContainer_<?php echo $regFormId; ?>' class="regLayerIframe" style='position:relative; display:none; z-index:98;'>
	<iframe scrolling="no" id="destinationCountryLayerIframe_<?php echo $regFormId; ?>" src="about:blank" frameborder="0" style="z-index:109; position:absolute; filter:alpha(opacity:20);opacity: .2; width:615px; top:-1px;"></iframe>
</div>

<div id='destinationCountryLayer_<?php echo $regFormId; ?>' class="regLayer" style='position:relative; display:none; z-index:99;'>
<div id='destinationCountryLayerInner_<?php echo $regFormId; ?>' style='z-index:110; position:absolute;width:775px; background: #fff; border:1px solid #ccc; top:-1px;'>

<div id="countryOverlayForStudyAbroad" class="country-layer">
	<div class="country-layer-cols first">
		<strong>Popular Countries</strong>
		<ul style="margin:0 !important; padding:0 !important;">
		<?php echo listCountries($countries[0],0,$regFormId,$registrationHelper); ?>
		</ul>
	</div>
	<div class="country-layer-cols">
		<?php echo displayRegionHeading(1,$regions[1],$regFormId); ?>
		<ul style="height:70px;"><?php echo listCountries($countries[1],1,$regFormId,$registrationHelper); ?></ul>
		<?php echo displayRegionHeading(6,$regions[6],$regFormId); ?>
		<ul style="height:130px;"><?php echo listCountries($countries[6],6,$regFormId,$registrationHelper); ?></ul>
	</div>
	<div class="country-layer-cols">
		<?php echo displayRegionHeading(2,$regions[2],$regFormId); ?>
		<ul><?php echo listCountries($countries[2],2,$regFormId,$registrationHelper); ?></ul>
	</div>
	<div class="country-layer-cols">
		<?php echo displayRegionHeading(3,$regions[3],$regFormId); ?>
		<ul style="height:110px;"><?php echo listCountries($countries[3],3,$regFormId,$registrationHelper); ?></ul>
		<?php echo displayRegionHeading(8,$regions[8],$regFormId); ?>
		<ul style="height:90px;"><?php echo listCountries($countries[8],8,$regFormId,$registrationHelper); ?></ul>
	</div>
	<div class="country-layer-cols">
		<?php echo displayRegionHeading(4,$regions[4],$regFormId); ?>
		<ul style="height:50px;"><?php echo listCountries($countries[4],4,$regFormId,$registrationHelper); ?></ul>
		<?php echo displayRegionHeading(5,$regions[5],$regFormId); ?>
		<ul style="height:50px;"><?php echo listCountries($countries[5],5,$regFormId,$registrationHelper); ?></ul>
		<?php echo displayRegionHeading(7,$regions[7],$regFormId); ?>
		<ul style="height:60px;"><?php echo listCountries($countries[7],7,$regFormId,$registrationHelper); ?></ul>
	</div>	
</div>

<div class="spacer15 clearFix"></div>
<div align="center"><input type="button" value="OK" class="orange-button" onClick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setDestinationCountries();" /></div>
<div class="spacer15 clearFix"></div>
    
</div>
</div>