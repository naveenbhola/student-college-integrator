<div style="height:45px;background-image:url(/public/images/enterpriceBg.gif); background-repeat:repeat-x;padding:0 10px;">
Filter by: 
           <select name="countryFilter">
                <option value="1">User</option>
                <option value="2">College</option>
           </select>
User creation date between
				<input style="width:75px;" type="text" required="true" name="StartDateFilter" id="StartDateFilter" value="Start Date" readonly maxlength="10" size="15" onClick="cal.select($('DOB'),'sd','yyyy-MM-dd');"/>
                <img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="sd" onClick="cal.select($('StartDateFilter'),'sd','yyyy-MM-dd');" />
				<input style="width:75px;" type="text" required="true" name="EndDateFilter" id="EndDateFilter" value="End Date" readonly maxlength="10" size="15" onClick="cal.select($('DOB'),'sd','yyyy-MM-dd');"/>
                <img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="sd" onClick="cal.select($('EndDateFilter'),'sd','yyyy-MM-dd');" />
	   <button class="btn-submit7 w6" name="filterMedia" id="filterMedia" value="Filter_MediaData_CMS" type="button" onClick="getPhotoMediaData($('StartDateFilter').value,$('EndDateFilter').value);" style="margin-left:10px;width:50px">
              <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Go</p></div>
	   </button>
</div>
</div>
                            <div class="lineSpace_10">&nbsp;</div>
                                                        <div class="row normaltxt_11p_blk">	
                                    <input type="hidden" id="methodName" value="getPopularNetworkCMSajax"/>             
<div id="paginataionPlace1" style="display:none;"></div>
								<div class="boxcontent_lgraynoBG bld">
									<div class="float_L" style="background-color:#D1D1D3; width:15%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Photo</div>
									<div class="float_L" style="background-color:#EFEFEF; width:20%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Displayname</div>
									<div class="float_L" style="background-color:#EFEFEF; width:25%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Email</div>
									<div class="float_L" style="background-color:#EFEFEF; width:25%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Creation Date</div>
									<div class="float_L" style="background-color:#EFEFEF; width:13%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Delete</div>
									<div class="clear_L"></div>
								</div>
							</div>
							<div id="cmsNetworkTable" name="cmsNetworkTable" class="row normaltxt_11p_blk boxcontent_lgraynoBG" style="height:290px; overflow:auto">
                                                        </div>
						<!-- code for pagination start -->
<?php		$this->load->view('common/calendardiv');?>
                                <div class="pagingID" id="paginataionPlace2"></div>
							<div class="lineSpace_5">&nbsp;</div>
        <div class="buttr3">
			<button class="btn-submit7 w9" type="button" onClick="deletephotomedia('image')">
				<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Delete Images</p></div>
			</button>
		</div>
        <div class="buttr3">
	<!--		<button class="btn-submit7 w9" type="button" onClick="deletephotomedia('user')">-->
			<button class="btn-submit7 w9" type="button">
				<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Delete User</p></div>
			</button>
		</div>
							<div class="lineSpace_20">&nbsp;</div>
						<!-- code for pagination ends -->
                        <input type = "hidden" name = "pageresultcount" id = "pageresultcount" value = ''/>
<script>
var cal = new CalendarPopup("calendardiv");
cal.setYearSelectStartOffset(35);
getPhotoMediaData();
</script>
		
