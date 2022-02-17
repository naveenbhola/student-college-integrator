<script type="text/javascript" src="/public/js/jquery.fixheadertable.js"></script>
<script type="text/javascript">
var executiveHierarchy = <?php echo (empty($executiveHierarchy) ? '{}' : json_encode($executiveHierarchy)); ?>;
var executiveClientMapping = <?php echo (empty($executiveClientMapping) ? '{}' : json_encode($executiveClientMapping)); ?>;
var clientInstituteMapping = <?php echo (empty($clientInstituteMapping) ? '{}' : json_encode($clientInstituteMapping)); ?>;
var institutePaid = <?php echo (empty($institutePaid) ? '{}' : json_encode($institutePaid)); ?>;
var clientLeadGenieMapping = <?php echo (empty($clientLeadGenieMapping) ? '{}' : json_encode($clientLeadGenieMapping)); ?>;
var clientLeadPortingMapping = <?php echo (empty($clientLeadPortingMapping) ? '{}' : json_encode($clientLeadPortingMapping)); ?>;
var clientResponsePortingMapping = <?php echo (empty($clientResponsePortingMapping) ? '{}' : json_encode($clientResponsePortingMapping)); ?>;

var names = <?php echo (empty($names) ? '{}' : json_encode($names)); ?>;

function populateDropDowns(widgetType) {
<?php if (!empty($executiveHierarchy)) { ?>
	var userId = '<?php echo $userId; ?>';
	var userDivId = "#" + widgetType + "_parent-" + userId +"-checker";
	if(widgetType != 'response') {
		$j(userDivId).attr('checked','checked');
		$j(userDivId).click();
		$j(userDivId).attr('checked','checked');
	}
<?php } elseif (!empty($executiveClientMapping)) { ?>
	populateClientList(widgetType);
<?php } elseif (empty($salesUser)) { ?>
	populateOtherList(widgetType);
<?php } ?>
	$j("#" + widgetType + "_Form").find('.formbutton').attr('disabled',false);
}

$j(document).ready(function() {
	$j('.dropdown').mousedown(function() {
		if (typeof chartDivIds !== 'undefined') {
			for (var index = 0; index < chartDivIds.length; index++) {
				if (dateLayerVisible[chartDivIds[index]] == 'yes') {
					hideDateLayer(chartDivIds[index]);
				}
			}
		}
		
		$j(this).attr('disabled','disabled');
		var id = this.id;
		var widgetType = id.split('_')[0];
		var dropDown = id.split('_')[1];
		var dropDownSelection = '#' + widgetType + '_' + dropDown + '_selection';
		
		$j('.dropdown_selection').each(function () {
			if(! $j(this).is(dropDownSelection) ) {
				$j(this).hide();
			}
		});
		
		$j(dropDownSelection).toggle();
		setTimeout(function () {
			$j('#' + id).attr('disabled',false);
		},10);
	});
	
	$j('.dropdown').click(function(event) {
		event.stopPropagation();
	});
	
	$j('.dropdown_selection').click(function(event) {
		event.stopPropagation();
	});
	
	$j(document).click(function() {
		$j('.dropdown_selection').hide();
		$j('.dropdown').attr('disabled',false);
	});
	
	$j('.populatedropdowns').each(function () {
		$j(this).click();
	});
});
</script>