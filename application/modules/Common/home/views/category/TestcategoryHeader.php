<?php $this->load->view('common/commonOverlay'); ?>
<div class="mar_full_10p">
<input type = "hidden" id = "country" value = "2"/>
<input type = "hidden" id = "subCategoryId" value = "<?php echo $examId?>" autocomplete = "off"/>
<input type = "hidden" id = "cities" value = "1"/>
	<div class="lineSpace_5">&nbsp;</div>

	<div class="OrgangeFont bld fontSize_14p">
		<div class="float_L" style="width:79%">
			<span style="font-size:20px" id = "categoryName"><?php echo $examName?></span>&nbsp;
			<span><a href="#" class="fontSize_12p" onClick = "showcommonOverlay(this,'examcategory');return false;" style="font-weight:normal" >Change Exams Category</a></span><img src="/public/images/changeCategoryArrow.gif" onClick = "showcommonOverlay(this,'examcategory')"/>
		</div>
		<div class="float_L" style="width:20%">
			<?php if(!is_array($validateuser)) { ?>
            <div class="lineSpace_10 txt_align_r" onClick="showuserLoginOverLay(this,'HOMEPAGE_TESTPREP_RIGHTPANEL_JOINBUTTON','refresh')" style="cursor:pointer"><img src="/public/images/joinBtn_shiksha.gif"/></div>
			<?php } ?>
		</div>		
		<div class="clear_L" style="line-height:1px">&nbsp;</div>
	</div>
</div>
<div class="mar_full_10p normaltxt_11p_blk_arial">		
		<!--LeftPanel-->
		<div class="lineSpace_5">&nbsp;</div>
        <div><span class="bld fontSize_14p">Browse By <span id = "countryName"><?php echo ucwords($countryName) ?></span> Cities</span> &nbsp; <span><a href="#" onClick = "showcommonOverlay(this,'country');return false;" class="fontSize_12p">Change Country</a></span><img src="/public/images/changeCategoryArrow.gif" onClick = "showcommonOverlay(this,'country');return false;"/></div>
		
		
		<div class="bgBrowseByCategory" style="margin:0 1px;border-left:1px solid #CCCCCC; border-right:1px solid #CCCCCC;z-index:100">
			<div class="float_R" style="width:1px;position:relative;left:1px"><img src="/public/images/bgBrowseByCategoryLeft.gif" /></div>
			<div class="float_L" style="width:1px;position:relative;left:-1px"><img src="/public/images/bgBrowseByCategoryLeft.gif"  /></div>
			<div>
				<div class="lineSpace_5">&nbsp;</div>
				<div style="margin:0 17px;">
                        <ul class = "browseCities">
                        <div id = "cityli"></div>
                        <li id = "liChangecity"><a id = "Changecity" href="#" onClick="showcommonOverlay(this,'cities'); return false;" >Change City<img src="/public/images/changeCategoryArrow.gif" onClick="showcommonOverlay(this,'cities'); return false;" border="0" /></a></li>
						</ul>
						<div class="clear_L lineSpace_5">&nbsp;</div>
				</div>
			</div>
			<div class="clear_B"></div>
		</div>
		<div class="defaultSpaceIE6">&nbsp;</div>
		
		
		<!--End_LeftPanel-->
<div id="subcategoryDiv" onmouseover="showOverlayCat();" onmouseout="hideOverlayCat();" class="subCategoryOverlay">
	<div class="inline-l subOptionArrow" style="position:relative;top:0px;left:-16px;"></div>
	<div  class="inline-l" id="subcategoryDivContent" style="padding:5px 5px 5px 0px;"></div>
	<div class="clear_L"></div>
</div>
</div>
<script>
document.getElementById('country').value = 2;
document.getElementById('cities').value = '';

var pageName = '';

function hideOverlayCat(objElement){
	objElement = 'subcategoryDiv';
	document.getElementById(objElement).style.display = 'none';
	dissolveOverlayHackForIE();
}

function selectCountry(countryName,countryId)
{
document.getElementById('countryName').innerHTML = countryName;
document.getElementById('cities').value = '' ;
document.getElementById('commonOverlay').style.display = "none" ;
document.getElementById('country').value = countryId ;
document.getElementById('citySelected').innerHTML = "All Cities";
document.getElementById('Changecity').style.display = '';
document.getElementById('Changecity').className = '';
document.getElementById('Changecity').parentNode.className = '';
document.getElementById('Changecity').innerHTML = 'Change City<img src="/public/images/changeCategoryArrow.gif" onClick="showcommonOverlay(this,\'cities\'); return false;" border="0"/>';
populatecities();
examId = document.getElementById('subCategoryId').value; 
if(document.getElementById('pagetype').value == "course")
{
document.getElementById('startOffSet').value = 0;
document.getElementById('startOffSet1').value = 0;
getTestPrepReqCourses();
getTestPrepCourses();
}
else
{
document.getElementById('testprepInstitutesListStartOffSet').value = 0;
document.getElementById('requiredInstitutesListStartOffSet').value = 0;
getCollegesForExam('testprep','examdirpage');
getCollegesForExam('required','examdirpage');
}
dissolveOverlayHackForIE();
}

function selectCity(cityName,cityId,tabId)
{
document.getElementById('citySelected').className = '';
document.getElementById('licitySelected').className = '';
document.getElementById('Changecity').innerHTML = 'Change City<img src="/public/images/changeCategoryArrow.gif" onClick="showcommonOverlay(this,\'cities\'); return false;" border="0"/>';
document.getElementById("liChangecity").className = '';
document.getElementById("Changecity").className = '';
for(var i = 0;i<16;++i)
{
    if(document.getElementById("city"+i))
    {
        document.getElementById("city"+i).className = '';
        document.getElementById("li"+i).className = '';
    }
}
if(tabId < 16)
{
if(cityName == 'All')
{
document.getElementById('citySelected').className = 'selected';
document.getElementById('licitySelected').className = 'selected';
}
else
{
if(typeof(tabId) != "undefined")
{
    document.getElementById("city"+tabId).className = 'selected';
    document.getElementById("li"+tabId).className = 'selected';
}
}
}
else
{

    document.getElementById("liChangecity").className = 'selected';
    document.getElementById("Changecity").className = 'selected';
    document.getElementById("Changecity").innerHTML = (cityName.length > 10 ? cityName.substr(0,7) + '...' : cityName ) + '<span style = "font-size:9px"> &#9660</span>';
    document.getElementById("Changecity").title= cityName;
}
document.getElementById('commonOverlay').style.display = "none" ;
document.getElementById('cities').value = cityId ;
if(document.getElementById('pagetype').value == "course")
{
document.getElementById('startOffSet').value = 0;
document.getElementById('startOffSet1').value = 0;
getTestPrepReqCourses();
getTestPrepCourses();
}
else
{
document.getElementById('testprepInstitutesListStartOffSet').value = 0;
document.getElementById('requiredInstitutesListStartOffSet').value = 0;
getCollegesForExam('testprep','examdirpage');
getCollegesForExam('required','examdirpage');
}
examId = document.getElementById('subCategoryId').value; 
dissolveOverlayHackForIE();
}
</script>
