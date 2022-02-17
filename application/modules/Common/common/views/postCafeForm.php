<?php
		$widget = 'askQuestionPage';

                $userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
		$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
		if($userId != 0){
			$tempJsArray = array('commonnetwork','alerts','ana_common','myShiksha', 'discussion_post','facebook','processForm');
			$loggedIn = 1;
		}else{
			$tempJsArray = array('commonnetwork','ana_common','myShiksha', 'discussion_post','facebook','processForm');
			$loggedIn = 0;
		}
		$bannerProperties = array('pageId'=>'ASK_DISCUSS', 'pageZone'=>'HEADER');
		$headerComponents = array(
						'css'	=>	array('header','common_new','ask'),
			                        'js' => array('common','discussion','user'),
						'jsFooter'=>    $tempJsArray,
						'title'	=>	'Ask and Answer - Education Career Forum Community - Study Forum - Education Career Counselors - Study Circle - Career Counseling',
						'tabName' =>	'Discussion',
						'taburl' =>  site_url('messageBoard/MsgBoard/discussionHome'),
						'metaDescription'	=>'Ask Questions on various education and career topics or find answers to questions related to education and career options from our education counselors and users in this education and career forum community.',
						'metaKeywords'	=>'Ask and Answer, Education, Career Forum Community, Study Forum, Education Career Counselors, Career Counseling, study circle, Education Events, Admissions, Scholarships, Examination Results, Career Fairs, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships, shiksha',
						'product'	=>'forums',
						'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
						'bannerProperties' => $bannerProperties,
						'questionText'	=> $questionText,
						'callShiksha'=>1,
						'notShowSearch' => true,
						'postQuestionKey' => 'ASK_ASKHOME_HEADER_POSTQUESTION',
						'showBottomMargin' => false
					);
		$this->load->view('common/header', $headerComponents);
                $commonTabURL = str_replace('@qnaTab@',$myqnaTab,$tabURL);
		$headerTabURL = site_url('messageBoard/MsgBoard/discussionHome')."/1/@tab@/1/".$myqnaTab."/".$actionDone;
		$dataForHeaderSearchPanel = array('commonTabURL' => $headerTabURL);
		$this->load->view('messageBoard/headerPanelForAnA',$dataForHeaderSearchPanel);
		$data = array(
				'successurl'=> '',
				'successfunction'=>'',
				'id'=>'',
				'redirect'=> 1,

			    );

if(isset($_COOKIE['posttitle'])){
    $questionTextValue = $_COOKIE['posttitle'];
}else{
    $questionTextValue = $_REQUEST['questionText'];
}
if(isset($_COOKIE['postdescription'])){
    $questionDescriptionValue = $_COOKIE['postdescription'];
}else{
    $questionDescriptionValue = 'To help the experts give a more relevant answer to your question, you may add details like Academic Percentage, Work-experience, Entrance test scores etc.';
}

if(isset($_COOKIE['questionPostFlag_qnaRehash'])){
    $questionPostFlag_qnaRehash = $_COOKIE['questionPostFlag_qnaRehash'];
}else{
    $questionPostFlag_qnaRehash = false;
}
//$questionTextValue = $_REQUEST['questionText'];

$postQuestionKey = 'ASK_ASKHOME_HEADER_POSTQUESTION';
?>

