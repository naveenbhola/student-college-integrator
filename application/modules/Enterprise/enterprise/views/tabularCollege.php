<div style="height:45px;background-image:url(/public/images/enterpriceBg.gif); background-repeat:repeat-x;padding:3px 10px;">
   <div class="float_L">
      <input type="radio" name="selectedSearchType" id="selectedSearchType1" value="filter" onClick="showSearchBar(this);" checked="checked" >Filter<br />
      <input type="radio" name="selectedSearchType" id="selectedSearchType2" value="search" onClick="showSearchBar(this);" >Search
   </div>
   <script>$('selectedSearchType1').checked = true; </script>
   <div style="margin-left:80px;display:none;padding:5px 0;" id="searchBar">
      Keyword <input type="text" id="keyword" name="keyword" style="width:250px" onkeypress="return enterKeySearch(event);"/>
      <div style="display:none">&nbsp;Location <input type="text" id="location" name="location" /></div>
      <?php if($usergroup == "cms") { ?>
      <input type="checkbox" id="showSponsored">Show Sponsored
      <input type="checkbox" id="showFeatured">Show Featured
      <select id="searchSubType" style="width:100px;">
		<option value="">Select Category</option>
      <?php foreach ($searchCategories as $key=>$val) { ?>
		<option value="<?php echo $key;?>"><?php echo $val;?></option>
      <?php } ?>
      </select>
      <input type="hidden" id="entUserId" value="" />
      <?php } else { ?>
      <input type="hidden" id="entUserId" value="<?php echo $userid; ?>" />
      <?php } ?>
      <input type="hidden" id="searchType" value="<?php echo $prodId; ?>" />
      <input type="hidden" id="startlucene" />
		<input type="button" style="border:0 none;background:url(/public/images/entSearch.gif) no-repeat left top;width:71px;height:20px;color:#FFF;font-weight:bold" value="Search" onClick="$('paginataionPlace2').innerHTML='';$('startOffSet1').value='0';$('prod_detail').innerHTML= '<div style=\'width:100%\' align=\'center\'><img src=\'/public/images/space.gif\' width=\'115\' height=\'25\' /></div>';searchLuceneCMS();"  />
      <!--<button class="btn-submit7" type="button" onClick="$('paginataionPlace2').innerHTML='';$('startOffSet1').value='0';searchLuceneCMS();" style="width:70px">
	 <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Search</p></div>
      </button>-->
   </div>
   <div style="margin-left:80px;padding:5px 0;" id="filterBar">
      Filter by:
      <?php if($usergroup != "cms") { $modstyle= 'style="display:none;"'; }?>
      <select name="instituteType" id="instituteType" <?php echo $modstyle; ?>>
	 <option value="All">All</option>
	 <option value="Academic_Institute">Academic</option>
	 <option value="Test_Preparatory_Institute">Test Preparation</option>
      </select>
      <select name="moderation" id="moderation" <?php echo $modstyle; ?>>
	 <option value="">Select</option>
	 <option value="1">Moderated</option>
	 <option value="2">Unmoderated</option>
      </select>
      <select name="crawlStatus" id="crawlStatus" <?php echo $modstyle; ?>>
	 <option value="">Select</option>
	 <option value="1">Crawled</option>
	 <option value="2">Noncrawled</option>
      </select>
      <select name="liveStatus" id="liveStatus">
	 <option value="">Select</option>
         <option value="live" selected="selected">Live</option>
         <option value="draft">Draft</option>
         <option value="queued">Queued</option>
        <!-- <option value="blocked">Blocked</option>
         <option value="deleted">Deleted</option>
         <option value="expired">Expired</option>
         <option value="cancelled">Cancelled</option> -->
      </select>
      <input name="filterColleges" id="filterColleges" type="button" style="border:0 none;background:url(/public/images/entGoBtn.gif) no-repeat left top;width:36px;height:20px;color:#FFF;font-weight:bold" value="Go"  onClick="$('paginataionPlace3').innerHTML='';$('startOffSet').value='0'; $('prod_detail').innerHTML= '<div style=\'width:100%\' align=\'center\'><img src=\'/public/images/space.gif\' width=\'115\' height=\'25\' /></div>'; searchCollegesCMS();"  />
	  
      <!--<button class="btn-submit7" name="filterColleges" id="filterColleges" value="Filter_College_CMS" type="button" onClick="$('paginataionPlace3').innerHTML='';$('startOffSet').value='0';searchCollegesCMS();" style="width:50px">
	 <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Go</p></div>
      </button>-->
      <input type="checkbox" onclick="$('paginataionPlace3').innerHTML='';searchCollegesCMS();" id="abuseonly" autocomplete="off">Show Reported Abuse Colleges
      </div>
   <div class="clear_L"></div>
	   <div id="messageAfterAjax" style="background:#FFF1A8;line-height:18px;"></div>
                 </div>       
                 
			    <div class="lineSpace_5">&nbsp;</div>
			    <div class="mar_full_10p">
			       <div class="float_R" style="padding:5px">
				  <!-- code for pagination start -->
				  <div class="pagingID" id="paginataionPlace2"></div>
				  <div class="pagingID" id="paginataionPlace3"></div>
				  <!-- code for pagination ends -->
			       </div>
                               <div class="float_L">
                                  <?php if($extraParam=='choose'){?>
                                  <div class="bld" style="font-size:14px"> Please choose an Institute to add new Course </div>
                                  <?php }else{ ?>
				  <div class="normaltxt_11p_blk bld" align="right">
				  <button class="btn-submit7 w9" name="newCourseCMS" id="newCourseCMS" value="New_Course_CMS" type="button" onClick="showInstituteTypeSelectionOverlay()">
				     <!-- <button class="btn-submit7 w9" name="newCourseCMS" id="newCourseCMS" value="New_Course_CMS" type="button" onClick="window.location='<?php echo site_url().'/enterprise/ShowForms/ShowInstituteForm/'; ?>'"> -->
                                         <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Add new Institute</p></div>
				     </button>
                                 </div>
                                 <?php } ?>
			       </div>
			       <div class="clear_L"></div>
			    </div>
			    <div class="lineSpace_5">&nbsp;</div>
                            <div class="row normaltxt_11p_blk bld">
                                <!-- <input type="hidden" id="methodName" value="searchCollegesCMS"/>             -->
                                <input type="hidden" id="methodName" value="getPopularCollegeCMSajax"/>
                                <div class="boxcontent_lgraynoBG">
                                    <div style="background-color:#EFEFEF; border-right:1px solid #B0AFB4;padding-left:2px;width:100%">
                                        <div class="float_L" style="background-color:#D1D1D3; width:52%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;College Name</div>
                                        <div class="float_L" style="background-color:#EFEFEF; width:23%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF;">&nbsp; &nbsp;Added by User</div>
                                        <div class="float_L" style="background-color:#EFEFEF; width:23%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF;">&nbsp; &nbsp;Type of Institute</div>
                                        <div class="clear_L"></div>
                                    </div>
                                </div>
                            </div>
							<div id="cmsCollegeTable" name="cmsCollegeTable" class="row normaltxt_11p_blk boxcontent_lgraynoBG" style="height:190px; overflow:auto">
                                                        </div>
                                            <!--this div is hidden :ravindra-->
                                                <div style="display:none">
                                                        <div class="bld">
                                                           Institute <input type="text" id="collegeName" name="collegeName" value=""/>
                                                           City <input type="text" id="cityName" name="cityName" value=""/>
                                                           Country <input type="text" id="countryName" name="countryNname" value=""/>
                                                           <button class="btn-submit7 w9" name="searchCollCMS" id="searchCollCMS" value="Search_College_CMS" type="button" onClick="searchCollegesCMS();">
                                                              <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Search College</p></div>
                                                           </button>
                                                        </div>
                                                        <div class="lineSpace_5">&nbsp;</div>
                                                  </div>
                                                  <!--hidden div ends-->
                                                  
							<div class="w395" id="instituteSelectionForm" style="display:none">
								<div class="orblkBrd">
							        <!--<div class="otit">
							            <div class="float_L">Type of Institute</div>
							            <div class="float_R"><div class="cssSprite1 allShikCloseBtn">&nbsp;</div></div>
							            <div class="clear_B">&nbsp;</div>
							        </div> -->
							        <div class="pf10">
										<div style="width:100%">
							        	<div><strong>What type of institute do you want to add:</strong></div>
							            <div><input type="radio" name="instituteType[]" value="1" id="academicInstitute" checked="true"> Academic institute</div>
							            <div><input type="radio" name="instituteType[]" value="2" id="testprepInstitute"> Test preparatory institute</div>
							            <div class="lineSpace_20">&nbsp;</div>							            
										</div>										
							            <div align="center">
											<div class="float_L"><div style="margin-left:140px"><input type="button" class="entr_Allbtn ebtn_6" value="&nbsp;" onclick="return addInstituteByType()"> &nbsp; &nbsp; </div></div>
											<div class="float_L"><div style="padding-top:5px"><a class="Fnt16" href="javascript:void(0)" onclick="hideLoginOverlay()"><strong>Cancel</strong></a></div></div>
											<div class="clear_B">&nbsp;</div>
										</div>            
							            <div class="lineSpace_10">&nbsp;</div>
							        </div>
							    </div>
							</div>
							
							<div class="w395" id="courseOrderForm" style="display:none">
								<div class="orblkBrd">
							        <div class="pf10">
							        	<div id="courseOrderSelect">
							            </div>
							            <div class="lineSpace_20">&nbsp;</div>							            
							            <div align="center"><input type="button" class="entr_Allbtn ebtn_6" value="&nbsp;" onclick="return saveCourseOrder()"></div>            
							            <div class="lineSpace_10">&nbsp;</div>
							        </div>
							    </div>
							</div>
                                                  
<script>
 searchCollegesCMS();
</script>
<script>
function addInstituteByType(type){
	var type;
	if($('academicInstitute').checked==true){
		type=1;
	}else if($('testprepInstitute').checked==true){
		type=2;
	}else{
		return false;
	}
//	document.getElementById("showInstituteSelectionForm").style.display = "block";
	window.location='<?php echo site_url().'/enterprise/ShowForms/ShowInstituteForm/-1/'; ?>'+type;
}
</script>
