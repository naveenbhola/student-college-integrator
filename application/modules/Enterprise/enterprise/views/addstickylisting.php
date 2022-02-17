<div id = "addstickylisting" style="display:none;z-index:100001" >
		<form enctype="multipart/form-data"  onsubmit="if(validatemanagelistingform(this) === false) { return false;} else {submissionInProgress = 1;(this),AIM.submit(this, {'onStart' : startCallback, 'onComplete' : updatemanagelistingforms}); }"  action="<?php echo site_url().'enterprise/Enterprise/cmsaddstickylisting';?>" method="post" name="addstickylistingform" autocomplete = "off" novalidate>
		<input id = "clientIdasl" name = "clientIdasl" value = "<?php echo $clientId;?>" type = "hidden"/>
		<input id = "bannerIdasl" name = "bannerIdasl" value = "" type = "hidden"/>
		<input id = "countrySelectedasl" name = "countrySelectedasl" value = "<?php echo $countrySelected;?>" type = "hidden"/>
		<input id = "countryofshoshkele" name = "countrySelectedasl" value = "2" type = "hidden"/>
		<div style="margin-left:23px">
				<div class="lineSpace_10"></div>
				<div class="normaltxt_11p_blk lineSpace_20 float_L"></div>
				<div>
						<div id="locationnameasl_error" class="errorMsg" style="padding-left:92px"></div>
				</div>
				<div class="row">
						<div>
								Country:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<select name="locationnameasl" id="locationnameasl"  onChange = "showCitydiv(this.value);showlistingDiv();showHideSubscriptionDropDown(this.value);" validate = "validateSelect" required = "true" caption = "country">
										<option value = ''>Select</option>
										<?php
										//global $countries;
										?>
										<?php
										if($countrySelected != "india"){
												foreach($countries as $countryObj){
														if($countryObj['countryId']>1){
																?>
																<option value = "<?php echo $countryObj['countryId']?>" title="<?php echo $countryObj['name']?>"><?php echo $countryObj['name']?></option>
														<?php
														}
												}
										}
										else{
												for($j = 0;$j < count($cities); $j++) {?>
														<option value = "<?php echo $cities[$j]['cityId']?>" title="<?php echo $cities[$j]['cityName']?>"><?php echo $cities[$j]['cityName']?></option>
												<?php
												}
										} ?>
								</select>
						</div>
				</div>
				<!--Now We will need this subscription Drop Down For National only-->
				<div class="row" id="subscriptionRow" style="margin-top:10px;display: none;">
						<div>
								Subscription:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<select  id="subscription_id" validate = "validateSelect" style = "width:200px;" caption = "Subscription To Proceed" required = "true" name = "subscription_id" minlength="1" maxlength="100" caption="Pack" onchange="setSubscriptionDetails();" >
												<option value="" selected>Select Subscription</option>
												<?php
												foreach($subscriptionDetails as $key=>$vals){
														if( $vals['BaseProdCategory']=='Category-Sponsor'){
												?>
														<option value="<?php echo $key; ?>" ><?php echo $vals['BaseProdCategory'],"-",$vals['BaseProdSubCategory']," : ",$key; ?></option>
														
												<?php 
														}
												} ?>
								</select>
								<table>
												<tr><th align="left">Start Date </th><td>:</td><td align="right"><div id="startDateSubscription">N/A</div></td></tr>
												<tr><th align="left">Expiry Date </th><td>:</td><td align="right"><div id="endDateSubscription">N/A</div></td></tr>
												<tr><th align="left">Listings Remaining</th><td>:</td><td align="right"><div id="qtyRemainingSubscription">N/A</div></td></tr>
								</table>
						</div>
						<div>
								<div id="subscription_id_error" class="errorMsg" style="padding-left:92px"></div>
						</div>
						<div class="lineSpace_10">&nbsp;</div>				
						
				</div>
				<!--subscription Drop Down For National only END-->
				<div id="listingDiv" class="row" style="display:none">		
						Listing Id:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="text" name="listingIdasl" id="listingIdasl" validate = "validateInteger" required = "true" caption = "listing id" minlength = "1" maxlength = "15" onChange = "checkstickylisting(this.value);"/>
				</div>
				<br clear="left" />
				<div class="lineSpace_10">&nbsp;</div>
				<div>
						<div id="listingIdasl_error" class="errorMsg" style="padding-left:92px"></div>
				</div>
				<div class="lineSpace_10">&nbsp;</div>
				<div class="row" id= "selectCity" style = "display:none">
						<div>
								City:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<select id="citiesofshoshkele" style = "display:none" name = "citiesofshoshkele" caption = "city">
												<option value = ''>Select</option>
												<option value = '1'>All cities</option>
												<?php
												for($j = 0;$j < count($cities); $j++) {?>
														<option value = "<?php echo $cities[$j]['cityId']?>" title="<?php echo $cities[$j]['cityName']?>"><?php echo $cities[$j]['cityName']?></option>
												<?php
												} ?>
								</select>
						</div>
				</div>
				<div>
						<div id="citiesofshoshkele_error" class="errorMsg" style="padding-left:92px"></div>
				</div>
				<div id="OR1" style="display:none;margin-top:10px;margin-left:120px;font-weight:bold;margin-bottom: 10px;">
						<div class="lineSpace_10" >OR</div>
				</div>
				<div class="row" id= "selectState" style = "display:none">
						<div>
								State:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<select id="statesofshoshkele" style = "display:none" name = "statesofshoshkele" caption = "state">
										<option value = ''>Select</option>
										<?php
										foreach($states as $state){
												if($state['stateId']> 0){
										?>
														<option value = "<?php echo $state['stateId']?>" title="<?php echo $state['stateName']?>"><?php echo $state['stateName']?></option>
												<?php
												}
										} ?>
								</select>
						</div>
				</div>
				<div>
						<div id="statesofshoshkele_error" class="errorMsg" style="padding-left:92px"></div>
				</div>
				<div class="lineSpace_10">&nbsp;</div>
				<div class="row" id = "categoriesdiv" style="display:none">
						<div>
								Category:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<select name="nationalCategorySelect" id="nationalCategorySelect" caption = "category" style="display:none">
										<option value = ''>Select</option>
										<?php
										foreach($nationalMainCategoryList as $nationalMainCategory){
												if($nationalMainCategory['id'] == $selectedcategoryId){ ?>
														<option selected = 'true' value = "<?php echo $nationalMainCategory['id'];?>"><?php echo $nationalMainCategory['name'];?></option>
												<?php
												}
												else{ ?>
														<option value = "<?php echo $nationalMainCategory['id'];?>" ><?php echo $nationalMainCategory['name'];?></option>
										<?php
												}	
										}
										?>
								</select>
								<select name="saCategorySelect" id="saCategorySelect" caption = "category" style="display:none">
										<option value = ''>Select</option>
										<?php
										foreach($saMainCategoryList as $saMainCategory){
												if($saMainCategory['id'] == $selectedcategoryId){ ?>
														<option selected = 'true' value = "<?php echo $saMainCategory['id']?>"><?php echo $saMainCategory['name']?></option>
												<?php
												}
												else{ ?>
														<option value = "<?php echo $saMainCategory['id']?>" ><?php echo $saMainCategory['name']?></option>
												<?php
												}	
										}
										?>
								</select>
						</div>
						<br clear="left" />
				</div>
				<div>
						<div id="categorynameasl_error" class="errorMsg" style="padding-left:92px"></div>
				</div>
				<div class="row" id="course_level_div" style="display:none">
						Course Level:&nbsp;&nbsp;&nbsp;&nbsp;
						<select name="course_level" id="course_level" caption="Course Level" style="display:none">
								<option selected='true' value='All'>All</option>
								<?php
								foreach($courseLevels as $level){
								?>
										<option value='<?php echo reset($level)?>'><?php echo reset($level)?></option>
								<?php 
								}
								?>
						</select>
						
				</div>
				<div>
						
						<div id="course_level_error" class="errorMsg" style="padding-left:92px"></div>
				</div>
				<div id="OR2" style="display:none;margin-top:10px;margin-left:120px;font-weight:bold;margin-bottom: 10px;">
						<div class="lineSpace_10" >OR</div>
				</div>
				<div class="row" id = "subcategoriesdiv" style="display:none">
						<div class="formInput" id="categoryPlace" style="display:inline">Sub Category:&nbsp;&nbsp;&nbsp;&nbsp;</div>
						<div class="formInput normaltxt_11p_blk_verdana mb5" id="c_categories_combo" style="display:inline"></div>
				</div>
				<div>
						<div id="subcategorynameasl_error" class="errorMsg" style="padding-left:92px"></div>
				</div>
				<div class="lineSpace_10" >&nbsp;</div>
				<!-- category select end -->			
				<script>
						var completeCategoryTree = eval(<?php echo $categoryList; ?>);
						getCategories(true,"c_categories_combo","subcategorynameasl","subcategorynameasl",false,false,'forBanners');
				</script>
		
				<?php
				$this->load->view('common/calendardiv');
				?>
				<div class="row">
						<br clear="left" />
						<div>
								<div id="all_error" class="errorMsg" style="padding-left:92px"></div>
						</div>
						<div id="error_bannername" class="normaltxt_11p_blk" style = "display:none;color:red;margin-left:34px"></div>
				</div>
				<div class="lineSpace_1">&nbsp;</div>
				<div class="lineSpace_8">&nbsp;</div>
				<div class="row" style="">
						<div class="buttr3">
								<button class="btn-submit7 w3" value="" type="submit" onclick="if(submissionInProgress == 1) {alert('Form submission in progress');return false;}">
										<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Add</p></div>
								</button>
						</div>
						<div class="buttr3">
								<button class="btn-submit5 w3" value="" type="button" onClick="hideOverlay();">
										<div class="btn-submit5"><p class="btn-submit6">Cancel</p></div>
								</button>
						</div>
						<br clear="left" />
				</div>
		</div>
		<span id="nr" style="display:inline"></span>
		</form>
		<div class="lineSpace_10">&nbsp;</div>
