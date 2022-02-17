<?php 	
	$this->load->view('/mcommon/header',array('flag_off_home'=>true));
?>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("customCityList"); ?>"></script>
<div id="head-sep"></div>
<div id="head-title">
	<h4 style="padding:5px 0">Request E-brochure</h4>
    <span>&nbsp;</span>
</div>
<?php global $user_logged_in;global $logged_in_usermobile;
	global $logged_in_user_name;
	global $logged_in_first_name;
	global $logged_in_last_name; 
	global $logged_in_user_email;global $logged_in_usermobile;
?>
<div id="content-wrap">
	<div id="login-cont"><?php $hidden = array('currentUrl' => $current_url, 
			'referralUrl' => $referral_url,
			'courseArray'=>$courseArray,
			'user_email'=>$logged_in_user_email,
			'from_where'=>$from_where
			); $attributes = array("autocomplete" => "off", 'accept-charset' => 'utf-8', 'onsubmit' => 'return validateForm()');?>
	<?=form_open('muser/MobileUser/request_validation',$attributes,$hidden)?>
    	<ul>	
        <li><?php 
	if($user_logged_in!="false"){
		$attributes = array( 'name'=> 'user_first_name', 'id'=> 'user_first_name', 'value'=>$logged_in_first_name,'class'=>"login-field", 'onBlur'=>"checkEmpty(this.id)");}
	else
		$attributes = array( 'name'=> 'user_first_name', 'id'=> 'user_first_name', 'value'=>set_value('user_first_name'),'class'=>"login-field",'maxlength'=>"50",'minlength'=>"1", 'onBlur'=>"checkEmpty(this.id)");
	?>	
		<label><?=form_label('First Name', 'user_first_name')?></label>
                <div class="field-cont">
			<?=form_input($attributes)?>
			<div style="color:red;font-size:13px;" id ="error_user_first_name"><?php $err=form_error('user_first_name');echo strip_tags($err);?> </div>
    	        </div>
	 </li>
	<li><?php 
 		        if($user_logged_in!="false"){ 
 		                $attributes = array( 'name'=> 'user_last_name', 'id'=> 'user_last_name', 'value'=>$logged_in_last_name,'class'=>"login-field", 'onBlur'=>"checkEmpty(this.id)");} 
 		        else 
 		                $attributes = array( 'name'=> 'user_last_name', 'id'=> 'user_last_name', 'value'=>set_value('user_last_name'),'class'=>"login-field",'maxlength'=>"50",'minlength'=>"1", 'onBlur'=>"checkEmpty(this.id)"); 
 		        ?> 
 		                <label><?=form_label('Last Name', 'user_last_name')?></label> 
 		                <div class="field-cont"> 
 		                        <?=form_input($attributes)?> 
 		        <div style="color:red;font-size:13px;" id="error_user_last_name"><?php $err=form_error('user_last_name');echo strip_tags($err);?> </div> 
 	</div>
 	</li> 
 	<li><?php if($user_logged_in!="false")$attributes = array('name'=> 'user_email','type'=>'email','id'=> 'user_email','value'=>$logged_in_user_email,'disabled'=>"disabled",'class'=>"login-field"); else $attributes = array('pattern'=>"^(?:[-+âˆ¼=!#$%&amp;'*/?\^`{|}\w]+)(?:\.[-+âˆ¼=!#$%&amp;'*/?\^`{|}\w]+)*@(?:[a-zA-Z0-9][-a-zA-Z0-9]*[a-zA-Z0-9]\.)+[a-zA-Z]{2,6}$",'required'=>'','name'=> 'user_email','id'=> 'user_email','value'=>set_value('user_email'),'class'=>"login-field",'maxlength'=>"125", 'onBlur'=>"checkEmpty(this.id)");
		?>
            	<label><?=form_label('E-mail', 'user_email')?></label>
                <div class="field-cont">
			<?=form_email($attributes)?>
			<div style="color:red;font-size:13px;" id="error_user_email"><?php $err=form_error('user_email');echo strip_tags($err);?></div>
              </div>
           </li>
		<?php $courseAtrr_decoded=(base64_decode($courseArray,false));
		      $courseAtrr_unserialized=(unserialize($courseAtrr_decoded));
		?>		
	   <li><?php if(!empty($courseAtrr_unserialized)){?>
		<label><?=form_label('Course of Interest', 'list')?></label>
			<?php
			// Loading Listing builder and repository..
			$this->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$courseRepository = $listingBuilder->getCourseRepository();
			
			$courseIdsArray = array();
			foreach($courseAtrr_unserialized as $name => $value){
				$value = explode('*', $value);
				$currentCourseId = $value[0];
				if(!in_array($currentCourseId, $courseIdsArray)) {
					$courseIdsArray[] = $currentCourseId;
				}
				
			}
			
			// Getting Courses info..
			$coursesObjAray = $courseRepository->findMultiple($courseIdsArray);
			$localityArray = array();
			$courseListOptions = array("0" => "Please Select Course");
			foreach($coursesObjAray as $course){
				$instituteId = $course->getInstId();
				
				$courseListOptions[$course->getId()] = html_escape($course->getName());
				$localityArray[$course->getId()] = getLocationsCityWise($course->getLocations());
			}
			
			echo form_dropdown('list', $courseListOptions , $preselected_course_id," id = 'list' class='select-field' onChange='reloadLocations(".$instituteId.", this.options[this.selectedIndex].value);'");
			?><div style="color:red;font-size:13px;" id="error_list"><?php $err=form_error('list');echo strip_tags($err);?></div>
          <?php }?> </li>
	   
	  <div id="city-locality-div"></div>
	   
		<li><?php if($user_logged_in!="false")$attributes = array( "pattern"=>"\d{10}",'type'=>'mobile',"required"=>"",'name'=> 'user_mobile','value'=>$logged_in_usermobile,'class'=>"login-field",'maxlength'=>"10", 'id'=>"user_mobile");
		else $attributes = array( "pattern"=>"\d{10}","required"=>"",'name'=> 'user_mobile','value'=>set_value('user_mobile'),'class'=>"login-field",'maxlength'=>"10", 'id'=>"user_mobile", 'onBlur'=>"checkEmpty(this.id)");?>
            	<label><?=form_label('Mobile', 'user_mobile')?></label>
                <div class="field-cont">
			<?=form_mobile($attributes)?>
			<div style="color:red;font-size:13px;" id="error_user_mobile"><?php $err=form_error('user_mobile');echo strip_tags($err);?></div>
              	</div>
           </li>
           <li>
            <div style="color:red;font-size:13px;"><?php if($show_error=="User already exists."){echo $show_error." Please  ";?><a href="/muser/MobileUser/login/">Login </a><?php }?></div>
           </li><?php $attributes = array( 'name'=> 'login','value'=>'Request E-brochure','class' => 'orange-button');?>
            <li style="padding-top:5px"><?=form_submit($attributes)?> &nbsp; &nbsp; <strong>
		<?php if($from_where == 'SEARCH'): ?><br/><a style="position:relative;top:10px;" href="<?php echo url_base64_decode($current_url);?>">Back to Search Results</a><?php else:?><a href="javascript: window.history.go(-1)"> Cancel</a><?php endif;?></strong></li>
	
    </ul>    
   <div class="clearFix"></div>	
    </div>
