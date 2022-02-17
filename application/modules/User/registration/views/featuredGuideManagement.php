<?php 
$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style','smart','exampages_cms'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>''
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs');
$this->load->view("enterprise/featuredContentTabs", array("activeTab" => "guide"));
?>

<form method="post" enctype="multipart/form-data" id="uploadPDF" action="/registration/WelcomeMailerAttachment/saveAllData" style=" padding-bottom: 30px;">


<div class="bld fontSize_18p OrgangeFont" style="margin-bottom:15px; margin-left: 25px; font-size: 20px;">Featured Guide</div>
<br>

<label for="category" id="labelCategory" style="width: 126px;float: left; margin-left: 80px; display: inline-block; font-size: 15px; position: relative;">Select Category<i class="semiColon1">:</i></label>
<select name="fieldOfInterest" id="fieldOfInterest" onchange="getCourseDropdown(this.value);">
    <option value="">Select Stream</option>
    <?php foreach($category as $categoryId => $categoryName) { ?>
    <option <?php if($fieldOfInterest == $categoryId ){echo "selected='selected'";} ?>value="<?php echo $categoryId; ?>"><?php echo $categoryName; ?></option>
<?php } ?>
</select>
<br>
    <div id="desiredCourseList" style="margin-top: 12px;"></div>

<br/>

<hr style="margin-bottom: 15px">

<div class="bld fontSize_18p OrgangeFont" style="margin-bottom:15px; margin-left: 25px; font-size: 20px;">Upload New Guide</div>
<br>

<label for="attachmentName" id="labelAttachmentName" style="width: 126px; margin-bottom: 15px; margin-left: 80px; display: inline-block; font-size: 15px;">Attachment Name: </label>
<input type="text" id="attachment_name" value="<?php echo $attachment_name; ?>" name="attachment_name" style="margin-bottom: 15px; margin-left: 1px; display: inline-block; font-size: 15px;"/>
<br>
<label for="pdfUpload" id="labelpdfUpload" style="width: 126px; margin-bottom: 15px; margin-left: 118px; display: inline-block; font-size: 15px;">Upload PDF: </label>

<input style=" margin-bottom:15px; margin-left: -30px" id="pdf" type="file" name="pdf[]"/>
<br/>
<div>
<div style="" id="pdf_error" class="errorMsg"></div>
</div>
<?php if(!empty($error_message)):?>
<div id="upload_error" class="errorMsg" style='width: 320px; margin-left: 114px; display: inline-block; font-size: 15px;position: relative;'><?php echo $error_message?></div>
<?php else:?>
<div id="upload_error" class="errorMsg" style="width: 320px;float: left; margin-left: 114px; display: inline-block; font-size: 15px;position: relative; color:green!important;"><?php echo $success_message?></div>
<?php endif;?>
<div class="form-fields" style="margin-top:5px;">
<input type="button" class="orange-button" style="margin-left: 150px; " value="Upload" onClick="onSaveToUpload(); hideErrorMessage();" />
</div>

<div id='existing_guides'>

</div>
</form>

<?php $this->load->view('common/footerNew');?>
<script>
    
    var fieldOfinterest = '<?php echo $fieldOfInterest; ?>';
    var desiredCourse = '<?php echo $desiredCourse; ?>';
    if (fieldOfinterest !=""){ 
        $j('#fieldOfInterest').change();
        
    }
    
    function checkDesiredCourse(){
        if (desiredCourse !='' && fieldOfinterest !="") {
            $j('#desiredCourse').val( desiredCourse ).attr('selected',true);
             $j('#desiredCourse').change();
            
        }
    }


</script>