</div>

<script>
var submissionInProgress = 0;
function checkforvalinarr(val,arrcsv){
		if(arrcsv == "")
				return false;
		arr = arrcsv.split(",");
		for(var i = 0;i < arr.length;i++)
		{
				if(arr[i] == val)
				{
						return true;
				}
		}	
		return false;
}

var errormsg = '';

function checkforvaliddata(val,type)
{
		var listingval = document.getElementById('listingIdasl').value ;
		var check = true;
		switch(type)
		{
		case 'city':
				cityidcs = cityidcs + ",1"; // Including all cities as well as requested by Product team (Saurabh).
				check = checkforvalinarr(val,cityidcs);
				if(!check && listingval != '')
				{
						errormsg += 'selected city,';
				}	
				break;
		case 'state':
				check = checkforvalinarr(val,stateidcs);
				if(!check && listingval != '')
				{
						errormsg += 'selected state,';
				}
				break;
		case 'country':
				check = checkforvalinarr(val,countryidcs);
				if(!check && listingval != '')
				{
						errormsg += 'selected country,';
				}	
				break;
		case 'listing':
				errormsg = '';
				check = checkforvalinarr(val,clientidcs);
				if(!check && listingval != '')
				{
						errormsg += 'entered client,';
				}
				checkforvaliddata(document.getElementById('locationnameasl').value,'country');
				if(document.getElementById('locationnameasl').value == 2)
				{
						if(document.getElementById('nationalCategorySelect').value)
								checkforvaliddata(document.getElementById('nationalCategorySelect').value,'category');
						if(document.getElementById('subcategorynameasl').value)
								checkforvaliddata(document.getElementById('subcategorynameasl').value,'subcategory');
						if(document.getElementById('citiesofshoshkele').value)
								checkforvaliddata(document.getElementById('citiesofshoshkele').value,'city');
						if(document.getElementById('statesofshoshkele').value)
								checkforvaliddata(document.getElementById('statesofshoshkele').value,'state');
				}
				else{
						checkforvaliddata(document.getElementById('saCategorySelect').value,'category');
						checkforvaliddata(document.getElementById('course_level').value,'courseLevel');
						checkforvaliddata(document.getElementById('saCategorySelect').value+':'+document.getElementById('course_level').value,'existingStickies');
				}
				break;
		case 'category':
				check = checkforvalinarr(val,categoryidscs);
				if(!check && listingval != ''){
						errormsg += 'selected category,';
				}	
				break;	
		case 'subcategory':
				check = checkforvalinarr(val,subcategoryidscs);
				if(!check && listingval != ''){
						errormsg += 'selected sub-category,';
				}
				break;
		case 'testprep_category':
				if(blogids == null){
						errormsg += 'any testprep category,';
						break;
				}	
				check = checkforvalinarr(val,blogids);
				if(!check && listingval != ''){
						errormsg += 'selected testprep category,';
				}
				break;
		case 'courseLevel':
				courselevelscs += ",All";
				check = checkforvalinarr(val,courselevelscs);
				if(!check && listingval != ''){
						errormsg += 'selected Course-level,';
				}
				break;
		case 'existingStickies':
				check = checkforvalinarr(val,existingstickiescs);
				if (check) {
						alert('This combination of category/course level for this university already exists as a sticky listing');
				}
				break;
		}
}

