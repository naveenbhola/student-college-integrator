<?php $this->load->view('common/calendardiv'); ?>

<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('header'); ?>"></script>
<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('CalendarPopup'); ?>"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
				<!-- LABJs utility loaded in parallel-->
<script type="text/javascript">
  (function(g,b,d){var c=b.head||b.getElementsByTagName("head"),D="readyState",E="onreadystatechange",F="DOMContentLoaded",G="addEventListener",H=setTimeout;
  function f(){ loadJsFilesInParallel();}
  H(function(){if("item"in c){if(!c[0]){H(arguments.callee,25);return}c=c[0]}var a=b.createElement("script"),e=false;a.onload=a[E]=function(){if((a[D]&&a[D]!=="complete"&&a[D]!=="loaded")||e){return false}a.onload=a[E]=null;e=true;f()};
  a.src="/public/js/LAB.min.js";c.insertBefore(a,c.firstChild)},0);
  if(b[D]==null&&b[G]){b[D]="loading";b[G](F,d=function(){b.removeEventListener(F,d,false);b[D]="complete"},false)}})(this,document);
</script>

<script type="text/javascript">
  function loadJsFilesInParallel(){
	$LAB
	.script("//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("AutoSuggestor"); ?>")
	.wait(function(){
		<?php
    $autosuggestorConfigHomepage = Modules::run('common/GlobalShiksha/getHeaderSearchConfig', array('instituteSearchReviewsCMS')); 
                                foreach($autosuggestorConfigHomepage as $autosuggestorConfig) {?>
                                    initializeAutoSuggestorInstanceSearchV2('<?php echo json_encode($autosuggestorConfig["options"]);?>');                              
                                <?php } ?>
		if(SHOW_AUTOSUGGESTOR_JS){
		autosuggestorInstanceCheck = setInterval(function(){
			var fileLoaded = false;
			try{
				var aso = new AutoSuggestor();
				fileLoaded = true;
			} catch(e) {
				fileLoaded = false;
			}
			
			if(fileLoaded){
				clearInterval(autosuggestorInstanceCheck);
				if(typeof(initializeAutoSuggestorInstance) == 'function') {
                    initializeAutoSuggestorInstance();
                }
				if(typeof(initializeAutoSuggestorInstanceAlt) == 'function') {
                    initializeAutoSuggestorInstanceAlt();
                }
			}
                
		},1000);
	  }
	});
  }
</script>
				
