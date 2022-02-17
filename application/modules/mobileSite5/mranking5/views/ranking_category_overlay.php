<div class="change-course-layer" id="overlayCategoryHolder" name="overlayCategoryHolder" style="display:none;" onmouseover="displayCategoryLayer('show');" onmouseout="displayCategoryLayer('hide');">
	<ul>
		<?php
		if(!empty($category_filters)) {
			foreach($category_filters as $filter){
			?>
				<li class="" style="width:100%">
					<a uniqueattr="RankingPage/categorylayerlinkclick" href="<?php echo $filter->getURL();?>" style="font-size:12px"><?php echo $filter->getName();?></a>
				</li>
				<div class="lineSpace_5">&nbsp;</div>
			<?php
			}
		} else {
			?>
			<li class="" style="width:100%">
				No course available.	
			</li>
			<div class="lineSpace_5">&nbsp;</div>
			<?php
		}
		?>
	</ul>
</div>