var citiesForCountry = cityList['2'];
var i = 0;
for(var city in citiesForCountry){
		var obj = document.getElementById('citiesofshoshkele').getElementsByTagName('option') ;
		var flag = 0;
		var i = 0;
		while(obj[i].value != 'undefined'){
				if(obj[i].value != city){
						flag = 1;
						break;
				}
				i++;
		}
		if(flag == 1){
				var optionElement = '';
				var optionElement = document.createElement('option');
				optionElement.value = city;
				optionElement.title = citiesForCountry[city];
				optionElement.innerHTML = citiesForCountry[city] ;
				document.getElementById('citiesofshoshkele').appendChild(optionElement);
		}
}

var clientidcs = '';
var cityidcs = '';
var countryidcs = '';
var categoryidscs = '';
var blogids = '';
var countrytiercs = '';
var categorytierscs = '';
var existingstickiescs = '';
var courselevelscs = ''; //courselevelsCommaSeparated;

function checkstickylisting(listingid){
		clientidcs = '';
		cityidcs = '';
		countryidcs = '';
		categoryidscs = '';
		subcategoryidscs = '';
		stateidcs = '';
		courselevelscs = '';
		var xmlHttp = getXMLHTTPObject();
		xmlHttp.onreadystatechange=function(){
				if(xmlHttp.readyState==4){
						var response = eval("eval("+xmlHttp.responseText+")");
						//alert(response.country_id + '' + response.city_id);
						if(response != 0){
								countryidcs = response.country_id;
								cityidcs = response.city_id;
								stateidcs = response.state_id;
								clientidcs = response.username;
								categoryidscs = response.categoryids;
								subcategoryidscs = response.subcategoryids;
								blogids = response.blogids;
								countrytiercs = response.country_tier;
								categorytierscs = response.category_tier;
								courselevelscs = response.course_level;
								existingstickiescs = response.existingStickies;
								checkforvaliddata(document.getElementById('categorysponsorclientid').value,'listing');
						}
				}
		}
		var url = '/enterprise/Enterprise/cmsgetlistingdetails/'+ listingid+'/'+listingtype;
		xmlHttp.open("POST",url,true);
		xmlHttp.setRequestHeader("Content-length", 0);
		xmlHttp.setRequestHeader("Connection", "close");
		xmlHttp.send(null);
		return false;
}

