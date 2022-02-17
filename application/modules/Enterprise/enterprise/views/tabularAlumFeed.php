            <!--Start_Alumni_Speak-->
<?php $this->load->view('common/ga'); ?>
<script>
	function loadScript(url, callback){
	    var script = document.createElement("script")
	    script.type = "text/javascript";
	    if (script.readyState){  //IE
		script.onreadystatechange = function(){
		    if (script.readyState == "loaded" ||
			    script.readyState == "complete"){
			script.onreadystatechange = null;
			callback();
		    }
		};
	    } else {  //Others
		script.onload = function(){
		    callback();
		};
	    }
	    script.src = url;
	    document.getElementsByTagName("head")[0].appendChild(script);
	}

	function trackEventByGA(eventAction,eventLabel) {
		if(typeof(pageTracker)!='undefined' && currentPageName!=null) {
			pageTracker._trackEvent(currentPageName, eventAction, eventLabel);
		}
		return true;
	}

	var B=(function x(){})[-5]=='x'?'FF3':(function x(){})[-6]=='x'?'FF2':/a/[-1]=='a'?'FF':'\v'=='v'?'IE':/a/.__proto__=='//'?'Saf':/s/.test(/a/.toString)?'Chr':/^function \(/.test([].sort)?'Op':'Unknown';
	function logJSErrors(errObj) {
	    var img = new Image();
	    var url = '/public/secimg/jslog.php?m=' + encodeURIComponent(errObj.message) +
	    '&ln=' + encodeURIComponent(errObj.lineNumber) +
	    '&url=' + encodeURIComponent(errObj.fileName) +
	    '&browser=' + encodeURIComponent(errObj.browserInfo) +'&'+B;
	    img.src = url;
	}

	function toggleCriteriaFeedbacks(id) {
		for(var criteriaCount =1; criteriaCount <=4; criteriaCount++) {
			hideAllComments(id);
			if(inlineOpenFormId != 'undefined' && inlineOpenFormId != '')
				hideAnswerForm(inlineOpenFormId,20,'',true);

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
	    if(document.getElementById('userReply'+id+i).innerHTML == ""){
	      if(document.getElementById('replyButton'+id+i))
		document.getElementById('replyButton'+id+i).style.display = "block";
	    }
	    else{
	      if(document.getElementById('replyForm'+id+i))
		document.getElementById('replyForm'+id+i).style.display = "block";
	    }
	    document.getElementById('reviewLine'+id+i).style.display = "block";
	    if(document.getElementById('userReplyDiv'+id+i))
	      document.getElementById('userReplyDiv'+id+i).style.display = "block";
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
	    if(document.getElementById('userReply'+id+i).innerHTML == ""){
	      if(document.getElementById('replyButton'+id+i))
		document.getElementById('replyButton'+id+i).style.display = "none";
	    }
	    else{
	      if(document.getElementById('replyForm'+id+i))
		document.getElementById('replyForm'+id+i).style.display = "none";
	    }
	    document.getElementById('reviewLine'+id+i).style.display = "none";
	    if(document.getElementById('userReplyDiv'+id+i))
	      document.getElementById('userReplyDiv'+id+i).style.display = "none";
	    i++;
	  }
	  if(document.getElementById('view'+id))
	    document.getElementById('view'+id).style.display = "block";
	  var j=i-1;
	  if(document.getElementById('hide'+id+j))
	    document.getElementById('hide'+id+j).style.display = "none";
	  if(inlineOpenFormId != 'undefined' && inlineOpenFormId != '')
		  hideAnswerForm(inlineOpenFormId,20,'',true);
	}
</script>
<?php
	//$alumniReviews = $cmsPageArr['alumniReviews'];
?>
            <div  style="margin-left:8px;display:none;" id="noAlumniReviewSection">
            	<div class="raised_11">
			<b class="b2"></b><b class="b3"></b><b class="b4"></b>
            		<div class="boxcontent_11" style="position:relative">
                    	<div style="padding:5px 10px;height:43px">
				<div style="float:right;position:absolute;right:40px;top:-17px"></div>
                        	<div class="fontSize_14p">
                            	<div class="bld"><img src="/public/images/alminiBlog.gif" align="absmiddle" /> Alumni Speak</div>
                                <div style="font-size:11px;color:#7c7c7c;padding-left:28px;margin-right:95px">There are no Alumni feedbacks available for the institute.</div>
				</div>
                        </div>
			</div>
                    <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		<div class="bgsplit">
		</div>
		<div style="padding-left: 5 px;">
		    <button class="btn-submit5 w20" name="collegeListing" id="collegeListing" value="collegeListing" type="button" onClick="window.location.href = '<?php echo site_url(); ?>enterprise/Enterprise'; return false;">
			<div class="btn-submit5"><p class="btn-submit6">Back to Colleges</p></div>
		    </button>
		</div>
	      </div>
	    </div>

            <div  style="margin-left:8px" id="alumniReviewSection">
            	<div class="raised_11">
			<b class="b2"></b><b class="b3"></b><b class="b4"></b>
            		<div class="boxcontent_11" style="position:relative">
                    	<div style="padding:5px 10px;height:43px">
				<div style="float:right;position:absolute;right:40px;top:-17px"></div>
                        	<div class="fontSize_14p">
                            	<div class="bld"><img src="/public/images/alminiBlog.gif" align="absmiddle" /> Alumni Speak</div>
                                <div style="font-size:11px;color:#7c7c7c;padding-left:28px;margin-right:95px">Hear what alumni have to say about your institute collected exclusively on Shiksha.com</div>
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
				$threadId = 100;
				foreach($alumniReviews as $alumniReview)
				{
					$threadId++;
					$criteriaName = $alumniReview['criteria_name'];
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
					$replyReviewerName = $reviewerName;
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
					    <div style="padding:0 10px;" id="reviewBox<?php echo $criteriaId; ?>">
						<div style="line-height:23px;height:23px;border-bottom:1px solid #fcecc8">
						    <div class="float_R" style="background:url(/public/images/blogM.gif) no-repeat right top;padding-right:35px"><a href="javascript:void(0)" id="totalPublishedReviews<?php echo $criteriaId; ?>" onclick="toggleCriteriaFeedbacks(<?php echo $criteriaId; ?>)"></a></div>
						    <div>
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
			    if($replyThreadId=='' || $replyThreadId==0){
			?>
			    <div style="padding:5px 15px;color:#0e0f11;" id="replyButton<?php echo $criteriaId.$threadId;?>">&nbsp;<a href="javascript:void(0)" onClick="try{ showAnswerForm('<?php echo $criteriaId.$threadId; ?>',58,''); }catch (e){}">Reply</a></div>
			    <div  id="replyForm<?php echo $criteriaId.$threadId;?>" style="padding:0px 10px 10px 10px;display:none;">
			      <div style="padding:10px 10px 10px 10px;margin-right:85px;margin-left:30px;background-color:white;border-left:solid 2px #fcecc8;text-align:justify;">
				      <div style="font-size:11px;color:#7c7c7c;padding-left:10px;margin-right:95px"><FONT COLOR="black"><b>Re: </b></FONT><b>by <?php echo $alumniReview['institute_name'];?></b></div>
				      <div style="margin-right:20px;padding-left:10px;"><span name="userReply<?php echo $criteriaId.$threadId; ?>" id="userReply<?php echo $criteriaId.$threadId; ?>"></span></div>
			      </div>
			    </div>
			    <!--Start_Inline_Comment-->
			    <div id="inlineAnswerFormCntainer<?php echo $threadId; ?>" style="width:100%;">
			    <?php
				$url = '/enterprise/Enterprise/setAlumFeedReply';
				echo $this->ajax->form_remote_tag( array('url'=>$url.'/'.$threadId ,'before' => 'if(validateFields(this) != true){return false;}','success' => 'javascript:showAlumFeedReply(request.responseText);'));
				?>
				    <div style="display:none;background:#eef7fe;padding:5px 15px;padding-left:15px;padding-right:50px;" class="textboxBorder" id="hiddenFormPart<?php echo $criteriaId.$threadId; ?>">
					    <div style="margin-left:0px">
						    <div class="float_L row">
							    <!--Start_For Show and Hide-->
							    <div style="padding-left:0px;overflow:hidden;line-height:20px;" class="mar_left_10p" >
								    <?php if($reviewerName != ''){ ?>
								    <div style="margin-right:7px;"><FONT color="orange">Reply To </FONT><?php echo $replyReviewerName;?></div>
								    <?php } ?>
								    <div style="margin-right:7px;">
									    <textarea name="replyText<?php echo $criteriaId.$threadId; ?>" onkeyup="textKey(this)" class="textboxBorder inlineWidgetFormClass" id="replyText<?php echo $criteriaId.$threadId; ?>" validate="validateResponse" caption="Response" maxlength="2000" minlength="200" required="true" onfocus="try{ showAnswerForm('<?php echo $criteriaId.$threadId; ?>',58,''); }catch (e){}" style="height:20px;width:96%;"></textarea>
								    </div>
								    <div style="padding-top:1px">
									    <span id="replyText<?php echo $criteriaId.$threadId; ?>_counter">0</span> out of 2000 character
								    </div>
								    <div class="errorPlace" style="display:block;line-height:17px;">
									    <div class="errorMsg" id="replyText<?php echo $criteriaId.$threadId; ?>_error"></div>
								    </div>
								    <div style="padding-top:3px;">Type in the character you see in picture below:</div>
								    <div style="padding-top:8px">
									    <img src="/public/images/blankImg.gif" onabort="javascript:try{reloadCaptcha(this.id);}catch(e){alert(e.message);}" onClick="javascript:reloadCaptcha(this.id);" id="secimg<?php echo $criteriaId.$threadId; ?>" align="absmiddle" />&nbsp;&nbsp;
									    <input type="text" name="seccode<?php echo $criteriaId.$threadId; ?>" id="seccode<?php echo $criteriaId.$threadId; ?>" validate="validateSecurityCode" caption="Security Code" maxlength="5" minlength="5" required="true" size="5" style="width:55px" />
								    </div>
								    <div class="errorPlace" style="display:block;line-height:17px;">
									    <div class="errorMsg" id="seccode<?php echo $criteriaId.$threadId; ?>_error"></div>
								    </div>
								    <input type="hidden" name="threadid<?php echo $threadId; ?>" value="<?php echo $threadId; ?>" />
								    <input type="hidden" name="secCodeIndex" value="seccodeForInlineAnswer" />
								    <input type="hidden" name="fromOthers<?php echo $threadId; ?>" value="AlumFeedReply" />
								    <input type="hidden" name="threadId" value="<?php echo $threadId; ?>" />
								    <input type="hidden" name="email<?php echo $threadId; ?>" value="<?php echo $alumniReview['email']?>" />
								    <input type="hidden" name="institute<?php echo $threadId; ?>" value="<?php echo $alumniReview['institute_id']?>" />
								    <input type="hidden" name="criteria<?php echo $threadId; ?>" value="<?php echo $alumniReview['criteria_id']?>" />
								    <input type="hidden" name="review<?php echo $threadId; ?>" value="<?php echo $review; ?>" />
								    <input type="hidden" name="hideReviewNumber<?php echo $threadId; ?>" value="-1" />
								    <div style="padding:6px 0 13px 0">
									    <input type="Submit" value="Submit" class="submitGlobal" id="submitButton<?php echo $criteriaId.$threadId; ?>"/>
									    <span><a href="javascript:void(0);" onClick="try{hideAnswerForm('<?php echo $criteriaId.$threadId; ?>',20,'',true);} catch(e){}">Cancel</a></span>
								    </div>
							    </div>
							    <!--End_For Show and Hide-->
						    </div>
					    </div>
					    <div class="withClear clear_L">&nbsp;</div>
				    </div>
			    </form>
			    </div>
			    <!--End_Inline_Comment-->
			<?php
			    }
			    else
			    {
			      foreach($alumniReviewsReply[$replyThreadId] as $temp){
				      if($temp['parentId'] == $replyThreadId){
					if($temp['msgTxt']!=''){
			  ?>
			  <div style="width:100%;">
			    <div  style="padding:0px 10px 10px 10px;">
			      <div style="padding:10px 10px 10px 10px;margin-right:85px;margin-left:30px;background-color:white;border-left:solid 2px #fcecc8;text-align:justify;">
				      <div style="font-size:11px;color:#7c7c7c;padding-left:10px;margin-right:95px"><FONT COLOR="black"><b>Re: </b></FONT><b>by <?php echo $alumniReview['institute_name'];?></b></div>
				      <div style="margin-right:20px;padding-left:10px;"><span name="userReply<?php echo $criteriaId.$threadId; ?>" id="userReply<?php echo $criteriaId.$threadId; ?>"><?php echo nl2br($temp['msgTxt']); ?></span></div>
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

				<!-- End : Reviews -->
			<?php
			    }
			else{
			?>
				<!-- Start : Reviews -->
                            <div style="display:none;padding:5px 15px;color:#0e0f11; " id="review<?php echo $criteriaId.$HideReviewNumber; ?>">"<?php echo $review; ?> - <b><?php echo $reviewerName; ?> Class of <?php echo $batch .' '. ucwords(strtolower($courseName)); ?></b>" </div>
			  <?php
			      $replyThreadId = $alumniReview['thread_id'];
			      if($replyThreadId=='' || $replyThreadId==0){
			  ?>
			    <div style="padding:5px 15px;color:#0e0f11;display:none;" id="replyButton<?php echo $criteriaId.$HideReviewNumber; ?>">&nbsp;<a href="javascript:void(0)" onClick="try{ showAnswerForm('<?php echo $criteriaId.$HideReviewNumber;; ?>',58,''); }catch (e){}">Reply</a></div>
			    <div  id="replyForm<?php echo $criteriaId.$HideReviewNumber;?>" style="padding:0px 10px 10px 10px;display:none;">
			      <div style="padding:10px 10px 10px 10px;margin-right:85px;margin-left:30px;background-color:white;border-left:solid 2px #fcecc8;text-align:justify;">
				      <div style="font-size:11px;color:#7c7c7c;padding-left:10px;margin-right:95px"><FONT COLOR="black"><b>Re: </b></FONT><b>by <?php echo $alumniReview['institute_name'];?></b></div>
				      <div style="margin-right:20px;padding-left:10px;"><span name="userReply<?php echo $criteriaId.$HideReviewNumber; ?>" id="userReply<?php echo $criteriaId.$HideReviewNumber; ?>"></span></div>
			      </div>
			    </div>
			    <!--Start_Inline_Comment-->
			    <div id="inlineAnswerFormCntainer<?php echo $threadId; ?>" style="width:100%;">
			    <?php
				$url = '/enterprise/Enterprise/setAlumFeedReply';
				echo $this->ajax->form_remote_tag( array('url'=>$url.'/'.$threadId ,'before' => 'if(validateFields(this) != true){return false;}','success' => 'javascript:showAlumFeedReply(request.responseText);'));
				?>
				    <div style="display:none;background:#eef7fe;padding:5px 15px;padding-left:15px;padding-right:50px;" class="textboxBorder" id="hiddenFormPart<?php echo $criteriaId.$HideReviewNumber; ?>">
					    <div style="margin-left:0px">
						    <div class="float_L row">
							    <!--Start_For Show and Hide-->
							    <div style="padding-left:0px;overflow:hidden;line-height:20px;" class="mar_left_10p" >
								    <?php if($reviewerName != ''){ ?>
								    <div style="margin-right:7px;"><FONT color="orange">Reply To </FONT><?php echo $replyReviewerName;?></div>
								    <?php } ?>
								    <div style="margin-right:7px;">
									    <textarea name="replyText<?php echo $criteriaId.$HideReviewNumber; ?>" onkeyup="textKey(this)" class="textboxBorder inlineWidgetFormClass" id="replyText<?php echo $criteriaId.$HideReviewNumber; ?>" validate="validateResponse" caption="Response" maxlength="2000" minlength="200" required="true" onfocus="try{ showAnswerForm('<?php echo $criteriaId.$HideReviewNumber; ?>',58,''); }catch (e){}" style="height:20px;width:96%;"></textarea>
								    </div>
								    <div style="padding-top:1px">
									    <span id="replyText<?php echo $criteriaId.$HideReviewNumber; ?>_counter">0</span> out of 2000 character
								    </div>
								    <div class="errorPlace" style="display:block;line-height:17px;">
									    <div class="errorMsg" id="replyText<?php echo $criteriaId.$HideReviewNumber; ?>_error"></div>
								    </div>
								    <div style="padding-top:3px;">Type in the character you see in picture below:</div>
								    <div style="padding-top:8px">
									    <img src="/public/images/blankImg.gif" onabort="javascript:try{reloadCaptcha(this.id);}catch(e){alert(e.message);}" onClick="javascript:reloadCaptcha(this.id);" id="secimg<?php echo $criteriaId.$HideReviewNumber; ?>" align="absmiddle" />&nbsp;&nbsp;
									    <input type="text" name="seccode<?php echo $criteriaId.$HideReviewNumber; ?>" id="seccode<?php echo $criteriaId.$HideReviewNumber; ?>" validate="validateSecurityCode" caption="Security Code" maxlength="5" minlength="5" required="true" size="5" style="width:55px" />
								    </div>
								    <div class="errorPlace" style="display:block;line-height:17px;">
									    <div class="errorMsg" id="seccode<?php echo $criteriaId.$HideReviewNumber; ?>_error"></div>
								    </div>
								    <input type="hidden" name="threadid<?php echo $threadId; ?>" value="<?php echo $threadId; ?>" />
								    <input type="hidden" name="secCodeIndex" value="seccodeForInlineAnswer" />
								    <input type="hidden" name="fromOthers<?php echo $threadId; ?>" value="AlumFeedReply" />
								    <input type="hidden" name="threadId" value="<?php echo $threadId; ?>" />
								    <input type="hidden" name="email<?php echo $threadId; ?>" value="<?php echo $alumniReview['email']?>" />
								    <input type="hidden" name="institute<?php echo $threadId; ?>" value="<?php echo $alumniReview['institute_id']?>" />
								    <input type="hidden" name="criteria<?php echo $threadId; ?>" value="<?php echo $alumniReview['criteria_id']?>" />
								    <input type="hidden" name="review<?php echo $threadId; ?>" value="<?php echo $review; ?>" />
								    <input type="hidden" name="hideReviewNumber<?php echo $threadId; ?>" value="<?php echo $HideReviewNumber;?>" />
								    <div style="padding:6px 0 13px 0">
									    <input type="Submit" value="Submit" class="submitGlobal" id="submitButton<?php echo $criteriaId.$HideReviewNumber; ?>"/>
									    <span><a href="javascript:void(0);" onClick="try{hideAnswerForm('<?php echo $criteriaId.$HideReviewNumber; ?>',20,'',true);} catch(e){}">Cancel</a></span>
								    </div>
							    </div>
							    <!--End_For Show and Hide-->
						    </div>
					    </div>
					    <div class="withClear clear_L">&nbsp;</div>
				    </div>
				  </form>
				</div>
				<!--End_Inline_Comment-->
			      <?php
				  }
				  else
				  {
				    foreach($alumniReviewsReply[$replyThreadId] as $temp){
					    if($temp['parentId'] == $replyThreadId){
					      if($temp['msgTxt']!=''){
				?>
				<div style="width:100%;">
				  <div  id="userReplyDiv<?php echo $criteriaId.$HideReviewNumber; ?>" style="padding:0px 10px 10px 10px;display:none;">
				    <div style="padding:10px 10px 10px 10px;margin-right:85px;margin-left:30px;background-color:white;border-left:solid 2px #fcecc8;text-align:justify;">
					    <div style="font-size:11px;color:#7c7c7c;padding-left:10px;margin-right:95px"><FONT COLOR="black"><b>Re: </b></FONT><b>by <?php echo $alumniReview['institute_name'];?></b></div>
					    <div style="margin-right:20px;padding-left:10px;"><span name="userReply<?php echo $criteriaId.$HideReviewNumber; ?>" id="userReply<?php echo $criteriaId.$HideReviewNumber; ?>"><?php echo nl2br($temp['msgTxt']); ?></span></div>
				    </div>
				  </div>
				</div>
				<?php

					      }
					    }
					}
				  }
				?>
				  <div style="border-bottom:solid 1px #eae9e9;display:none;" id="reviewLine<?php echo $criteriaId.$HideReviewNumber; ?>"></div>
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
				if(($alumniReview['criteria_rating'] > 0) && ($alumniReview['status'] == 'published')) {
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
		<div class="bgsplit">
		</div>
		<div style="float:left; padding-left: 5 px;">
		    <button class="btn-submit5 w20" name="publishListing" id="publishListing" value="publishListing" type="button" onClick="window.open('<?php echo site_url(); ?>getListingDetail/<?php echo $extraParam; ?>/institute','hurray','fullscreen=yes,menubar=yes,status=yes,location=yes,toolbar=yes,scrollbars=yes,directories=yes'); return false;">
			<div class="btn-submit5"><p class="btn-submit6">See Detail Page</p></div>
		    </button>
		</div>
		<div style="padding-left: 5 px;">
		    <button class="btn-submit5 w20" name="collegeListing" id="collegeListing" value="collegeListing" type="button" onClick="window.location.href = '<?php echo site_url(); ?>enterprise/Enterprise'; return false;">
			<div class="btn-submit5"><p class="btn-submit6">Back to Colleges</p></div>
		    </button>
		</div>
            </div>
            <!--End_Alumni_Speak-->


<script>
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
	    document.getElementById('noAlumniReviewSection').style.display = 'block';
        } else {
            var criteriaIdToOpen = parseInt(document.getElementById('reviewsContainer').firstChild.id.replace('reviewBox',''));
            toggleCriteriaFeedbacks(criteriaIdToOpen);
            toggleCriteriaFeedbacks(criteriaIdToOpen);
        }
	}
	orderReviews();
	var clFlag = new Array();
	var previousMessageForInline = '';
	var inlineOpenFormId = '';

	function showAnswerForm(threadId,heightOfTextArea,message){
	try{
		var hiddenPartObj = $('hiddenFormPart'+threadId);
		var textAreaObject = $('replyText'+threadId);
		if(hiddenPartObj.style.display !== 'block'){
			//if(inlineOpenFormId != 'undefined' && inlineOpenFormId != ''){
			if(!isNaN(inlineOpenFormId)){
				hideAnswerForm(inlineOpenFormId,20,'',true);
			}
			inlineOpenFormId = threadId;
			previousMessageForInline = message;
			var currntHeight = textAreaObject.style.height;
			currntHeight = Number(currntHeight.substring(0,(currntHeight.length - 2)));
			var currntHeight2 = 10;
			var intervalValue = ((heightOfTextArea-currntHeight)/10);
			var intervalValue2 = (130/10);
			var count = 0;
			$(textAreaObject.id+'_counter').innerHTML = '0';
			hiddenPartObj.style.display = 'block';
			hiddenPartObj.style.height = 10 + 'px';

			var intervalHadle = setInterval(function (){
					currntHeight += intervalValue;
					currntHeight2 += intervalValue2;
					textAreaObject.style.height = currntHeight + 'px';
					hiddenPartObj.style.height = currntHeight2 + 'px';
					count++;
					if(count >= 10){ clearInterval(intervalHadle);hiddenPartObj.style.height = null; hiddenPartObj.style.overflow = ''; }
					},(500/10));

			//clearMessages(textAreaObject.form,true,true);
			reloadCaptcha('secimg'+threadId,'seccodeForInlineAnswer');
			textAreaObject.style.color = "#000";

		}
	}catch(e){ }
	return;
      }

      function hideAnswerForm(threadId,heightOfTextArea,message,hideByForce){
	try{
		var textAreaObject = $('replyText'+threadId);
		var hiddenPartObj = $('hiddenFormPart'+threadId);
		if(!textAreaObject){
			return;
		}
		if(typeof(hideByForce) == 'undefined'){
			hideByForce = false;
		}
		if(threadId == inlineOpenFormId){
			inlineOpenFormId = '';
		}
		hiddenPartObj.style.overflow = 'hidden';
		if((((hiddenPartObj.style.display !== 'none') && (trim(textAreaObject.value) == "")) || (hideByForce))){
			var currntHeight = textAreaObject.style.height;
			//$('seccode'+threadId).value = "";
			currntHeight = Number(currntHeight.substring(0,(currntHeight.length - 2)));
			var currntHeight2 = 130;
			var intervalValue = ((currntHeight - heightOfTextArea)/10);
			var intervalValue2 = ((currntHeight2-10)/10);
			var count = 0;
			hiddenPartObj.style.display = 'block';
			var intervalHadle = setInterval(function (){
					currntHeight = currntHeight - intervalValue;
					currntHeight2 = currntHeight2 - intervalValue2;
					textAreaObject.style.height = currntHeight + 'px';
					hiddenPartObj.style.height = currntHeight2 + 'px';
					count++;
					if(count >= 10){hiddenPartObj.style.display = 'none'; clearInterval(intervalHadle);}
					},(500/10));
			textAreaObject.style.color = "#a8a7ac";
			textAreaObject.value = "";
			document.getElementById('seccode'+threadId).value = "";
			document.getElementById('replyText'+threadId+"_error").innerHTML = "";
			document.getElementById('seccode'+threadId+"_error").innerHTML = "";
		}
	}catch(e){alert(e.message); }
    return;
}

function showAlumFeedReply(response)
{
  var responseArray = response.split("::");
  if(responseArray[3]<0)
  {
    if(responseArray[0] == "secimg error")
    {
      reloadCaptcha('secimg'+responseArray[2]+responseArray[1],'seccodeForInlineAnswer');
      document.getElementById('seccode'+responseArray[2]+responseArray[1]+'_error').parentNode.style.display = 'inline';
      document.getElementById('seccode'+responseArray[2]+responseArray[1]+'_error').innerHTML = "Please enter the Security Code as shown in the image.";
    }
    else if(responseArray[0] == "db error")
    {
      hideAnswerForm(responseArray[2]+responseArray[1],20,'',true);
    }
    else
    {
      hideAnswerForm(responseArray[2]+responseArray[1],20,'',true);
      document.getElementById('userReply'+responseArray[2]+responseArray[1]).innerHTML = responseArray[0];
      document.getElementById('replyForm'+responseArray[2]+responseArray[1]).style.display = 'block';
      document.getElementById('replyButton'+responseArray[2]+responseArray[1]).style.display = 'none';
    }
  }
  else
  {
    if(responseArray[0] == "secimg error")
    {
      reloadCaptcha('secimg'+responseArray[2]+responseArray[3],'seccodeForInlineAnswer');
      document.getElementById('seccode'+responseArray[2]+responseArray[3]+'_error').parentNode.style.display = 'inline';
      document.getElementById('seccode'+responseArray[2]+responseArray[3]+'_error').innerHTML = "Please enter the Security Code as shown in the image.";
    }
    else if(responseArray[0] == "db error")
    {
      hideAnswerForm(responseArray[2]+responseArray[3],20,'',true);
    }
    else
    {
      hideAnswerForm(responseArray[2]+responseArray[3],20,'',true);
      document.getElementById('userReply'+responseArray[2]+responseArray[3]).innerHTML = responseArray[0];
      document.getElementById('replyForm'+responseArray[2]+responseArray[3]).style.display = 'block';
      document.getElementById('replyButton'+responseArray[2]+responseArray[3]).style.display = 'none';
    }
  }
}
</script>
