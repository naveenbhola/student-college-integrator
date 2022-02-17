<?php
	$this->load->view("enterprise/featuredContentTabs", array("activeTab" => "article"));
?>
<form id="form_featuredArticleForm">
<div style="margin: 0 30px;">
	<h3 class="section-title">Featured Article</h3>
	<div class="cms-form-wrap cms-accordion-div">
		<ul>
			<li>
            	<label>Select Course Homepage : </label>
            	<!-- <div class="cms-fields">
            		<select id="courseHomePageId" validationtype="select" required="true" caption="Course Homepage" name="courseHomePageId" class="universal-select cms-field" onchange="changeCoursepage();">
                        <?php
                        	
                        	foreach ($courseHomeDictionary as $courseHomePageId => $detailArr) {
                        		$selected = "";
                        		if($courseHomeId== $courseHomePageId)
                        			$selected = "selected";

                        		echo "<option value=".$courseHomePageId." ".$selected.">".$detailArr['Name']."</option>";
                        	}
                        ?>
                    </select>
            	</div> -->
            	<div style="display: inline-block;position: relative;margin-left: 29px;">
			       <input type="text" name="chpField" id="chpField" class="input-txt" placeholder="Search a CHP" minlength="1" maxlength="50" autocomplete="off" style="width:100%" id="chpId"> 
			  
			       <ul id="chpSearchOptions" class="chpSearchOptions" style="width:112%"></ul>

			       <div id="chpField_error" style="display: none;margin-left: 10px" class="errorMsg erh">Please select a valid CHP display name.</div>
			       <input type="hidden" id="chpId" class="seoFld">
			    </div>
            </li>
		</ul>
	</div>

	<h3 class="section-title">Set New Featured Article</h3>
	<div class="cms-form-wrap cms-accordion-div">
		    	<div class="add-more-sec2 clear-width">
            		<ul>
            			<li>
            				<label>Article ID : </label>
			            	<div class="cms-fields">
			            		<input type="text" id="article_id" name="article_id" maxlength="10" required="true" value="" caption="Article Id" style="98% !important;" class="universal-txt-field cms-text-field" autocomplete="off">
			            		<div style="" class="errorMsg" id="article_id_error"></div>
			            	</div>
            			</li>
	        			<li>
            				<label>Start Date : </label>
            				<div class="cms-fields">
								<input type="text" id="from_date" name="from_date" readonly="" validationtype="html" caption="Start date" class="universal-txt-field cms-text-field start-date" autocomplete="off">
								<i onclick="pickStartDate(this);" style="cursor:pointer" id="importantDateStartIcon_1" class="abroad-cms-sprite calendar-icon"></i>
							</div>
            			</li>
            			<li>
            				<label>End Date : </label>
            				<div class="cms-fields">
								<input type="text" id="to_date" name="to_date" readonly="" validationtype="html" caption="End date" class="universal-txt-field cms-text-field end-date" autocomplete="off">
								<i onclick="pickEndDate(this);" style="cursor:pointer" id="importantDateEndIcon_1" class="abroad-cms-sprite calendar-icon"></i>
							</div>
            			</li>
            			<li>
            				<div class="cms-fields">
								<a class="orange-btn" onclick="addFeaturedArticle();" href="javaScript:void(0);">Save</a>
							</div>
            			</li>
            		</ul>
            	</div>
	</div>

	<h3 class="section-title">Scheduled Featured Articles</h3>
	<div class="search-section">
		<?php
			$this->load->view('featuredArticleTable');
		?>
	</div>
	<div class="clearFix"></div>