function updateStatusImage(response)
{
    alert(response);
}

var listingtype = '';
function showlistingDiv() {
		
		if (document.getElementById('locationnameasl').value != '') {
				document.getElementById('listingDiv').style.display='';
		}
		if (document.getElementById('locationnameasl').value == 2) {
				listingtype='institute';
		}
		else{
				listingtype='university';
		}
}

function validatemanagelistingform(objform)
{
		var flagresults = validateFields(objform);
		document.getElementById('subcategorynameasl_error').parentNode.style.display = '';
		document.getElementById('statesofshoshkele_error').parentNode.style.display = '';
		document.getElementById('categorynameasl_error').parentNode.style.display = '';
		if(document.getElementById('locationnameasl').value == 2){
				if(document.getElementById('subcategorynameasl').value && document.getElementById('nationalCategorySelect').value){
						document.getElementById('subcategorynameasl_error').parentNode.style.display = '';
						document.getElementById('subcategorynameasl_error').innerHTML = "Please select either a category or subcategory.";
						flagresults = false
				}
				if(!(document.getElementById('subcategorynameasl').value) && !(document.getElementById('nationalCategorySelect').value)){
						document.getElementById('subcategorynameasl_error').parentNode.style.display = '';
						document.getElementById('subcategorynameasl_error').innerHTML = "Please select a category or subcategory.";
						flagresults = false
				}
				if(document.getElementById('statesofshoshkele').value && document.getElementById('citiesofshoshkele').value){
						document.getElementById('statesofshoshkele_error').parentNode.style.display = '';
						document.getElementById('statesofshoshkele_error').innerHTML = "Please select either a city or state.";
						flagresults = false
				}
				if(!(document.getElementById('statesofshoshkele').value) && !(document.getElementById('citiesofshoshkele').value)){
						document.getElementById('statesofshoshkele_error').parentNode.style.display = '';
						document.getElementById('statesofshoshkele_error').innerHTML = "Please select a city or state.";
						flagresults = false
				}
		}else{
				if(!document.getElementById('saCategorySelect').value){
						document.getElementById('categorynameasl_error').parentNode.style.display = '';
						document.getElementById('categorynameasl_error').innerHTML = "Please select a category";
						flagresults = false
				}
				if(!document.getElementById('course_level').value){
						document.getElementById('course_level_error').parentNode.style.display = '';
						document.getElementById('course_level_error').innerHTML = "Please Select a Course Level";
						flagresults = false
				}
		}
		if(flagresults == true)
		{
				errormsg = '';
				//checkstickylisting(document.getElementById('listingIdasl').value);
				document.getElementById('all_error').innerHTML = '';
				if(clientidcs != 0)
				{
						checkforvaliddata(document.getElementById('categorysponsorclientid').value,'listing');
						errormsg = errormsg.substring(0,errormsg.length -1);
						if(errormsg != '')
						{
								if (document.getElementById('locationnameasl').value == 2) {
										document.getElementById('all_error').innerHTML = "The institute doesn't belong to " + errormsg;
								}
								else{
										document.getElementById('all_error').innerHTML = "The university doesn't belong to " + errormsg;
								}
						}
				}
				else
				{
						if (document.getElementById('locationnameasl').value == 2) {
								document.getElementById('all_error').innerHTML = "Either the institute is invalid or is no longer active";
						}
						else{
								document.getElementById('all_error').innerHTML = "Either the university is invalid or is no longer active";
						}
						
				}
	       
				if(document.getElementById('all_error').innerHTML != '' || flagresults != true)
						return false;
				else
						return true;
		}
		return false;
}

