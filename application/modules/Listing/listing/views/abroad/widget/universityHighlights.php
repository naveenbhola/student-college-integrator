<?php
 /* this file consists of 3 parts :
  *
  * 1. university 's why join factor
  * 2. ranking (lowest rank across all factors)
  * 3. photo video widget
  *
  */
 $whyJoin = $universityObj->getWhyJoin();
 $univRank = $universityRank;
 $photoArray = $universityObj->getPhotos();
 $videoArray = $universityObj->getVideos();
 // prepare link caption
 $showPhotoVideoSection = false;
 if(count($photoArray)>0){
		$photoLinkCaption = "Photos (".count($photoArray).")";
		$showPhotoVideoSection = true;
 }
 if(count($photoArray)>0 && count($videoArray)>0){
		$photoVideoLinkSeparator =  " | ";
 }
 if(count($videoArray)>0){
		$videoLinkCaption .= "Videos (".count($videoArray).")";
		$showPhotoVideoSection = true;
 }
 // combine the photo & video arrays
 $photoVideoArray = array_merge($photoArray,$videoArray);
 //decide the width of why join section
 $whyJoinWidth = ($univRank == "" && !$showPhotoVideoSection? 630 : 435);
?>
<div class="widget-wrap clearwidth">
	   <h2 style="border:0; margin:0; padding:0;">University highlights</h2>
	   <div class="updated-pop-courses clearwidth">
			  <?php if($universityRank !=''){?>
			  <p><i class="common-sprite top-rnk-sml-icon"></i> Ranked <?php echo $universityRank;?> in <a target="_blank" href="<?php echo $universityRankURL; ?>"><?php echo htmlentities($universityRankName); ?></a></p>
			  <?php } ?>
			  <div class="updated-top-ranked-list  dyanamic-content" style="margin:8px 0 0 15px;">
			  	<?php  echo $whyJoin; ?>
			  </div>
	   </div>
</div>
