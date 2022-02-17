<style type="text/css">
    table.ofctable { font-size:15px;  border-left:1px solid #ddd;  border-top:1px solid #ddd;}
    table.ofctable th { background: #eee; padding:8px; padding-left: 15px; text-align: left;  border-right:1px solid #ddd;  border-bottom:1px solid #ddd;}
    table.ofctable td { padding:8px; padding-left: 15px; text-align: left; border-right:1px solid #ddd;  border-bottom:1px solid #ddd;}
    .paginationlink {display: block; float:left; padding:2px 5px; border:1px solid #ddd; margin-right: 8px;}
</style>

<div style='padding:0px 15px 40px 15px;'>

<?php if($form) { ?>

<div style='background: #f7f7f7; border:1px solid #ddd; padding:20px 20px 25px 20px;'>
<div class="fontSize_14p bld" style="font-size:18px; margin-top: 0px;">Edit #<?php echo $form['pixel_id']; ?></div>

<form action="/Online/OnlineFormConversionTracking/addCourse" id='OFForm' method="POST">
<div style='width:800px; border:0px solid #ddd; font-size: 15px; margin-top: 20px;'>

<div style='float:left; width:200px; text-align: right; margin-top: 4px;'>Courses IDs:
<div style='color:#777; font-size: 12px; margin-top: 7px;'>Enter comma separated course ids</div>
</div>
<div style='float:left; width:500px; margin-left: 20px;'>
    <input type="text" name="courseIds" id="courseIds" style="font-size: 15px; padding:3px; width:450px;" value="<?php echo $form['courses']; ?>" />
    <div style='color:red; font-size: 12px; margin-top: 4px; display: none;' id='courseIds_error'></div></div>
</div>
<div style='clear: both; padding-bottom: 20px;'></div>

<div style='float:left; width:200px; text-align: right; margin-top: 4px;'>URL:</div>
<div style='float:left; width:500px; margin-left: 20px;'><input type="text" name="OFURL" id="OFURL" style="font-size: 15px; padding:3px; width:450px;" value="<?php echo $form['url']; ?>" />
<div style='color:red; font-size: 12px; margin-top: 4px; display: none;' id='OFURL_error'></div></div>
<div style='clear: both; padding-bottom: 20px;'></div>

<div style='float:left; width:500px; margin-left: 220px;'>
<input type="button" value="Submit" class="orange-button flLt" onclick="validateOFTrackingForm();">
<input type="hidden" name="pixelId" id="pixelId" value="<?php echo $form['pixel_id']; ?>" />
</div>
<div style='clear: both'></div>
</form>
</div>


<?php } else { ?>



<div style='background: #f7f7f7; border:1px solid #ddd; padding:20px 20px 25px 20px;'>
<div class="fontSize_14p bld" style="font-size:18px; margin-top: 0px;">Enter details to generate tracking code</div>

<form action="/Online/OnlineFormConversionTracking/addCourse" id='OFForm' method="POST">
<div style='width:800px; border:0px solid #ddd; font-size: 15px; margin-top: 20px;'>

<div style='float:left; width:200px; text-align: right; margin-top: 4px;'>Courses IDs:
<div style='color:#777; font-size: 12px; margin-top: 7px;'>Enter comma separated course ids</div>
</div>
<div style='float:left; width:500px; margin-left: 20px;'>
    <input type="text" name="courseIds" id="courseIds" style="font-size: 15px; padding:3px; width:450px;" />
    <div style='color:red; font-size: 12px; margin-top: 4px; display: none;' id='courseIds_error'></div></div>
</div>
<div style='clear: both; padding-bottom: 20px;'></div>

<div style='float:left; width:200px; text-align: right; margin-top: 4px;'>URL:</div>
<div style='float:left; width:500px; margin-left: 20px;'><input type="text" name="OFURL" id="OFURL" style="font-size: 15px; padding:3px; width:450px;" />
<div style='color:red; font-size: 12px; margin-top: 4px; display: none;' id='OFURL_error'></div></div>
<div style='clear: both; padding-bottom: 20px;'></div>

<div style='float:left; width:500px; margin-left: 220px;'>
<input type="button" value="Submit" class="orange-button flLt" onclick="validateOFTrackingForm();">
</div>
<div style='clear: both'></div>
</form>
</div>
    
<table width='957' border='0' class='ofctable' cellpadding='0' cellspacing='0' style="margin-left: 0px; margin-top:20px; font-size:13px;">
    <tr>
        <th width='80'>Pixel ID</th>
        <th width='160'>Courses</th>
        <th width='530'>URL</th>
        <th>Pixel Codes</th>
    </tr>
    <?php foreach($forms as $form) { ?>
        <tr>
        <td valign='top'><a href='/enterprise/Enterprise/index/817/admin?edit=<?php echo $form['pixel_id']; ?>'><?php echo $form['pixel_id']; ?></a></td>
        <td valign='top'><?php echo str_replace(',', ', ', $form['courses']); ?></td>
        <td valign='top'><div style='width:500px; word-wrap: break-word;'><?php echo $form['url']; ?></div></td>
        <td valign='top'>
            <a href='/enterprise/Enterprise/index/817/pixelCode?id=<?php echo $form['pixel_id']; ?>'>View</a>
        </td>
    </tr>
    <?php } ?>
</table>

<br />

<?php $numOptions = array(1,2,3,5,10,20,50,100); ?>
<div style='float:right;'>
    <select onchange="changeNumResults(this)">
        <?php foreach($numOptions as $numOption) { ?>
            <option value='<?php echo $numOption; ?>' <?php if($numOption == $numResults) { echo "selected='selected'"; } ?>>
                <?php echo $numOption; ?>
            </option>
        <?php } ?>    
    </select>
</div>

<div style="float:left; color:#888; font-size:12px;">
    Click on the pixel ID to edit URL or add/remove courses.
</div>

<div style='float:right; margin-right: 20px;'>
<a href='/enterprise/Enterprise/index/817/admin?page=1&num=<?php echo $numResults; ?>' class='paginationlink' <?php if($currentPage == 1) { echo "style='background:#eee;'"; } ?>'>1</a>

<?php if($currentPage >= 4) { echo "<div style='float:left; margin:6px 5px 0 0;'>...</div>"; } ?>

<?php
$startPage = ($currentPage - 1) > 2 ? ($currentPage - 1) : 2;
$endPage = ($currentPage + 1) < ($numPages - 1) ? ($currentPage + 1) : ($numPages - 1);
?>
<?php for($j=$startPage;$j<=$endPage;$j++) { ?>    
    <a href='/enterprise/Enterprise/index/817/admin?page=<?php echo $j; ?>&num=<?php echo $numResults; ?>' class='paginationlink' <?php if($j == $currentPage) { echo "style='background:#eee;'"; } ?>'><?php echo $j; ?></a>
<?php } ?>

<?php if($currentPage <= $numPages-3) { echo "<div style='float:left; margin:6px 5px 0 0;'>...</div>"; } ?>

<?php if($numPages > 1) { ?>
<a href='/enterprise/Enterprise/index/817/admin?page=<?php echo $numPages; ?>&num=<?php echo $numResults; ?>' class='paginationlink' <?php if($currentPage == $numPages) { echo "style='background:#eee;'"; } ?>'><?php echo $numPages; ?></a>
<?php } ?>
</div>

<div style='clear: both;'></div>
</div>    
<?php } ?>    

<script>
    function validateOFTrackingForm() {
        var courseIds = trim(document.getElementById('courseIds').value);
        var OFURL = trim(document.getElementById('OFURL').value);
        var pixelId = 0;
        if (document.getElementById('pixelId')) {
            pixelId = trim(document.getElementById('pixelId').value);
        }
        
        var error = false;
        document.getElementById('courseIds_error').style.display = 'none';
        document.getElementById('OFURL_error').style.display = 'none';
        
        if (!courseIds) {
            document.getElementById('courseIds_error').innerHTML = 'Please enter at least one course ID.';
            document.getElementById('courseIds_error').style.display = 'block';
            error = true;
        }
    
        if (!validateCourses(courseIds, false)) {
            error = true;
        }
    
        if (!OFURL || !isValidOFURL(OFURL)) {
            document.getElementById('OFURL_error').innerHTML = 'Please enter valid URL.';
            document.getElementById('OFURL_error').style.display = 'block';
            error = true;
        }

        if (error) {
            return false;
        }
        
        if (!validateCourses(courseIds, true)) {
            return false;
        }
        
        $j.post("/Online/OnlineFormConversionTracking/checkCourses", {"courseIds" : courseIds, "pixelId" : pixelId}, function(data) {
            if (data.existingCourses.length > 0) {
                document.getElementById('courseIds_error').innerHTML = 'Course IDs already exist: '+data.existingCourses.join(', ');
                document.getElementById('courseIds_error').style.display = 'block';
            }
            else {
                document.getElementById('OFForm').submit();
            }
        }, 'json');
    }
    
    function validateCourses(courseIds, askConfirmation) {
        courseIds = courseIds.split(',');
        invalidIds = [];
        
        for(i=0;i<courseIds.length;i++) {
            courseIds[i] = trim(courseIds[i]);
            if (!isValidCourseId(courseIds[i])) {
                invalidIds.push(courseIds[i]);
            }
        }
        
        if (askConfirmation) {
            return confirm("Please confirm that the course ids are correct:\n\n"+courseIds.join("\n"));
        }
        else {
            if (invalidIds.length > 0) {
                document.getElementById('courseIds_error').innerHTML = 'Invalid course IDs: '+invalidIds.join(', ');
                document.getElementById('courseIds_error').style.display = 'block';
                return false;
            }
            
            return true;
        }
    }
    
    function checkCourses(courseId, linkedCourseIds) {
        
    }
    
    function isValidCourseId(str) {
        return /^\+?[1-9]\d*$/.test(str);
    }
    
    function isValidOFURL(url) {
        return /^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i.test(url);
    }
    
    function changeNumResults(sb) {
        var num = sb.options[sb.selectedIndex].value;
        window.location='/enterprise/Enterprise/index/817/admin?num='+num;
    }
</script>

<?php $this->load->view('common/leanFooter'); ?>