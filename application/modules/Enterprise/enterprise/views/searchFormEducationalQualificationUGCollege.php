<div style="width:100%">
	<div>
    	<div class="cmsSearch_RowLeft">
        	<div style="width:100%">
            	<div class="txt_align_r" style="padding-right:5px">UG Institute:&nbsp;</div>
            </div>
        </div>
        <div class="cmsSearch_RowRight" style="width:480px">
        	<div style="width:100%">
            	<div>
                    <input id="keyword" type="text" style="width:445px" onkeyup="document.getElementById('startOffSet').value='0';searchInstitutes()"/>
                    <input id="startOffSet" type="hidden" value="0" autocomplete="off"/>
                    <input id="countOffset" type="hidden" value="10" autocomplete="off"/>
                    <input id="resultSetCount" type="hidden" value="0" autocomplete="off"/>
                    <input id="UGlistingIdCSV" name="UGlistingIdCSV" type="hidden" value="" autocomplete="off"/>
                    </div>
                <div style="width:449px;display:block" id="instituteHolder">
                	<div style="border:1px solid #d6d6d6;height:130px">
                    	<div style="height:100px;overflow:auto">
                        	<div style="width:429px;line-height:25px" id="instituteListPlace">
                            </div>
                        </div>
                        <!--Start_LeftRight_Arrow-->
                        <div style="margin:3px 4px;position:relative;">
                        	<div style="position:absolute;left:0;width:28px;height:22px"><div style="width:100%"><a href="#" id="UGCollegePrevPageButton" style="text-decoration:none" onClick="prevPage();return false;" class="cmsSearch_UGdLeftArrow">&nbsp;</a></div></div>
                            <div style="position:absolute;left:30px;width:380px;height:22px"><div style="width:100%"><div style="line-height:22px;padding-left:10px;font-size:14px"><span id="resultCountNumber" ></span><span><b id="searchKeywordHolder"></span></div></b></div></div>
                            <div style="position:absolute;right:0;width:28px;height:22px"><div style="width:100%"><a href="#" id="UGCollegeNextPageButton" style="text-decoration:none" onclick="nextPage();return false;" class="cmsSearch_UGdRightArrow">&nbsp;</a></div></div>
                            <!--<div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>-->
                        </div>
                        <!--End_LeftRight_Arrow-->
                    </div>
                </div>
            </div>
        </div>
        <div style="float:left;width:235px">
        	<div style="width:100%">
            	<div style="border:1px solid #e6e58b;background:#fffbb4;padding:5px">
                	<div style="padding-bottom:15px"><b>Selected Institutes</b> [&nbsp;<a href="#" onClick="removeAllInstitute();return false;">Remove all</a>&nbsp;]</div>
                    <div style="font-size:11px" id="selectedInstituteHolder">
                    </div>
                </div>
            </div>                        
        </div>
        <div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
    </div>                
