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
$uId = 2193486;
$this->load->view('coursepages/coursePagesHeader',array('tab' => 'home'));
echo jsb9recordServerTime('SHIKSHA_COURSEPAGES_FAQ_MASTER_PAGE',1); ?>
<script>isCPGSAutoScrollEnabled = 0;</script>
<div class="faq-title clear-width">
                	<h1 class="flLt font-weight-normal"><?=$COURSE_HOME_PAGES_LIST[$courseHomePageId]['Name'];?> - Frequently Asked Questions</h1>
                	<?php if(count($section_wise_details)>0):?>
                    <a href="javascript:void(0);" onclick="collapseAllAccordionElements();" class="flRt all-faq">Collapse all questions</a>
                    <?php endif;?>
                </div>
<input type="hidden" id="courseHomePageId" value="<?php echo $courseHomePageId ?>">
                <div class="clearFix"></div>
            	<!--Course Left Col Starts here-->
            	<?php if(count($section_id_name_array)>0):?>            	
            	<div id="faq-left">            	
                	<ul>                	
                	   <?php $index=0; foreach ($section_id_name_array as $key=>$value):?>
                    	<li><a section_id ="<?php echo $key;?>" id="mp_section_anchr_<?php echo $index;?>" href="javascript:void(0);" onclick="$j('#question_list_section_id'+<?php echo $index?>).show();setFaqMasterPageHeading('<?php echo $index;?>',this);" <?php if($index == 0):?>class="active"<?php endif;?>><?php echo $value?></a></li>                        
                        <?php $index++;endforeach;;?>
					</ul>
                </div>   
                <?php endif;?>          
                <!--Course Left Col Ends here-->
                
                <!--Course Right Col Starts here-->
                <div id="faq-right" <?php  if(count($section_wise_details) == 0):?> style="border-left:none;"<?php endif;?>>
                	<h2 class="faq-sub-title"><span id='faq_mp_heading'><?php echo reset($section_id_name_array);?></span><?php if($validateuser!='false' && $validateuser[0]['userid']==$uId){//render link only to thisn userid. ?>
			<a id ="faq_master_edit_layer" href="javascript:void(0);" onClick="openCrudFaqContainer('-coursepages-faqNewQuestion', 'faq-containerID','openCrudFaqContainer','Edit FAQs','<?php echo $first_section_key;?>','<?php echo $courseHomePageId;?>');" class="font-16"><i class="edit-icon"></i><?php echo $edit_text;?></a>
			<?php }	?>
			</h2>  
			        <?php $count=0; if(count($section_wise_details)>0):?>
			        <?php foreach ($section_wise_details as $section_details):?>
                    <ul class="faq-qna" id="accordion_cpgs<?php echo $count;?>" <?php if($selected_index !=$count):?> style='display:none;'<?php endif;?>>
                    <?php foreach ($section_details as $link_details):?>
                    	<li id="question_list_section_id<?php echo $count;?>">
                        	<dt onclick="cpgsTrackQuestionClick(this,'<?php echo $link_details['question_id'];?>');">
                        		<i class="qa-icon">Q</i>
                                <p class="qna-details">
                                <a onclick="location.hash=<?=$link_details['question_id'];?>" href="#<?=$link_details['question_id'];?>" rel="qid_<?=$link_details['question_id'];?>">
                                <?php     
                                   		$questionText = new tidy();
										$questionText->parseString(html_entity_decode(trim($link_details['questionText'])),array('show-body-only'=>true),'utf8');
										$questionText->cleanRepair();
                                		echo $questionText;                                	                                	
                                ?>
                               </a>
                               </p>
                            </dt>
                            <dd style='display:none;'>
                            	<i class="qa-icon">A</i>
                                <div class="qna-details">
                                	<p>
                                	<?php 
	                                		$answerText = new tidy();
											$answerText->parseString(/*utf8_encode(*/preg_replace('/\s\s+/', ' ', html_entity_decode(trim($link_details['answerText']))/*)*/),array('show-body-only'=>true),'utf8');
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
                        <?php endforeach;?>                                          
                    </ul>
                    <?php $count++;endforeach;?>
                    <?php else:?>
                    <div>No faq has been added for this category.</div>
                    <?php endif;?>
                    <div class="clearFix"></div>
                </div>
		<div class="clearFix spacer20"></div>
		<div class="clearFix spacer20"></div>
                <!--Course Right Col Ends here-->
<?php
$this->load->view('coursepages/coursePagesFooter');
if(is_array($validateuser) && $validateuser[0]['userid'] == $uId) {	?>
	<script language = "javascript" src = "/public/js/tinymce3/jscripts/tiny_mce/tiny_mce.js"></script>
<?php	
}
?>

<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>
    var courseHomePageId=$j('#courseHomePageId').val();
if($j('#accordion_cpgs0').length>0) {
   
	$j(document).ready(function($j) {
		$j("ul[id^=accordion_cpgs]").each(
				function(index,element) {
					var element = $j(element);	
					element.accordion({ 
						collapsible: true,
						active:false,
						heightStyle:'content',
                                                speed:"fast",
						 beforeActivate: function(event, ui) {
					         // The accordion believes a panel is being opened
					        if (ui.newHeader[0]) {
					            var currHeader  = ui.newHeader;
					            var currContent = currHeader.next('.ui-accordion-content');
					         // The accordion believes a panel is being closed
					        } else {
					            var currHeader  = ui.oldHeader;
					            var currContent = currHeader.next('.ui-accordion-content');
					        }
					         // Since we've changed the default behavior, this detects the actual status
					        var isPanelSelected = currHeader.attr('aria-selected') == 'true';

					         // Toggle the panel's header
					        currHeader.toggleClass('ui-corner-all',isPanelSelected).toggleClass('accordion-header-active ui-state-active ui-corner-top',!isPanelSelected).attr('aria-selected',((!isPanelSelected).toString()));

					        // Toggle the panel's icon
					        currHeader.children('.ui-icon').toggleClass('ui-icon-triangle-1-e',isPanelSelected).toggleClass('ui-icon-triangle-1-s',!isPanelSelected);

					         // Toggle the panel's content
					        currContent.toggleClass('accordion-content-active',!isPanelSelected)    
					        if (isPanelSelected) { currContent.slideUp(); }  else { currContent.slideDown(); }

					        return false; // Cancel the default action
					    } 
				});
			}
	    );	
	    

	} 
	);	
}
var stickyFunc=function(){
	    $j('#mp_section_anchr_'+<?php echo $selected_index?>).trigger('click');
	    var hashTag = window.location.hash;
		if(typeof hashTag != 'undefined' && hashTag != "") {
			var anchorElement = $j("div#faq-right a[href="+hashTag+"]");
			var leftSectionIdNo = anchorElement.closest('li').attr('id').split('_id')[1];
			$j('#mp_section_anchr_'+leftSectionIdNo).click();
			anchorElement.click();
			setTimeout(function() {
                var shikshaHeaderHeight=$j('.n-headerP').outerHeight()+41;
				anchorElement.closest('dt')[0].scrollIntoView({behavior:"smooth", block: "end"});
                var currentWindowHeight=window.scrollY;
                if(currentWindowHeight>0 && shikshaHeaderHeight>0){
                    window.scroll(0,currentWindowHeight-shikshaHeaderHeight);
                }
			}, 1000);
			
		}
}
var prevOnLoad = window.onload;
window.onload = function(){
	if(typeof prevOnLoad == 'function'){
		prevOnLoad();
	}
	stickyFunc();
}
</script>
