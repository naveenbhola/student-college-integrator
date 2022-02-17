<?php
$headerComponents = array(
      'css'                     => array('headerCms','raised_all','mainStyle','footer'),
      'js'                      => array('user','tooltip','common','newcommon','listing','prototype','scriptaculous','utils','imageUpload'),
      'title'                   => "Featured Colleges GNB",
      'product'                 => '',
                                'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:"")
   );

   $this->load->view('enterprise/headerCMS', $headerComponents);
   $this->load->view('enterprise/cmsTabs',$cmsUserInfo);
   if(count($popularInsData)>0) {
   	foreach ($popularInsData as $data) {
   		$popularInsDataCat[$data['type']][$data['position']] = $data;
   	}
   } else {
   		$popularInsDataCat = array();
   }

?>
<style>
#tgclID td input.tBx, #tgclID td input.fBx{font-size:12px;font-family:Arial, Helvetica, sans-serif}
#tgclID td select.sBx{font-size:12px;font-family:Arial, Helvetica, sans-serif}
.tgcl_btn{background:url(/public/images/tgcl_btn.gif);width:52px;height:29px;border:0 none;cursor:pointer}
</style>
<div id="wrapperMainForCompleteShiksha">
        <!--Start_Pagewrpper-->
        <div class="wrapperFxd">
         <div class="mlr10">
            <div class="Fnt16 bld mb10">Top Featured Colleges GNB</div>

            <table cellpadding="0" cellspacing="0" border="0" id="tgclID">
               <tr>              
                   <td class="Fnt16 mb10">Type of logo:</td>   
                    <td><select id="type_of_logo"class="sBx" style="width:200px;font-size:14px;" onchange="populateCoursesInfo(this);">
                    <option value="topgradcourses">Top Engineering Courses</option>
                    <option value="topmbacourses">Top MBA Courses</option>
                    <option value="topdesigncourses">Top Design UGCourses</option>
                    <option value="topstudyabroadUGcourses">Top StudyAbroad UGCourses</option>
                    <option value="topstudyabroadPGcourses">Top StudyAbroad PGCourses</option>
                    <option value="topstudyabroadPHDcourses">Top StudyAbroad PHDCourses</option>
                    <option value="tophospitalityandtravelcourses">Top Hospitality & Travel Courses</option>
                    <option value="toplawcourses">Top Law Courses</option>
                    <option value="topanimationcourses">Top Animation Courses</option>
                    <option value="topmasscommcourses">Top Mass Communication & Media Courses</option>
                    <option value="topbusinessmanagementcourses">Top Business & Management Studies Courses</option>
                    <option value="topitandsoftwarecourses">Top IT & Software Courses</option>
                    <option value="tophumanitiescourses">Top Humanities & Social Sciences Courses</option>
                    <option value="topartscourses">Top Arts ( Fine / Visual / Performing ) Courses</option>
                    <option value="topsciencecourses">Top Science Courses</option>
                    <option value="toparchitecturecourses">Top Architecture & Planning Courses</option>
                    <option value="topaccountingcourses">Top Accounting & Commerce Courses</option>
                    <option value="topbankingcourses">Top Banking, Finance & Insurance Courses</option>
                    <option value="topaviationcourses">Top Aviation Courses</option>
                    <option value="topteachingcourses">Top Teaching & Education Courses</option>
                    <option value="topnursingcourses">Top Nursing Courses</option>
                    <option value="topmedicinecourses">Top Medicine & Health Sciences Courses</option>
                    <option value="topbeautycourses">Top Beauty & Fitness Courses</option>

                    </select></td>
                   <td></td>
		   <td></td>
                </tr>
                <tr><td colspan="4" height="5">&nbsp;</td></tr>
                <tr>
                    
                    <td><select id="select1"class="sBx" style="width:100px;margin-right:20px" onClick="javascript: removeMainErrorMessage()" ><option value="1">Position 1</option><option value="2">Position 2</option><option value="3">Position 3</option><option value="4">Position 4</option><option value="5">Position 5</option></select></td>
                    <td><input id="insId1" onclick="removeText('insId1')"  onblur= "checkError('insId1','courseId1') "type="text" <?php $isStored=0;foreach ($popularInsData as $indexGradCourse=> $gradCourseDetails) { if ($gradCourseDetails['position']==1){ $isStored=1; ?>value="<?php echo $gradCourseDetails['institute_id']; ?>" <?php }} if ($isStored == 0){ ?> value="Institute ID" <?php } ?>class="tBx" style="width:180px" /></td>
                    <td><select id="courseId1"  class="tBx" style="width:180px;margin-right:10px;" ><option value="">Select course</option></select></td>
                    
                    <td>&nbsp;&nbsp;<input type="button" onClick="doReset(1)" value="Reset" /></td>

                </tr>
                
                <tr><td colspan="4"><div class="wdh100 mb10"><div class="float_L mr10"><div class=""><div class="errorMsg" id="errorText1"></div></div></div><div class="clear_B">&nbsp;</div></div></td></tr>
                <tr>
                    <td><select id="select2"class="sBx" style="width:100px" onClick="javascript: removeMainErrorMessage()"><option value="1">Position 1</option><option value="2">Position 2</option><option value="3">Position 3</option><option value="4">Position 4</option><option value="5">Position 5</option></select></td>
                    
                    <td><input id="insId2" onclick="removeText('insId2')"  onblur= "checkError('insId2','courseId2')" type="text" <?php $isStored=0;foreach ($popularInsData as $indexGradCourse=> $gradCourseDetails) { if ($gradCourseDetails['position']==2){ $isStored=1;?>value="<?php echo $gradCourseDetails['institute_id']; ?>" <?php }} if($isStored ==0) { ?> value="Institute ID" <?php } ?> class="tBx" style="width:180px" /></td>
                    <td><select id="courseId2"  class="tBx" style="width:180px;margin-right:10px;" ><option value="">Select course</option></select></td>
                   

                    <td>&nbsp;&nbsp;<input type="button"  onClick="doReset(2)"  value="Reset" /></td>
                </tr>
                
                <tr><td colspan="4"><div class="wdh100 mb10"><div class="float_L mr10"><div class=""><div class="errorMsg" id="errorText2"></div></div></div><div class="clear_B">&nbsp;</div></div></td></tr>
                <tr>
                    <td><select id="select3"class="sBx" style="width:100px" onClick="javascript: removeMainErrorMessage()"><option value="1">Position 1</option><option value="2">Position 2</option><option value="3">Position 3</option><option value="4">Position 4</option><option value="5">Position 5</option></select></td>
                    <td><input id="insId3" onclick="removeText('insId3')"  onblur= "checkError('insId3','courseId3')" type="text" <?php $isStored=0;foreach ($popularInsData as $indexGradCourse=> $gradCourseDetails) { if ($gradCourseDetails['position']==3){ $isStored=1;?>value="<?php echo $gradCourseDetails['institute_id']; ?>" <?php }} if($isStored == 0) { ?> value="Institute ID" <?php } ?>class="tBx" style="width:180px" /></td>
                   
                    <td><select id="courseId3"  class="tBx" style="width:180px;margin-right:10px;" ><option value="">Select course</option></select></td>
                    
                    <td>&nbsp;&nbsp;<input type="button"  onClick="doReset(3)"  value="Reset" /></td>
                </tr>

                <tr><td colspan="4"><div class="wdh100 mb10"><div class="float_L mr10"><div class=""><div class="errorMsg" id="errorText3"></div></div></div><div class="clear_B">&nbsp;</div></div><td></tr>
                <tr>
                    <td><select id="select4" class="sBx" style="width:100px" onClick="javascript: removeMainErrorMessage()"><option value="1">Position 1</option><option value="2">Position 2</option><option value="3">Position 3</option><option value="4">Position 4</option><option value="5">Position 5</option></select></td>
                    <td><input id="insId4" onclick="removeText('insId4')"  onblur= "checkError('insId4','courseId4')" type="text" <?php $isStored=0;foreach ($popularInsData as $indexGradCourse=> $gradCourseDetails) { if ($gradCourseDetails['position']==4){ $isStored=1;?>value="<?php echo $gradCourseDetails['institute_id']; ?>" <?php }} if($isStored == 0) { ?> value="Institute ID" <?php } ?>class="tBx" style="width:180px" /></td>
                   
                    <td><select id="courseId4" class="tBx" style="width:180px;margin-right:10px;" ><option value="">Select course</option></select></td>
                   
                    <td>&nbsp;&nbsp;<input type="button"  onClick="doReset(4)"  value="Reset" /></td>
                </tr>
                
                <tr>
                    <td colspan="4"></td>
                    
                </tr>

                <tr><td colspan="4"><div class="wdh100 mb10"><div class="float_L mr10"><div class=""><div class="errorMsg" id="errorText4"></div></div></div><div class="clear_B">&nbsp;</div></div><td></tr>
                             
                <tr>
                    <td><select id="select5" class="sBx" style="width:100px" onClick="javascript: removeMainErrorMessage()"><option value="1">Position 1</option><option value="2">Position 2</option><option value="3">Position 3</option><option value="4">Position 4</option><option value="5">Position 5</option></select></td>
                    <td><input id="insId5" onclick="removeText('insId5')"  onblur= "checkError('insId5','courseId5')" type="text" <?php $isStored=0;foreach ($popularInsData as $indexGradCourse=> $gradCourseDetails) { if ($gradCourseDetails['position']==5){ $isStored=1;?>value="<?php echo $gradCourseDetails['institute_id']; ?>" <?php }} if($isStored == 0) { ?> value="Institute ID" <?php } ?>class="tBx" style="width:180px" /></td>
                    <td><select id="courseId5" class="tBx" style="width:180px;margin-right:10px;" ><option value="">Select course</option></select></td>
                   

                    <td>&nbsp;&nbsp;<input type="button"  onClick="doReset(5)"  value="Reset" /></td>
                </tr>
                
                <tr><td colspan="4"><div class="wdh100 mb10"><div class="float_L mr10"><div class=""><div class="errorMsg" id="errorText5"></div></div></div><div class="clear_B">&nbsp;</div></div><td></tr>
                
                <tr>
                    <td colspan="4"></td>
                    
                </tr>
                <tr><td colspan="4"><input type="button" class="tgcl_btn" onCLick="storePopularInstitutes()"value="&nbsp;" /></td></tr>
            </table>
           
        </div>
    </div>
    <!--End_pagewrapper-->

