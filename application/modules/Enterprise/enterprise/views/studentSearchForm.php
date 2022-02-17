<?php
$headerComponents = array(
        'css'   =>  array('modal-message','headerCms','raised_all','mainStyle','footer','cal_style','lms_porting','smart','common_new','searchCriteria'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog','ajax-api','ldb_search','searchAgents','tooltip','searchCriteria'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>'',
	'title' => 'Enterprise Student Search'
        );
$this->load->view('enterprise/headerCMS', $headerComponents);

$this->load->view('common/calendardiv');
?>
<script LANGUAGE="JavaScript">
    var calMain = new CalendarPopup("calendardiv");
</script>
<script>
function hideDivElement(obj, divElement)
{
    if(divElement)
    {
        if(divElement.style.display != 'none')
        {
            divElement.style.display = 'none';
            obj.className = "cmsSearch_plusImg";
        }
        else
        {
            divElement.style.display = 'block';
            obj.className = "cmsSearch_minImg";
        }
    }
}
 function Numbers(e)
{
var tr_match = /(^\d+\.\d{1,2}$)|(^\d+$)/;
var chk = tr_match.test(e.value);
if(chk){
	return true;
	} else {
		alert("Please Enter only number");
		return false;
	}
}
</script>
<?php
$this->load->view('enterprise/cmsTabs');
?>
<div style="width:100%">
	<div>
		<div style="margin:0 10px">
    	<div style="width:100%">
            <div id="studentresolutionSet_800">
            <script>
            if(document.body.offsetWidth<900){
                 document.getElementById('studentresolutionSet_800').style.width='994px';
            }
            </script>
            <div style="padding-bottom:11px"><b>Search Students:</b> Use the forms below to find relevant students matching your requirements</div>
<?php
$this->load->view("enterprise/searchTabsNew");

$this->load->view($viewFile);



?>
</div>
</div>
</div>
<div style="margin-top:40px">
<div id="hack_ie_operation_aborted_error"></div>
</div>
</div>
</div>
<?php
$this->load->view('enterprise/footer');
?>
<script>
	try {
		window.onload = function () {
			//binding serach form elements
			var searchCMSBinder = {};
		    var searchCMSBinder =  new SearchCMSBinder();
			searchCMSBinder.bindOnloadElements();
			searchCMSBinder.criteriaNo = '<?php echo $criteriaNo;?>';
			searchCMSBinder.virtualCitiesParentChildMapping = new  Array('<?php echo json_encode($virtualCitiesParentChildMapping);?>');
    		searchCMSBinder.virtualCitiesChildParentMapping = new  Array('<?php echo json_encode($virtualCitiesChildParentMapping);?>');
			
			var el = document.createElement("iframe");
			el.setAttribute('id', 'ifrm_csv_download');
			el.setAttribute("height","0");
			el.setAttribute("width","0");
			el.setAttribute('src', '');
			document.body.appendChild(el);
		}
	} catch(e){
		//alert(e);
	}
	$j('.PG').hide();
	$j('.UG').show();
	$j('.localPG').hide();
	$j('.localUG').show();
	$j('#PGCourses').hide();
	function filterCourseInLevel1() {
		    $j('.PG').hide();
		    $j('.UG').show();
		    $j('#PGCourses').hide();
		    $j('#UGCourses').show();
		    document.getElementById('gradStartYear').value="";
		    document.getElementById('gradEndYear').value="";
		    $j('.UG,.PG,#all_specialization').attr("checked",false);
		    
	}
	function filterCourseInLevel2(){
	   
		    $j('.PG').show();
		    $j('.UG').hide();
		    $j('#PGCourses').show();
		    $j('#UGCourses').hide();
		     document.getElementById('XIIStartYear').value="";
		    document.getElementById('XIIEndYear').value="";
		    //$j('.PG').attr("disabled","");
		    $j('.PG,.UG,#all_specialization').attr("checked",false);		   
	}
	
	function checkallfunction(){
		    if(document.getElementById('educationLevel1').checked) {
			if (!document.getElementById('all_specialization').checked) {
                            $j('.localUG').attr("checked",false);
			    $j('.UG').attr("checked",false);
			}else{
			    $j('.UG').attr("checked",true);
                            $j('.localUG').attr("checked",true);
			    $j('.PG').attr("checked",false);
                            $j('.localPG').attr("checked",false);
			}
		    }else if(document.getElementById('educationLevel2').checked) {
			if(!document.getElementById('all_specialization').checked) {
                            $j('.localPG').attr("checked",false);
			    $j('.PG').attr("checked",false);
			}else{
			    $j('.UG').attr("checked",false);
                            $j('.localUG').attr("checked",false);
			    $j('.PG').attr("checked",true);
                             $j('.localPG').attr("checked",true);
			}
		    }
	    
	}
	
	function filterCourseInLevel3(){
		    $j('.localPG').hide();
		    $j('.localUG').show();
		    $j('#PGCourses').hide();
		    $j('#UGCourses').show();
		    document.getElementById('gradStartYear').value="";
		    document.getElementById('gradEndYear').value="";
		    $j('.localUG,.localPG,#all_specialization').attr("checked",false);
	}
	
	function filterCourseInLevel4(){
		    $j('.localPG').show();
		    $j('.localUG').hide();
		    $j('#PGCourses').show();
		    $j('#UGCourses').hide();
		    document.getElementById('XIIStartYear').value="";
		    document.getElementById('XIIEndYear').value="";
		    //$j('.PG').attr("disabled","");
		    $j('.localPG,.localUG,#all_specialization').attr("checked",false);	
	}
</script>