<script>
currentPageName = 'INTERMEDIATE PAGE';
</script>

    
    
    	<!--Copy HTML frome here-->
		<div id="ana-contents">
        	<div id="left-col" class="box-shadow">
		<?php if($userId>0){ ?>
		<form id="form_<?=$widget?>" novalidate="novalidate" method ="post"  action = ""  onsubmit="try{ var questionTextValue = document.getElementById('questionText').value;
		fbcookieType='question'; if(validateQuestionForm_old(this,'<?php echo $postQuestionKey; ?>','formsubmit','form_<?=$widget?>')) showCatCounOverlay(questionTextValue,'question'); return false;}catch (e){ return false;}">
		<?php }else{ ?>
		<form id="form_<?=$widget?>" onsubmit="processAskQuestion('<?=$widget?>'); return false;" novalidate>
		<?php } ?>
		
            	<div class="ana-child-cont">
                	<div class="ask-section">
				<h2>Your Question</h2>
				<h5>Question Title</h5>
				<!--<textarea rows="3" cols="6" class="universal-select">which institute offers best course in mba?is their any mba institute which offers executive mba?</textarea>-->
				<div style="position: relative; top: 0pt;" >
						<textarea rows="3" cols="6" class="universal-select" name="questionText" id="questionText" autocomplete="off" blurMethod="validateForTips('questionText'); getDataFromGoogleAjax('<?php echo htmlspecialchars(json_encode($questionTextValue));?>',document.getElementById('questionText').value); enterEnabled=false;" onfocus="try{ enterEnabled=true; }catch (e){}" onkeypress="if(event.keyCode == 13){ return false;} else { textKey(this); }" onkeyup="if(event.keyCode == 13){ return false;} else { textKey(this); }" profanity="true" validate="validateStr" caption="Title" maxlength="140" minlength="2" required="true" validateSingleChar='true'><?php echo htmlspecialchars($questionTextValue); ?></textarea>
						<div style="width:269px;height:0px;display:none" id ="questionText_tips"></div>
				</div>
				<p class="char-count"><span id="questionText_counter">0</span> out of 140 characters</p>
				<div class="row errorPlace" style="padding-left: 5px;"><span id="questionText_error" class="errorMsg">&nbsp;</span></div>
		        </div>
            <script>textKey(document.getElementById('questionText'));</script>

			<div id="postQuesGoogleResult">
				<?php                
                			if($typeOfSearch == "QER") 
			                    $this->load->view('/common/qerRelatedQuestionOnPostQuestion');
                			else
			                    $this->load->view('/common/googleRelatedQuestionOnPostQuestion');
                		?>
			</div>                   
                </div>
                <div class="user-msg">
                <?php
                if((count($googleRes['title'])>0)){ 
                    echo '<div class="details"  id="descriptionHeading">Still want to ask a new question, add Description to your question</div>';
                }
                else{
                    echo '<div class="details"  id="descriptionHeading">Add Description to your question</div>';
                }
                ?>
                <div class="pointer">&nbsp;</div>
                </div>
                
                <div class="ana-child-cont" style="position:relative">
                	<a href="javascript: void(0);" class="font-11" onmouseover="$('sampleDescDiv').style.display = ''; attachOutMouseClickEventForPageAnA($('sampleDescDiv'),'closeSampleDescription()');">Sample description</a>
                    <!--Sample Description Div STARTS here-->
                    <div class="sample-desc" style="display: none;" id="sampleDescDiv">
                    	<div class="tar"><strong><a href="javascript: void(0);" onClick="closeSampleDescription(); return false;">Close X</a></strong></div>
                    	<p>To help experts give a more relevant answer to your question, you may add details like Academic percentages, work-experience, entrance test scores etc. Please fin below a few sample description texts</p>
                        
                        <ul>
                        	<li><strong>1.</strong> I'm a XII student of St Mary's Convent, Bangalore. I scored 85% in PCB in my class XI final exam and 86% in prelims. I want to get into an MBBS college and study in Pune. I'm going to appear for NEET-UG exam this year.</li>
                            <li><strong>2.</strong> I was a science student in school. I passed class X (ICSE) with overall 82%, and did my XII Sc (CBSE) scoring 74%. Now I've completed BBA from Lucknow University and taking CAT coaching for the past one year. I've taken one attempt at CAT last year and scored 89 percentile.  I'm aiming to appear for CAT again next year and get into IIM-A.</li>
                            <li><strong>3.</strong> I was a science student of Gujarat Board. I scored 87% in Class X and 76% in class XII (PCM).  I like Math and scored 90% and 82% in my class X and XII boards. I realized that I want to become a Chartered Accountant but I don't have any background in Accounting. So, now I've changed my stream and joined B.com in Mumbai University and I'm taking coaching for CA.</li>
                        </ul>
                    </div>
                    <!--Sample Description Div ENDS here-->
                    <div class="spacer8 clearFix"></div>

			<div id="catCounOverlay" style="display:none;"></div>
			
			<!-- Description Textarea Starts -->
			<textarea rows="3" cols="3" class="universal-select text-area2" style="color: #ADA6AD;" name="questionDescription" id="questionDescription" autocomplete="off" value="<?php echo $questionDescriptionValue; ?>" blurMethod="enterEnabled=false;checkTextElementOnTransition(this,'blur');" onfocus="try{ enterEnabled=true;checkTextElementOnTransition(this,'focus'); }catch (e){}" onkeyup="try{ textKey(this); } catch (e){}" profanity="true" validate="validateStr" caption="Description" maxlength="300" minlength="2"  default="<?php echo $questionDescriptionValue;?>" validateSingleChar='true'><?php echo $questionDescriptionValue; ?></textarea>

				<!-- Tool tip for Description textarea Starts -->
				<div style="position:absolute;top:10px;right:-272px;" id ="D_tips">
					<div style="width:269px;overflow:hidden">
						<div class="tooltipAnAm">
							<div class="tooltipAnAt" >
								<div class="tar" style="padding:7px 14px 0 0" onclick="closeTipsTool('D')"><img src="/public/images/cBtn.gif" class="pointer"/></div>
								<div class="tooltip-cont">
									<p>To help the experts give a more relevant answer to your question, you may add details like:</p>
									<ul>
									    <li><p>Academic Percentage</p></li>
									    <li><p>Work-experience</p></li>
									    <li><p>Entrance test scores etc.</p></li>
									</ul>
								</div>
							</div>
							<img src="/public/images/tooltipAnAb.gif" />
						</div>
					</div>                                                                                                                          
				</div>
				<!-- Tool tip for Description textarea Ends -->		
			
			<p class="char-count"><span id="questionDescription_counter">0</span> out of 300 characters</p>
			<div class="row errorPlace" style="padding-left: 5px;"><span id="questionDescription_error" class="errorMsg">&nbsp;</span></div>
			<!-- Description Textarea Ends -->

                        <div class="clearFix spacer15"></div>

			<!-- Register Section Starts -->
                        <?php if($userId<=0){ ?>
							<div id="inlineANA">

							</div>
                        <?php } ?>
			<!-- Register Section Ends -->
						<?php if($userId > 0){ ?>
                        <button class="orange-button" type="submit" onClick="trackEventByGA('ASK_NOW_BUTTON','SHIKSHA_INTERMEDIATE_PAGE'); processAskQuestion('<?=$widget?>'); return false;"  id="submit_<?=$widget?>" disabled style="background-color: #d2d2d2;cursor:default;">Post question now <span class="btn-arrow"></span></button>
            			<input type="hidden" name="tracking_keyid" id="tracking_keyid" value='<?=$trackingPageKeyId?>'>
            			 <?php } ?>          
            		<div class="clearFix"></div>        
                </div>
            </div>
            
            <div id="right-col" class="box-shadow">
            	<div class="best-ans-tips">
                	<h4>Do's & Don'ts of posting a question</h4>
                	<ul>
                    	<li><p>Before asking a new question, do a Search. The same question might have been answered earlier. </p></li>
                        <li><p>Be specific about the question. Share your relevant academic details.</p></li>
                        <li><p>Keep the title of the question short and understandable.</p></li>
                        <li><p>Do <strong>NOT</strong> use terms like "dear sir","good morning" etc.</p></li>
                        <li><p>Do <strong>NOT</strong> use informal language like "wanna","gonna" etc.</p></li>
                        <li><p>Do <strong>NOT</strong> use SMS lingo. Write complete words.</p></li>
                    </ul>
                </div>
            </div>
	</form>	            
            <div class="clearFix"></div>
        </div>    
        <!--Copy HTML upto here-->
        

