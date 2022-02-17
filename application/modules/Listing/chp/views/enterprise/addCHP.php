<div class="abroad-cms-rt-box">
     <div class="flRt">
         <a href="/chp/ChpCMS/viewList" class="orange-btn addMainExamToggle addMainExamLink" style="padding:6px 7px 8px">View All CHP</a>
	 <a href="/chp/ChpCMS/manageSeoCHP" class="orange-btn addMainExamToggle addMainExamLink" style="padding:6px 7px 8px"> Manage SEO </a>	     </div>
   </div>

<div class="clear-width" id="managechps">
            <div class="cms-form-wrap">
               <ul>
                 <li>
                   <label>CHP Type<span class="errorMsg">*</span> : </label>
                   <div class="cms-fields">
                     <select id="type" class="universal-select cms-field" name="type">
		                    <option value="">Select CHP Type</option>
                        <option value="single">Single</option>
                        <option value="combination">Combination</option>
                     </select>
                     <div id="type_error" style="display: none;" class="errorMsg erh">Please select CHP Type.</div>
                   </div>
		   <div style="margin: 15px 0px"> 	
		   <div class='p15'>
			 <label>Select a Stream<span class="errorMsg">*</span> : </label>
                   <div class="cms-fields">
                     <select id="chp_stream" class="universal-select cms-field select" name="chp_stream">
                       <option value="">Select</option>
                     </select>
		     <span class="ml15" >
            <span class="sideview"><input id="x_stream" type="radio" class="shide" name="x_options" value="stream"></span>
            <span class="sideview enable"><input  id="y_stream" type="radio" name="y_options" value="stream"></span>
          </span>
                      <div id="stream_error" style="display: none;" class="errorMsg erh">Please select Stream.</div>
                   </div>
		   </div>
		   <div class='p15'>
                         <label>Select a Sub-Stream : </label>
                   <div class="cms-fields">
                     <select id="chp_subStream" class="universal-select cms-field select" name="chp_subStream">
                       <option value="">Select</option>
                     </select>
		    <span class="ml15"><span class="sideview"><input id="x_subStream" type="radio" class="shide" name="x_options" value="subStream"></span><span class="sideview enable"><input id="y_subStream" type="radio" name="y_options" value="subStream"></span></span>
        <div id="subStream_error" style="display: none;" class="errorMsg erh">Please select Sub-Stream.</div>
                   </div>
                   </div>
		   <div class='p15'>
                         <label>Select a Specialization : </label>
                   <div class="cms-fields">
                     <select id="chp_spec" class="universal-select cms-field select" name="chp_spec">
                       <option value="">Select</option>
                     </select>
		     <span class="ml15"><span class="sideview"><input id="x_spec" type="radio" name="x_options" value="spec"></span><span class="sideview enable"><input type="radio" id="y_spec" name="y_options" value="spec"></span></span>
         <div id="spec_error" style="display: none;" class="errorMsg erh">Please select Specialization.</div>
                   </div>
                   </div>
         <div class='p15'>
                         <label>Select a Base Course : </label>
                   <div class="cms-fields">
                     <select id="chp_baseCourse" class="universal-select cms-field select" name="chp_baseCourse">
                       <option value="">Select</option>
                     </select>
         <span class="ml15"><span class="sideview"><input id="x_baseCourse" type="radio" name="x_options" value="baseCourse"></span><span class="sideview enable"><input id="y_baseCourse" type="radio" name="y_options" class="shide" value="baseCourse"></span></span>
         <div id="baseCourse_error" style="display: none;" class="errorMsg erh">Please select Base Course.</div>
                   </div>
                   </div>

		</div>
<?php if($chpType == 'single'){?>
     <div class='p15'>
                         <label>Select a Education Type : </label>
                   <div class="cms-fields">
                     <select id="chp_eduType" class="universal-select cms-field select" name="chp_eduType">
                            <option value="">Select</option>
                     </select>
                   </div>
                   </div>

         <div class='p15'>
                         <label>Select a Delivery Method : </label>
                   <div class="cms-fields">
                     <select id="chp_mode" class="universal-select cms-field select" name="chp_mode">
                            <option value="">Select</option>
                     </select>
                   </div>
                   </div>

		    <div class='p15'>
                         <label>Select a Credential : </label>
                   <div class="cms-fields">
                     <select id="chp_credential" class="universal-select cms-field select" name="chp_credential">
                       <option value="">Select</option>
                     </select>
                   </div>
                   </div>
                 <?php }?>  

		   <div class="p15">
			   <label>CHP Name : </label>
			   <div class="cms-fields">
			  <div>
			    <span id="chp_name_text" style="line-height: 29px;width: 40%;display: inline-block;">NA</span>
          <input style="width: 290px;" type="hidden" name="chp_name" id="chp_name">
			  </div>
			   </div>
		</div>

		<div class="p15">
        <label>CHP Display Name : </label>
           <div class="cms-fields">
                <div id="edit_main">
                  <span style="width: 300px;"><input style="width: 290px;" type="hidden" name="chp_displayName" id="chp_displayName"></span>
                  <span id="edit_displayName" style="display: inline-block;line-height: 27px;width: 34%;margin-right: 10px;">NA</span><a style="display: none;" id="edit_displayNameBtn" href="javascript:void(0);">Edit</a>
                  <span style="margin-left: 80px;"> <strong>Allowed Characters :</strong>  [0-9a-zA-Z\s/()&amp;.,#+] </span>
                </div> 
           </div><br>

		    <div class="flLt">
		         <a id="savePub" href="javascript:void(0);" class="orange-btn addMainExamToggle addMainExamLink" style="padding:6px 7px 8px" onclick="saveChp()">Save & Publish</a>
		         <a href="/chp/ChpCMS/addCHP" onclick="window.location.reload();" class="orange-btn addMainExamToggle addMainExamLink" style="padding:6px 7px 8px"> Reset </a>
             
            <?php if($chpType == 'single'){?>
             <span id="options_error" style="display: none;margin-left: 60px;" class="errorMsg erh">Please select an Option.</span>
           <?php }else{?>
            <span id="options_error" style="display: none;margin-left: 60px;" class="errorMsg erh">Please select an Options ( X and Y ).</span>
           <?php }?>
             <span id="chpdn_error" style="display: none;margin-left: 60px;" class="errorMsg erh">Please enter a valid CHP display name / Special characters not allowed.</span>
             <span id="save_error" style="display: none;margin-left: 60px;" class="errorMsg erh"></span>
		     </div>
                </div>
                 </li>
            </ul>
       </div>
</div>