<div class="lineSpace_15">&nbsp;</div>

<div style="display: none" class="showMessages" id ="sucksecs" align="center">Popular Institutes Saved.</div>
<div class="lineSpace_15">&nbsp;</div>
<div class="lineSpace_15">&nbsp;</div>   
</div>
<div class="spacer20 clearFix">&nbsp;</div>  
<?php $this->load->view('common/footerNew');?>
<!-- Included for uploading logo pictures -->
<script language="javascript" src="/public/js/<?php echo getJSWithVersion('myShiksha'); ?>"></script>
<script>
var pouplarInstitutes = <?php echo json_encode($popularInsDataCat);?>;
document.getElementById("select1").options[0].selected= true;
document.getElementById("select2").options[1].selected= true;
document.getElementById("select3").options[2].selected= true;
document.getElementById("select4").options[3].selected= true;
document.getElementById("select5").options[4].selected= true;


function removeText(eid)
{  
    removeMainErrorMessage();
    var idOfText= eid;
    var index = idOfText.substring(5,6);
    if(document.getElementById(idOfText).value == 'Institute ID')
    document.getElementById(idOfText).value= '';
    var errorObj= document.getElementById('errorText'+index);
    if(errorObj.style.display== 'block')
            errorObj.style.display='none';
}
function checkError(eid,drop_down_id)
{
    var idOfText= eid;
    var instituteId = document.getElementById(idOfText).value;
    if(instituteId != '')
    {
        if (! (/^\s*\d+\s*$/.test(instituteId)))
        {
                 document.getElementById(idOfText).value= 'Institute ID';
                 var index = idOfText.substring(5,6);                 
                 var errorObj= document.getElementById('errorText'+index);
                 errorObj.innerText="Please provide the institue id containg numerals";
                 errorObj.style.display= 'block';
                 return false;
        }    
    poupulateCourseDropdown(instituteId,drop_down_id,eid);                 
    } else if(instituteId == '') {
        document.getElementById(idOfText).value='Institute ID';
    }   
}

