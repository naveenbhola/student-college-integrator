<?php 
function formatTitle($content, $charToDisplay) {
if(strlen($content) <= $charToDisplay)
return($content);
else
return (preg_replace('/\s+?(\S+)?$/', '', substr($content, 0, $charToDisplay))) ;
}

if(isset($moduleName) && $moduleName=='Articles'){
	$attrName = 'article_detail_page_quicklinkwidget';
}else{
	$attrName = 'question_detail_page_quicklinkwidget';
}
?>
<div class="quick-link-pannel">
<div class="quick-link-figure"><img src="/public/images/category_widget_images_qdp/<?=$categoryId.".jpg"?>" alt="" /></div>
<div class="quick-link-content" id="articlePlaceHolder">
	<h4>All about <?php echo $selectedSubCategoryName;?></h4>
    <ul>
    <?php for($i=0;$i<count($articleWidgetsData) && $i<3;$i++){?>
	<?php if(!empty($articleWidgetsData[$i]['articleTitle'])){?>
	<li><a href="<?php echo $articleWidgetsData[$i]['articleURL']; ?>" title="<?=$articleWidgetsData[$i]['articleTitle'];?>">
    <?php
echo $finalContent = formatTitle($articleWidgetsData[$i]['articleTitle'], 52);
     } 
//echo $finalContent = wordwrap($finalContent, 27, true);
?></a></li>
<?php } ?>
</ul>
<div class="clearFix spacer5"></div>
<a class="gray-button" style="text-decoration:none;" href="<?=$quickLinkURL;?>">View Institutes offering <?php echo $selectedSubCategoryName;?>
</a>
</div>
<div class="clearFix"></div>
</div>
