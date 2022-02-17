            <!--Start_Alumni_Speak-->
<script>
	function trackEventByGA(eventAction,eventLabel) {
		if(typeof(pageTracker)!='undefined' && currentPageName!=null) {
			pageTracker._trackEvent(currentPageName, eventAction, eventLabel);
		}
		return true;
	}

	function toggleCriteriaFeedbacks(id) {
		for(var criteriaCount =1; criteriaCount <=4; criteriaCount++) {
			hideAllComments(id);
			if(document.getElementById(criteriaCount +'Reviews')) {
				if(id !== criteriaCount) {
					document.getElementById(criteriaCount+'Reviews').style.display = 'none';
					document.getElementById(criteriaCount +'Toggler').src =  '/public/images/plusSign.gif';
					document.getElementById('totalPublishedReviews'+ criteriaCount).className ='';
				} else {
					if(document.getElementById(id+'Reviews').style.display != 'none') {
						document.getElementById(id+'Toggler').src = '/public/images/plusSign.gif';
						document.getElementById(id+'Reviews').style.display =  'none';
						document.getElementById('totalPublishedReviews'+ id).className ='';
					} else {
						var pageTrackerlabelName='';
						switch(id){
							case 1:
								pageTrackerlabelName='infrastructure';
								break;
							case 2:
								pageTrackerlabelName='faculty';
								break;
							case 3:
								pageTrackerlabelName='placement';
								break;
							case 4:
								pageTrackerlabelName='overview';
								break;
						}
						document.getElementById(id+'Toggler').src = '/public/images/closedocument.gif';
						document.getElementById(id+'Reviews').style.display = '';
						document.getElementById('totalPublishedReviews'+ id).className ='blackFont';
						try{
						  if(windowLoaded)
						    trackEventByGA('alumni speak click', pageTrackerlabelName);
						  }catch(ex){
						    logJSErrors(ex);
						  }
					}
				}
				
			}
		}
	}

	function updateReviewInventory(id, totalRating, totalResponses, totalPublishedRatings) {
		//if(totalPublishedRatings == 0 || totalResponses == 0 || totalRating == 0 || Math.round(totalRating / totalResponses) == 0) {
		if(totalPublishedRatings == 0) {
			document.getElementById('reviewBox'+ id) .style.display = 'none';
			return false;
		}
		var ratingStarHtml = '';
		document.getElementById('totalPublishedReviews'+id).innerHTML = totalPublishedRatings == 1 ? '1 alumnus review' : totalPublishedRatings + ' alumni reviews';
		document.getElementById('totalPublishedReviews'+id).title = totalPublishedRatings == 1 ? '1 alumnus review' : totalPublishedRatings + ' alumni reviews';
		if(totalRating>0 && totalResponses>0)
		{
		  var avgRating = Math.round(totalRating / totalResponses);
		  for(var starCount =0; starCount< avgRating; starCount++){
			  ratingStarHtml += '<img src="/public/images/fullStar.gif" align="absmiddle"/>';
		  }
		  document.getElementById('averageRating'+id).innerHTML = avgRating +'/5';
		  document.getElementById('starRating'+id).innerHTML = ratingStarHtml;
		}
	}

	function showAllComments(id)
	{
	  //document.getElementById('reviewBox'+id).style.overflow = "auto";
	  var i=0;
	  while(document.getElementById('review'+id+i)){
	    document.getElementById('review'+id+i).style.display = "block";
	    document.getElementById('reviewLine'+id+i).style.display = "block";
	    if(document.getElementById('reviewReply'+id+i))
	      document.getElementById('reviewReply'+id+i).style.display = "block";
	    i++;
	  }
	  if(document.getElementById('view'+id))
	    document.getElementById('view'+id).style.display = "none";
	  var j=i-1;
	  if(document.getElementById('hide'+id+j))
	    document.getElementById('hide'+id+j).style.display = "block";
	}

	function hideAllComments(id)
	{
	  var i=0;
	  while(document.getElementById('review'+id+i)){
	    document.getElementById('review'+id+i).style.display = "none";
	    document.getElementById('reviewLine'+id+i).style.display = "none";
	    if(document.getElementById('reviewReply'+id+i))
	      document.getElementById('reviewReply'+id+i).style.display = "none";
	    i++;
	  }
	  if(document.getElementById('view'+id))
	    document.getElementById('view'+id).style.display = "block";
	  var j=i-1;
	  if(document.getElementById('hide'+id+j))
	    document.getElementById('hide'+id+j).style.display = "none";
	}