<?php $this->load->view('/common/footer');?>
<input id="questionPostFlag_qnaRehash" type="hidden" value = "<?php echo $questionPostFlag_qnaRehash?>"/>

<script>
addOnBlurValidate($('form_<?=$widget?>'));
validateForTips('questionText');
if((document.getElementById('questionPostFlag_qnaRehash').value)&&(isUserLoggedIn)){
		var entityType = "<?php echo $entity;?>";
		if(entityType == "question"){
				deleteCookie('questionPostFlag_qnaRehash');
				var questionTextValue = $('questionText').value;
				if(validateQuestionForm_old($('form_<?=$widget?>'),'ASK_ASKHOME_HEADER_POSTQUESTION','formsubmit','form_<?=$widget?>')){
						showCatCounOverlay(questionTextValue,'question');
				}
		}
}

function closeSampleDescription(){
		$('sampleDescDiv').style.display = 'none';	
}
</script>
<script>
var tab_required_course_page = '<?php echo $tab_required_course_page;?>';
var subcat_id_course_page = '<?php echo $subcat_id_course_page?>';
var cat_id_course_page = '<?php echo $cat_id_course_page?>';
</script>

<script>
$j( document ).ready(function() {
	$j('#submit_<?=$widget?>').prop('disabled', false);
	$j('#submit_<?=$widget?>').css({'cursor':'pointer','background-color':'#f78640'});

});
</script>

<?php if($userId <= 0){ ?>
<script>

$j( document ).ready(function() {
	var context = "inlineANA";
	var trackingPageKeyId='<?php echo $trackingPageKeyId?>';
	$j.ajax({
		type:'POST',
		url:'/registration/Forms/loadANALeadForm',
		data:{'context': context,'trackingPageKeyId':trackingPageKeyId},
		success:function(response){
			$j('#inlineANA').html(response);
		}
	});

});
</script>
<?php }else{ ?>

<?php } ?>