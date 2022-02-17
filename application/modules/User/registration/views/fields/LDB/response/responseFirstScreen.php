
<?php 
	if(empty($isMultipleStreamCase) || $isMultipleStreamCase != 'yes'){
		echo $this->load->view('registration/fields/LDB/response/preferredLocations'); 
	}

	if(!empty($workExList)){ 
		$this->load->view('registration/fields/LDB/response/workExp'); 
	}

?>

<div class="ih">
	<?php $unMappedSpec = $mappedHierarchies['hierarchies'][0]; unset($mappedHierarchies['hierarchies'][0]); ?>
	<input type="hidden" value="<?php echo $mappedHierarchies['stream']; ?>" name="stream" id="stream_<?php echo $regFormId; ?>" regformid="<?php echo $regFormId; ?>" />
	<input type="hidden" value="<?php echo $level->getId(); ?>" name="level" id="level_<?php echo $regFormId; ?>" />
	<input type="hidden" value="<?php echo $credential->getId(); ?>" name="credential" id="credential_<?php echo $regFormId; ?>" />
	<?php foreach($mappedHierarchies['hierarchies'] as $subStreamId=>$specilaizations){ ?>
	<div class="ssGroup">
		<input type="checkbox" value="<?php echo $subStreamId; ?>" name="subStream[]" id="subStream_<?php echo $subStreamId.'_'.$regFormId; ?>" class="pLevel" checked="checked"/>
		<div class="childGrp">
			<?php foreach($specilaizations as $key=>$specId){ ?>
			<input type="checkbox" value="<?php echo $specId; ?>" id="spec_<?php echo $specId.'_'.$regFormId; ?>" class="cLevel" name="specializations[]" checked="checked" parentId="subStream_<?php echo $subStreamId.'_'.$regFormId; ?>"/>
			<?php } ?>
		</div>
	</div>
	<?php }  ?>
	<div class="unmppedSpec">
		<?php foreach($unMappedSpec as $key=>$specId){ ?>
		<input type="checkbox" value="<?php echo $specId; ?>" id="spec_unmp_<?php echo $specId.'_'.$regFormId; ?>" class="unmp cLevel" name="specializations[]" checked="checked" />
		<?php } ?>
	</div>
</div>

<input type="hidden" name="baseCourses[]" value="<?php echo $baseCourse; ?>" id="baseCourse_<?php echo $baseCourse; ?>" checked="checked">


<?php if($mode){ ?>
	<input type="hidden" id="mode_<?php echo $mode; ?>" value="<?php echo $mode; ?>" name="educationType[]" >
<?php } ?>

<?php $this->load->view('registration/fields/LDB/response/courseDependentFields'); ?>