<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('common'); ?>"></script>

				
<style>
		ul.suggestion-box{ border:1px solid #b3b3b3; position:absolute;background:#fff; z-index:10; top:36px; box-sizing:border-box;-moz-box-sizing:border-box; -webkit-box-sizing:border-box;}
		ul.suggestion-box li{margin-bottom:0; border-bottom:1px solid #e5e5e5;color:#121212; padding:8px 0 2px 6px; font-size: 0.8em}
		ul.suggestion-box li:last-child{border-bottom:none;}
		.suggestion-box{-webkit-box-shadow: 0px 0px 2px 0px #b3b3b3; -moz-box-shadow: 0px 0px 2px 0px #b3b3b3; box-shadow: 0px 0px 2px 0px #b3b3b3; padding:10px; font-family:Tahoma, Geneva, sans-serif; font-size:12px; border:1px solid #dfdfdf; background:#FFF; text-align:left}
		.suggestion-box-active-option , .suggestion-box-normal-option  {padding:4px 5px; background-color:#dff1ff; color:#000000; line-height:17px}
		.suggestion-box-normal-option {background:#FFF;}
				
				
		ul.approve-list{float:left; padding-left:0; width:100%;}
		ul.approve-list li{list-style:none; width: 100%; float: left;}
		ul.approve-list li span{float:left;}
</style>

<?php

$ratingText = array(1=>'Poor',2=>'Below Average',3=>'Average',4=>'Above Average',5=>'Excellent')
?>
<?php
	$displaySubFilter = "none";
	if($statusFilter == "Later" || $statusFilter == 'Rejected'){
		$displaySubFilter = "";
	}
?>
	<div id="review_page_main" class="container">
    
    	<div class="review-section">
        	<div class="review-head">
            	<div class="review-title flLt">College Reviews: <?php echo $num_rows; ?> &nbsp;
            		<a href="javascript:void(0)" onclick=toggleFilterDiv();>Filter <i id="minusplus-icon" class="review-sprite plus-icon"></i></a>
            	</div>
				<input type="hidden" id="start" name="start" value="" />
				<input type="hidden" id="startUnmapped" name="startUnmapped" value="<?php echo $startUnmapped;?>" />
				
				<form id="reviewForm" method="post" autocomplete="off" accept-charset="utf-8" action="/CAEnterprise/CampusAmbassadorEnterprise/getCollegeReviews" onsubmit="return false;" name="reviewForm">
	    			<input type='hidden' name='email_status_case' value=0 id="email_status_case">
                	<select id="sortReviews" name='sortReviews' class="flRt sort-width" onchange="sortReview()";>
		        		<option value="">Sort</option>
                		<option value="New first">New first</option>
						<option value="Old first">Old first</option>
						<option value="Quality_Score_Desc">Highest Quality Score</option>
						<option value="Quality_Score_Asc">Lowest Quality Score</option>
                	</select>
					<div class="college-review-sec clear-width" id="collegeReviewFilter" style= "display:none;">
            			<div style="position:relative;" class="collge-status flLt">
                			<ul>
			 					<li style="margin-right:15px;">
                        			<label>Category</label>
                            		<select id="categoryFilter" name ="categoryFilter" class="status-select">
								        <option value="All">All</option>
								        <?php foreach ($streamList as $key => $stream) { ?>
										<option value="<?php echo $stream['id'] ?>"><?php echo $stream['name'] ?></option>
								        <?php } ?>
                            		</select>
                        		</li> 
			  
                    	<li style="margin-right:10px;">
                        <label>Status</label>
                            <select id="statusFilter" name ="statusFilter" class="status-select" style="margin-bottom:10px;" onchange="displayReason(this.value);">
                            	<?php if(CR_MOD_SOLR_FLAG){?>
									<option value="All">All</option>
								<?php } ?>
                            	<option value="Pending">Pending</option>
                                <option value="Published">Published</option>
                                <option value="Unpublished">Unpublished</option>
                                <option value="Later">Later</option>
                                <option value="Unverified">Unverified</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </li>

                        <li style="margin-right:10px; display:<?=$displaySubFilter?>;" id="reviewReason">
                       		<label>Reason</label>
                            <select id="reasonFilter" name ="reasonFilter" class="status-select" style="margin-bottom:10px;">
								<?php if(!empty($reasons)){?>
								<option value="All">All</option>
								<?php foreach($reasons as $key=>$value){ ?>
								<option value='<?php echo $value['id']; ?>'><?php echo $value['reason']; ?></option>
								<?php } ?>
								<?php } ?>
                            </select>
                        </li>
			
						<li style="margin-right:10px;">
							<label>Type</label>
                            <select name= "typeFilter" id="typeFilter" class="status-select" style="margin-bottom:10px;">
								<option value="All">All</option>
                            	<option value="Mapped-colleges">Mapped-colleges</option>
                                <option value="UnMapped-colleges">UnMapped-colleges</option>
                            </select>
                        </li>

                        <li style="margin-right:10px;" id="reviewSource">
                        	<label>Source</label>
                            <select id="sourceFilter" name ="sourceFilter" class="status-select" style="margin-bottom:10px;">
								<?php if(!empty($reviewSource)){?>
								<option value="All">All</option>
								<?php foreach($reviewSource as $key=>$value){ ?>
								<option value='<?php echo $value; ?>'><?php echo $value; ?></option>
								<?php } ?>
								<?php } ?>
                            </select>
                        </li>

                        <br>
						<li style="margin-right:10px;">
							<div style="display:inline;">
                        		<label>College name</label>
								<input type="hidden" style="" id="instituteName" name="instituteName" id="instituteName" value="<?=$_POST['instituteName'];?>" />
								<input id="keywordSuggest" type="search" class="collge-text" name="keyword" value="<?=$_POST['keyword']?>"  minlength="1" style="width:200px;"/>
								<ul id="suggestions_container" class="suggestion-box" style="display: none;position:absolute;top:89px;width:auto;left:106px;"></ul>
                        	</div>
                        </li>

                        <li style="/*margin:0 10px 10px 0px;width: 100%; display: inline-block;*/">
							<div style="display:inline;">
                        		<label>Phone</label>
								<input id="phone_search" type="text" class="collge-text" name="phone_search" value="<?=$_POST['phone_search']?>" maxlength="20" style="width:200px;"/>
                        	</div>
                        </li>

                        <br>
                        <?php if($showModeratedByFilter) { ?>

                        <li style='display: inline; line-height: 4;'>
                        	<label>Moderated By</label>
                        	<div class="customSelect alignBox">
							    <div style="position:static;height:30px;display:block;">
							        <input type="text" id="moderatedBySelect" placeholder="Select Moderated By" onclick="$j('.mod-lst-wrapper').show();" style="border: 1px solid #868686; padding: 6px;" />
							        <em class="pointerDown"></em>
							    </div>
							    
							    <div class="mod-lst-wrapper" id="mod_list">
							     	<ul class="mod-drpdwn-ul mod-lst">
							     		<?php
		                            		foreach ($collegeReviewModerators as $userid => $emailId) {
		                            	?>
		                            	<li class="clear-width">
											<div class="Customcheckbox">
												<input id="mod_<?php echo $userid; ?>" name="moderator_list[]" <?php if(in_array($emailId, $_POST['moderator_list'])){ echo 'checked'; } ?> type="checkbox" value="<?php echo $emailId; ?>" />
												<label for="mod_<?php echo $userid; ?>"><?php echo $emailId;?></label>
											</div>
										</li>
		                            	<?php
		                            		}
		                            	?>
									</ul>
							    </div>
							</div>

                        </li>

                        <?php } ?>

                        <br>

                        <li style="margin:0 10px 10px 0px;width: 100%; display: inline-block;">
							<div style="display:inline;">
                        		<label style="width: 150px;display: inline-block;">Date Posted</label>
								From
								<input style="border:1px solid #ddd; padding:6px;" type="text" placeholder="dd/mm/yyyy" readonly="readonly" id="posted_timeRangeFrom" name="posted_timeRangeFrom" onclick="selectTimeRangeFrom(this,'posted');" value="<?=$_POST['posted_timeRangeFrom']?>" />
                        		<img src="/public/images/cal-icn.gif" id="posted_timeRangeFromImage" style="vertical-align: middle; cursor:pointer;" onclick="selectTimeRangeFrom(this,'posted');" />
								To
								<input style="border:1px solid #ddd; padding:6px;" type="text" placeholder="dd/mm/yyyy" readonly="readonly" id="posted_timeRangeTo" name="posted_timeRangeTo" onclick="selectTimeRangeTo(this,'posted');" value="<?=$_POST['posted_timeRangeTo']?>" />
                        		<img src="/public/images/cal-icn.gif" id="posted_timeRangeToImage" style="vertical-align: middle; cursor:pointer;" onclick="selectTimeRangeTo(this,'posted');" />
                        	</div>
                        </li>
                        
                        <br>

                        <li style="margin:0 10px 10px 0px;width: 100%; display: inline-block;">
							<div style="display:inline;">
                        		<label style="width: 150px;display: inline-block;">Last Moderated Date</label>
								From
								<input  style="border:1px solid #ddd; padding:6px;" type="text" placeholder="dd/mm/yyyy" readonly="readonly" id="moderated_timeRangeFrom" name="moderated_timeRangeFrom" onclick="selectTimeRangeFrom(this,'moderated');" value="<?=$_POST['moderated_timeRangeFrom']?>" />
                        		<img src="/public/images/cal-icn.gif" id="moderated_timeRangeFromImage" style="vertical-align: middle; cursor:pointer;" onclick="selectTimeRangeFrom(this,'moderated');" />
								To
								<input style="border:1px solid #ddd; padding:6px;" type="text" placeholder="dd/mm/yyyy" readonly="readonly" id="moderated_timeRangeTo" name="moderated_timeRangeTo" onclick="selectTimeRangeTo(this,'moderated');" value="<?=$_POST['moderated_timeRangeTo']?>" />
                        		<img src="/public/images/cal-icn.gif" id="moderated_timeRangeToImage" style="vertical-align: middle; cursor:pointer;" onclick="selectTimeRangeTo(this,'moderated');" />
                        	</div>
                        </li>

                        <br>

                        <li>
                        	<a href="javascript:void(0);" class="orange-btn" style="padding:5px 8px; line-height: 3;" onclick="searchCollegeReview();">Search College Reviews</a>
						</li>
                    </ul>
                    
                </div>
		
				<p class="or-tag" style="width:500px !important;">OR</p>
				<div class="search-by-email-sec" style="text-align:left !important;">
		    		<label style="font-size:14px;">Search by Email/Mobile:</label>
		    		<input id='email' name='email' type="text" class="collge-text" value=<?=$email?>>
		    		<a onclick="searchCollegeReviewByEmail();" style="padding: 5px 8px; margin: 5px 8px 5px 5px;" class="orange-btn" href="javascript:void(0);">Search College Reviews</a>

		    		<?php if($statusFilter == "Later" && $showAllReviewRejectButton){?>
		    			<input type="hidden" style="" id="rejectRevId" name="rejectRevId"  value="0" />
		    			<?php if($num_rows>0){ ?>
		    			<a id="rejectAllRev" onclick="markAllReviewRejected();" style="padding: 5px 8px; margin: 5px 8px 5px 5px;" class="orange-btn" href="javascript:void(0);">Reject <?php echo $num_rows; ?> College Reviews (Max 1000)</a>
		    			<?php } ?>
		    		<?php }?>

				</div>
				<input type = 'hidden' name = 'checkSearchCall' value = ''/>

            </div>

	    </form>
	    
	    <div id="reviewDisplay">
	    <?php $this->load->view('CAEnterprise/college_reviews') ?>
	    </div>   

	    <?php
	    /*
		if(count($result)<=0){ ?>
			<div class="review-details clear-width" style="text-align:center;">
			<b>No Reviews Available</b>
			</div>
		<?php } */
	    ?>
    
	  
<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/stringDiff/diff_match_patch_uncompressed.js"></script>	
	
<script type="text/javascript">        
	var dmp = new diff_match_patch();
	$(function(){
	  $j('#start').val('<?=$start+$count;?>');
	  $('#sortReviews').val('<?php echo $sortReviews;?>')
	  $('#statusFilter').val('<?php echo $statusFilter;?>')
	  $('#instituteName').val('<?php echo $instituteName;?>')
	  $('#typeFilter').val('<?php echo $typeFilter;?>')
	  $('#categoryFilter').val('<?php echo $categoryFilter;?>')
	  $ ('#reasonFilter').val('<?php echo $reasonFilter;?>')
	  $ ('#sourceFilter').val('<?php echo $sourceFilter;?>')



	  $condition = "<?php echo $openFilterTab;?>";
	  $openFilterTab = false;
	  if ($condition != "") {
	    $openFilterTab = true;  
	  }
	  if ($openFilterTab || $('#statusFilter').val() != 'Pending' || $('#typeFilter').val() != 'All'  || $('#instituteName').val() != '' ||  $('#categoryFilter').val() != 'All' )
	  {
		toggleFilterDiv();
	   }
	  
	});
	
	function submitFilterManually(status,instituteName,instituteId,type,condition){
	  $('#statusFilter').val(status);
	  $('#keywordSuggest').val(instituteName);
	  $('#instituteName').val(instituteId);
	  $('#typeFilter').val(type);
	  if (condition != undefined) {
	      $("#email").val("");
	  }

	  if (document.getElementById('keywordSuggest').value == ""){
		
			document.getElementById('instituteName').value = "";
		}
	  document.getElementById('reviewForm').submit();
	    
	}
	
	var flag=0;
	function toggleFilterDiv() {
	        if (flag == 0){
			$('#collegeReviewFilter').slideDown();
			$('#minusplus-icon').attr('class','review-sprite minus-icon');
			flag = 1;
   		 }else{
			$('#minusplus-icon').attr('class','review-sprite plus-icon');
			$('#collegeReviewFilter').slideUp();
			flag = 0;
    		}
		
	}
	
	
	function hideFilter(id) {
		document.getElementById(id).style.display = 'none';
	}

		
	function searchCollegeReview(){
		
		if (document.getElementById('keywordSuggest').value == ""){
			document.getElementById('instituteName').value = "";
		}
		$("input[name='checkSearchCall']").val("normal");
		var mobile = document.getElementById('phone_search').value;
		
		if(mobile != ''){
			var mobileCheck = validateMobileInteger(mobile,'mobile number',20,10,true);
			if(mobileCheck != true){
				alert('Please enter a valid Phone number');
	    		return false;
			}
		}
		
		document.getElementById('reviewForm').submit();	
		
	}
	
	function searchCollegeReviewByEmail(){
	  if (document.getElementById('email').value == '') {
	    alert('Please enter an email id.');
	    return false;
	  }

	  //detect input is email or number
	  //check for number
	   var inputType = isNaN(document.getElementById('email').value);
		if(inputType){
			  if (!validateEmail(document.getElementById('email').value)) {
			    alert('Please enter a valid email id');
			    return false;
			  }			
		}else{
		  	var mobileCheck = validateMobileInteger(document.getElementById('email').value,'mobile number',20,10,true);
			if(mobileCheck != true){
				alert('Please enter a valid Phone number');
				return false;
			}			
		}
	  

	  $("input[name='checkSearchCall']").val("email");
	  document.getElementById('reviewForm').submit();
	}
	
	function validateEmail(value) {
	  if(value) {
	    var filter = /^((([a-z]|[A-Z]|[0-9]|\-|_)+(\.([a-z]|[A-Z]|[0-9]|\-|_)+)*)@((((([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.))*([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/
	      if(!filter.test(value)) {
			return false;
	      }
	  }
	  return true;
	}
	
	function hideReviewEditForm(reviewId,reviewType)
	{
	    document.getElementById(reviewType+'editReviewDiv_'+reviewId).style.display = 'none';
	    document.getElementById(reviewType+'Description_'+reviewId).style.display='block';
	}

	function editReviewDescription(reviewId,reviewType)
	{
	    document.getElementById(reviewType+'editReviewDiv_'+reviewId).style.display='block';
	    document.getElementById(reviewType+'Description_'+reviewId).style.display='none';
	}

	function editReviewTitle(reviewId){
		document.getElementById('editTitleReview_'+reviewId).style.display='block';
	    document.getElementById('reviewTitleDiv_'+reviewId).style.display='none';
	}

	
	function EditReviewDescription(reviewId,reviewType)
	{
	  var reviewDesc= document.getElementById(reviewType+'reviewText'+reviewId).value;
	  jQuery.ajax({
	  url: '/CAEnterprise/CampusAmbassadorEnterprise/editReviewByModerator',
	  type: "POST",
	  data: { 
	    'reviewId': reviewId,
	    'reviewType': reviewType,
	    'reviewDesc':reviewDesc
	  },
	  success: function(result){
	      //location.reload();
	      document.getElementById(reviewType+'editReviewDiv_'+reviewId).style.display='none';
	      document.getElementById(reviewType+'DescriptionTextField_'+reviewId).innerHTML = document.getElementById(reviewType+'reviewText'+reviewId).value;
	      document.getElementById(reviewType+'Description_'+reviewId).style.display='block';
	 
	  }
	  });   
	}

	function sortReview(){
		
		$val = '<?php echo $checkForSearchCall ?>';
		if($.trim($val) == "" || $.trim($val) == "normal")
		{
			searchCollegeReview();
		}else{
			searchCollegeReviewByEmail();
		}
	
	}
	
	
</script>

<script>try{ overlayViewsArray.push(new Array('network/commonOverlay','addRequestOverlay')); }catch(e){ }</script>
                <script language="javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('user'); ?>"></script>
                <script language="javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('lazyload'); ?>"></script>
                <script language="javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('myShiksha'); ?>"></script>


<script>
 if(typeof(handleClickForAutoSuggestor) == "function") {
	if (window.addEventListener){
		document.addEventListener('click', handleClickForAutoSuggestor, false); 
	} else if (window.attachEvent){
		document.attachEvent('onclick', handleClickForAutoSuggestor);
	}
 }
 
 if(typeof(handleClickForAutoSuggestorAlt) == "function") {
	if (window.addEventListener){
		document.addEventListener('click', handleClickForAutoSuggestorAlt, false); 
	} else if (window.attachEvent){
		document.attachEvent('onclick', handleClickForAutoSuggestorAlt);
	}
 }
 
 

 
 
</script>


<script>
    /*
     For Institute Suggestor
    */

    var autoSuggestorInstanceCompare = null;
    //var isMobile = true;
    function initializeAutoSuggestorInstancesCompare(){
        
        autoSuggestorInstanceArray.autoSuggestorInstituteSearchReviewsCMS.callBackFunctionOnMouseClick = handleAutoSuggestorMouseClickCompare;
      
        autoSuggestorInstanceArray.autoSuggestorInstituteSearchReviewsCMS.callBackFunctionOnEnterPressed = handleAutoSuggestorEnterPressedCompare;
    
        autoSuggestorInstanceArray.autoSuggestorInstituteSearchReviewsCMS.callBackFunctionOnRightKeyPressed = handleAutoSuggestorRightKeyPressedCompare;
       
    }
    
    var keywordEnteredByUserCompare = '';
    function handleInputKeysForInstituteSuggestorCompare(e){
       
        window.jQuery('#keywordSuggest').change(function(){
            keywordEnteredByUserCompare = $j('#keywordSuggest').val();        
        });
        
        if(autoSuggestorInstanceCompare ){
            autoSuggestorInstanceCompare.handleInputKeys(e);
        }
    }
    
    function handleClickForAutoSuggestorCompare(){
        if(autoSuggestorInstanceArray.autoSuggestorInstanceInstitute){ 
            autoSuggestorInstanceArray.autoSuggestorInstanceInstitute.hideSuggestionContainer();
        }
    }
               
    function handleAutoSuggestorMouseClickCompare(callBackData){
    	if(callBackData && autoSuggestorInstanceArray.autoSuggestorInstituteSearchReviewsCMS){
    	    autoSuggestorInstanceArray.autoSuggestorInstituteSearchReviewsCMS.hideSuggestionContainer();
            instituteSelected(callBackData['id'],callBackData['sp']);
        }
    }
    
    function handleAutoSuggestorRightKeyPressedCompare(callBackData){
        //autoSuggestorInstance.hideSuggestionContainer(); 
    }
    
    function handleAutoSuggestorEnterPressedCompare(callBackData){
         if(callBackData && autoSuggestorInstanceArray.autoSuggestorInstanceInstitute){
            autoSuggestorInstanceArray.autoSuggestorInstanceInstitute.hideSuggestionContainer();
            instituteSelected(callBackData['id'],callBackData['sp']);
        }
    }
    function instituteSelected(instId,instTitle){
	document.getElementById('instituteName').value = instId;
    }

window.onload=function(){
	if(typeof(initializeAutoSuggestorInstancesCompare) == "function") {
			initializeAutoSuggestorInstancesCompare(); //For initiating AutoSuggestor Instance
		}
		//Event listener for hiding dropdown suggestions when user clicks outside the suggestion container
		if(typeof(handleClickForAutoSuggestorCompare) == "function") {
		    if(window.addEventListener){
			document.addEventListener('click', handleClickForAutoSuggestorCompare, false);
		    } else if (window.attachEvent){
			document.attachEvent('onclick', handleClickForAutoSuggestorCompare);
		    }
		}
		if(typeof(handleClickForAutoSuggestorAlt) == "function") {
			if (window.addEventListener){
				document.addEventListener('click', handleClickForAutoSuggestorAlt, false); 
			} else if (window.attachEvent){
				document.attachEvent('onclick', handleClickForAutoSuggestorAlt);
			}
		}
};

</script>

<script>
    
    function doReviewPagination(){
      	start = $j('#start').val();
      	$j('#showMoreReview'+start).hide();
      	statusFilter = $j('#statusFilter').val();
      	reasonFilter = $j('#reasonFilter').val();
      	keywordSuggest = $j('#keywordSuggest').val();
      	instituteName = $j('#instituteName').val();
      	typeFilter = $j('#typeFilter').val();
      	categoryFilter=$j('#categoryFilter').val();
      	// startUnmapped = $j('#startUnmapped').val();
      	sortReviews = $j('#sortReviews').val();
      	sourceFilter = $j('#sourceFilter').val();
      	phone_search = $j('#phone_search').val();
      	moderator_list = [];
        $j("input[name='moderator_list[]']").each(function() { 
            if(this.checked){
                moderator_list.push(this.value);
            }
        });
      	posted_timeRangeFrom = $j("#posted_timeRangeFrom").val();
      	posted_timeRangeTo = $j("#posted_timeRangeTo").val();
      	moderated_timeRangeFrom = $j("#moderated_timeRangeFrom").val();
      	moderated_timeRangeTo = $j("#moderated_timeRangeTo").val();
      
        jQuery.ajax({
	      	url: '/CAEnterprise/CampusAmbassadorEnterprise/getCollegeReviews',
	      	type: "POST",
	      	data: { 
			  	'statusFilter': statusFilter,
			  	'reasonFilter':reasonFilter,
			  	'instituteName': instituteName,
		        'typeFilter':typeFilter,
		        'keywordSuggest':keywordSuggest,
			  	'categoryFilter':categoryFilter,
		        'start':start,
		        'startUnmapped':startUnmapped,
			  	'sortReviews':sortReviews,
			  	'sourceFilter':sourceFilter,
			  	'phone_search':phone_search,
			  	'moderator_list':moderator_list,
			  	'posted_timeRangeFrom':posted_timeRangeFrom,
			  	'posted_timeRangeTo':posted_timeRangeTo,
			  	'moderated_timeRangeFrom':moderated_timeRangeFrom,
			  	'moderated_timeRangeTo':moderated_timeRangeTo
      		},
      		success: function(result){ 
				$j('#start').val(parseInt(start)+parseInt('<?=$count?>'));	
				$j('#showMoreReview'+start).hide();
				htmlVal = $j('#reviewDisplay').html();
				$j('#reviewDisplay').html(htmlVal+result);
      		},
      		error: function(){
      			$j('#showMoreReview'+start).show();
      		}
  		});
		
	}

	function selectTimeRangeFrom(obj, context) {
		var calendarMain = new CalendarPopup('calendardiv');
		disDate = null;
		isresponseViewer = 1;
    	frmdisDate = new Date();
		var id = obj.id;
    	calendarMain.select($j('#'+context+'_timeRangeFrom')[0], id, 'dd/mm/yyyy');
	}

	function selectTimeRangeTo(obj, context) {
		var calendarMain = new CalendarPopup('calendardiv');
		var mindate = $j('#'+context+'_timeRangeFrom').val();
        var dateStr = mindate.split("/");
        var passedDate = dateStr[0]%32;
        var passedMonth = dateStr[1]%13;
        var passedYear = dateStr[2];
        disDate = new Date(passedYear,passedMonth-1,passedDate);
        var id = obj.id;
        frmdisDate = new Date();
        isresponseViewer = 1;
    	calendarMain.select($j('#'+context+'_timeRangeTo')[0], id, 'dd/mm/yyyy');
	}

	$(document).ready(function () {
        $j("body").on("click", function(e){
        	if(!$j(e.target).is("#moderatedBySelect,#mod_list *")){
        		$j("#mod_list").hide();
        	}       
    	});
    	
    	$('input#moderatedBySelect').keydown(function(e) {
		   	e.preventDefault();
		   	return false;
		});

		$j('input[type="checkbox"]').click(function(event) {
			<?php if(!CR_MOD_SOLR_FLAG){?>
	        if($j("input[name='moderator_list[]']:checked").length > 1) { 
	        	alert("Only one moderator can be selected");
	        	event.preventDefault();
	        	return false;
	        }
	        <?php } ?>
		});

    });

</script>             

