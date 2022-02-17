<?php
if(is_array($topicListings)){
	$result = $topicListings['comments'];
	$params = array('instituteId'=>$details->getId(),'instituteName'=>$details->getName(),'type'=>'institute','abbrevation'=>$details->getAbbreviation());
	$askNAnswerTabUrl = listing_detail_ask_answer_url($params);
	$userStatus = $validateuser;
	$divCount = 0;		
?>

<!-- Main Div Starts -->
<div class="" style="padding-right: 0px;">
	<div id="divForOverall">
	<?php
	$commentCount = $topicListings['totalComments'];
	if($commentCount==0){
		$commentCount = " ";
	}else{
		$commentCount = ($commentCount==1)?$commentCount." comment":$commentCount." comments";
	}	
        $ownerImageURL = ($topicListings['avtarimageurl']=='')?'/public/images/photoNotAvailable.gif':$topicListings['avtarimageurl'];
        $topicListings['msgTxt'] = (strlen($topicListings['msgTxt'])>100)?substr($topicListings['msgTxt'],0,100)."...":$topicListings['msgTxt'];
	?>
	
	<!--STARTS : Current Student ASK Section-->
	<div class="section-cont" style="padding-bottom: 15px;">
		<h4 class="section-cont-title"><?=$topicListings['msgTxt']?></h4>
	    
		<?php if(isset($topicListings['description']) && $topicListings['description']!=''){ ?>
		    <div class="current-stdnt-picBg2">
			<div style='margin: 0 0 6px 5px;' ><img src="<?php echo getSmallImage($ownerImageURL); ?>" alt="" /></div>
			<div class="current-student-patch">Current Student</div>
		    </div>
		    <div class="student-details">
			<p style="line-height:16px;">
				<span style="font-weight:700"><a href="javascript:void(0);" onclick="window.open('/getUserProfile/<?php echo $topicListings['displayname'];?>');"><?=$topicListings['displayname']?></a></span>
				&nbsp;<?php echo formatQNAforQuestionDetailPage($topicListings['description'],300); ?>
			</p>
		    </div>
		<?php } ?>
	    
		<div class="spacer10 clearFix"></div>
		<input type="button" class="gray-button" value="Participate in this discussion" onclick="trackEventByGA('LinkClick','LISTING_PARTICIPATE_DISCUSSION_CLICK'); window.open('<?php echo $topicListings['url'].'#answerFormDetailPage1';?>');" />
	</div>
	<!--ENDS : Current Student ASK Section-->
	<div class="clearFix"></div>
    <?php if(is_array($result) && count($result)>0){ ?>
	<strong style="margin-bottom: 5px;"><?=$commentCount?></strong>
	<!-- Wall Data Starts -->
	<div class="ml46 mr20" style="color:#000;text-decoration:none" <?php if(count($result)>2){ ?> onmouseover="slider.stopShow();" onmouseout="slider.startShow();" <?php } ?> >
		<ul id="slider1" class="latest-discussion-list" <?php if(count($result)==1){echo "style='height:100px;'";} ?> >
			<?php
			$i=0;
			for($count=0;$count<6 && $i<count($result);){
			?>
			<li style="height:89px;">
				<div style="border-bottom:1px solid #ededed; padding-top:12px; margin-bottom:12px; height:80px; overflow:hidden;">
				<?php
				    $commentDisplayName = $result[$i]['displayname'];
				    $commentTxt = $result[$i]['msgTxt'];
				    $commentTxt = (strlen($commentTxt)>100)?substr($commentTxt,0,100)."...":$commentTxt;
				    $commentUserId = $result[$i]['userId'];
				    $imageURL = ($result[$i]['avtarimageurl']=='')?'/public/images/photoNotAvailable.gif':$result[$i]['avtarimageurl'];
				    $time = makeRelativeTime($result[$i]['creationDate']);
				  ?>
				<div id ="askWidgetDiv_<?php echo $count?>">
					<div>
						<div class="figure"><img src ="<?php echo getTinyImage($imageURL);?>" border="0"></div>
						<div class="details">
							<div style='height:50px;overflow:hidden;'>
								<strong><?php echo $commentDisplayName; ?></strong><strong> commented </strong>
								<?php echo formatQNAforQuestionDetailPage($commentTxt); ?>
							</div>
							<div class='Fnt11 grayFont' style='margin-top:5px;'><?php echo $time;?></div>
						</div>
					</div>
				</div>
				
				<?php $count++;
				$i++;
				?>
				</div>
			</li>
			<?php
			$divCount = $count;
			}
			if($divCount==1){
			$divCount=0;
			}
			?>
			<!--<li><div id ="askWidgetDiv_<?php echo $divCount;?>" style=""></div></li>-->
		</ul>
	</div>
	<!-- Wall Data Ends -->

        <strong class="flRt" style="margin-top: 5px;"><a href="javascript:void(0);" onClick="trackEventByGA('LinkClick','LISTING_VIEW_ALL_DISCUSSION_COMMENTS_CLICK'); window.open('<?php echo $topicListings['url'].'#questionMainDiv';?>');">View All Comments</a></strong>
	<div class="clearFix"></div>	
    <?php } ?>        
	</div>
	
</div>
<!-- Main Div Ends -->

<?php
if($showWallData){
	echo "<script>";
	if($divCount == 0){
		if($tab == 'overview')
			echo "document.getElementById('divForOverall').style.height= '127px'";
		else
			echo "document.getElementById('divForOverall').style.height= '155px'";
	}
	echo "</script>";
}
?>

<?php if(count($result)>2){ ?>
<script>
    var slider = $j('#slider1').bxSlider({
	mode: 'vertical',
        auto: true,
        nextText:'',
        prevText:'',
        pause:5000,
	displaySlideQty: 2,
        onAfterSlide: function(currentSlide, totalSlides){
        }
    });
</script>
<?php } ?>

<?php } ?>