<?php $this->load->view('/mcommon/footer');?>
<script>
var listings_with_localities = <?php echo $listings_with_localities; ?>;
var localityArray = <?=json_encode($localityArray)?>;	
window.jQuery.each(localityArray,function(index,element){
	custom_localities[index] = element;
});

var widget = 'mobileResponseForm';
var institute_id = <?=$instituteId?>;
function reloadLocations(institute_id, course_id){
	if (course_id == 0) {
		window.jQuery('#city-locality-div').hide();
		return ;
	}
	
	window.jQuery('#error_list').html("");
		
	var tempHTML = generatePreferredCityLocalityDropdowns(institute_id, widget, course_id, "new");
	window.jQuery('#city-locality-div').html(tempHTML);
	$city = window.jQuery('#preferred_city_category_'+widget+institute_id);
	$city.trigger('change');
}

function generatePreferredCityLocalityDropdowns(id, widget, course_id, style)
{    
	listing_key = '';
	if(typeof(widget) == 'undefined'){
		widget = "";
	}
	if(typeof(course_id) == 'undefined'){
		course_id = 0;
	}
	if(typeof(style) == "undefined"){
		style = "new";
	}
	if(typeof(listings_with_localities) == 'undefined')
	{
		return '';
	}
	
	var compared_listing_id = "";
	
	for(i in listings_with_localities)
	{	    
	    // For ticket #2289 requirement..
	    if (i == "COURSE_WISE_CUSTOMIZED_LOCATIONS") {		
		compared_listing_id = course_id;
	    } else {		
		compared_listing_id = id;
	    }
	    
	    var listing_ids = listings_with_localities[i];
	    for(var j=0;j<=listing_ids.length;j++)
	    {  
		    if(listing_ids[j] == compared_listing_id)
		    {
			    listing_key = i;
		    }
	    }	    
	}

	var locality_html = '';	
	if(listing_key)
	{
		locality_html = getLocalityHTML(id,listing_key,widget,style, course_id);
	}else{
		if(course_id){
			locality_html = getLocalityHTML(id,course_id,widget,style);
		}
	}	
	
	return locality_html;
}