</script>
<?php 
	$alumniReviews = $details['alumniReviews'];
	$alumniReviewsReply = $details['alumniReviewsReply'];
?>
<a name = "alumrating"></a>
            <div style="margin-top:20px" id="alumniReviewSection">
            	<div class="raised_11"> 
			<b class="b2"></b><b class="b3"></b><b class="b4"></b>
            		<div class="boxcontent_11" style="position:relative">
                    	<div style="padding:5px 10px;height:43px">
				<div style="float:right;position:absolute;right:40px;top:-17px"><img src="/public/images/shikshaExclusive.gif" border="0" /></div>
                        	<div class="fontSize_14p">
                            	<div class="bld"><img src="/public/images/alminiBlog.gif" align="absmiddle" /> Alumni Speak</div>
                                <div style="font-size:11px;color:#7c7c7c;padding-left:28px;margin-right:95px">Hear what alumni have to say about their institute collected exclusively on Shiksha.com</div>
                            </div>
                        </div>
			<div id="reviewsContainer" id="display:none"></div>
			<!-- Don't remove these two open divs : Ashish -->
			<div>
			<div>
			<?php
				$totalResponses = 0;
				$totalRating =  0;
				$totalPublishedRatings =  0;
				$lastCriteria = '';
				$NumberOfViews = 0;
				$HideReviewNumber = 0;
				foreach($alumniReviews as $alumniReview) 
				{
					$criteriaName = $alumniReview['criteria_name'];
					$criteriaId = $alumniReview['criteria_id'];
					$review = ($alumniReview['status'] == 'published') ? $alumniReview['criteria_desc'] : '';
					$courseName = ($alumniReview['course_name'] == '') ?  '' : ' - '. $alumniReview['course_name'] ;
					
					$reviewerName = ucwords(strtolower($alumniReview['name']));
					$shikshaShowFlag = $alumniReview['showShikshaFlag'];
					if($shikshaShowFlag != 'yes') 
					{
					    $reviewerName = '';
					}
					$batch = $alumniReview['course_comp_year'];
					$reviewerName .= ($reviewerName != '' && $batch != '') ? ',' : '';
			?>
			<!-- Start  : Criteria -->
			<?php

					if($lastCriteria != $criteriaId) 
					{
					    if($lastCriteria != '') 
					    {
			?>
					      <script>
						      updateReviewInventory('<?php echo $lastCriteria; ?>', <?php echo $totalRating; ?>, <?php  echo $totalResponses; ?>,<?php echo  $totalPublishedRatings; ?>); 
					      </script>
			<?php
					    }
					    $totalResponses = 0;
					    $totalRating =  0;
					    $totalPublishedRatings =  0;
					    $NumberOfViews = 0;
					    $HideReviewNumber = 0;
			?>
					    <!-- Don't remove these two closed divs : Ashish -->
					    </div>
					    </div>
					    <div style="padding:0 10px;_padding: 0px 10px;" id="reviewBox<?php echo $criteriaId; ?>">
						<!--<div style="line-height:23px;height:23px;border-bottom:1px solid #fcecc8;width:100%;">-->
						    <div>
                                                    <div class="float_R" style="background:url(/public/images/blogM.gif) no-repeat right top;padding-right:35px"><a href="javascript:void(0)" id="totalPublishedReviews<?php echo $criteriaId; ?>" onclick="toggleCriteriaFeedbacks(<?php echo $criteriaId; ?>)"></a></div>
						    <div class="float_L Fnt16 bld pt3">
							    <img id="<?php echo $criteriaId; ?>Toggler" src="/public/images/closedocument.gif" onclick="toggleCriteriaFeedbacks(<?php echo $criteriaId; ?>)" align="absmiddle" style="cursor:pointer;margin-top:-8px"/>&nbsp; &nbsp;<a href="javascript:void(0);" class="fontSize_12p blackFont bld" onclick="toggleCriteriaFeedbacks(<?php echo $criteriaId; ?>)"><?php echo ucwords(strtolower($criteriaName)); ?></a> 
							    <span id="starRating<?php echo $criteriaId; ?>"></span>
							    <span id="averageRating<?php echo $criteriaId; ?>"></span>
						    </div>
						</div>
						    <div id="<?php echo $criteriaId; ?>Reviews">
					    <?php
					  }
				if(trim($review) !== '') {
					$totalPublishedRatings ++;

				if($NumberOfViews === 4 && $totalPublishedRatings>4)
				{
				?>
				      <div style="padding:5px 15px;border-bottom:solid 1px #eae9e9;" id="view<?php echo $criteriaId; ?>"><a href="javascript:void(0)" onClick="showAllComments(<?php echo $criteriaId; ?>)">view more</a></div>
				      <div class="lineSpace_5">&nbsp;</div>
				
				<?php

				}
			    if($NumberOfViews<4){
			?>
				<!-- Start : Reviews -->
                            <div style="padding:5px 15px;color:#0e0f11; ">"<?php echo $review; ?> - <b><?php echo $reviewerName; ?> Class of <?php echo $batch .' '. ucwords(strtolower($courseName)); ?></b>" </div>
				<?php
				    $replyThreadId = $alumniReview['thread_id'];
				    if($replyThreadId!='' && $replyThreadId!=0){
				      foreach($alumniReviewsReply[$replyThreadId] as $temp){
					      if($temp['parentId'] == $replyThreadId){
						if($temp['msgTxt']!=''){
				  ?>
				  <div style="width:100%;">
				    <div  style="padding:0px 10px 10px 10px;_padding:0px 10px 10px 10px;">
				      <div style="padding:10px 10px 10px 10px;_padding:10px 10px 10px 10px;margin-right:35px;margin-left:30px;background-color:white;border-left:solid 2px #fcecc8;text-align:justify;">
					      <div style="font-size:11px;color:#7c7c7c;padding-left:10px;_padding-left:10px;margin-right:95px"><FONT COLOR="black"><b>Re: </b></FONT><b>by <?php echo $alumniReview['institute_name'];?></b></div>
					      <div style="margin-right:20px;padding-left:10px;_padding-left:10px;"><span><?php echo nl2br($temp['msgTxt']); ?></span></div>
				      </div>
				    </div>
				    </div>
				  <?php

						}
					      }
					  }
				    }
				  ?>
			     <div style="border-bottom:solid 1px #eae9e9;"></div>
 				<div class="lineSpace_5">&nbsp;</div>

				<!-- End : Reviews -->
			<?php
				  }
			      else{
			?>
				<!-- Start : Reviews -->
				<div style="display:none;padding:5px 15px;color:#0e0f11; " id="review<?php echo $criteriaId.$HideReviewNumber; ?>">"<?php echo $review; ?> - <b><?php echo $reviewerName; ?> Class of <?php echo $batch .' '. ucwords(strtolower($courseName)); ?></b>" </div>
 				<div class="lineSpace_5" style="display:none;">&nbsp;</div>
				<?php
				    $replyThreadId = $alumniReview['thread_id'];
				    if($replyThreadId!='' && $replyThreadId!=0){
				      foreach($alumniReviewsReply[$replyThreadId] as $temp){
					      if($temp['parentId'] == $replyThreadId){
						if($temp['msgTxt']!=''){
				  ?>
				  <div style="width:100%;">
				    <div  style="padding:0px 10px 10px 10px;_padding:0px 10px 10px 10px;display:none;" id="reviewReply<?php echo $criteriaId.$HideReviewNumber; ?>">
				      <div style="padding:10px 10px 10px 10px;_padding:10px 10px 10px 10px;margin-right:35px;margin-left:30px;background-color:white;border-left:solid 2px #fcecc8;text-align:justify;">
					      <div style="font-size:11px;color:#7c7c7c;padding-left:10px;_padding-left:10px;margin-right:95px"><FONT COLOR="black"><b>Re: </b></FONT><b>by <?php echo $alumniReview['institute_name'];?></b></div>
					      <div style="margin-right:20px;padding-left:10px;_padding-left:10px;"><span><?php echo nl2br($temp['msgTxt']); ?></span></div>
				      </div>
				    </div>
				   </div>
				  <?php

						}
					      }
					  }
				    }
				  ?>
			     <div style="border-bottom:solid 1px #eae9e9;" id="reviewLine<?php echo $criteriaId.$HideReviewNumber; ?>"></div>
			     <div style="padding:5px 15px;border-bottom:solid 1px #eae9e9;display:none;" id="hide<?php echo $criteriaId.$HideReviewNumber; ?>"><a href="javascript:void(0)" onClick="hideAllComments(<?php echo $criteriaId; ?>)">show less</a></div>
			
				<!-- End : Reviews -->
			<?php
				$HideReviewNumber++;
				}
				$NumberOfViews++;

				}
				if($lastCriteria != $criteriaId) {
			?>
			
			<?php
				}
				if($alumniReview['criteria_rating'] > 0) {
					$totalResponses++;
					$totalRating += $alumniReview['criteria_rating'];
				}
				$lastCriteria = $criteriaId;
			?>
			<!-- End  : Criteria -->
			<?php
				}
			?>
				<script>
					try{
						updateReviewInventory('<?php echo $lastCriteria; ?>', <?php echo $totalRating; ?>, <?php  echo $totalResponses; ?>,<?php echo  $totalPublishedRatings; ?>);
					} catch(e) {}
				</script>
                        </div>
                        </div>
			
                        <div style="padding:0 10px">
                            <div style="font-size:11px;color:#7c7c7c;line-height:24px" class="txt_align_r">The views expressed above do not represent the opinion of Info Edge (India) Limited</div>
                        </div>
                    </div>
                    <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
                </div>
            </div>
            <!--End_Alumni_Speak-->