</div>
<div style="line-height:6px">&nbsp;</div>
<script>
var SITE_URL = '<?php echo SHIKSHA_HOME; ?>';
var autocompleteSemaphore = 1;
function searchInstitutes(){
    var searchkeyword = document.getElementById('keyword').value;
    if(autocompleteSemaphore == 1)
    {
        if(searchkeyword.length>2)
        {
            if(searchkeyword == '')
            {
                searchkeyword = '+';
            }
            var countOffset = document.getElementById('countOffset').value;
            countOffset = countOffset.toString();
            countOffset = escapeHTML(countOffset);
            if(countOffset===false)
            {
                return;
            }
            var startFrom = document.getElementById('startOffSet').value;
            startFrom = startFrom.toString();
            startFrom = escapeHTML(startFrom);
            if(startFrom===false)
            {
                return;
            }

            var url = SITE_URL + '/listing/Listing/getListingAutoComplete/institute/'+searchkeyword+'/'+startFrom +'/'+countOffset;
            document.getElementById('searchKeywordHolder').innerHTML = searchkeyword;
            autocompleteSemaphore = 0;
            new Ajax.Request (url,{ method:'get',onSuccess:function (xmlHttp) {
                    autocompleteSemaphore = 1;
                    var instituteList = eval("eval("+xmlHttp.responseText+")");
                    populateInstituteDD(instituteList[0].results, instituteList[0].totalCount, searchkeyword, startFrom, countOffset);
                    }});
        }
    }
}
function populateInstituteDD(instituteList, totalcount, searchkeyword, startFrom, countOffset){
   var tempHTML='';
   if(instituteList.length>0)
   {
       for(var i=0;i<instituteList.length;i++)
       {
           if(document.getElementById('selectedSchool_'+instituteList[i].listing_type_id))
           {
               tempHTML += '<div style="border-bottom:1px solid #d6d6d6"><input type="checkbox" id="school_'+instituteList[i].listing_type_id+'" checked="on" onClick="selectInstitute(this,\''+instituteList[i].listing_type_id+'\')" /><span id="schoolName_'+instituteList[i].listing_type_id+'">'+instituteList[i].listing_title+'&nbsp;-<span style="color:#007906">'+instituteList[i].city_name+'</span></span></div>';
           }
           else
           {
               tempHTML += '<div style="border-bottom:1px solid #d6d6d6"><input type="checkbox" id="school_'+instituteList[i].listing_type_id+'" onClick="selectInstitute(this,\''+instituteList[i].listing_type_id+'\')" /><span id="schoolName_'+instituteList[i].listing_type_id+'">'+instituteList[i].listing_title+'&nbsp;-<span style="color:#007906">'+instituteList[i].city_name+'</span></span></div>';
           }
       }
       document.getElementById('instituteListPlace').innerHTML = tempHTML;
   }
   document.getElementById('resultCountNumber').innerHTML = "Total <b>"+totalcount+"</b> results for ";
   document.getElementById('resultSetCount').value = totalcount;
   if(document.getElementById("startOffSet").value != 0)
   {
    document.getElementById('UGCollegePrevPageButton').className = "cmsSearch_UGLeftArrow"; 
   }
   else
   {
    document.getElementById('UGCollegePrevPageButton').className = "cmsSearch_UGdLeftArrow"; 
   }
   if(parseInt(document.getElementById("startOffSet").value)+parseInt(document.getElementById("countOffset").value) < parseInt(totalcount) )
   {
       document.getElementById('UGCollegeNextPageButton').className = "cmsSearch_UGRightArrow"; 
   }
   else
   {
       document.getElementById('UGCollegeNextPageButton').className = "cmsSearch_UGdRightArrow"; 
   }

   if(searchkeyword != document.getElementById('keyword').value)
   {
       searchInstitutes();
   }
}
function nextPage()
{
    var countOffset = parseInt(document.getElementById('countOffset').value);
    var startFrom = parseInt(document.getElementById('startOffSet').value);
    var resultCount = parseInt(document.getElementById('resultSetCount').value);
    if(startFrom+countOffset < resultCount)
    {
        document.getElementById('startOffSet').value = startFrom+countOffset;
        searchInstitutes();
    }
    else
    {
        return false;
    }
}
function prevPage()
{
    var countOffset = parseInt(document.getElementById('countOffset').value);
    var startFrom = parseInt(document.getElementById('startOffSet').value);
    var resultCount = parseInt(document.getElementById('resultSetCount').value);
    if(startFrom !=0 )
    {
        document.getElementById('startOffSet').value = startFrom-countOffset;
        searchInstitutes();
    }
    else
    {
        return false;
    }
}
function removeAllInstitute()
{
    document.getElementById('selectedInstituteHolder').innerHTML ="";
    document.getElementById('UGlistingIdCSV').value = "";
    var UGColleges = document.getElementById("instituteListPlace").getElementsByTagName("input");
    for(var i=0; i< UGColleges.length; i++)
    {
        if(UGColleges[i].checked)
        {
            UGColleges[i].checked = false;
        }
    }
}
function selectInstitute(checkboxObj, instituteId)
{
    if(checkboxObj.checked) {
        var tempHtml = document.getElementById('selectedInstituteHolder').innerHTML;
        tempHtml += '<div id="selectedSchool_'+instituteId+'"> '+document.getElementById('schoolName_'+instituteId).innerHTML+' <img style="cursor:hand;cursor:pointer" src="/public/images/cmsSearch_redCross.gif" border="0" onClick="unselectInstitute(\''+instituteId+'\')"/> <input type="hidden" id="hiddenSchool_'+instituteId+'" name="hiddenSchoolList[]" value="'+stripHTML(document.getElementById('schoolName_'+instituteId).innerHTML)+'"/> <input type="hidden" id="hiddenSchoolId_'+instituteId+'" name="listingIDSelected[]" value="'+instituteId+'"/></div><br/>';
        document.getElementById('selectedInstituteHolder').innerHTML = tempHtml;
        if(document.getElementById('UGlistingIdCSV').value == '')
        {
            document.getElementById('UGlistingIdCSV').value = instituteId;   
        }
        else
        {
            document.getElementById('UGlistingIdCSV').value += ','+instituteId;   
        }
    } else {
        unselectInstitute(instituteId);
    }
}
function unselectInstitute(instituteId)
{
    var elementDiv = document.getElementById('selectedSchool_'+instituteId);
    if(elementDiv) {
        elementDiv.parentNode.removeChild(elementDiv);
		document.getElementById('school_'+instituteId).checked = false;
    }
    var elementDiv1 = document.getElementById('hiddenSchool_'+instituteId);
    if(elementDiv1) {
        elementDiv1.parentNode.removeChild(elementDiv);
    }
    var elementDiv2 = document.getElementById('hiddenSchoolId_'+instituteId);
    if(elementDiv2) {
        elementDiv2.parentNode.removeChild(elementDiv);
    }
    var tempSchoolList = document.getElementById('UGlistingIdCSV').value.split(',');
    var tempSchoolCSV='';
    for(var i=0;i<tempSchoolList.length;i++)
    {
        if(document.getElementById('selectedSchool_'+tempSchoolList[i]))
        {
            if(tempSchoolCSV == '')
            {
                tempSchoolCSV = tempSchoolList[i];
            }
            else
            {
                tempSchoolCSV += ','+tempSchoolList[i];
            }
        }
    }
    document.getElementById('UGlistingIdCSV').value=tempSchoolCSV;
}
function stripHTML(oldString) {

    var newString = "";
    var inTag = false;
    for(var i = 0; i < oldString.length; i++) {

        if(oldString.charAt(i) == '<') inTag = true;
        if(oldString.charAt(i) == '>') {
            if(oldString.charAt(i+1)=="<")
            {
                //dont do anything
            }
            else
            {
                inTag = false;
                i++;
            }
        }

        if(!inTag) newString += oldString.charAt(i);

    }

    return newString;
}
</script>
