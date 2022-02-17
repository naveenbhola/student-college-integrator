<div id="commonParentCategoryPanel" onMouseOver="MM_showHideLayers('commonParentCategoryPanel','','show');" onMouseOut="MM_showHideLayers('commonParentCategoryPanel','','hide');">
        <?php
            global $categoryParentMap;
            foreach($categoryParentMap as $categoryName => $category) {
			$categoryId = $category['id'];
        ?>
            <a class="shikIcons" href="javascript:void(0);" title="<?php echo $categoryName; ?>"  style="" onClick="changeParentCategory('<?php echo $categoryName; ?>','<?php echo $categoryId; ?>');"><span style=""><?php echo $categoryName; ?></span></a>
        <?php } ?>
</div>
