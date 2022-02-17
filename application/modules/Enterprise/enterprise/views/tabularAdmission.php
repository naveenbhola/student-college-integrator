<div style="height:45px;background-image:url(/public/images/enterpriceBg.gif); background-repeat:repeat-x;padding:0 10px;">
   <div class="float_L" style="display:none">
      <input type="radio" name="selectedSearchType" id="selectedSearchType1" value="filter" onClick="showSearchBar(this);" checked="checked" >Filter<br />
      <input type="radio" name="selectedSearchType" id="selectedSearchType2" value="search" onClick="showSearchBar(this);" >Search
   </div>
   <script>$('selectedSearchType1').checked = true; </script>
   <div style="margin-left:80px;display:none;padding:5px 0;" id="searchBar">
      Keyword <input type="text" id="keyword" name="keyword" style="width:250px">
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
      <input type="hidden" id="searchType" value="<?php echo $prodId; ?>" />
      <input type="hidden" id="startlucene" />
      <button class="btn-submit7" type="button" onClick="$('paginataionPlace2').innerHTML='';$('startOffSet1').value='0';searchLuceneCMS();" style="width:70px">
	 <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Search</p></div>
      </button>
   </div>
   <div style="margin-left:10px;;padding:5px 0;" id="filterBar">
   Filter by:
   <?php if($usergroup != "cms") { $modstyle= 'style="display:none;"'; }?>
   <select name="moderation" id="moderation" <?php echo $modstyle; ?>>
      <option value="">Select</option>
      <option value="1">Moderated</option>
      <option value="2">Unmoderated</option>
   </select>
   <select name="liveStatus" id="liveStatus">
      <option value="">Select</option>
         <option value="live" selected="selected">Live</option>
         <option value="draft">Draft</option>
         <option value="blocked">Blocked</option>
         <option value="deleted">Deleted</option>
         <option value="expired">Expired</option>
         <option value="cancelled">Cancelled</option>
   </select>
	  <input name="filterAdmissions" id="filterAdmissions" type="button" style="border:0 none;background:url(/public/images/entGoBtn.gif) no-repeat left top;width:36px;height:20px;color:#FFF;font-weight:bold" value="Go"  onClick="$('paginataionPlace3').innerHTML='';searchAdmissionCMS();"  />
	  
   <!--<button class="btn-submit7" name="filterAdmissions" id="filterAdmissions" value="Filter_Admission_CMS" type="button" onClick="$('paginataionPlace3').innerHTML='';searchAdmissionCMS();" style="width:50px;padding-left:5px;">
      <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Go</p></div>
   </button>-->
   <input type="checkbox" onclick="$('paginataionPlace3').innerHTML='';searchAdmissionCMS();" id="abuseonly" autocomplete="off">Show Reported Abuse Notifications
</div>
<div class="clear_L"></div>
</div>
<div id="messageAfterAjax" style="background:#FFF1A8;line-height:18px;"></div>
<div class="lineSpace_10">&nbsp;</div>
<div class="mar_full_10p">
   <div class="float_R" style="padding:5px">
      <div class="pagingID" id="paginataionPlace2"></div>
      <div class="pagingID" id="paginataionPlace3"></div>
   </div>
   <div class="float_L">
      <!-- code for pagination start -->
      <div class="normaltxt_11p_blk bld" >
	 <button class="btn-submit7 w7" name="newAdmit" id="newAdmit" value="New_Admit_CMS" type="button" onClick="window.location='<?php echo site_url().'/enterprise/Enterprise/addAdmissionCMS/'.$prodId; ?>'" style="width:140px;">
	    <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Add New Notification</p></div>
	 </button>
      </div>
   </div>
   <div class="clear_L"></div>
</div>
<div class="lineSpace_5">&nbsp;</div>
<!-- code for pagination ends -->

<div class="row normaltxt_11p_blk">
    <input type="hidden" id="methodName" value="getPopularAdmitCMSajax"/>
    <div id="paginataionPlace1" style="display:none;"></div>
    <div class="boxcontent_lgraynoBG bld">
        <div style="background-color:#EFEFEF; border-right:1px solid #B0AFB4;padding-left:2px;width:100%">
            <div class="float_L" style="background-color:#D1D1D3; width:40%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Name</div>
            <div class="float_L" style="background-color:#EFEFEF; width:30%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Year</div>
            <div class="float_L" style="background-color:#EFEFEF; width:14%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Start Date</div>
            <div class="float_L" style="background-color:#EFEFEF; width:14%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF;">&nbsp; &nbsp;End Date</div>
            <div class="clear_L"></div>
        </div>
    </div>
</div>
<div id="cms_admit_table" name="cms_admit_table" class="row normaltxt_11p_blk boxcontent_lgraynoBG" style="height:200px; overflow:auto"></div>
<div class="bld" style="display:none">
   Name <input type="text" id="admissionName" name="admissionName" value=""/>
   Year <input type="text" id="year" name="year" value=""/>
   <button class="btn-submit7" name="searchAdmCMS" id="searchAdmCMS" value="Search_Adm_CMS" type="button" onClick="searchAdmissionCMS();" style="width:80px">
      <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog"> Search </p></div>
   </button>
</div>
</div>
<div class="lineSpace_5">&nbsp;</div>
<script>
   searchAdmissionCMS();
</script>
