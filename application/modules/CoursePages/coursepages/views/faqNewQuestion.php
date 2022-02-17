<div class="faq-container" id="faq-containerID">
      <div class="faq-tab" >
     	<ul>
        	<li id='tab-li-new-ques' onclick='showNewQuesTabContent();'  class="active"><a href="javascript:void(0);">New Questions</a></li>
            <li id='tab-li-added-ques' onclick='showAddedQuesTabContent();'><a href="javascript:void(0);">Already Added Questions</a></li>
        </ul>
        <!--<script></script>-->
       </div>
      <div class="clearFix"></div>
      
      <form id='faqForm' action="/coursepages/CoursePageCms/saveNewFaqs/" method="post">
      <div class="questions-form" style="display:none;">
        <div class='heading-block'>
            <ul class='faq-headings'>
                <li>
            	<label class='heading-label'>Add heading 1:</label>
                	<div class="que-details">
                            <input type="text" maxlength="255" name='groupHeading[]'  onblur='checkExtraText(this,255);' onkeypress='checkMaxLength(this,255);preventSubmitOnEnter(event);' class="universal-txt-field faq-group-heading-input faq-form-input text-width" value=""/>&nbsp;&nbsp;
                            <span id='headingPositionSpan' class='headingPositionSpan' style='display: none;'>
                                <span style='font-size: 16px;'>Sequence: </span>
                                <select style='width:100px;' class="universal-select select-width faq-heading-sequence-select" onchange="displayWarning();" name='faq_heading_sequence'>
                                </select>
                                <input type="hidden" id='faq-heading-id' name='faqHeadingId' value='0'/>
                            </span>
                        </div>
                </li>
                <li class='faq-questions'>
                    <!--div id='faq-ques-ans-div' style="border:1px solid;"-->
                        <div class='faq-questions-div' style='margin-bottom:2px;'>
                            <label>Add question <br />& answer:</label>
                            <div class="que-details">
                            <div class="que-details-child">
                              <div class='sequenceSelectDiv' style='display:none;'>
                                  <span style='font-size: 16px;'>Sequence: </span>
                                  <select style='width:100px;' class="universal-select select-width faq-sequence-select" name='faq_sequence[0][]'>
                                  </select></br>
                              </div>
                              <input type='hidden' id='subCatId' name='subCatId' value=''>
                              <input type="hidden" id='faqId' name='faqId[0][]' value='0'/>
                              <p class="question-head">Question:</p>
                              <div class='faq-question'>
                                  <textarea validate="validateStr" maxlength="256" minlength="0"  onblur='checkExtraText(this,256);' onkeypress='checkMaxLength(this,256);' name="faq_ques[0][]" class='faq-ques-textarea faq-form-input' style="width:465px;height:60px"></textarea>
                              </div>
                              <p class="question-head">Answer:</p>
                              <div class='faq-answer' id='faq_answer'>    
                                  <textarea validate="validateStr" maxlength="10000" minlength="0" name="faq_ans[0][]" id="faq_ans_1" class='mceEditor faq-form-input faq-ans-textarea' style="width:465px;height:100px"></textarea>
                                  <div><div id="faq_ans_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
                              </div>
                              <a href="javascript:void(0);" class="add-link add-more-faqs-link" onClick='addMoreFaqs(0,this);'>+ Add More question & answer</a>
                            </div><!-- end of que-details-child-->
                            </div><!-- end of que-details-->
                        </div><!-- end of faq-questions-div -->
                    <!--/div-->
                </li>                
            </ul>
            <ul>
            <li><label><a href="javascript:void(0);" class="add-link add-more-headings-link font-16" onClick='addMoreFaqHeadings(this);'>+ Add new heading</a></label></li>
            </ul>
            <div class="clearFix"></div>
        </div><!-- end of heading block -->
      </div><!-- end of questions-form -->
      <div id='submit-div' style="margin-left:260px;display:none;">
	<input type="button" onclick="submitFaqs();" name='submit' value="Submit" class="orange-button" />&nbsp;&nbsp;
        <input type="button" onclick="cancelAddQuestion();" value="Cancel" class="orange-button" />
      </div>
      </form>
    
    <!-- already added ques tab -->
    <div class="added-faq-section">
        <table border="0" cellpadding="0" cellspacing="0" id='addedFaqTable'>
            <thead style='display:none;'>
            <tr>
                <!-- <th width="20%">Heading 6 <i class="heading-arrow"></i></th>-->
                <th width="20%">
                    <select id="faqHeadingSelect" onchange="getAddedFaqHtmlData(courseHomePageId,this.value);" style="width: 90%;"></select>
                </th>
                <th width="30%">Question</th>
                <th width="40%">Answer</th>
                <th width="10%" class="last-border">Edit</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    	
    </div>
