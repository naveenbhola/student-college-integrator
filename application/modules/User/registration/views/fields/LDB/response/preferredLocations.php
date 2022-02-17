<div>
	<div id="prefCityHolder_<?php echo $regFormId; ?>" class="ih">
	</div>

	<div id="prefLocalityHolder_<?php echo $regFormId; ?>" class="ih">
	</div>
</div>

<script type="text/javascript">
	<?php 
		$localities = array();
		if(!empty($locations) && count($locations) == 1){ 
			foreach ($locations as $key => $value) {
				$localities = 	$value['localities'];
			}
		} 
	?>

	<?php if((!empty($locations) && count($locations) > 1) || (!empty($localities) && count($localities) > 1) ){ ?>
			userRegistrationRequest['<?php echo $regFormId; ?>'].prefLocations = JSON.parse('<?php echo json_encode($locations); ?>');	
	<?php }else{ ?>
			userRegistrationRequest['<?php echo $regFormId; ?>'].prefLocations = {};
	<?php } ?>
</script>
	