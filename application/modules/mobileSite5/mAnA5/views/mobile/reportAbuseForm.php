
<form id="report_abuse_form" action=""  accept-charset="utf-8" method="post"  novalidate="novalidate" name="report_abuse_form">
  <div class="qstn-container">
      <!--comment secion heading-->
       <div class="qstn-col">
          <div class="q-box"><p class="q-titl">REPORT ABUSE</p></div>
          <div class="q-cls"><a href="javascript:void(0)" data-enhance="false" data-rel="back" onclick="hideOverFlowTab()" id="reportAbuseLayerClose">&times;</a></div>
       </div>
      <!--comment section card view--> 
       <div class="qstn-block abuseScroll">
           <div class="report-list">
               <ul class="report-ul">
                  <li class="">
                     <div class="report-col">
                         <input type="checkbox" name="report" id="1" class="reportCheckbox">
                         <label for="1">Marketing / Spam 
                          <p class="new-line">Unrelated commercial information (or spam)</p>
                         </label>
                          
                     </div>
                  </li>
                   <li class="">
                     <div class="report-col">
                         <input type="checkbox" name="report" id="2" class="reportCheckbox">
                         <label for="2">Abusive / Inappropriate
                          <p class="new-line">Obscene, illegal, vulgar or objectionable language</p>
                         </label>
                          
                     </div>
                  </li>
                   <li class="">
                     <div class="report-col">
                         <input type="checkbox" name="report" id="3" class="reportCheckbox">
                         <label for="3">Irrelevant
                          <p class="new-line">Out of context or wrongly posted / categorized</p>
                         </label>
                          
                     </div>
                  </li>
                  <li class="">
                     <div class="report-col">
                         <input type="checkbox" name="report" id="4" class="reportCheckbox">
                         <label for="4">Duplicate
                            <p class="new-line">Identical or nearly identical to content already posted</p>
                         </label>
                          
                     </div>
                  </li>
                  <li class="">
                     <div class="report-col">
                         <input type="checkbox" name="report" id="5" class="reportCheckbox">
                         <label for="5">Copyright Violation
                          <p class="new-line">The content violates any stipulated law or regulation</p>
                         </label>
                          
                     </div>
                  </li>
                  <li class="">
                     <div class="report-col reportAbuseTextArea">
                         <input type="checkbox" name="report" id="6" class="reasonText reportCheckbox" >
                         <label for="6">Other</label>
                          <div class="write-col">
                            <textarea class="write-txt" name="write-cmnt" placeholder="Enter reason here" id="reportAbuseReasonText" validate="validateStr" required="true" minlength="5" maxlength="100" caption="Report Abuse Reason" style="display:none" onkeyup="autoGrowField(this);textKey(this);"  onpaste="handlePastedTextInTextField('reportAbuseReasonText')"></textarea>  
                            <p class="count-numbr" style="display:none"><span id ="reportAbuseReasonText_counter">0</span>/100</p> 
                          </div>
                          <div style="display:none;"><p class="error-msg" id="reportAbuseReasonText_error">Reason must contain at least 20 characters</p></div>
                          <div style="display:block" class="errorPlace"><div id="checkboxreportAbuse_error" class="errorMsg"></div></div>
                     </div>
                  </li>
               </ul>
           </div>
       </div>
       <input type="hidden" name ="msgIdReportAbuse" id="msgIdReportAbuse" value="<?php echo $msgId;?>"/>
       <input type="hidden" name="threadIdReportAbuse" id="threadIdReportAbuse" value="<?php echo $threadId;?>"/>
       <input type="hidden" name="entityTypeReportAbuse" id="entityTypeReportAbuse" value="<?php echo $entityType;?>"/>
       <input type="hidden" name="tracking_keyid" id="tracking_keyid_reportAbuse" value="<?php echo $trackingPageKeyId;?>">
       <div class="bottom-wrapper">
          <a href="javascript:void(0);" class="p-btn u-btns" id="reportAbuseDone" style="display:none">done</a> 
       </div>   
  </div>
  </form>
<script>

//keyUpHandlerPostAnA('reportAbuseReasonText','textLengthShow',100,'questionPost_desc');

$(document).ready(function()
{
  initializeReportAbuseLayerHandlers();
  /*var allowedCharsArray = new Array(222,186,49,50,61,219,221,51,42,53,54,55,59,56,57,48,0,220,59,190,191,192,13,188);
  var regEx = "a-zA-Z0-9?=.*!@# $%^&_*+>:;?,\"\'{}()|\/\\\[\\\]-";
  initializeReportAbuseLayerHandlers();
  allowedCharsInTextField('reportAbuseReasonText',regEx,'textLengthShow',100,allowedCharsArray);
  allowedCharactersInTextField('reportAbuseReasonText',regEx,'textLengthShow',100);*/
  //handleCharacterInTextField('reportAbuseReasonText');
});

</script>
