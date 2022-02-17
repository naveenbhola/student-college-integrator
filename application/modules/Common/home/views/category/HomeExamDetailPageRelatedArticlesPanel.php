<?php
    if(isset($parentExamCategories) && is_array($parentExamCategories) && (count($parentExamCategories) > 2) ) {
?>
<div class="raised_lgraynoBG">
    <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
    <div class="boxcontent_lgraynoBG">
        <div class="pd_lft_rgt">			
        	<div class="lineSpace_5">&nbsp;</div>
	        <div class="OrgangeFont bld fontSize_13p">Related Articles</div>
		    <div class="lineSpace_10">&nbsp;</div>
		    <ul class="FurtherReading">
                <?php
                    $uniqueExams=array();
                    foreach($parentExamCategories as $parentExamCategory) {
                        $parentExamCategoryId = $parentExamCategory['blogId'];
                        $parentExamCategoryName = $parentExamCategory['blogTitle'];
                        $parenrtExamCategoryUrl = $parentExamCategory['url'];
                        if($selectedExamCategory == $parentExamCategoryId) {continue;}
                        if(in_array($parentExamCategoryId, $uniqueExams)) {continue;}
                        $uniqueExams[] = $parentExamCategoryId;
                ?>
					<li>
                        <a href="<?php echo $parenrtExamCategoryUrl; ?>" title="<?php echo $parentExamCategoryName; ?>" class="lineSpace_22 fontSize_12p"><?php echo $parentExamCategoryName; ?></a>
                    </li>
                <?php
                    }
                ?>
			</ul>
		</div>
        <div class="lineSpace_10">&nbsp;</div>
	</div>
    <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
</div>
<?php
}
?>
