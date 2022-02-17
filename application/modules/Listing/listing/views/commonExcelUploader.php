<div style="width:500px;margin-left:400px;margin-top:50px;">
<h3 style="display:block;text-align:center">Update Naukri Data</h3>
<div id="mainContainer" style="background-color:#eee;padding:10px 0px">
	<form id="nud" method="post" enctype="multipart/form-data" method="post" accept-charset="utf-8" action="/naukri-data" style="padding:15px 30px">
	<label for="file" style="margin-bottom: 10px;">Data Type :</label>
	<select name="type" style="margin-bottom: 20px;">
			<option value="">--Select--</option>
			<option value="salary">naukri_salary_data</option>
			<option value="alumni_stats">naukri_alumni_stats</option>
			<option value="functional_salary_data"> naukri_functional_salary_data</option>
	</select></br>
	<label for="file">File :</label>
	<input type="file" name="datafile" id="file"><br><br>
	<input type="submit" name="submit" value="Upload" style="margin-bottom:10px;padding:5px 35px;cursor:pointer;">
	<br><span <?php if($this->session->flashdata('error')==1){?> style="color:red;"  <?php }else{?> style="color:green;" <?php }?>><?php if($this->session->flashdata('msg')){ echo $this->session->flashdata('msg');}?></span>
	</form>
	<span style="font-size: 12px;margin-left: 30px">Note: It can be upload 80000 data (rows) only at a time.</span>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script>
$(document).ready(function(){
   	$("input[name='submit']").on("click", function(){
   		$(this).val('Wait...').css({'pointer-events':'none'});
   		readData(this);
	});
	$('#nud')[0].reset();
	clearInterval();
});
function readData(obj) {
	var txt = $(obj).val();
    setInterval(function(){
	txt = $(obj).val();
    if(txt == 'Wait...'){
    	$(obj).val('Reading...');
    }else if(txt == 'Reading...'){
    	$(obj).val('Wait...');
	}	
}, 3000);
}
</script>