function getLocalityHTML(id,listing_key,widget,style, listing_id)
{    
	if(typeof(listing_id) == "undefined")
	{
	    listing_id = '';
	}
	
	locality_html = '<li><label><label for="city">Preferred City</label></label><select name="preferred_city_dropdown" id="preferred_city_category_'+widget+id+'" unrestricted="true"  caption="preferred city" onchange=\'populateLocalities(this, "category_'+widget+id+'", "'+listing_key+'", "'+style+'", "'+listing_id+'");\' class="select-field">'+'';
	var locality_html_temp = '';
	var listing_localities = custom_localities[listing_key];
	var count = 0;
	
	// For ticket #2289 requirement..
	if (listing_key == "COURSE_WISE_CUSTOMIZED_LOCATIONS" && listing_id != "") {
		    for (var city_name in listing_localities[listing_id]) {
			if (listing_localities[listing_id].hasOwnProperty(city_name)) {
			  count++;
			  if (preselected_city == city_name) {
				locality_html_temp += "<option value='"+city_name+"' selected='selected'>"+city_name+"</option>";
			  } else {
				locality_html_temp += "<option value='"+city_name+"'>"+city_name+"</option>";
			  }
			}
		    }
	} else {
		// This is for the older customized city - localities solution..
		for(i in listing_localities)
		{
			count++;
			
			if (preselected_city == i) {
				var defaultSelectedText = "selected='selected'";
			}else {
				var defaultSelectedText = "";
			}	
			if(typeof(listing_localities[i].name) == "undefined"){
				locality_html_temp += "<option value='"+i+"' "+defaultSelectedText+">"+i+"</option>";
			}else{
				locality_html_temp += "<option value='"+i+"' "+defaultSelectedText+">"+listing_localities[i].name+"</option>";
			}
		}
	}
	
	
	if(count>1){
		locality_html += '<option value="0">Preferred City</option>';		
	}	
	
	locality_html += locality_html_temp;
	locality_html += '</select>';
	locality_html += '<div style="color:red;font-size:13px;display:none;" id="error_preferred_city_category_'+widget+id+'" >The Preferred City is required.</div>';
	locality_html += '</li>';
	
	var locality_error_div = "error_preferred_locality_category_"+widget+id;
	
	locality_html += '<li id="preferred_locality_container"><label><label for="city">Preferred Locality</label></label><select name="preferred_locality_dropdwon" id="preferred_locality_category_'+widget+id+'" caption="preferred locality" class="select-field" onChange="hideError(\''+locality_error_div+'\')">';
	locality_html += '<option value="0">Preferred Locality</option></select>';
	locality_html += '<div style="color:red;font-size:13px;display:none;" id="'+locality_error_div+'">The Preferred Locality is required.</div>';
	locality_html += '</li>';
	return locality_html;
}


