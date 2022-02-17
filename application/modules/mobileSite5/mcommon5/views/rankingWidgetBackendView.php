<style>
    .form-wrapper{width:30%; float: left; box-sizing:border-box;}
    h3.section-title{margin: 5px 0 5px 20px;font-size: 14px; }
    .form-col{float: left; width:700px; border:1px solid #eee; padding: 13px; margin-left:120px; margin-bottom:20px; }
    .form-col h4{margin:3px 0;}
    ul.mob-widget-form{width:100%; margin:0 0 0 20px; padding: 0;}
    ul.mob-widget-form li{list-style: none; float: left; width:100%; margin-bottom:10px;}
    ul.mob-widget-form li label{font-size:12px; margin-bottom:5px; float: left; margin-right:10px;padding-top:4px; width: 150px; text-align: right}
    h4.mob-widget-title{font-size:14px; color: #333; margin:0 0 8px 20px; float: left; width:150px; text-align: right;}
    .widget-detail{}
    .widget-txtfield, .widget-select{ float: left; border: 1px solid #ccc; padding: 4px; border-radius:3px; width:500px;}
    .widget-select{padding: 3px; width:200px;}
    .cms-fields{float: left;}
    .save-button{padding:8px 30px 8px; margin-left:20px;margin-bottom: 10px;}
    .clear{clear: both}
</style>


<?php if($usergroup == "cms"){ ?>
<title>CMS Control Page</title>
<?php } ?>
<?php if($usergroup == "enterprise"){ ?>
<title>Enterprise Control Page</title>
<?php } ?>
<?php
$middlePanelView = '-1';
$paramArray = array();
$js = array('imageUpload','common','caEnterprise','ajax-api','CalendarPopup');
$jsFooter = array();
$dontShowStartingFormBorder = 0;
$js = array_merge(array('footer','lazyload'),$js);
$headerComponents = array(
'css'	=>	array('headerCms','raised_all','mainStyle','articles','common_new','caenterprise'),
'js'	=> $js,
'jsFooter' => $jsFooter,
'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
'tabName'	=>	'',
'taburl' => site_url('enterprise/Enterprise'),
'metaKeywords'	=>''
);
$this->load->view('enterprise/headerCMS', $headerComponents);
?>
<div class="mar_full_10p">
        <?php $this->load->view('enterprise/cmsTabs'); ?>
	
</div>

<form id ="form_mobileRankingWidget" name="form_mobileRankingWidget" action="/mcommon5/MobileSiteHome/mobileRankingWidgetData"  method="POST" >
<div class="form-wrapper">
    <h3 class="section-title">Mobile Home Page Ranking Widget </h3>
    <div class="form-col"  style="margin-top:15px;">
	<h4>Tile 1</h4>
	<ul class="mob-widget-form">
	    <li>
		<label>Select Course :</label>
		<div class="widget-detail">
		    <select name="courseName[]" id="courseName1" class="widget-select" >
			<option value="selected='selected'">Select Course</option>
                        <option>FULL-TIME MBA</option>
                        <option>BE/B.Tech</option>
                        <option>Executive MBA</option>
                        <option>Part-Time MBA</option>
                        <option>LLB</option>
		    </select>
		
		<div id="courseName[]_error" class="errorMsg" style="display: none;"></div>
                </div>
	    </li>
	    <li>
		<label>Course Title :</label>
		<div class="widget-detail">
		    <input type="text" class="widget-txtfield" id="courseTitle1" name="courseTitle[]"  caption="course title"  required="true" value='<?php echo $mobileRankingDataArray['0']['course_title'] ?>'/>
		</div>
	    </li>
	    <li>
		<label>Description :</label>
		<div class="widget-detail">
		    <input type="text" class="widget-txtfield" id="courseDesc1" name="courseDescription[]"   caption="course description"  required="true"  value='<?php echo $mobileRankingDataArray['0']['course_description'] ?>'/>
		</div>
	    </li>
	    <li>
		<label>Link :</label>
		<div class="widget-detail">
		    <input type="text" class="widget-txtfield" id="courseLink1" name="courseLink[]"  type="text"   caption="course description"  required="true"  value='<?php echo $mobileRankingDataArray['0']['link'] ?>'/>
		</div>
	    </li>
	</ul>
    </div>
    
    <div class="form-col">
	<h4>Tile 2</h4>
	<ul class="mob-widget-form">
	    <li>
		<label>Select Course :</label>
		<div class="widget-detail">
		    <select name="courseName[]" id="courseName2" class="widget-select">
			<option value="selected='selected'">Select Course</option>
                        <option>FULL-TIME MBA</option>
                        <option>BE/B.Tech</option>
                        <option>Executive MBA</option>
                        <option>Part-Time MBA</option>
                        <option>LLB</option>
		    </select>
		</div>
	    </li>
	    <li>
		<label>Course Title :</label>
		<div class="widget-detail">
		    <input type="text" class="widget-txtfield" id="courseTitle2" name="courseTitle[]"  caption="course title"  required="true"  value='<?php echo $mobileRankingDataArray['1']['course_title'] ?>'/>
		</div>
	    </li>
	    <li>
		<label>Description :</label>
		<div class="widget-detail">
		    <input type="text" class="widget-txtfield" id="courseDesc2" name="courseDescription[]"  caption="course description"  required="true"  value='<?php echo $mobileRankingDataArray['1']['course_description'] ?>'/>
		</div>
	    </li>
	    <li>
		<label>Link :</label>
		<div class="widget-detail">
		    <input type="text" class="widget-txtfield" id="courseLink2" name="courseLink[]"  caption="course link"  required="true"  value='<?php echo $mobileRankingDataArray['1']['link'] ?>'/>
		</div>
	    </li>
	</ul>
    </div>
    
    <div class="form-col">
	<h4>Tile 3</h4>
	<ul class="mob-widget-form">
	    
	    <li>
		<label>Course Title :</label>
		<div class="widget-detail">
		    <input type="text" class="widget-txtfield" id="courseTitle3" name="courseTitle[]"  caption="course title"  required="true" value='<?php echo $mobileRankingDataArray['2']['course_title'] ?>'/>
		</div>
	    </li>
	    <li>
		<label>Description :</label>
		<div class="widget-detail">
		    <input type="text" class="widget-txtfield" id="courseDesc3" name="courseDescription[]"  caption="course description"  required="true" value='<?php echo $mobileRankingDataArray['2']['course_description'] ?>'/>
		</div>
	    </li>
	    
	</ul>
    </div>
    
    <div style="width: 400px;">
            <input class="save-button" type="button" id="submitForm_1" value="Save" onclick="return validationForFields();"/>
	    <input class="save-button" type="button" id="submitForm_1" value="Preview" onclick="window.open('<?=SHIKSHA_HOME?>/mcommon5/MobileSiteHome/renderHomePage?preview=1');"/>
	    <input class="save-button" type="button" id="submitForm_1" value="Publish" onclick="return publishRankingConfig();"/>
    </div>
</div>
</form>

<script>

document.getElementById('courseName1').value='<?php echo $mobileRankingDataArray[0]['course_name'];?>';
document.getElementById('courseName2').value='<?php echo $mobileRankingDataArray[1]['course_name'];?>';

function validationForFields(){
if (document.getElementById('courseName1').value=='' || document.getElementById('courseName2' ).value=='' || document.getElementById('courseTitle1').value == '' || document.getElementById('courseTitle2').value=='' || document.getElementById('courseTitle3').value=='' || document.getElementById('courseDesc1').value=='' || document.getElementById('courseDesc2').value=='' || document.getElementById('courseDesc3').value=='' || document.getElementById('courseLink1').value=='' || document.getElementById('courseLink2').value=='') {
    alert('you cant left any field blank');
    return false;
}
 document.getElementById('form_mobileRankingWidget').submit();
}

function publishRankingConfig(){
	url="/mcommon5/MobileSiteHome/clearRankingCache";
	$j.ajax({type:"POST",data:"configPublish=yes",url:url,success:function(){
		alert("Ranking Config is now Published.");
		location.reload();
	}});
}

<?php if( isset($_COOKIE['showSuccessMessageOnSave']) && $_COOKIE['showSuccessMessageOnSave']!=''){ ?>
	alert("The config has been Saved");
<?php 
	setcookie('showSuccessMessageOnSave','', time() - 3600 ,'/',COOKIEDOMAIN);
      } ?>
</script>
<?php $this->load->view('enterprise/footer'); ?>

