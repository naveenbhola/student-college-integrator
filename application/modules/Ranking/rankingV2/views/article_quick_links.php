<?php 
function sanitizeArticleTitle($content, $charToDisplay) {
  if(strlen($content) <= $charToDisplay) {
	return($content);
  } else {
	return (preg_replace('/\s+?(\S+)?$/', '', substr($content, 0, $charToDisplay))) ;
  }
}
if(is_array($articleWidgetsData) && count($articleWidgetsData) > 0){
?>
<div class="quick-link-pannel" style="margin-top:0px;margin-bottom:10px;">
  <div class="quick-link-figure" style="height:136px;">
	<img src="/public/images/category_widget_images_qdp/<?=$categoryId.".jpg"?>" alt=""/>
  </div>
  <div class="quick-link-content" id="articlePlaceHolder" style="width:312px;padding-bottom: 0px;">
	<h2>Articles About Top <?php echo $selectedSubCategoryName;?> Colleges and Courses</h2>
	<ul>
	  <?php
	  $count = 0;
	  while($count < count($articleWidgetsData) && $count < 3) {
		if(!empty($articleWidgetsData[$count]['articleTitle'])){
		  ?>
		  <li>
			<a href="<?php echo $articleWidgetsData[$count]['articleURL'];?>" title="<?=$articleWidgetsData[$count]['articleTitle'];?>">
			<?php
			  echo sanitizeArticleTitle($articleWidgetsData[$count]['articleTitle'], 52);
			?>
			</a>
		  </li>
		  <?php
		  $count++;
		}
	  }
	  ?>
	</ul>
	<div class="clearFix"></div>
	<!--<a class="gray-button" style="text-decoration:none;color: #444343 !important;font-size:10px !important;" href="<?=$quickLinkURL;?>" uniqueattr="rankingpage_articlewidget_button_click">View Institutes offering <?php //echo $selectedSubCategoryName;?></a>-->
  </div>
  <div class="clearFix"></div>
</div>

<?php
}
?>