function updatemanagelistingforms(response)
{
		var id = 'categorysponsorclientid';
		document.getElementById(id + '_error').innerHTML = '';
		var clientid = document.getElementById(id).value;
		if(clientid == ''){
				document.getElementById(id + '_error').innerHTML = 'Please enter a client id';
                                var submissionInProgress = 0;
				return false;
		}
		var filter = /^(\d)+$/;
		if(!filter.test(clientid)){
				document.getElementById(id + '_error').innerHTML = 'Please enter a valid client id';
                                var submissionInProgress = 0;
				return false;
		}	
		if(response == -1){
				document.getElementById('end_date_error').innerHTML = 'The listing is already added for the selected criteria';
				document.getElementById('end_date_error').parentNode.style.display = '';
                                var submissionInProgress = 0;
				return false;
		}
		window.location = '/enterprise/Enterprise/cmsuploadbanner/listing' + '/' + clientid + '/' + document.getElementById('sortorder').value;
		return true;
}

function showCitydiv(id)
{
		if (id == '') {
				document.getElementById('selectCity').style.display = 'none';
				document.getElementById('citiesofshoshkele').style.display = 'none';
				document.getElementById('selectState').style.display = 'none';
				document.getElementById('statesofshoshkele').style.display = 'none';
				document.getElementById('categoriesdiv').style.display = 'none';
				document.getElementById('nationalCategorySelect').style.display = 'none';
				document.getElementById('saCategorySelect').style.display = 'none'
				document.getElementById('subcategoriesdiv').style.display = 'none';
				document.getElementById('subcategorynameasl').style.display = 'none';
				document.getElementById('OR1').style.display = 'none';
				document.getElementById('OR2').style.display = 'none';
				document.getElementById('course_level').style.display = 'none';
				document.getElementById('course_level_div').style.display = 'none';
		}
		if(id == 2){
				document.getElementById('selectCity').style.display = '';
				document.getElementById('citiesofshoshkele').style.display = '';
				document.getElementById('selectState').style.display = '';
				document.getElementById('statesofshoshkele').style.display = '';
				document.getElementById('categoriesdiv').style.display = '';
				document.getElementById('nationalCategorySelect').style.display = '';
				document.getElementById('saCategorySelect').style.display = 'none'
				document.getElementById('subcategoriesdiv').style.display = 'inline';
				document.getElementById('subcategorynameasl').style.display = '';
				document.getElementById('OR1').style.display = '';
				document.getElementById('OR2').style.display = '';
				document.getElementById('course_level').style.display = 'none';
				document.getElementById('course_level_div').style.display = 'none';
		}
		else
		{
				document.getElementById('selectCity').style.display = 'none';
				document.getElementById('citiesofshoshkele').style.display = 'none';
				document.getElementById('selectState').style.display = 'none';
				document.getElementById('statesofshoshkele').style.display = 'none';
				document.getElementById('categoriesdiv').style.display = '';
				document.getElementById('nationalCategorySelect').style.display='none';
				document.getElementById('saCategorySelect').style.display = '';
				document.getElementById('subcategoriesdiv').style.display = 'none';
				document.getElementById('subcategorynameasl').style.display = 'none';
				document.getElementById('OR1').style.display = 'none';
				document.getElementById('OR2').style.display = 'none';
				document.getElementById('course_level').style.display = '';
				document.getElementById('course_level_div').style.display = '';
		}
}

