<?php $widget = "crIns-all";?>
<div class="clearFix"></div>
        <div class="com-ask-qry com-main-bdr" id="crMain-widget">
	<!----manage tab---->	
        <div class="com-ask-qry-tab" id="tabSection">
        	<a href="javascript:void(0);" class="com-ask-qry-sng">Ask Current Students</a>
		</div>
	       <?php if($repData['repInfo']['totalRep'] >0){?>
	       <p class="com-qry-hd">Ask your queries to current students of this college</p>
	       <div id="repInfo">
			<div class="com-qry-stu-info" id="inst-crWidgetSlider">
				<?php if($repData['repInfo']['totalRep']==1){?> <!--view for single rep-->
				<div class="com-ask-stu-lst" style="width: 232px; overflow: hidden; float: left;"> 
				<ul class="com-stu-lst-info">
					<?php if($repData['repInfo'][0]['imageURL'] !='' && $repData['repInfo'][0]['displayName'] !='')
						{
						//handling size of the image
						$rData['imageURL'] = substr_replace($repData['repInfo'][0]['imageURL'],"_s.",strrpos($repData['repInfo'][0]['imageURL'],"." ),1);  ?>
					<li style="width: 100% !important;">
						<div><img src="<?php echo $rData['imageURL'];?>" alt=""/></div>
						<span class="com-qry-lst-name"><?php echo substr($repData['repInfo'][0]['displayName'],0,18);?></span>
					</li>
					<?php }?>
				</ul>
				</div>
				<?php }?>
				<div class="prevArrow" <?php if($repData['repInfo']['totalRep'] <= 3){?> style="visibility: hidden;" <?php }?>><div id="navPhotoPrev-mba" class="com-qry-stu-l  flLt prev" onclick="stopClick('navPhotoPrev-mba','inst-crWidgetSlider',event);"><i class="sprite-bg com-qry-l-icon"></i></div></div>
				<?php if($repData['repInfo']['totalRep']>1){?> <!--more then one rep-->
				<div class="com-ask-stu-lst viewport" style="width: 232px; overflow: hidden; float: left;"> 
				<ul class="com-stu-lst-info overview">
					<?php foreach($repData['repInfo'] as $rData){
						if($rData['imageURL'] !='' && $rData['displayName'] !='')
						{
						//handling size of the image
						$rData['imageURL'] = substr_replace($rData['imageURL'],"_s.",strrpos($rData['imageURL'],"." ),1);  ?>
					<li>
						<div><img src="<?php echo $rData['imageURL'];?>" alt=""/></div>
						<span class="com-qry-lst-name"><?php echo substr($rData['displayName'],0,18);?></span>
					</li>
					<?php }}?>
				</ul>
				</div>
				<?php }?>
				<div class="nextArrow" <?php if($repData['repInfo']['totalRep'] <= 3){?> style="visibility: hidden;" <?php }?>>
					<div class="com-qry-stu-r flLt next" id="navPhotoNxt-mba" onclick="stopClick('navPhotoNxt-mba','inst-crWidgetSlider',event);"><i class="sprite-bg com-qry-r-icon"></i></div></div>
			</div>
			
			<div class="clearFix"></div>
			<!---answer given count-->
			<?php if($repData['repInfo']['commentCount']>0){?>
			<p class="com-ask-qry-ans" id="comments-mba"><?=$repData['repInfo']['commentCount'];?><?php echo ($repData['repInfo']['commentCount'] == 1) ? " Answer" : " Answers"; ?> given</p>
			<?php }?>
			
			<?php if(count($repData['numberOfCACourses']['mbaCourse']) == 1) {//for mba single course, no dropdown ?>
				<div class="com-ask-selt-bt">
				<strong class="com-ask-hd-txt">Answers queries for:</strong>
				<p><?php echo $repData['campusConnectCourses']['mbaCourseObj'][0]->getName(); ?></p>
				
				<select class="com-ask-qry-selt com-main-bdr" style="display:none" id="campus_rep_course_<?=$widget?>">
					<option selected customurl = "<?=$repData['campusConnectCourses']['mbaCourseObj'][0]->getURL()?>" value="<?=$repData['campusConnectCourses']['mbaCourseObj'][0]->getId()?>"></option>
				</select>
				<a href="javascript:void(0);" onclick="goToCourseURL('campus_rep_course_<?=$widget?>')" class="com-ask-qry-bt com-main-bdr">Ask Now</a>
				</div>
			<?php } else { ?>
				<div class="com-ask-selt-bt">
				<select class="com-ask-qry-selt com-main-bdr" caption="course" id="campus_rep_course_<?=$widget?>" name="campus_rep_course_<?=$widget?>" required="true">
					<option selected value="">Please select course</option>
					<?php  foreach($repData['campusConnectCourses']['mbaCourseObj'] as $course)
					{
						echo '<option customurl = "'.$course->getURL().'" value="'.$course->getId().'">'.html_escape($course->getName()).'</option>';
					} ?>
				</select>
				<div class="clearFix"></div>
				<div style="display:none">
					<div class="errorMsg" id="campus_rep_course_<?=$widget?>_error" style="padding-left:3px; clear:both;"></div>
				</div>
				<a href="javascript:void(0);" onclick="goToCourseURL('campus_rep_course_<?=$widget?>')" class="com-ask-qry-bt com-main-bdr">Ask Now</a>
				</div>
			<?php }?>
		</div><!----end-repInfo---->
		
		<?php }?>
	
	</div>
		<div class="clearFix"></div>
<script>
	
function goToCourseURL(id) {
	var obj = $j("#" + id);
	if(typeof id != "undefined") {
		if ($j("#" + id + " option:selected").val() == "") {
			national_listings_obj.validateDropDown(obj[0]);
		}
		else {
			var url = $j("#"+ id + " option:selected").attr("customurl");
			url = url + "#ca_aqf";
			window.location.href = url;
		}
	}
}
</script>

<style>                              
/* Tiny Carousel */
.com-ask-stu-lst .overview {
	list-style: none;
	position: relative;
	padding: 0;
	margin: 0;
	
}

.com-ask-stu-lst .overview li {
	float: left;
	cursor: pointer;
	position: relative;
	width: 62px; 
	
}
.com-ask-stu-lst .overview li{ cursor: default !important;}
</style>