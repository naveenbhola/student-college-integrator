<div style="height:45px;background-image:url(/public/images/enterpriceBg.gif); background-repeat:repeat-x;padding:0 10px;">
    <div class="float_L" style="display:none;">
      <input type="radio" name="selectedSearchType" id="selectedSearchType1" value="filter" onClick="showSearchBar(this);" checked="checked" >Filter<br/>
      <input type="radio" name="selectedSearchType" id="selectedSearchType2" value="search" onClick="showSearchBar(this);" >Search
  </div>

   <script>
      $('selectedSearchType1').checked = true;
   </script>
   <div style="margin-left:80px;display:none" id="searchBar">
       Keyword <input type="text" id="keyword" name="keyword" style="width:250px" onkeypress="return enterKeySearch(event);"/>
      <div style="display:none">&nbsp;Location <input type="text" id="location" name="location" /></div>
      <?php if($usergroup == "cms") { ?>
      <input type="checkbox" id="showSponsored">Show Sponsored
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
      <input type="hidden" id="searchType" value="<?php echo $prodId; ?>"/>
      <input type="hidden" id="startlucene" />
	  <input type="button" style="border:0 none;background:url(/public/images/entSearch.gif) no-repeat left top;width:71px;height:20px;color:#FFF;font-weight:bold" value="Search" onClick="$('paginataionPlace2').innerHTML='';$('startOffSet1').value='0';$('prod_detail').innerHTML= '<div style=\'width:100%\' align=\'center\'><img src=\'/public/images/space.gif\' width=\'115\' height=\'25\' /></div>';searchLuceneCMS();"  />
	  <!--
      <button class="btn-submit7 w9" type="button" onClick="$('paginataionPlace2').innerHTML='';$('startOffSet1').value='0';searchLuceneCMS();">
	 <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Search</p></div>
      </button>-->
   </div>
   <div style="margin-left:2px;" id="filterBar">
       Search:
      <?php $modstyle= 'style="width:70px"';
          if($usergroup != "cms") { $modstyle= 'style="display:none;"'; }?>
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
      <select name="liveStatus" id="liveStatus" style="width:60px">
	 <option value="">Select</option>
         <option value="live" selected="selected">Live</option>
         <option value="draft">Draft</option>
         <option value="queued">Queued</option>
         <!-- <option value="blocked">Blocked</option>
         <option value="deleted">Deleted</option>
         <option value="expired">Expired</option>
         <option value="cancelled">Cancelled</option>-->
     </select>
     <!--this div is hidden :ravindra-->
     Course Name <input type="text" id="courseName" name="courseName" value="" style="width:140px"/>
     Institute <input type="text" id="collegeName" name="collegeName" value="" style="width:140px"/>
     <!--hidden div ends-->

     <input name="filterColleges" id="filterColleges" type="button" style="border:0 none;background:url(/public/images/entSearch.gif) no-repeat left top;width:71px;height:20px;color:#FFF;font-weight:bold" value="Search"  onClick="$('paginataionPlace3').innerHTML='';$('startOffSet').value='0'; $('prod_detail').innerHTML= '<div style=\'width:100%\' align=\'center\'><img src=\'/public/images/space.gif\' width=\'115\' height=\'25\' /></div>'; searchCoursesCMS();"  />
	  
    <!--<button class="btn-submit7 w6" name="filterColleges" id="filterColleges" value="Filter_College_CMS" type="button" onClick="$('paginataionPlace3').innerHTML='';$('startOffSet').value='0';searchCoursesCMS();" style="width:50px; padding-left:5px;">
	 <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Go</p></div>
      </button>-->
      <input type="checkbox" onclick="$('paginataionPlace3').innerHTML='';searchCoursesCMS();" id="abuseonly" autocomplete="off">Reported Abuse
   </div>
   <div class="clear_L"></div>
</div>

<div id="messageAfterAjax" style="background:#FFF1A8;line-height:18px;"></div>
			    </div>

			    <div class="lineSpace_5">&nbsp;</div>
			    <div class="mar_full_10p">
			       <div class="float_R" style="padding:5px">
				  <div class="pagingID" id="paginataionPlace2"></div>
				  <div class="pagingID" id="paginataionPlace3"></div>
			       </div>
			       <div class="float_L">
                                  <?php if($extraParam=='choose'){?>
                                  <div class="bld" style="font-size:14px"> Please choose a Course to edit</div>
                                  <?php }else{ ?>
				  <div class="normaltxt_11p_blk bld">
                                      <button class="btn-submit7 w9" name="newCourseCMS" id="newCourseCMS" value="New_Course_CMS" type="button" onClick="window.location='<?php echo site_url().'/enterprise/ShowForms/ShowCourseForm'; ?>'" style="width:120px">
                                         <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Add new Course</p></div>
				     </button>
				  </div>
                                  <?php } ?>
			       </div>
			       <div class="clear_L"></div>
			    </div>
                            <div class="lineSpace_5">&nbsp;</div>
                            <div class="row normaltxt_11p_blk bld">
                                <!-- <input type="hidden" id="methodName" value="searchCoursesCMS"/>-->
                                <input type="hidden" id="methodName" value="getPopularCourColCMSajax"/>
                                <div id="paginataionPlace1" style="display:none;"></div>
                                <div class="boxcontent_lgraynoBG">
                                    <div style="background-color:#EFEFEF; border-right:1px solid #B0AFB4;padding-left:2px;width:100%">
                                        <div class="float_L" style="background-color:#D1D1D3; width:39%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Course Name</div>
                                        <div class="float_L" style="background-color:#EFEFEF; width:40%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Institute Name</div>
                                        <div class="float_L" style="background-color:#EFEFEF; width:20%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF;">&nbsp; &nbsp;Added by User</div>
                                        <div class="clear_L"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="cmsCourColTable" name="cmsCourColTable" class="row normaltxt_11p_blk boxcontent_lgraynoBG" style="height:200px; overflow:auto">
                            </div>


						<!-- code for pagination ends -->
<script>
 searchCoursesCMS();
</script>
