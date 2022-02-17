<div style="height:45px;background-image:url(/public/images/enterpriceBg.gif); background-repeat:repeat-x;padding:0 10px;">
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
      <select id="searchSubType" style="width:100px;<?php if ($prodId==5) echo "display:none;"?>">
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
		<input type="button" style="border:0 none;background:url(/public/images/entSearch.gif) no-repeat left top;width:71px;height:20px;color:#FFF;font-weight:bold" value="Search" onClick="$('paginataionPlace2').innerHTML='';$('startOffSet1').value='0';searchLuceneCMS();"  />
      <!--<button class="btn-submit7" type="button" onClick="$('paginataionPlace2').innerHTML='';$('startOffSet1').value='0';searchLuceneCMS();" style="width:70px">
	 <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Search</p></div>
      </button>-->
   </div>
   <div style="margin-left:80px;padding:5px 0;" id="filterBar">
	Filter by:
           <select name="eventType" id="eventType">
                <option value="">Select Event Type</option>
                <option value="0">Application Submission</option>
                <option value="1">Course Comencement</option>
                <option value="2">Results Declaration</option>
                <option value="3">Examination Date</option>
		<option value="4">Form Issuance</option>
                <option value="5">General</option>
           </select>
           <select name="eventCountry" id="eventCountry">
              <option value="">Select Event Country</option>
              <?php
              
                 foreach($countryList as $countryNum)
                 {  
                     /**** REMOVING Abroad countries *****/
                 	if($countryNum['countryID'] == 2) {
                    echo '<option value="'.$countryNum['countryID'].'">'.$countryNum['countryName'].'</option>';
                    }
                 }
              ?>
           </select>
	  <input name="filterEvents" id="filterEvents" type="button" style="border:0 none;background:url(/public/images/entGoBtn.gif) no-repeat left top;width:36px;height:20px;color:#FFF;font-weight:bold" value="Go"  onClick="$('paginataionPlace3').innerHTML='';searchEventsCMS();"  />
	   <!--<button class="btn-submit7 w6" name="filterEvents" id="filterEvents" value="Filter_Event_CMS" type="button" onClick="$('paginataionPlace3').innerHTML='';searchEventsCMS();" style="width:50px;padding-left:5px">
              <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Go</p></div>
           </button>-->
	   <input type="checkbox" onclick="$('paginataionPlace3').innerHTML='';searchEventsCMS();" id="abuseonly" autocomplete="off">Show Reported Abuse Events
	</div>
	<div class="clear_L"></div>
</div>
	   <div id="messageAfterAjax" style="background:#FFF1A8;line-height:18px;"></div>
	</div>


	<div class="lineSpace_10">&nbsp;</div>
	<div class="mar_full_10p">
	   <div class="float_R" style="padding:5px">
	      <div class="pagingID" id="paginataionPlace2"></div>
	      <div class="pagingID" id="paginataionPlace3"></div>
	   </div>
	   <div class="clear_R"></div>
	</div>

	<div class="lineSpace_10">&nbsp;</div>

        <div class="row normaltxt_11p_blk">
            <input type="hidden" id="methodName" value="getPopularEventsCMSajax"/>
            <div id="paginataionPlace1" style="display:none;"></div>
            <div class="boxcontent_lgraynoBG bld">
                <div style="background-color:#EFEFEF; border-right:1px solid #B0AFB4;padding-left:2px;width:100%">
                    <div class="float_L" style="background-color:#D1D1D3; width:42%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Event Title</div>
                    <div class="float_L" style="background-color:#EFEFEF; width:28%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Venue Name</div>
                    <div class="float_L" style="background-color:#EFEFEF; width:14%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Start Date</div>
                    <div class="float_L" style="background-color:#EFEFEF; width:14%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF;">&nbsp; &nbsp;End Date</div>
                    <div class="clear_L"></div>
                </div>
            </div>
        </div>
							<div id="cms_event_table" name="cms_event_table" class="row normaltxt_11p_blk boxcontent_lgraynoBG" style="height:240px; overflow:auto">
                                                        </div>
						<!-- code for pagination start -->
						<!-- code for pagination ends -->
							<div class="lineSpace_5">&nbsp;</div>
                                                        <div class="bld" style="display:none">
                                                           Event Name <input type="text" id="eventName" name="eventName" value=""/>
                                                           Venue <input type="text" id="eventVenue" name="eventVenue" value=""/>
                                                           <button class="btn-submit7 w9" name="searchEventCMS" id="searchEventCMS" value="Search_Event_CMS" type="button" onClick="searchEventsCMS();">
                                                              <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Search Event</p></div>
                                                           </button>
                                                        </div>
                                                        <div class="lineSpace_5">&nbsp;</div>

<script>
   searchEventsCMS();
</script>
