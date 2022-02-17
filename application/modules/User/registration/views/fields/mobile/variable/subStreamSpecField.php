<?php 

$subStreamSpec = $fields['subStreamSpecializations']->getValues(array('streamIds'=>array($streamId), 'baseCourseIds'=>$baseCourses, 'customSubStreamSpecializations'=>$customFieldValueSource['subStreamSpecs']));

if(!empty($subStreamSpec[$streamId]['substreams'])){ ?>
	<?php foreach($subStreamSpec[$streamId]['substreams'] as $subStreamId=>$subStreamValues){ ?> 
		<div class="subStrmGrp nav-cont">
			<input type="checkbox" id="subStream_<?php echo $subStreamId.'_'.$regFormId; ?>" class="pLevel sSS ih" name="subStream[]" value="<?php echo $subStreamId; ?>" classHolder="sSS_<?php echo $regFormId; ?>" match="sS_<?php echo $subStreamId.'_'.$regFormId; ?>" norest = 'yes' label="<?php echo $subStreamValues['name']; ?>">
			<div class="child">
				<?php if(!empty($subStreamValues['specializations'])){ ?>

					<?php foreach($subStreamValues['specializations'] as $SubSpecKey=>$subSpecValues){ ?>

						<input type="checkbox" id="spec_<?php echo $subSpecValues['id'].'_'.$subStreamId.'_'.$regFormId; ?>_uss" class="cLevel sSS_<?php echo $regFormId; ?> ih" classHolder="sSS_<?php echo $regFormId; ?>" name="specializations[]" value="<?php echo $subSpecValues['id']; ?>" match="sS_<?php echo $subStreamId.'_'.$regFormId; ?>" parentId="subStream_<?php echo $subStreamId.'_'.$regFormId; ?>" norest = 'yes' label="<?php echo $subSpecValues['name']; ?>">
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
<?php } ?>

<?php if(!empty($subStreamSpec[$streamId]['specializations'])){ ?>
	<div class="unmppedSpec">
		<?php 
		foreach ($subStreamSpec[$streamId]['specializations'] as $specId => $specValues) { ?>
			<input type="checkbox" id="spec_<?php echo $specId.'_'.$regFormId; ?>" class="unmp cLevel sSS_<?php echo $regFormId; ?> ih" name="specializations[]" value="<?php echo $specId; ?>" classHolder="sSS_<?php echo $regFormId; ?>" match="spec_<?php echo $specId.'_'.$regFormId; ?>" norest = 'yes' label="<?php echo $specValues['name']; ?>">

		<?php } ?>
	</div>
<?php } ?>