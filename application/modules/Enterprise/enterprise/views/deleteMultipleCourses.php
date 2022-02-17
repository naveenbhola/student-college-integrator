<?php
$headerComponents = array(
      'css'                     => array('headerCms','raised_all','mainStyle','footer'),
      'js'                      => array('user','tooltip','common','newcommon','listing','prototype','scriptaculous','utils','imageUpload'),
      'title'                   => "Delete Multiple Courses",
      'product'                 => '',
      'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:"")
   );

   $this->load->view('enterprise/headerCMS', $headerComponents);
   $this->load->view('enterprise/cmsTabs',$cmsUserInfo);
?>
<div style="width:700px;padding:20px;">
   <h1>Delete Courses for <?=$institute?></h1>
   <table border="1" cellpadding="10" cellspacing="0" style="padding-top:10px;">
	  <tr><td><input type="checkbox" name="selectall" onclick="checkUncheckAll(this);"/></td><td><h2>Course ID</h2></td><td><h2>Course Name</h2></td></tr>
	  <?php
		 foreach($courses as $course){
	  ?>
	  <tr><td><input type="checkbox" name="course[]" value="<?=$course['courseID']?>" /></td><td><?=$course['courseID']?></td><td><?=$course['courseName']?></td></tr>
	  <?php
		 }
	  ?>
   </table>
   <input class="orangebutton" type="button" value="Delete All" onclick="deleteListings();" style="margin-top:10px;"/>
   <div id="pleaseWait" style="font-size:14px;color:#aa0000">
   </div>
</div>
<?php
   $this->load->view('common/footerNew.php',$sumsUserInfo);
?>
<script>
function checkUncheckAll(element){
   var newCheck;
   if(element.checked){
	  newCheck = true;
   }else{
	  newCheck = false;
   }
   var courses = document.getElementsByName('course[]');
   for(var i=0;i<courses.length;i++){
	  courses[i].checked = newCheck;
   }
}

function deleteListings(){
   var flag = 0;
   var courseIds = new Array();
    var courses = document.getElementsByName('course[]');
   for(var i=0;i<courses.length;i++){
	  if(courses[i].checked){
		 courseIds.push(courses[i].value);
		 flag = 1;
	  }
   }
   if(!flag){
	  $('pleaseWait').innerHTML = 'Please select some courses to delete';
	  return true;
   }
   if(confirm("Are you sure. This Process is irreversable.")){
	  var mysack = new sack();
	  mysack.setVar("courses", courseIds.join("|"));
	  mysack.setVar("institute", <?=$instituteId?>);
	  mysack.method = 'POST';
	  //mysack.requestFile = "/listing/Listing/deleteMultipleCourses/";
	  mysack.requestFile = "/listing/posting/ListingDelete/deleteMultipleCourses/";
	  mysack.onError = function() {
        $('pleaseWait').innerHTML = 'Error Encountered'; 
	  };
	  mysack.onLoading = function() {
		 $('pleaseWait').innerHTML = 'Please Wait..'; 
	  };
	  mysack.onCompletion = function() {
		alert(trim(mysack.response));
		window.location.reload();
      }
	  mysack.runAJAX();
    }
   
}
</script>
