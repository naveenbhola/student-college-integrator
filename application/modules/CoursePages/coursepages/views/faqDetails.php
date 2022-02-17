<?php

$section_id_name_array = array();
$first_section_key = '';
foreach ($section_wise_details as $key=>$value) {
	foreach ($value as $value1) {
		if(empty($first_section_key)) $first_section_key = $key;
		$section_id_name_array[$key] = $value1['groupHeading']; 
	}
}

$edit_text = "Edit";
if(count($section_wise_details) == 0) {
	$edit_text = "Add";
	$first_section_key = 0;
}

$session_id = sessionId();
$user_id = ($validateuser !='false')?$validateuser[0]['userid']:'';
$this->load->view('coursepages/coursePagesHeader',array('tab' => 'home'));
echo jsb9recordServerTime('SHIKSHA_COURSEPAGES_FAQ_PERMA_PAGE',1); ?>
<script>isCPGSAutoScrollEnabled = 0;</script>
<div class="faq-title clear-width">
                	<h1 class="flLt font-weight-normal"><?=$COURSE_HOME_PAGES_LIST[$courseHomePageId]['Name'];?> - Frequently Asked Questions</h1>
                	<?php if(count($section_wise_details)>0):?>
                    <a href="<?php echo $taburl;?>" class="flRt all-faq">View all FAQ questions &raquo;</a>
                    <?php endif;?>
                </div>
                <div class="clearFix"></div>
            	<!--Course Left Col Starts here-->
            	<?php if(count($section_id_name_array)>0):?>            	
            	<div id="faq-left">            	
                	<ul>                	
                	   <?php $index=0; foreach ($section_id_name_array as $key=>$value):?>                    	
                    	<li><a section_id ="<?php echo $key;?>" id="mp_section_anchr_<?php echo $index;?>" href="<?php echo $taburl."?index=$index"?>" <?php if($key == $section_id):?>class="active"<?php endif;?>><?php echo $value?></a></li>                        
                        <?php $index++;endforeach;;?>
					</ul>
                </div>   
                <?php endif;?>          
                <!--Course Left Col Ends here-->
                
                <!--Course Right Col Starts here-->
                <div id="faq-right" <?php  if(count($section_wise_details) == 0):?> style="border-left:none;"<?php endif;?>>
                	<h2 class="faq-sub-title"><span id='faq_mp_heading'><?php echo $section_id_name_array[$section_id];?></span><?php if($validateuser!='false' && $validateuser[0]['userid']==11){//render link only if valid cms user logged in. ?>
			<a id ="faq_master_edit_layer" href="javascript:void(0);" onClick="openCrudFaqContainer('-coursepages-faqNewQuestion', 'faq-containerID','openCrudFaqContainer','Edit FAQs','<?php echo $first_section_key;?>');" class="font-16"><i class="edit-icon"></i><?php echo $edit_text;?></a>
			<?php }	?>
			</h2>  
			        <?php 			 
			            			        		       
			        $link_details = $section_wise_details[$section_id][$question_id];				     
			        if(count($link_details)>0):			        
			        ?>			    
                    <ul class="faq-qna" id="accordion_cpgs">                  
                    	<li>
                        	<dt onclick="cpgsTrackQuestionClick(this,'<?php echo $link_details['question_id'];?>');">
                        		<i class="qa-icon">Q</i>
                                <p class="qna-details">
                                <a href="javascript:void(0);">
                                <?php     
                                   		$questionText = new tidy();
										$questionText->parseString(html_entity_decode(trim($link_details['questionText'])),array('show-body-only'=>true),'utf8');
										$questionText->cleanRepair();
                                		echo $questionText;                                	                                	
                                ?>
                               </a>
                               </p>
                            </dt>
                            <dd>
                            	<i class="qa-icon">A</i>
                                <div class="qna-details">
                                	<p>
                                	<?php 
	                                		$answerText = new tidy();
											$answerText->parseString(/*utf8_encode(*/preg_replace('/\s\s+/', ' ', html_entity_decode(trim($link_details['answerText'])))/*)*/,array('show-body-only'=>true),'utf8');
											$answerText->cleanRepair();
	                                		echo $answerText;
                                	?>
                                	</p>
                                   <div class="clearFix"></div>
                                   <div id="permalink_<?php echo $link_details['question_id'];?>">
                                   <?php $this->load->view('coursepages/permalink',array('question_id'=>$link_details['question_id'],'session_id'=>$session_id,'user_id'=>$user_id,'permaUrl'=>$link_details['questionUrl']));?>
                                   </div>                                 
                                </div>
                            </dd>
                        </li> 
                    </ul>                   
                    <?php else:?>
                    <div>Question details not found.</div>
                    <?php endif;?>
                    
                    <?php if(count($related_questions[$courseHomePageId])>0):?>
                     <div class="faq-related-ques-box clear-width">
                        <h2 class="faq-sub-title">Related Questions</h2>
                        <ul>
                            <?php foreach ($related_questions[$courseHomePageId] as $question_details):?>
                            <li><a style="word-wrap:break-word;" href="<?php echo $question_details['questionUrl'];?>"><?php echo $question_details['questionText'];?></a></li>                           
                            <?php endforeach;?>
                        </ul>
                        </div>
                      <?php endif;?>
                                                
                    <div class="clearFix"></div>
                </div>
		<div class="clearFix spacer20"></div>
		<div class="clearFix spacer20"></div>
                <!--Course Right Col Ends here-->
<?php

$this->load->view('coursepages/coursePagesFooter');
if(is_array($validateuser) && $validateuser[0]['userid'] == 11) {	?>
	<script language = "javascript" src = "/public/js/tinymce3/jscripts/tiny_mce/tiny_mce.js"></script>
<?php	
}
?>
<script>
$j(document).ready(function($j) {  
	trackCpgsQuestionDetailsPageView('<?php echo $question_id;?>');
});
</script>
