<?php $widget = "campusRepRightInstt";?>

<div class="clearFix"></div>
        <div class="com-ask-qry com-main-bdr" id="crMain-widget" onclick="scrollToSpecifiedElement('campus-connect-sec-id');" style="cursor:pointer">
	<!----manage tab------>	
    <div class="com-ask-qry-tab" id="tabSection">
		<a href="javascript:void(0);" class="com-ask-qry-sng">Ask Current Students</a>	
	</div>
	
        <?php if($repData['engineering']['totalRep'] > 0 || $repData['repInfo']['totalRep'] > 0){?>
                <p class="com-qry-hd">Ask your queries to current students of this college</p>
        <?php }?>       
	       
               <?php if($repData['repInfo']['totalRep'] > 0 && $repData['engineering']['totalRep'] <= 0){?>
               
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
				<div class="prevArrow" <?php if($repData['repInfo']['totalRep']<=3){?> style="visibility: hidden;margin-left:0px !important;" <?php }else{?> style="margin-left:0px !important;" <?php }?>><div id="navPhotoPrev-mba" class="com-qry-stu-l  flLt prev" onclick="stopClick('navPhotoPrev-mba','inst-crWidgetSlider',event);"><i class="sprite-bg com-qry-l-icon"></i></div></div>
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
				<div class="com-ask-selt-bt">
				<strong class="com-ask-hd-txt">Answers queries for:</strong>
				<p><?php echo $repData['campusConnectCourses']['mbaCourseObj'][0]->getName(); ?></p>

                                <?php if($question_detail_page):?>
                                <a class="com-ask-qry-bt com-main-bdr" uniqueattr = "LISTING_COURSE_PAGES/CampusRepCourseRightPanel" onclick=" $j('#ask_question_askAQuestion').focus(); $('askQuestionFormDiv').scrollIntoView(false);">Ask Now</a>
                                <?php else:?>
                                <a class="com-ask-qry-bt com-main-bdr" uniqueattr = "LISTING_COURSE_PAGES/CampusRepCourseRightPanel">Ask Now</a>
                                <?php endif;?>
				
				</div>
			
		</div><!----end-repInfo---->
        <?php }?>
	</div>
		<div class="clearFix"></div>

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
</style>