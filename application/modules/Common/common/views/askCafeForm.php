<?php
	if(!isset($product)){
		$product = 'entire';
	}
	$questionText = '';
/*	if(isset($_COOKIE['commentContent']) && ($questionText == '')){
		$questionText = $_COOKIE['commentContent'];
		if((stripos($questionText,'@$#@#$$') !== false) || (stripos($questionText,'@#@!@%@') !== false)){
			$questionText = '';
		}
	}
*/
	$cookieTextD = "Enter Topic";$cookieDescD = "Enter Description";
	$cookieTextA = "Enter Topic";$cookieDescA = "Enter Description";
	$displayFieldStyle = "color: rgb(173, 166, 173)";
	$showPostOverlay = "false";
	if(isset($_COOKIE['posttitle']) &&  isset($_COOKIE['postdescription']) ){
	      if($_COOKIE['entitytype']=="discussion"){
		$cookieTextD = $_COOKIE['posttitle'];
		$cookieDescD = $_COOKIE['postdescription'];
	      }
	      else if($_COOKIE['entitytype']=="announcement"){
		$cookieTextA = $_COOKIE['posttitle'];
		$cookieDescA = $_COOKIE['postdescription'];
	      }
	      else if($_COOKIE['entitytype']=="question"){
		$questionText = $_COOKIE['posttitle'];
	      }
	      $displayFieldStyle = "";
	      $showPostOverlay = "true";
	}
	if(isset($_COOKIE['homepageQuestion']) &&  $_COOKIE['homepageQuestion']!=''){
	      $questionText = $_COOKIE['homepageQuestion'];
	      $showPostOverlay = "true";
	}
	$questionText = str_replace('"','&quot;',$questionText);
	//$questionText = (isset($questionText)&&($questionText!=''))?$questionText:'Ask your education related question here and get answers from Shiksha experts.';
	$questionTextLength = strlen($questionText);
	$base64url = base64_encode(site_url("'".$_SERVER['REQUEST_URI']."'"));
	$displayTitle = (isset($displayHeading))?$displayHeading:true;
	$entity = (isset($entity))?$entity:'question';
	$displayQuestion = 'none';$displayDiscuss = 'none';$displayReview='none';$displayEvent='none';
	$classQues = 'atab1_b'; $classDis = 'atab2_b'; $classReview = 'atab3_b'; $classEvent = 'atab4_b';
	switch($entity){
	  case 'question': $displayQuestion = 'display'; $classQues = 'atab1_w';break;
	  case 'discussion': $displayDiscuss = 'display';$classDis = 'atab2_w'; break;
	  case 'review': $displayReview = 'display'; $classReview = 'atab3_w'; break;
	  case 'announcement': $displayEvent = 'display';$classEvent = 'atab4_w'; break;
	  case 'eventAnA': $displayEvent = 'display'; $classEvent = 'atab4_w'; break;
	}