<script>
	var windowLoaded = false;
	function orderReviews() {
        /*
        1 - Infra
        2 - Faculty
        3 - Placement
        4 - Overall 
        */
        var reviewsOrder = new Array(3,1,2,4);
		for(var orderIndex= 0; orderIndex < reviewsOrder.length; orderIndex++) {
            var reviewCounts = reviewsOrder[orderIndex];
            if(document.getElementById('reviewBox'+ reviewCounts) != null) {
                if((document.getElementById('reviewBox'+ reviewCounts).style.display != 'none')) {
                    document.getElementById('reviewsContainer').appendChild(document.getElementById('reviewBox'+ reviewCounts));
                } else {
                    document.getElementById('reviewBox'+ reviewCounts).parentNode.removeChild(document.getElementById('reviewBox'+ reviewCounts));
                }
            }
		}
		document.getElementById('reviewsContainer').style.display = '';
        if(document.getElementById('reviewsContainer').childNodes.length == 0) {
            document.getElementById('alumniReviewSection').parentNode.removeChild(document.getElementById('alumniReviewSection'));
        } else {
            var criteriaIdToOpen = parseInt(document.getElementById('reviewsContainer').firstChild.id.replace('reviewBox',''));
            toggleCriteriaFeedbacks(criteriaIdToOpen);
            toggleCriteriaFeedbacks(criteriaIdToOpen);
	    windowLoaded = true;
        }
	}
	orderReviews();
</script>