</form>
</div>
<script>
var isProgress			= 0;
function addFeaturedArticle(){

	var chpId = $j('#chpId').val();

	$j('#chpField_error').css({'display':'none'});

	if(chpId == 'undefined' || chpId == "" || chpId <= 0){
		$j('#chpField_error').css({'display':'block'})
		return;
	}


	if(isProgress == 1)
		return false;

	isProgress = 1;

	if(!(/^[0-9]+$/.test($j("#article_id").val()))){
		alert("Please enter a valid Article Id");
		$j("#article_id").select().focus(); isProgress = 0;return false;
	}

	if(trim($j("#from_date").val()) == ""){
		alert("You cannot leave FROM DATE empty");
		$j("#from_date").select().focus(); isProgress = 0;return false;
	}

	if(trim($j("#to_date").val()) == ""){
		alert("You cannot leave TO DATE empty");
		$j("#to_date").select().focus(); isProgress = 0;return false;
	}

	startDate = $j("#from_date").val().split('-');
	startDate = new Date(startDate[2],startDate[1]-1,startDate[0]).setHours(0, 0, 0, 0);

	endDate = $j("#to_date").val().split('-');
	endDate = new Date(endDate[2],endDate[1]-1,endDate[0]).setHours(0, 0, 0, 0);

	if(startDate > endDate)
	{
		alert("FROM date cannot be greater than TO date");
		$j("#to_date").val(""); isProgress = 0;return false;
	}

	var postData = 'courseHomePageId='+$j('#chpId').val()+'&article_id='+$j("#article_id").val()+'&from_date='+$j("#from_date").val()+'&to_date='+$j("#to_date").val();

	$j.ajax({
		data:postData,
		url:'/enterprise/Enterprise/saveFeaturedArticle',
		aync:false,
		type:"POST",
	})
	.done(function( res ) {
		if(res == 1){
			alert("Successfully Saved !!!");
			window.location.href = '/enterprise/Enterprise/showFeaturedContentCMS';
		}
		else if(res == "article_error"){
			alert("Article not exist");
			isProgress = 0;
		}
		else{
			alert("Something went wrong !!!");
			window.location.href = '/enterprise/Enterprise/showFeaturedContentCMS';
		}
	});
}

function pickStartDate(thisObj) {
	disDate = new Date();
	calMain = new CalendarPopup('calendardiv');
	var passedYear = new Date().getFullYear();
	disDate = new Date(passedYear,new Date().getMonth(),new Date().getDate());
	calMain.select($j(thisObj).prev()[0] ,$j(thisObj).attr("id"),'dd-MM-yyyy');//'yyyy-MM-dd');
	return false;
}

function pickEndDate(thisObj) {
	calMain = new CalendarPopup('calendardiv');
	if (calMain, $j(thisObj).closest("ul").find(".start-date").val()) {
		var startDate = $j(thisObj).closest("ul").find(".start-date").val();
		if(startDate){
			startDate = startDate.split('-');
			startDate = new Date(startDate[2],startDate[1]-1,startDate[0]);
			disDate = startDate;
		}
	} else {
		disDate = new Date();
	}

	calMain.select($j(thisObj).prev()[0], $j(thisObj).attr("id"), 'dd-MM-yyyy');
	return false;
}

/*function changeCoursepage(){
	//alert($j("#courseHomePageId").val());
	window.location.href = '/enterprise/Enterprise/showFeaturedContentCMS/'+$j("#chpId").val();
}*/

function deleteFeaturedArticle(thisObj, id, courseHomePageId){

	var res = confirm("Are your sure you want to delete this Schedule ?");
	if (res != true) {
		return false;
	}
    
	$j(thisObj).replaceWith('Deleting...');
	$j.ajax({
		data:"id="+id+"&courseHomePageId="+courseHomePageId,
		url:'/enterprise/Enterprise/deleteFeaturedArticle',
		aync:false,
		type:"POST",
	})
	.done(function( res ) {
		alert("Successfully deleted !!!");
		window.location.href = '/enterprise/Enterprise/showFeaturedContentCMS/'+$j("#chpId").val();
	});
}
function getFeaturedArticlesOnChp(chpId,displayName){
	$j('#chpField').val(displayName);
	$j('#chpId').val(chpId);
	if(chpId > 0){
			$j.ajax({
				data : 'courseHomePageId='+chpId,
				url : '/enterprise/Enterprise/getFeaturedArticles',
				type : "POST",
					beforeSend: function() {
		            $j('#udpateTablle').html('Loading ....');
	        	},
			success: function(res){
				$j('#udpateTablle').html(res);
			} 
		});
	}
}
</script>
<?php $this->load->view('common/footerNew'); ?>
<script type="text/javascript">
$j(function() {
   initSeoChp('featuredArticle');
});
</script>