</script>
<script>
				function setSubscriptionDetails(){
								var details = getSubscriptionDetails(document.getElementById('subscription_id').value);
								if (details) {
												document.getElementById('startDateSubscription').innerHTML = details[0];
												document.getElementById('endDateSubscription').innerHTML = details[1];
												document.getElementById('qtyRemainingSubscription').innerHTML = details[2];
								}
								else{
												document.getElementById('startDateSubscription').innerHTML = "N/A";
												document.getElementById('endDateSubscription').innerHTML = "N/A";
												document.getElementById('qtyRemainingSubscription').innerHTML = "N/A";
								}
				}

function showHideSubscriptionDropDown(id)
{
		document.getElementById('startDateSubscription').innerHTML = "N/A";
		document.getElementById('endDateSubscription').innerHTML = "N/A";
		document.getElementById('qtyRemainingSubscription').innerHTML = "N/A";
		document.getElementById('subscription_id').value = '';
		if (id == '') {	
			document.getElementById('subscriptionRow').style.display = 'none';	
		}
		if(id == 2){
			document.getElementById('subscriptionRow').style.display = 'block';
			document.getElementById('subscription_id').setAttribute('required',true);
		}
		else
		{
		     document.getElementById('subscriptionRow').style.display = 'none';
			 document.getElementById('subscription_id').removeAttribute('required');
		}
		
}				
</script>
