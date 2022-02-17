<form id="report_abuse_form" action=""  accept-charset="utf-8" method="post"  novalidate="novalidate" name="report_abuse_form">
<div id="rabgLayer" class="tags-layer"></div>
 <div id="abuse-layer" class="posting-layer" style="width:680px;">
          <div class="tags-head">Report Abuse <a id="cls-add-tags" class="cls-head" href="javascript:void(0);" onclick="closeReportAbuseLayer()"></a></div>
           <div class="tag-body">
            <p class="new-line">Kindly select reason(s) from below & cast your vote for removal of this content</p>
               <ul class="report-ul">
                  <li class="">
                     <div class="report-col">
                         <input type="checkbox" name="report" id="1" class="reportCheckbox">
                         <label for="1">Marketing / Spam -Unrelated commercial information (or spam)</label>
                      </div>
                  </li>
                   <li class="">
                     <div class="report-col">
                         <input type="checkbox" name="report" id="2" class="reportCheckbox">
                         <label for="2">Abusive / Inappropriate Obscene-illegal, vulgar or objectionable language </label>
                      </div>
                  </li>
                   <li class="">
                     <div class="report-col">
                         <input type="checkbox" name="report" id="3" class="reportCheckbox">
                         <label for="3">Irrelevant- Out of context or wrongly posted / categorized </label>
                     </div>
                  </li>
                  <li class="">
                     <div class="report-col">
                         <input type="checkbox" name="report" id="4" class="reportCheckbox">
                         <label for="4">Duplicate-Identical or nearly identical to content already posted</label>
                      </div>
                  </li>
                  <li class="">
                     <div class="report-col">
                         <input type="checkbox" name="report" id="5" class="reportCheckbox">
                         <label for="5">Copyright Violation-The content violates any stipulated law or regulation </label>
                          
                     </div>
                  </li>
                  <li class="">
                     <div class="report-col reportAbuseTextArea">
                         <input type="checkbox" name="report" id="6" class="reasonText reportCheckbox">
                         <label for="6">Other</label>
                          <div class="write-col">
                            <textarea class="write-txt" name="write-cmnt" placeholder="Enter reason here..." id="reportAbuseReasonText" required="true" minlength="5" maxlength="100" caption="Report Abuse Reason"  onkeyup="autoGrowField(this);textKey(this);" onpaste="handlePastedTextInTextField('reportAbuseReasonText',false);" onkeypress="handleCharacterInTextField(event,false);" validate="validateStr"></textarea>  
                            <p class="count-numbr" style=""><span id="reportAbuseReasonText_counter">0</span>/100</p> 
                            <div style="display:none;"><p class="err0r-msg" id="reportAbuseReasonText_error">Reason must contain at least 20 characters</p></div>
                          </div>
                     </div>
                  </li>
               </ul>
               <div class="btns-col">
                                            <span class="right-box">
                                               
                                                <a id="reportAbuseDone" class="ana-btns d-btn" href="javascript:void(0)" style="cursor:default">Submit</a>
                                            </span>
                                            <p class="clr"></p>
                                        </div>
            </div>
</div> 

       <input type="hidden" name ="msgIdReportAbuse" id="msgIdReportAbuse" value="<?php echo $msgId;?>"/>
       <input type="hidden" name="threadIdReportAbuse" id="threadIdReportAbuse" value="<?php echo $threadId;?>"/>
       <input type="hidden" name="entityTypeReportAbuse" id="entityTypeReportAbuse" value="<?php echo $entityType;?>"/>
       <input type="hidden" name="tracking_keyid" id="tracking_keyid_reportAbuse" value="<?php echo $trackingPageKeyId;?>">
</form>
<script>
$j(document).ready(function()
  {
      initializeReportAbuseLayerHandlers();
      closeOverflowTabOnPage('abuse-layer','qdp-ul');
  });
</script>