function poupulateCourseDropdown(instituteId,drop_down_id,eid,course_id) {
	
	//alert($j('#'+drop_down_id).val());
	if(instituteId =='' || drop_down_id =='' || $j('#'+drop_down_id).val()) {
		return false;
	}

	var url = '/enterprise/Enterprise/getPopularCourseDropDown/'+instituteId; 
	url = url+'?rnd='+Math.floor((Math.random()*1000000)+1);

	$j.get(url, function(data) {
		if(data !='') { 
			$j('#'+drop_down_id).html(data);
			if(typeof(course_id)!='undefined' && course_id) {
				$j('#'+drop_down_id).val(course_id);
			}
		} else {
			if(typeof(eid) !='undefined' && eid) {
				var index = eid.substring(5,6);                 
            	var errorObj= document.getElementById('errorText'+index);	
				errorObj.innerText="Please provide a valid Institute id";
            	errorObj.style.display= 'block';
			}
		}
	});
}

function doReset(ind)
{
    removeMainErrorMessage();
    var index=ind;
    var selectObj=document.getElementById('select'+index);
    var textObj=document.getElementById('insId'+index);
  //  var formObj=document.getElementById('form'+index);
    textObj.value='Institute ID';
    selectObj.options[index-1].selected= true;
    $j('#courseId'+ind).empty();
    $j('#courseId'+ind).append('<option value="">Select course</option>');		
}

