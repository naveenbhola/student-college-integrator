<?php
if(is_array($headerContentaarray) && count($headerContentaarray)>0) {
	foreach ($headerContentaarray as $content) {
            echo $content;
	}
}
?>


<script src="/public/js/<?php echo getJSWithVersion("porting"); ?>"></script>
<center>
<div class = client>
    
    <label style="width:74px; display: inline-block">Client ID:</label>
    <input style="width:252px; margin:8px 0 " type="text" id="clientID" /> &nbsp;
  <br><input style="margin:8px 0" type="button" name= "getCourses" value="Get Courses" onclick="getCoursebyClient()" class="gray-button">
  <div id="customlocation-div"></div>
   

</div>
</center>
<?php $this->load->view('common/footer');?>