<?php
    if(isset($parentExamCategories) && is_array($parentExamCategories) && (count($parentExamCategories) > 0) ) {
?>
        <div style="width:173px; float:left">
			<div class="testPreHeading bld">
				<a href="<?php echo $examParent['url'];?>" ><?php echo $examParent['blogTitle'];?></a>
			</div>
			<div class="testPreDataLeft" style="border-top:none">
				<div class="lineSpace_5">&nbsp;</div>
				<ul style="margin:0; padding:0;list-style-type:none">
                    <?php
                        foreach($parentExamCategories as $parentExamCategory) {
                            $parentExamCategoryId = $parentExamCategory['blogId'];
                            $parentExamCategoryName = $parentExamCategory['blogTitle'];
                            $parenrtExamCategoryUrl = $parentExamCategory['url'];

                            $liClass = ($selectedExamCategory == $parentExamCategoryId) ? 'listTestPreSelected' : 'listTestPreUnSelected';
                    ?>
					<li class="<?php echo $liClass; ?>">
                        <a href="<?php echo $parenrtExamCategoryUrl; ?>" title="<?php echo $parentExamCategoryName; ?>" class="lineSpace_22"><?php echo $parentExamCategoryName; ?></a>
                    </li>
                    <?php
                        }
                    ?>
				</ul>
			</div>
		</div>
<?php
    }
?>
