<div class="abroad-cms-rt-box">
     <div class="flRt">
	 <a href="/chp/ChpCMS/addCHP" class="orange-btn addMainExamToggle addMainExamLink" style="padding:6px 7px 8px"> + Add CHP </a>
         <a href="/chp/ChpCMS/viewList" class="orange-btn addMainExamToggle addMainExamLink" style="padding:6px 7px 8px">View All CHP</a>
     </div>
   </div>

<div style="display: block;margin:20px 0px 10px;width: 100%;position: relative;" class="_container_">

  

<div>
<div style="margin-bottom: 20px;position: relative;display: inline-block;width: 50%;" class="_container_">

    <span style="width: 108px;display: inline-block;">CHP Display Name:</span>    
     <div style="display: inline-block;position: relative;margin-left: 29px;">
       <input type="text" name="chpField" id="chpField" class="input-txt" placeholder="Search a CHP" minlength="1" maxlength="50" autocomplete="off" style="width:100%"> 
  
       <ul id="chpSearchOptions" class="chpSearchOptions" style="width:112%"></ul>

       <div id="chpField_error" style="display: none;margin-left: 10px" class="errorMsg erh">Please select a valid CHP display name.</div>
    </div>
    <span id="chp-loader"style="margin-left: 35px;display: none;">Loading...</span>
    
    </div>
    <!-- <a style="font-size: 15px;margin-left: 40px;" href="javascript:void(0);" id="resetFltr">Cancel</a> -->
 </div>

<div>

<div style="margin-bottom: 20px;position: relative;display: inline-block;width: 45%;" class="_container_">
    <span style="display: inline-block;width: 100px;vertical-align: middle;">Meta Title:</span>
      <div style="display: inline-block;position: relative;margin-left: 18px;vertical-align: middle;">
         <textarea maxlength="200" id="chpTitle" rows="3" class="input-txt seoFld" style="width: 100%;vertical-align: bottom;margin-left: 10px;"></textarea>
         <div id="chpTitle_error" style="display: none;margin-left: 10px" class="errorMsg erh">Please enter title.</div>
       </div>
    </div>
</div>

<div>
  <div style="margin-bottom: 20px;position: relative;display: inline-block;width: 45%;" class="_container_">
    <span style="display: inline-block;vertical-align: middle;width: 100px;">Meta Description:</span>
    <div style="display: inline-block;position: relative;margin-left: 18px;vertical-align: middle;">
         <textarea maxlength="450" id="chpDesc" rows="3" class="input-txt seoFld" style="width: 100%;vertical-align: bottom;margin-left: 10px;"></textarea>
         <div id="chpDesc_error" style="display: none;margin-left: 10px" class="errorMsg erh">Please enter description.</div>
     </div>
    
 </div>
</div>


   <div>
       <div style="margin-bottom: 20px;position: relative;display: inline-block;" class="_container_">
       <span style="display: inline-block;vertical-align: middle;width: 100px;">URL (Read Only)</span>
       <div style="display: inline-block;position: relative; margin-left: 18px;vertical-align: middle;">
          <input id="chpUrl" disabled="disabled" type="text" class="input-txt seoFld" style="width: 400px;vertical-align: bottom;margin-left: 10px;">
        </div>
    
    </div>
    <input type="hidden" id="chpId" class="seoFld">
  </div>
 
<div class="flLt">
         <a id="saveSeo" href="javascript:void(0);" onclick="saveSeoDetails()" class="orange-btn addMainExamToggle addMainExamLink" style="padding:6px 7px 8px"> Save </a>
         <a href="/chp/ChpCMS/manageSeoCHP" class="orange-btn addMainExamToggle addMainExamLink" style="padding:6px 7px 8px">Reset</a>
         <span id="cmn_error" style="display: none;margin-left: 10px;color: green;font-size: 12px;"></span>
     </div>
 
</div>