function storePopularInstitutes()
{
    //if(!($('insId1').value && $('insId2').value && $('insId3').value)) return false;
    var pass=1;
    var type = $('type_of_logo').value
    var errorObj= document.getElementById('errorText'+5);
    if(!checkUniqueOrder())
        {
            errorObj.innerText="Please provide unique position for each popular institute";
            errorObj.style.display= 'block';
            pass=0;
        }
    if (pass == 1)
        {
            var pos= new Array();
            var insId= new Array();
            var logo= new Array();
            var course_ids = new Array();

            for(i=1;i<=5;i++)
            {
                var position=  document.getElementById('select'+i).selectedIndex+1;
                var insI=      document.getElementById('insId'+i).value;
                var course_id = $j('#courseId'+i).val();

                if (insI !='Institute ID' && insI !='' ) {
                    
                	pos[i-1]=position; 
                	insId[i-1]=insI; 
                	course_ids[i-1] = course_id;
                }
            }            
            url ="/enterprise/Enterprise/setPopularInstitutes/";
            data = 'courses='+course_ids+'&insId='+insId+'&pos='+pos+'&type='+type;
            new Ajax.Request (url,{method:'post', parameters: data, onSuccess:function (request) {checkValidInsId(request.responseText);}});
        }



}
  

function checkUniqueOrder(){
     try {
     var arr1 = new Array();
     var k=0;
     for (var l=1;l<= 5;l++) {
     	if($('insId'+l).value && $('insId'+l).value !='Institute ID') {
		arr1[k] = $('select'+l).value;
                k++;
        }
     }
     if(arr1.length == 0 || arr1.length == 1) return true;
     for(var i =0; i < arr1.length; i++)
     {
                var st = (i+1);
                var now = arr1[i];
                var j = arr1.length;
                while(j > 0)
                   {
                        if(i != j && now == arr1[j]) {
                        	return false;
                        }
                        j--;
                    }
     }
     return true;
    } catch(e) {
	//
    }
}


