<?php global $queryStringFieldNameToAliasMapping; 
            $appliedFilters = $request->getAppliedFilters();
            $appliedRestrictions = $appliedFilters['saScholarshipSpecialRestriction'];
?>
<li>
	<input type="checkbox" class="main__check">
	<i class="custm__ico"></i>
	<h3 class="main__titl f12-clr3 fnt-sbold">Special restrictions</h3>
	<div id="rstScroll" class="restictions__sc">
	<ul class="sub__menu">
	<?php foreach($scholarshipData['filterData']['saScholarshipSpecialRestriction'] as $restrictionId=> $restrictionData){ ?>
		<li>
                <?php unset($scholarshipData['filterData']['saScholarshipSpecialRestriction_parent'][$restrictionId]);?>
		<a class="chek__box">
			<div class="">
			<input type="checkbox" name="specialRestrictions[]" id="restriction_<?php echo $restrictionId; ?>" class="inputChk" value="<?php echo $restrictionId; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['specialRestrictions'];?>" <?php echo (in_array($restrictionId,$appliedRestrictions)?'checked':''); ?>/>
			<label class="f12-clr3 check__opt" for="restriction_<?php echo $restrictionId; ?>">
				<i></i>
			<?php echo $restrictionData['name']." (".$restrictionData['count'].")"; ?>
			</label>
			</div>
		</a>
		</li>
		<?php } ?>
                <?php foreach($scholarshipData['filterData']['saScholarshipSpecialRestriction_parent'] as $restrictionId=> $restrictionData){ ?>
		<li>
                <a class="chek__box">
			<div class="">
			<input type="checkbox" disabled name="specialRestrictions[]" id="restriction_<?php echo $restrictionId; ?>" class="inputChk" value="<?php echo $restrictionId; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['specialRestrictions'];?>" <?php echo (in_array($restrictionId,$appliedRestrictions)?'checked':''); ?>/>
			<label class="f12-clr3 check__opt" for="restriction_<?php echo $restrictionId; ?>">
				<i></i>
			<?php echo $restrictionData['name']." (0)"; ?>
			</label>
			</div>
		</a>
		</li>
		<?php } ?>
	</ul>
	</div>
</li>