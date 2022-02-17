<div style="height:45px;background-image:url(/public/images/enterpriceBg.gif); background-repeat:repeat-x;padding:3px 10px;">
	<div class="float_L">
		<input type="radio" name="selectedSearchType" id="selectedSearchType1" value="filter" onClick="showSearchBar(this);" checked="checked" >Filter<br />
		<input type="radio" name="selectedSearchType" id="selectedSearchType2" value="search" onClick="showSearchBar(this);" >Search
	</div>
	<script>$('selectedSearchType1').checked = true; </script>
	<div style="margin-left:80px;display:none;padding:5px 0;" id="searchBar">
		Keyword <input type="text" id="keyword" name="keyword" style="width:250px"/>	
		<input type="hidden" id="entUserId" value="" />
		<input type="hidden" id="searchType" value="<?php echo $prodId; ?>" />
		<input type="hidden" id="sortField" value=''/>
		<input type="hidden" id="startlucene" />
		<input type="button" style="border:0 none;background:url(/public/images/entSearch.gif) no-repeat left top;width:71px;height:20px;color:#FFF;font-weight:bold" value="Search" onClick="$('paginataionPlace2').innerHTML='';$('startOffSet').value='0';$('sortField').value='';$('prod_detail').innerHTML= '<div style=\'width:100%\' align=\'center\'><img src=\'/public/images/space.gif\' width=\'115\' height=\'25\' /></div>';searchConsultantCMSajax();"  />
	</div>
	<div style="margin-left:80px;padding:5px 0;" id="filterBar">
		Filter by:
		<?php if($usergroup != "cms") { $modstyle= 'style="display:none;"'; }?>
		<select name="countrySelected" id="countrySelected"<?php echo $modstyle; ?>>
			<?php
				echo "<option value=\"-1\">Select Country</option>";
				foreach($countryList as $value)
				{
					if($value['countryName']!="India" && $value['countryName']!="All") 
						echo "<option value=\"".$value['countryID']."\">".$value['countryName']."</option>";
				}
			?>
		</select>
		<select name="citySelected" id="citySelected" <?php echo $modstyle; ?>>
			<option value="-1">Select City</option>	
			<?php
			foreach($cityTier1 as $key=>$value)
			{
				echo "<option value=\"".$value['cityId']."\">".$value['cityName']."</option>";
			}
			foreach($cityTier2 as $key=>$value)
			{
				echo "<option value=\"".$value['cityId']."\">".$value['cityName']."</option>";
			}	
			?>	
		</select>
		<select name="categorySelected" id="categorySelected">
			<option value="-1">Select Categroy</option>			
			<?php
			global $categoryParentMap; 
			foreach ($categoryParentMap as $key=>$value) { 
				echo "<option value=\"".$value['id']."\">".$key."</option>";
			} ?>
		</select>
		<input name="filterColleges" id="filterColleges" type="button" style="border:0 none;background:url(/public/images/entGoBtn.gif) no-repeat left top;width:36px;height:20px;color:#FFF;font-weight:bold" value="Go"  onClick="$('paginataionPlace3').innerHTML='';$('startOffSet').value='0';$('sortField').value=''; $('prod_detail').innerHTML= '<div style=\'width:100%\' align=\'center\'><img src=\'/public/images/space.gif\' width=\'115\' height=\'25\' /></div>'; getConsultantCMSajax();"  />		
	</div>
	<div class="clear_L"></div>
</div>

<!--AJAX_DATA_SHOW_IN_THIS_DIV-->
<div id="messageAfterAjax" style="background:#FFF1A8;line-height:18px;"></div>

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
			<div class="bld" style="font-size:14px">&nbsp;</div>
		<?php }else{ ?>
		<div class="normaltxt_11p_blk bld" align="right">
			<button class="btn-submit7 w9" name="newCourseCMS" id="newCourseCMS" value="New_Course_CMS" type="button" onClick="window.location='<?php echo site_url().'/consultants/shikshaConsultants/showConsultantsForm/-1/'; ?>'">
			<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Add Consultants</p></div>
			</button>
		</div>
		<?php } ?>
	</div>
	<div class="clear_L"></div>
