<ul class="locality-list-head" data-section="locality">
	<?php foreach ($localityFilterValues as $key => $localityId) {
		$checked = '';
		if(in_array($localityId, $appliedLocality)) {
            $checked = "checked='checked'";
        } else {
        	$checked = '';
        } ?>
        <li>
            <input type="checkbox" <?=$checked?> data-val="<?=$localityId?>">
        </li>
	<?php } ?>
</ul>