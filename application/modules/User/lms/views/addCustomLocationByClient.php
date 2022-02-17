<?php
if(is_array($headerContentaarray) && count($headerContentaarray)>0) {
	foreach ($headerContentaarray as $content) {
            echo $content;
	}
}
?>

<script src="/public/js/<?php echo getJSWithVersion("porting"); ?>"></script>
<center>
<div style="font-size: medium; color :red" id="message_div">
<?php
if($messagetext)
   echo $messagetext;
?>
</div>
    <div id="formDetail">
    <form method="post" enctype="multipart/form-data" id="uploadForm" action="/lms/Porting/uploadCSVForCustomLocation">
<input type="hidden" id="clientid" name="clientid" value="<?php echo $clientID; ?>" />

<?php if(is_array($headerContentaarray) && count($headerContentaarray)>0) { ?>
    <label style="width:74px; display: inline-block">Client ID:</label>
    <input type="text" style="width:252px; margin-bottom:8px;" id="clientID" name="clientid" value="<?php echo $clientID; ?>" />
    <br>
    <input style="margin:8px 0" type="button" name= "getCourses" value="Get Courses" onclick="getCoursebyClient(); hideErrorMessage();" class="gray-button">
<?php } ?>

<div id="customlocation-div">
<br>
<label for="subscription" id="labelsubscription" style="width: 74px; display: inline-block;">Subscription:</label>
  <select style="width:252px; margin-bottom:8px;" id="subscription" name="subscription" class="subscription-select">
   <option value=-1>Select</option>								
    <?php foreach($subscriptions as $row){
    ?>
       <option value="<?php echo $row['SubscriptionId'];?>"><?php echo $row['BaseProdSubCategory']; ?></option>
    <?php }  ?>
</select>
  <br>

<label for="course" id="labelcourse" style="width: 74px; display: inline-block;">Course:</label>
  <select style="width:252px; margin-bottom:8px;" id="course" name="course" class="course-select">
   <option value=-1>Select</option>								
    <?php foreach($instituteList as $instituteId=>$data){
            foreach($data['courseList'] as $index=>$courseData){
    ?>
       <option value="<?php echo $courseData['id'];?>"><?php echo $courseData['name']; ?></option>
    <?php }
    }
    ?>
</select>
  <br>

<label style="width:74px; display:inline-block">Upload File:</label> <input style=" margin-bottom:8px;" id="spreadsheet" type="file" name="spreadsheet"/>
<br/>
(Make sure CSV has following 3 columns: City, Locality, Pcode)

<div class="form-fields" style="margin-top:5px;">
<input type="button" class="orange-button" value="Save" onClick="if(onSaveToCustomLocationTable() == true){$j('#uploadForm').submit();}" />
</div>
</form>

    <?php
    error_log(" == aaa : ".print_r($collegeName,true));
    if($courseList && !empty($collegeName)){ ?>
    <div style="margin:10px 0;">
        <table border="5px" cellpadding="5" style="border:1px solid #ccc;" >
            <tr>
                <th>
                    College Name
                </th>
                <th>
                    Course Name
                </th>
                <th>
                    Course Id
                </th>
                <th>
                    Update CSV
                </th>
                <th>
                    Delete CSV
                </th>
            </tr>
            <?php for($i=0 ; $i<count($collegeName);$i++){
            ?>
            <tr>
                <td>
                    <?php echo $collegeName[$i]; ?>
                </td>
                <td>
                    <?php echo $courseName[$i]; ?>
                </td>
                <td>
                    <?php echo $courseID[$i]; ?>
                </td>
                <td>
                    <a onclick= "updateCustomLocationTableForCourse(<?php echo $courseID[$i]; ?>)" href=#main-wrapper>Update</a>
                </td>
                <td>
                    <a onclick= "deleteEntryFromCustomLocationTable(<?php echo $courseID[$i]; ?>,<?php echo $clientID;?>);" href=#main-wrapper>Delete</a>
                </td>
            </tr>
            <?php }  ?>
        </table>           
    
<?php }else{
    echo "No records to display.";
    }?>
    </div>
</div>    
</center>
    </div>
<?php if(is_array($headerContentaarray) && count($headerContentaarray)>0) {
$this->load->view('common/footer');
} ?>