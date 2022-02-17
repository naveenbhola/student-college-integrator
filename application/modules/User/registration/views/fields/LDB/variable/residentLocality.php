<?php
	
    $localitiesArr = array();
    foreach($localities as $zoneId => $localitiesInZone) {
        $firstLocality = reset($localitiesInZone);
        $localitiesArr[$firstLocality['zoneName']] = array();
        foreach($localitiesInZone as $locality) {
            $localitiesArr[$firstLocality['zoneName']][$locality['localityId']] = $locality['localityName'];
        }
    }

?>

<div class="reg-form signup-fld invalid ssLayer" tabindex="2" label="Locality" position="<?php if(!empty($isUserLoggedIn) && $isUserLoggedIn == 'yes'){ echo 'bottom'; }else{ echo 'top'; } ?>" layerFor="residenceLocality" regFormId="<?php echo $regFormId; ?>" hasOptGroup="yes" id="residenceLocality_block_<?php echo $regFormId; ?>" type="layer" regfieldid="residenceLocality" layerTitle="Locality" layerHeading="Locality" sub-label="Select one"> 
	<div class="ngPlaceholder">Nearest locality you live in</div>
	<div class="multiinput" id="residenceLocality_input_<?php echo $regFormId; ?>"></div>
	<div class="input-helper">
		<div class="up-arrow"></div>
		<div class="helper-text"></div>
	</div>
</div>
<div class="cusLayer ctmScroll layerHtml ih">
</div>

<div class="ih sValue">
	<select id="residenceLocality_<?php echo $regFormId; ?>" name="residenceLocality" prefNum='1' regfieldid="residenceLocality" mandatory="1" label="Residence Locality" caption="Nearest locality you live in">
	<option value='-1'>Select</option> 
	<?php foreach($localitiesArr as $zone=>$localities){ ?>
		<optgroup label="<?php echo $zone; ?>">
			<?php foreach ($localities as $localityId => $localityName) { ?>
				<option value="<?php echo $localityId; ?>"><?php echo $localityName; ?></option>
			<?php } ?>
		</optgroup>
	<?php } ?>
	</select>
</div>