function populateLocalities(sb, orientation, key, style, listing_id)
{
	var locality_error_div = "error_preferred_locality_"+orientation;
	var city_error_div = "error_preferred_city_"+orientation;
	window.jQuery('#'+locality_error_div).hide();
	window.jQuery('#'+city_error_div).hide();
	
	try {
		var localities_sb = document.getElementById('preferred_locality_'+orientation);
		if(typeof(sb.options[sb.selectedIndex]) == "undefined" || sb.selectedIndex == -1){
			sb.style.display = "none";
			localities_sb.style.display = "none";
			return true;
		}
		
		var city = sb.options[sb.selectedIndex].value;		
		localities_sb.length = 0;
		localities_sb.style.display = "none";
		localities_sb.options[localities_sb.options.length]  = new Option("Prefered Locality", "0");
		window.jQuery('#preferred_locality_container').hide();		
	
		if(city != "")
		{
			if(is_int(key)){
				var localities = custom_localities[key][city]['localities'];
			}else{
				if(key == "COURSE_WISE_CUSTOMIZED_LOCATIONS" && listing_id != "") {
				    var localities = custom_localities[key][listing_id][city];
				} else {
				    var localities = custom_localities[key][city];
				}
			}

			if(Object.size(localities) == 1){
				localities_sb.length = 0;
			}

			for(i in localities)
			{				
				if(typeof(localities[i]) != "object"){
					if (preselected_locality == localities[i]) {
						var defaultSelected = true;
					} else {
						var defaultSelected = false;
					}
							
					localities_sb.options[localities_sb.options.length] = new Option(localities[i], localities[i], defaultSelected, defaultSelected);
				}else{
					if (preselected_locality == i) {
						var defaultSelected = true;
					} else {
						var defaultSelected = false;
					}
					
					localities_sb.options[localities_sb.options.length] = new Option(localities[i].name, i, defaultSelected, defaultSelected);
				}
			}
		}

		if(localities_sb.length == 1){
			localities_sb.style.display = "none";
			window.jQuery('#preferred_locality_container').hide();
			if(sb.length == 1 && sb.value){
				sb.style.display = "none";
				if(style == "new"){
					window.jQuery('#city-locality-div').hide();
				}
			}else{
				window.jQuery('#city-locality-div').show();
				sb.style.display = "block";
			}
		}else{
			window.jQuery('#city-locality-div').show();
			sb.style.display = "block";
			localities_sb.style.display = "block";
			window.jQuery('#preferred_locality_container').show();
		}
			
	}catch(e){
		
	}
}

function validateForm() {	
	var errorFlag = 0;
	if (window.jQuery('#user_first_name').val() == "") {
		window.jQuery('#error_user_first_name').html("The First Name field is required.");
		errorFlag = 1;
	}
	
	if (window.jQuery('#user_last_name').val() == "") {
		window.jQuery('#error_user_last_name').html("The Last Name field is required.");
		errorFlag = 1;
	}
	
	if (window.jQuery('#user_email').val() == "") {
		window.jQuery('#error_user_email').html("The Email field is required.");
		errorFlag = 1;
	}
	
	if (window.jQuery('#list').val() == 0) {			
		window.jQuery('#error_list').html("The Course field is required.");
		errorFlag = 1;
	}
	
	var city_id = "preferred_city_category_"+widget+institute_id;
	if (window.jQuery("#"+city_id).is(':visible') && window.jQuery("#"+city_id).val() == 0) {
		var error_div_id = 'error_' + city_id;
		window.jQuery('#' + error_div_id).show();
		errorFlag = 1;
	}	
	
	var locality_id = "preferred_locality_category_"+widget+institute_id;
	if (window.jQuery("#"+locality_id).is(':visible') && window.jQuery("#"+locality_id).val() == 0) {
		var error_div_id = 'error_' + locality_id;
		window.jQuery('#' + error_div_id).show();
		errorFlag = 1;
	}	

	if (window.jQuery('#user_mobile').val() == "") {
		window.jQuery('#error_user_mobile').html("The Mobile field is required.");
		errorFlag = 1;
	}
	
	if (errorFlag) {
		return false;
	} else {
		return true;
	}
}

function hideError(locality_error_div) {
	if(window.jQuery("#"+locality_error_div).is(':visible')) {
		window.jQuery('#'+locality_error_div).hide();
	}	 
}

function checkEmpty(element_id) {
	if (window.jQuery('#'+element_id).val() != "") {		
		window.jQuery('#'+'error_'+element_id).html("");		
	}		
}

<?php
if(isset($preselected_course_id) && $preselected_course_id != "") { ?>
	var preselected_city = '<?php echo ($form_preferred_city == "" ? "" : $form_preferred_city);?>';
	var preselected_locality = '<?php echo ($form_preferred_locality == "" ? "" : $form_preferred_locality); ?>';
	reloadLocations(<?=$instituteId?>, <?=$preselected_course_id?>);
<?php } else { ?>
	var preselected_city = "";
	var preselected_locality = "";
<?php }?>
</script>