function checkAllValuesIn()
{
    var arr1 = new Array();
    var fine=false;
    var k=0;
     for (var l=1;l<= 5;l++) {
     	if($('insId'+l).value && $('insId'+l).value !='Institute ID') {
		arr1[k] = l;
                k++;
        }
     }
    if(arr1.length == 0) {fine=true;}
    for(var i=0; i< arr1.length; i++)
    {
        var idProvided = isIdProvided(arr1[i]);
        var logoProvided = isLogoProvided(arr1[i]);
        if(idProvided && logoProvided)
           fine=true;
        else if (!idProvided && !logoProvided)
           fine=true;
        else
           return false;


    }

    return fine;
}
function checkValidInsId(response){
    var ajaxResponse= response;
    if (ajaxResponse !=0 && ajaxResponse != "Deleted")
    {
        document.getElementById('errorText'+5).innerText="Institute Id "+ajaxResponse+" is not a valid Institute id, please change and then save";
        document.getElementById('errorText'+5).style.display="block";
    }
    else {
        	document.getElementById('sucksecs').style.display="block";
                setTimeout(function() {window.location.reload();},2000);
        }
}

function removeMainErrorMessage(){
 var errorObj= document.getElementById('errorText'+5);
 errorObj.innerText="";
 errorObj.style.display="none";
 document.getElementById('sucksecs').style.display="none";
}

function isIdProvided(ind)
{
    var index= ind;
    var idObj=document.getElementById("insId"+index);
    if (idObj.value != '' && idObj.value != "Institute ID")
        return true;
    else
        return false;
}
function isLogoProvided(ind)
{
    var index= ind;
    var logoObj=document.getElementById("sub"+index);
    if (logoObj.name != 'noname')
        return true;
    else
        return false;
}
function populateCoursesInfo(element) {
        $('insId1').value = '';
        $('insId2').value = '';
        $('insId3').value = '';
        $('insId4').value = '';
        $('insId5').value = '';	
        $j('#courseId1').empty();
	$j("#courseId1").append('<option value="">Select course</option>');
        $j('#courseId2').empty();
	$j("#courseId2").append('<option value="">Select course</option>');
        $j('#courseId3').empty();
	$j("#courseId3").append('<option value="">Select course</option>');
        $j('#courseId4').empty();	
	$j("#courseId4").append('<option value="">Select course</option>');
	$j('#courseId5').empty();	
	$j("#courseId5").append('<option value="">Select course</option>');
	var type_logo = element.value;
	if(typeof(pouplarInstitutes[type_logo]) !=='undefined') {
    	for(var i in pouplarInstitutes[type_logo]) {
                
        	var pos1 = pouplarInstitutes[type_logo][i].position;
                        
			$('insId'+pos1).value = pouplarInstitutes[type_logo][i].institute_id;
		//	$('sub'+pos1).name = pouplarInstitutes[type_logo][i].logo_url;
            $('select'+pos1).value = pos1;
            var course_id = pouplarInstitutes[type_logo][i].courseId;
            poupulateCourseDropdown(pouplarInstitutes[type_logo][i].institute_id,'courseId'+pos1,'',course_id);   
        }
	} else {
        for(var i=1;i<=5;i++) {
        	$('insId'+i).value = "";
        	$('sub'+i).name = "noname";
        }
	}
}
populateCoursesInfo($('type_of_logo'));
</script>   
