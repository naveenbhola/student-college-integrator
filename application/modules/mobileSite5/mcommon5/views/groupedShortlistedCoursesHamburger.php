<?php //---------------GROUPING SHORTLISTED SUBCATEGORIES STARTS HERE--------------- ?>
<?php 
if(sizeof($courseShortArray)>0 && $courseShortArray[0] !='') {
	$total = sizeof($courseShortArray);
}
else{$total = 0;}
if($total > 0) { ?>
<ul id="groupedShortlistedCourses" class="list-items recom-items">
<?php if(isset($groupedSubCategoryData[MBA_SUBCAT_ID])) { ?>
<li>
	<a href="javascript:void(0);" onclick="window.location='/my-shortlist-home'" style="color:#333;">
	<strong>
		<?php echo key($groupedSubCategoryData[MBA_SUBCAT_ID])." ({$groupedSubCategoryData[MBA_SUBCAT_ID][key($groupedSubCategoryData[MBA_SUBCAT_ID])]})";?>
	</strong>
	</a>
	</li>
<?php 
	unset($groupedSubCategoryData[MBA_SUBCAT_ID]);
	} 
?>
	<?php foreach($groupedSubCategoryData as $subCategoryId=>$subCategoryData) { ?>
	<li>
		<?php 
			$url = ($subCategoryId == 23) ? "" : "window.location='/shortlisted-colleges'; trackEventByGAMobile('MOBILE_RIGHT_PANEL_SHORTLIST_FROM_<?php echo strtoupper($boomr_pageid);?>');";
		?>
		<a href="javascript:void(0);" onclick="<?php echo $url;?>" style="color:#333;">
			<strong>
			<?php 
				echo key($subCategoryData) ." ({$subCategoryData[key($subCategoryData)]})";
			?>
			</strong>
		</a>
	</li>
	<?php } ?>
</ul>
<?php } ?>
<?php //---------------GROUPING SHORTLISTED SUBCATEGORIES ENDS HERE--------------- ?>