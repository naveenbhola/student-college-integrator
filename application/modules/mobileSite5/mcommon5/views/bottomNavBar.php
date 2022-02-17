<section class="category-tab-wrap clearfix" id="bottom_navigation_bar">
    	<ul class="tab-list">
            <?php
            $index = 0;
            $showMore = false;
	    $moreHtml = '<div class="more-layer" id="bottomNavShowMore"><ul>';
	    $moreSelected = false;
            foreach($finalTabs as $key => $tab)
            {
                $index++;
		if($index > 3 && count($finalTabs) != 4)
                {
                    $showMore = true;
		    if($pageType == $pagesArray[$key])
			$moreSelected = true;
		    
		    if($tab[0] == 'link')
		    {
			$moreHtml .= '<li><a href="'.(($pageType!=$pagesArray[$key])?$tab[1]:'javascript:void(0);').'" onclick="trackEventByGAMobileFooter(\''.$trackingCodes[$key].'\');">';
		    }
		    else
		    {
			$moreHtml .= '<li><a href="'.(($pageType!=$pagesArray[$key])?$tab[1]:'javascript:void(0);').'" data-inline="true" data-rel="dialog" data-transition="slide" onclick="trackEventByGAMobileFooter(\''.$trackingCodes[$key].'\');">';
		    }
		    $moreHtml .= $labels[$key].'</a></li>';
                    continue;
                }
            ?>
		<li class="<?=($pageType==$pagesArray[$key])?'active':''?>">
		    <?php
		    if($tab[0] == 'link')
		    {
		    ?>
			<a href="<?=($pageType!=$pagesArray[$key])?$tab[1]:'javascript:void(0);'?>" onclick="trackEventByGAMobileFooter('<?=$trackingCodes[$key]?>');"><?=$labels[$key]?></a>
		    <?php
		    }
		    else
		    {
		    ?>
			<a href="<?=($pageType!=$pagesArray[$key])?$tab[1]:'javascript:void(0);'?>" data-inline="true" data-rel="dialog" data-transition="slide" onclick="trackEventByGAMobileFooter('<?=$trackingCodes[$key]?>');"><?=$labels[$key]?></a>
		    <?php
		    }
		    ?>
		</li>
            <?php
            }
	    $moreHtml .= '</ul><i class="sprite more-pointer"></i></div>';
            if($showMore)
            {
            ?>
                <li onclick="$('#bottomNavShowMore').slideToggle(200);" style="border-right:none;" class="<?=($moreSelected)?'active':''?>">
                    <a href="javascript:void(0);">more</a>
                    <?php
		    if($index > 3) echo $moreHtml;
		    ?>
                </li>
            <?php
            }
            ?>
           
        </ul>
</section>
<script>
    /*if(hamburgerFlag!='1') 
        $('#bottom_navigation_bar').show();
    else
        $('#bottom_navigation_bar').hide();*/
</script>
<?php
if($subCategoryId == '')
{
?>
    <script>
    $(document).ready(function() {
            //Create Sub Category Div
            createSubCategoryHTML();
    });
    </script>
<?php
}
?>