?>
<div style="margin:0px 0 0 0px">
	<div class="wdh100" id="askQuestionFormDiv">
    	<div class="raised_11">
		<!--<b class="b2"></b><b class="b3" style="background:#fff9d5"></b><b class="b4" style="background:#fff9d5"></b>-->
                
		<div class="ana-tab-cont">

        	
            
                	<div class="float_L" style="width:135px">
                    	<div class="lineSpace_13">&nbsp;</div>
                        <div id="ansTabs">
			    <a href="javascript:void(0)" <?php if($tab_required_course_page) echo 'style="display:'.$displayQuestion.'"'; ?> class="<?php echo $classQues;?>" id="atab1" onclick="openTab(this, 'tab1',<?=$qtrackingPageKeyId?>)">Ask a Question</a>
                            <a href="javascript:void(0)" <?php if($tab_required_course_page) echo 'style="display:'.$displayDiscuss.'"'; ?> class="<?php echo $classDis;?>" id="atab2" onclick="openTab(this, 'tab2',<?=$dtrackingPageKeyId?>)">Discuss a Topic</a>
                            <a href="javascript:void(0)" <?php if($tab_required_course_page) echo 'style="display:'.$displayEvent.'"'; ?> class="<?php echo $classEvent;?>" id="atab4" onclick="openTab(this, 'tab4',<?=$atrackingPageKeyId?>)">Announce</a>
                        </div>
                        <div class="lineSpace_10">&nbsp;</div>
                    </div>
                    <div class="ana-tab-details">
                    	<div style="padding:12px">                                        	
                                            
					    <!--Start_WhiteBox_Tab_1-->
					    <!--<form id="askQuestionForm" name="askQuestionForm" method="get" action="" onsubmit="try{ var questionTextValue = document.getElementById('questionText').value; if(validateQuestionForm_old(this,'<?php echo $postQuestionKey; ?>','formsubmit','askQuestionForm')) showCatCounOverlay(questionTextValue,'question'); return false;}catch (e){ return false;}" style="margin:0;padding:0">-->
						<form id="askQuestionForm" name="askQuestionForm" method="get" action="" onsubmit="try{ var questionTextValue = document.getElementById('questionText').value; if(!validateQuestionForm_old(this,'<?php echo $postQuestionKey; ?>','formsubmit','askQuestionForm')){return false;}else{ setCookieForFB('question');proceedToPostQuestion(this,'questionText'); return false;} }catch (e){ return false;}" style="margin:0;padding:0" novalidate="novalidate">
						<div id="atab1_t" style="display:<?php echo $displayQuestion; ?>">
						    <h2 class="Fnt14 mb5 bld"  style="display:block">Need expert guidance on education or career? Ask our experts.</h2>
						    <div class="wdh100 mb5">
							<textarea style="height:40px;" class="universal-select" name="questionText" id="questionText" autocomplete="off" value="<?php echo $questionText; ?>" onblur="enterEnabled=false;" onfocus="try{ enterEnabled=true; }catch (e){}" onkeypress="if(event.keyCode == 13){ return false;} else { textKey(this); }" onkeyup="if(event.keyCode == 13){ return false;} else { textKey(this); }" profanity="false" validate="validateStr" caption="Question" maxlength="140" minlength="2" required="true" validateSingleChar='true'><?php echo $questionText; ?></textarea>
							<input name="referalUrlForAskQuestionFromHeader" id="referalUrlForAskQuestionFromHeader" type="hidden" value="<?php echo $base64url; ?>"/>
							<input type="hidden" id="crs_pg_prms" name="crs_pg_prms"/>
							<input type="hidden" id="tracking_keyid" name="tracking_keyid" value='<?=$trackingPageKeyId?>'>
						    </div>
						<input type = "hidden" id = "googleSearchCompleteArray" name= "googleSearchCompleteArray" value = ""/>
						    <div class="wdh100 ">
							<div class="float_L">
							    <div class=" Fnt10 fcdGya"><span id="questionText_counter">0</span> out of 140 characters</div>
							    <div class="row errorPlace"><span id="questionText_error" class="errorMsg">&nbsp;</span></div>
							    <div><img src="/public/images/sWat.gif" align="absmiddle" /> <span class="Fnt11 fcdGya">Average time to get an answer is <strong><?php echo $averageTimeToAnswer; ?>:00 Mins</strong></span></div>
							</div>
							<div class="float_R">
							    <div><input type="submit" value="Ask" class="orange-button" />&nbsp;</div>
							</div>
							<div class="clear_B">&nbsp;</div>
						    </div>                                               
						</div>
					    </form>
					    <!--End_WhiteBox_Tab_1-->
                                            
                                            <!--Start_WhiteBox_Tab_2-->
					    <form id="askQuestionFormD" name="askQuestionFormD" method="get" action="" onsubmit="return false;" style="margin:0;padding:0" novalidate="novalidate">
						<div id="atab2_t" style="display:<?php echo $displayDiscuss; ?>">
						    <h2 class="Fnt14 mb5 bld"  style="display:block">Hear what others have to say. Enter a topic to start a discussion.</h2>
						    <div class="wdh100 mb5">
							<input type="text" style="<?php echo $displayFieldStyle;?>;" onkeyup="checkForNameMention(event,this,'questionTextD');" class="universal-txt-field" name="questionTextD" id="questionTextD" autocomplete="off" onkeyup="try{ textKey(this); } catch (e){}" profanity="true" validate="validateStr" caption="Topic" maxlength="100" minlength="5" required="true" onblur="checkTextElementOnTransition(this,'blur');enterEnabled=false;" onfocus="checkTextElementOnTransition(this,'focus');try{ enterEnabled=true; }catch (e){}" default="Enter Topic" value="<?php echo $cookieTextD;?>" validateSingleChar='true'/>
							<input name="referalUrlForAskQuestionFromHeaderD" id="referalUrlForAskQuestionFromHeaderD" type="hidden" value="<?php echo $base64url; ?>"/>
						    </div> 
						    <div class="row errorPlace"><span id="questionTextD_error" class="errorMsg">&nbsp;</span></div>
						    <div class="wdh100 mb5">
							<textarea class="universal-select" style="<?php echo $displayFieldStyle;?>;" name="questionDescD" id="questionDescD" autocomplete="off" onkeyup="checkForNameMention(event,this,'questionDescD');" profanity="true" validate="validateStr" caption="Description" maxlength="2500" minlength="5" required="true"  onblur="checkTextElementOnTransition(this,'blur');enterEnabled=false;" onfocus="checkTextElementOnTransition(this,'focus');try{ enterEnabled=true; }catch (e){}" default="Enter Description" value="<?php echo $cookieDescD;?>" validateSingleChar='true'><?php echo $cookieDescD;?></textarea>
						    </div> 
						    <div class="row errorPlace"><span id="questionDescD_error" class="errorMsg">&nbsp;</span></div>
						    <input type="hidden" name="mentionedUsers" value="" id="mentionedUsers"/>
						    <div class="wdh100 ">
							<div class="float_L"><img src="/public/images/sWat.gif" align="absmiddle" /> <span class="Fnt11 fcdGya">Over 20,000 students visit Shiksha.com each day!</span></div>
							<div class="float_R">
							  <div>
							      <input type="button" id = 'ask-submit-button' value="Ask" class="orange-button" onclick="try{ var questionTextValue = document.getElementById('questionTextD').value; if($('questionTextD').value=='Enter Topic') $('questionTextD').value=''; if($('questionDescD').value=='Enter Description') $('questionDescD').value=''; if(validateQuestionForm_old($('askQuestionFormD'),'<?php echo $postQuestionKey; ?>','formsubmit','askQuestionFormD')) showCatCounOverlay(questionTextValue,'discussion'); return false;}catch (e){ return false;}" uniqueattr="CafeDiscussButton"/>
							  </div>
							</div>
							<div class="clearFix"></div>
						    </div>                                               
						</div>
					    </form>
					    <!--End_WhiteBox_Tab_2-->
                                            
                                            <!--Start_WhiteBox_Tab_3-->
                                            <!--<div id="atab3_t" style="display:<?php echo $displayReview; ?>">
                                                <div class="Fnt14 mb5 bld">Review the college you studied from and help others.</div>
						<?php //$this->load->view('common/institute_auto_suggester'); ?>
                                            </div>-->
                                            <!--End_WhiteBox_Tab_3-->
                                            
                                            <!--Start_WhiteBox_Tab_4-->
					    <form id="askQuestionFormA" name="askQuestionFormA" method="get" action="" onsubmit="try{ var questionTextValue = document.getElementById('questionTextA').value; if($('questionTextA').value=='Enter Topic') $('questionTextA').value=''; if($('questionDescA').value=='Enter Description') $('questionDescA').value='';if(validateQuestionForm_old(this,'<?php echo $postQuestionKey; ?>','formsubmit','askQuestionFormA')) {setCookieForFB('announcement');showCatCounOverlay(questionTextValue,'announcement');} return false;}catch (e){ return false;}" style="margin:0;padding:0" novalidate="novalidate">
						<div id="atab4_t" style="display:<?php echo $displayEvent; ?>">
						    <h2 class="Fnt14 mb5 bld" style="display:block">Got something interesting to share? Announce it here.</h2>
						    <div class="wdh100 mb5">
							<input type="text" style="<?php echo $displayFieldStyle;?>;" class="universal-txt-field" name="questionTextA" id="questionTextA" autocomplete="off" onkeyup="try{ textKey(this); } catch (e){}" profanity="true" validate="validateStr" caption="Topic" maxlength="100" minlength="5" required="true" onblur="checkTextElementOnTransition(this,'blur');enterEnabled=false;" onfocus="checkTextElementOnTransition(this,'focus');try{ enterEnabled=true; }catch (e){}" default="Enter Topic" value="<?php echo $cookieTextA;?>" validateSingleChar='true'/>
							<input name="referalUrlForAskQuestionFromHeaderA" id="referalUrlForAskQuestionFromHeaderA" type="hidden" value="<?php echo $base64url; ?>"/>
						    </div> 
						    <div class="row errorPlace"><span id="questionTextA_error" class="errorMsg">&nbsp;</span></div>
						    <div class="wdh100 mb5">
							<textarea class="universal-select" style="<?php echo $displayFieldStyle;?>;" name="questionDescA" id="questionDescA" autocomplete="off" onkeyup="try{ textKey(this); } catch (e){}" profanity="true" validate="validateStr" caption="Description" maxlength="2500" minlength="5" required="true"  onblur="checkTextElementOnTransition(this,'blur');enterEnabled=false;" onfocus="checkTextElementOnTransition(this,'focus');try{ enterEnabled=true; }catch (e){}" default="Enter Description" value="<?php echo $cookieDescA;?>" validateSingleChar='true'><?php echo $cookieDescA;?></textarea>
						    </div> 
						    <div class="row errorPlace"><span id="questionDescA_error" class="errorMsg">&nbsp;</span></div>
						    <div class="wdh100 ">
							<!--<div class="float_L"><span><input type="checkbox" /> Post as an Event</span></div>-->
							<div class="float_R">
							    <div>
								<input type="submit" value="Ask" class="orange-button" uniqueattr="CafeAnnounceButton"/>
							    </div>
							</div>
							<div class="clearFix"></div>
						    </div>                                               
						</div>
					    </form>
					    <!--End_WhiteBox_Tab_4-->
                                            
                                            
                                        </div>
                                    
                               
						
                    </div>
                    <div class="clearFix"></div>
            
            
        </div>
        
        </div>
    </div>
</div>
<div id="catCounOverlay" style="display:none;"></div>

