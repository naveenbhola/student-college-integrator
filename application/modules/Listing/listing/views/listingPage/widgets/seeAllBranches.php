<?php
 global $COURSE_PAGES_SUB_CAT_ARRAY;
	$height = 40;
	if($overlay == "no"){
?>
	<div class="school-branch-cont">
		<h6><?=html_escape($listing->getName())?> is available at the following branches</h6>
		<div class="layer-contents" style="background:transparent">
			<div id="layer-<?=$listing->getId()?>">
	
<?php
	}else{
		if($overlay == "onlylink"){
?>
<div class="gray-rule"></div>
<a href="#" class="see-all-link" onclick="showAllBranches<?=$listing->getId()?>(<?=$listing->getId()?>); return false;">See All Branches <span class="sprite-bg"></span></a>
<?php
		}else{
?>
<a href="#" class="see-all-link" onclick="showAllBranches<?=$listing->getId()?>(<?=$listing->getId()?>); return false;">See All Branches <span class="sprite-bg"></span></a>
<?php
		}
?>
<div id="allLocations<?=$listing->getId()?>" style="display:none;background:transparent">
<div class="layer-contents">
	<div id="layer-<?=$listing->getId()?>">
<?php
}
?>
    <?php
		if(count($loctionsWithLocality) > 0){
			foreach($loctionsWithLocality as $cityGroup){
				$height += 40 + count($cityGroup)*5;
	?>
		<h5><?=$cityGroup[0]->getCity()->getName()?></h5>
		<div class="multi-layer-contents">
			<?php
				foreach($cityGroup as $key=>$location){
					$additionalURLParams = "?city=".$location->getCity()->getId()."&locality=".$location->getLocality()->getId();
					$listing->setAdditionalURLParams($additionalURLParams);
			
			foreach($instituteCourses as $instituteCourse)
			{	//check if the institutes' courses are valid coursepages to show coursepage header.
				if(count($COURSE_PAGES_SUB_CAT_ARRAY[$instituteCourse])>0 )
				{
					if($_REQUEST['cpgs'])
					 $cpgs_append = '&cpgs='.$_REQUEST['cpgs'];
					else if($googleRemarketingParams['subcategoryId'][0]==$instituteCourse)
					 $cpgs_append="&cpgs=".$googleRemarketingParams['subcategoryId'][0];

				}
			}
			echo '<a href="'.$listing->getURL().$cpgs_append .'">'.$location->getLocality()->getName().'</a>';
					if($cityGroup[$key+1]){
						echo ' <span>|</span> ';
					}
				}
			?>
		</div>
        <div class="spacer20 clearFix"></div>
	<?php
			}
		}
		
		if(count($loctionsWithLocality) > 0 && count($otherLocations) > 0){
	?>
	<h5>Other Cities</h5>
	<?php
		}
	?>
   
	<?php
		if(count($otherLocations) > 0){
			$height += 40 + count($otherLocations)*5;
	?>
		<div class="multi-layer-contents">
			<?php
				foreach($otherLocations as $key=>$location){
					$additionalURLParams = "?city=".$location->getCity()->getId()."&locality=";
					$listing->setAdditionalURLParams($additionalURLParams);
					$cityName = explode("-",$location->getCity()->getName());
					if($location->getCustomLocalityName()!="" &&  $location->getLocality()->getId() ==''){
						echo '<ul class="layer-col" style="width:155px; margin:0 6px 8px 14px;"><li style="list-style:disc;"><a href="'.$listing->getURL().'">'.$cityName[0]." - ".$location->getCustomLocalityName().'</a></li></ul>';
					}else{
						echo '<ul class="layer-col" style="width:155px; margin:0 6px 8px 14px;"><li style="list-style:disc;"><a href="'.$listing->getURL().'">'.$location->getCity()->getName().'</a></li></ul>';
					}
					
				}
			?>
		</div>
	<?php
		}
	?>   
        </div>
		<div class="clearFix"></div>
    </div>
</div>
<script>
<?php
if($height > 400){
?>
	$("layer-<?=$listing->getId()?>").style.overflow = "auto";
	$('layer-<?=$listing->getId()?>').style.height = '400px';
<?php
}
?>
function showAllBranches<?=$listing->getId()?>(id){
    var content = $('allLocations'+id).innerHTML;
    overlayParentAnA = $('allLocations'+id);
    overlayParentAnA = '';
    showOverlayAnA(750,500,'All Branches of <?=$name?>',content);
}
</script>