</div>
<style>
.data_colums{background-color:#D1D1D3;padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4}
.data_inncolums{padding-top:2px 0;font-weight:normal}
</style>
<div class="lineSpace_5">&nbsp;</div>
<div class="row normaltxt_11p_blk bld">
	<input type="hidden" id="methodName" value="getPopularCollegeCMSajax" style="width:1px" />	
	<div style="background-color:#D1D1D3">
		<div class="float_L data_colums" style="width:39%">&nbsp; &nbsp;Consultant Name</div>
		<div class="float_L data_colums" style="width:15%;cursor:pointer" onClick="document.getElementById('sortField').value='cityName ' + this.getAttribute('order');changeSortOrder(this);;document.getElementById('startOffSet').value='0';eval(document.getElementById('methodName').value+'()');" order='desc' id="citySort">&nbsp; &nbsp;City&nbsp;<img src="/public/images/arrow_down.png" border="0" align="absmiddle" style="display:none" id="citySortImgdesc"/><img src="/public/images/arrow_up.png" border="0" align="absmiddle" style="display:none" id="citySortImgasc"/></div>
		<div class="float_L data_colums" style="width:13%">&nbsp; &nbsp;Lead Start Date</div>
		<div class="float_L data_colums" style="width:13%;cursor:pointer" onClick="document.getElementById('sortField').value='leadEndDate ' + this.getAttribute('order');changeSortOrder(this);document.getElementById('startOffSet').value='0';eval(document.getElementById('methodName').value+'()');" order='desc'id="leadEndDateSort">&nbsp; &nbsp;Lead End Date&nbsp;<img src="/public/images/arrow_down.png" border="0" align="absmiddle" style="display:none" id="leadEndDateSortImgdesc"/><img src="/public/images/arrow_up.png" border="0" align="absmiddle" style="display:none" id="leadEndDateSortImgasc"/></div>
		<div class="float_L data_colums" style="width:15%;border-right:none">&nbsp; &nbsp;Added by User</div>
		<div class="clear_L" style="font-size:1px;"></div>
	</div>
</div>
<div id="cmsConsultantTable" name="cmsConsultantTable" class="row normaltxt_11p_blk" style="height:190px; overflow:auto"></div>
<input type="hidden" value="" name="selectedConsultantId" id="selectedConsultantId" />
<div id="btnContainer" style="display:none">
	<button class="btn-submit5 w9" name="newCourseCMS" id="newCourseCMS" value="New_Course_CMS" type="button" onClick="window.location='<?php echo site_url().'/enterprise/ShowForms/ShowInstituteForm/'; ?>'">
			<div class="btn-submit5"><p class="btn-submit6">Delete Consultant</p></div>
	</button>
	<button class="btn-submit7 w9" name="newCourseCMS" id="newCourseCMS" value="New_Course_CMS" type="button" onClick="window.location='<?php echo site_url().'/enterprise/ShowForms/ShowInstituteForm/'; ?>'">
			<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Edit Consultant</p></div>
	</button>
</div>
<script>
document.getElementById('startOffSet').value = 0;
document.getElementById('countOffset').value = 7;
getConsultantCMSajax();
try {
    $('sponsoredDiv').style.display = "none";
} catch (e) {}

function changeSortOrder(obj) {
	if(obj.getAttribute('order') == 'desc') {
		document.getElementById(obj.id +'Img'+ obj.getAttribute('order')).style.display = 'none';
		obj.setAttribute('order','asc'); 
	} else {
		document.getElementById(obj.id +'Img'+ obj.getAttribute('order')).style.display = 'none';
		obj.setAttribute('order','desc'); 
	}
	document.getElementById(obj.id +'Img'+ obj.getAttribute('order')).style.display = '';
	//Hack
	if(obj.id !== 'citySort') {
		document.getElementById('citySortImgasc').style.display = 'none';
		document.getElementById('citySortImgdesc').style.display = 'none';
	} else {
		document.getElementById('leadEndDateSortImgasc').style.display = 'none';
		document.getElementById('leadEndDateSortImgdesc').style.display = 'none';
	}
	
}
</script>
