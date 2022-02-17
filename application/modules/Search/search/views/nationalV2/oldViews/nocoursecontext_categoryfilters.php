<?php
$subCatFilterValues = $filters['subCat'];
?>
<section class="ac-container">
	<div class="slctd-catgrs">
	   <div class="frst-sidebr">
		   <p class="p6">Show Colleges for</p>
		   <div class="arrow-pointertxt"></div>
	   </div> 
	</div>
	<ul id="accordion" class="accordion">
	<?php
	$filterTupleCount = 1;
	foreach($subCatFilterValues as $categoryId => $categoryData) {
		$subcategoryFilterData 	= $categoryData['subCategory'];
		$categoryName 			= $categoryData['name'];
		$checked = "";
		$subMenuDisp = "";
		if($filterTupleCount == 1){
			$checked = "class='open'";
			$subMenuDisp = "style='display:block'";
		}
		?>

  <li <?php echo $checked;?>>
    <div class="link"><?php echo $categoryName;?><i class="fa fa-chevron-down"></i></div>
    <ul class="submenu" <?=$subMenuDisp?>>
    <?php
					$subcategoryFilterDataFirstPass  = array_slice($subcategoryFilterData, 0, 5, true);
					$subcategoryFilterDataSecondPass = array_slice($subcategoryFilterData, 5, count($subcategoryFilterData), true);
					foreach($subcategoryFilterDataFirstPass as $filterSubCategoryId => $filterSubCategory){
					?>
				 	<li><a href="<?php echo $filterSubCategory['url'];?>"><?php echo $filterSubCategory['name'];?> (<?=$filterSubCategory['count'];?>)</a></li>
					 <?php
					}
					if(!empty($subcategoryFilterDataSecondPass)) {
						?>
						<li id="subcatfilters_extra_options_link_<?php echo $filterTupleCount;?>" ><a onclick="showMoreSubCategoryFilters(<?php echo $filterTupleCount;?>);" href="javascript:void(0);" class="course-italic">+ View more courses</a></li>
						<div style="display:none;" id="subcatfilters_extra_options_<?php echo $filterTupleCount;?>">
						<?php
						foreach($subcategoryFilterDataSecondPass as $filterSubCategoryId => $filterSubCategory) {
						?>
							<li><a href="<?php echo $filterSubCategory['url'];?>" class=""><?php echo $filterSubCategory['name'];?> (<?=$filterSubCategory['count'];?>)</a></li>
						<?php
						}
						?>
						</div>
						<?php
					}
					?>
				</ul>
  </li>
  <?php
		$filterTupleCount++;
	}
	?>
</ul>		
</section>