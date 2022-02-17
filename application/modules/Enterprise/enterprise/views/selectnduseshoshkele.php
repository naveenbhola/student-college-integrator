<div id="selectnduseshoshkele" style="display:none;z-index:90">
		<form enctype="multipart/form-data"  onsubmit="if(validateselectnduseform(this) === false) { return false;} else {/*(this),*/disableSubmitButtonForAddShoshkele();AIM.submit(this, {'onStart' : startCallback, 'onComplete' : updateshoshkeleselectnduse}); }"  action="<?php echo site_url().'/enterprise/Enterprise/selectnduseshoshkele';?>" method="post" name="selectnduseform" autocomplete = "off" id = "selectnduseform" novalidate>
		<input id = "clientIdsnu" name = "clientIdsnu" value = "<?php echo $clientId;?>" type = "hidden"/>
		<input id = "bannerIdsnu" name = "bannerIdsnu" value = "" type = "hidden"/>
		<input id = "countrySelected" name = "countrySelected" value = "<?php echo $countrySelected;?>" type = "hidden"/>
		<input id = "countryofshoshkele" name = "countryofshoshkele" value = "2" type = "hidden"/>
		<div style="margin-left:23px">
				<div class="lineSpace_10"></div>
				<div class="normaltxt_11p_blk lineSpace_20 float_L"></div>
				<div class="row">
						<div>
								<div id = "shoshkelenametobeused" style = "font-size:17px;"></div>
								<br/>
								<select  id="subscription_id" validate = "validateSelect"  caption = "Subscription To Proceed" required = "true" name = "subscription_id" minlength="1" maxlength="100" caption="Pack" style="width:200px;" onchange="setSubscriptionDetails();">
										<option value="" selected>Select Subscription</option>
										<!--<option value = ''>Select Subscription</option>-->
										<?php
										    foreach($subscriptionDetails as $key=>$vals){
											if($vals['BaseProdCategory']=='Category-Sponsor'){
											?>
											<option value="<?php echo $key; ?>" ><?php echo $vals['BaseProdCategory'],"-",$vals['BaseProdSubCategory']," : ",$key; ?></option>
											<?php 
											}
										    } ?>
								</select>
								<div>
												<!-- To show subscription details -->
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
								<hr style="color:#aaa;"/>                                                        
								<br/><br/>
								<div>
										<select id="cat_type" style = "" caption = "Type" name = "cat_type" validate = "validateSelect" required = "true" onchange="javascript:{changeCategory(this.value);}">
												<option value = ''>Select Category Type</option>
<!--												<option value = 'category'>Career Options (India)</option>-->
												<option value = 'studyabroad'>Study Abroad</option>	
										</select>
								</div>
								<div>
										<div id="cat_type_error" class="errorMsg" style="padding-left:92px"></div>
								</div>
								<div class="lineSpace_10">&nbsp;</div>
				<div class="row">
					<div>
                    <!--Select Country:&nbsp;&nbsp;-->
                    <select name="locationnamesnu" id="locationnamesnu" validate = "validateSelect" required = "true" caption = "country" style="display: none;">
                    <option value = ''>Select Country</option>
						<?php
						//global $countries;
						?>
							<?php
							if($countrySelected != "india")
								{
									foreach($countries as $countryObj)
									{
										if($countryObj['countryId'] > 2)		
										{
							 ?>
												<option value = "<?php echo $countryObj['countryId']?>" title="<?php echo $countryObj['name']?>"><?php echo $countryObj['name']?></option>
								<?php   	}
									 }
								}
								else
								{
										for($j = 0;$j < count($cities); $j++)
										{
										?>
												<option value = "<?php echo $cities[$j]['cityId']?>" title="<?php echo $cities[$j]['cityName']?>"><?php echo $cities[$j]['cityName']?></option>
										<?php
										}
								}
						?>
                    </select>
                    </div>
                    </div>
			<div>
				<div id="locationnamesnu_error" class="errorMsg" style="padding-left:92px"></div>
			</div>
				<div class="lineSpace_10">&nbsp;</div>
                <div class="row" id= "selectCity">
				<div>
                    <select id = "citiesofshoshkele" style = "display:none" name = "citiesofshoshkele" caption = "city">
											<option value = ''>Select City</option>
											<option value = '1'>All cities</option>
											<?php
                                            for($j = 0;$j < count($cities); $j++) {?>
											<option value = "<?php echo $cities[$j]['cityId']?>" title="<?php echo $cities[$j]['cityName']?>"><?php echo $cities[$j]['cityName']?></option>
											<?php } ?>
                    </select>
                </div>
			<div>
				<div id="citiesofshoshkele_error" class="errorMsg" style="padding-left:92px"></div>
			</div>
		<div id="OR1" style="display:none;margin-top:10px;margin-left:120px;font-weight:bold">
				<div class="lineSpace_10" >OR</div>
		</div>
				<div class="lineSpace_10">&nbsp;</div>
								<div>
                    <select id = "statesofshoshkele" style = "display:none" name = "statesofshoshkele"  caption = "state">
											<option value = ''>Select State</option>
											<?php
                                            foreach($states as $state) {if($state['stateId']> 0){?>
											<option value = "<?php echo $state['stateId']?>" title="<?php echo $state['stateName']?>"><?php echo $state['stateName']?></option>
											<?php }} ?>
                    </select>
                </div>
			<div>
				<div id="statesofshoshkele_error" class="errorMsg" style="padding-left:92px"></div>
			</div>
				<div class="lineSpace_10">&nbsp;</div>
				<div class="lineSpace_10">&nbsp;</div>
				<div class="lineSpace_10">&nbsp;</div>


                <div class="row" id="categoriesdiv">
                <div>
				<select name="nationalCategoryList" id="nationalCategoryList" caption = "category" style="display:none">
				<option value = ''>Select Category</option>
				<?php //global $categoryParentMap;
				foreach($nationalMainCategoryList as $nationalMainCategory){
						if($nationalMainCategory['id'] == $selectedcategoryId){
				?>
								<option selected = 'true' value = "<?php echo $nationalMainCategory['id']?>"><?php echo $nationalMainCategory['name']?></option>
				<?php
						}
						else{
				?>
								<option value = "<?php echo $nationalMainCategory['id']?>" ><?php echo $nationalMainCategory['name']?></option>
				<?php
						}
				}
				?>
				</select>
				<select name="saCategoryList" id="saCategoryList" caption = "category" style="display:none">
				<option value = ''>Select Category</option>
				<?php //global $categoryParentMap;
				//error_log("::INFO FOR REAVER ::");
				foreach($saMainCategoryList as $saMainCategory){
						if($saMainCategory['id'] == $selectedcategoryId){
				?>
								<option selected = 'true' value = "<?php echo $saMainCategory['id'];?>"><?php echo $saMainCategory['name']?></option>
				<?php
						}
						else{
				?>
								<option value = "<?php echo $saMainCategory['id']?>" ><?php echo $saMainCategory['name']?></option>
				<?php
						}
				}
				?>
				</select>
                </div>
		</div>
			<div>
				<div id="categorynamesnu_error" class="errorMsg" style="padding-left:92px"></div>
				</div>
		<div id="OR2" style="display:none;margin-top:10px;margin-left:120px;font-weight:bold">
				<div class="lineSpace_10" >OR</div>
		</div>
		<div>
				<div class="formInput" id="categoryPlace">&nbsp;</div>
				<div class="formInput normaltxt_11p_blk_verdana mb5" id="c_categories_combo"></div>
		</div>
		<div>
		<div id="subcategorynamesnu_error" class="errorMsg" style="padding-left:92px"></div>
		</div>
		<!-- category select end -->
		<!-- course level select -->
		<div>
				
				<select name="courseLevel" id="courseLevel" caption = "courseLevel" style="display:none">
				<option value = ''>Select Course Level</option>
				<option value = "<?php echo 'All';?>">All</option>
				<?php //global $categoryParentMap;
				foreach($courseLevels as $courseLevel){
				?>
						<option value = "<?=$courseLevel['CourseName']?>"><?=$courseLevel['CourseName']?></option>
				<?php }	?>
				</select>
				<div id="courseLevel_error" class="errorMsg" style="padding-left:92px"></div>
		</div>
		<div id="response_error" class="errorMsg" style="padding-left:92px"></div>
		<script>
		var completeCategoryTree = eval(<?php echo $categoryList; ?>);
		getCategories(true,"c_categories_combo","subcategorynamesnu","subcategorynamesnu",false,false,'forBanners');
		</script>
                                <!--Tesprep Category List-->

                                				<div class="row" id = "tp_categoriesdiv">
					<div>
                    <select name="tp_categorynameasl" id="tp_categorynameasl" caption = "category" validate = "validateSelect" required = "true" style="display:none;">
                    <option value = ''>Select Testprep Category</option>


										<?php global $testprepMap;?>
											<?php
foreach($testprepMap as $key=>$value)
{
    if($key == $selectedcategoryId)
    { ?>
        <option selected = 'true' value = "<?php echo $key?>"><?php echo $value?></option>
            <?php } else { ?>
                <option value = "<?php echo $key?>" ><?php echo $value?></option>
                    <?php
    }
}
                                            ?>
                    </select>
                    </div>
					<br clear="left" />
				</div>
			<div>
				<div id="tp_categorynameasl_error" class="errorMsg" style="padding-left:92px"></div>
			</div>
				<div class="lineSpace_10">&nbsp;</div>

                                <!--/Tesprep Category List-->







	<script>
    var cal = new CalendarPopup("calendardiv");
    </script>
				<div class="lineSpace_10">&nbsp;</div>
                    </div>
            <script>
//            getCitiesForCountry('',false,'ofshoshkele');
            </script>
               
				<div class="lineSpace_8">&nbsp;</div>
				<div class="row" style="">
					<div class="buttr3">
						<button class="btn-submit7 w3" value="" id = "addShoshkeleBtn" type="submit">
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

function updateshoshkeleselectnduse(response)
{
    if(response == -1)
    { 
        //document.getElementById('end_date_error').innerHTML = 'The shoshkele is already uploaded for the selected criteria';
        //document.getElementById('end_date_error').parentNode.style.display = '';
		enableSubmitButtonForAddShoshkele();
        document.getElementById('response_error').innerHTML = 'The shoshkele is already uploaded for the selected criteria';
        document.getElementById('response_error').style.display = '';
	return false;
    } 
  	
    if(response == -2) {
    if(confirm("Shoshkele you want to use is deleted. Want to refresh page ?")){
	  window.refreshPage();
	 }  
    	enableSubmitButtonForAddShoshkele();
	 return false;      
    }
    
    validateclient('categorysponsorclientid','banner');
    
}

var citiesForCountry = cityList['2'];
//var i = 0;
//for(var city in citiesForCountry){
//    var obj = document.getElementById('citiesofshoshkele').getElementsByTagName('option') ;
//    var flag = 0;
//    var i = 0;
//    while(obj[i].value != 'undefined')
//    {
//        if(obj[i].value != city)
//        {
//            flag = 1;
//            break;
//        }
//        i++;
//    }
//    if(flag == 1)
//    {
//        var optionElement = '';
//        var optionElement = document.createElement('option');
//        optionElement.value = city;
//        optionElement.title = citiesForCountry[city];
//        optionElement.innerHTML = citiesForCountry[city] ;
//        document.getElementById('citiesofshoshkele').appendChild(optionElement);
//    }
//}


function showCitydiv(id)
{
    return false;
    if(id == 2)
    {
        document.getElementById('selectCity').style.display = '';
        document.getElementById('citiesofshoshkele').style.display = '';
        document.getElementById('categoriesdiv').style.display = '';
        document.getElementById('nationalCategoryList').style.display = '';
    }
    else
    {
        document.getElementById('selectCity').style.display = 'none';
        document.getElementById('citiesofshoshkele').style.display = 'none';
        document.getElementById('nationalCategoryList').style.display = 'none';
    }
}


function changeCategory(cat_type)
{
		document.getElementById('saCategoryList').style.display='none';
		document.getElementById('citiesofshoshkele_error').innerHTML = '';
		document.getElementById('statesofshoshkele_error').innerHTML = '';
		document.getElementById('categorynamesnu_error').innerHTML = '';
		document.getElementById('tp_categorynameasl_error').innerHTML = '';
		document.getElementById('locationnamesnu_error').innerHTML = '';
		document.getElementById('subcategorynamesnu_error').innerHTML = '';
    		document.getElementById('courseLevel_error').innerHTML = '';
if(cat_type == 'category')
        {
		document.getElementById('citiesofshoshkele').style.display = '';
		document.getElementById('statesofshoshkele').style.display = '';
		document.getElementById('nationalCategoryList').style.display = '';
		document.getElementById('tp_categorynameasl').style.display = 'none';
		document.getElementById('locationnamesnu').style.display = 'none';
		document.getElementById('subcategorynamesnu').style.display = '';
		document.getElementById('OR1').style.display = '';
		document.getElementById('OR2').style.display = '';
		document.getElementById('courseLevel').style.display = 'none';
	    
        }
        else if(cat_type == 'studyabroad')
            {
		document.getElementById('statesofshoshkele').style.display = 'none';
		document.getElementById('citiesofshoshkele').style.display = 'none';
		document.getElementById('saCategoryList').style.display = '';
		document.getElementById('nationalCategoryList').style.display='none'
		document.getElementById('tp_categorynameasl').style.display = 'none';
		document.getElementById('locationnamesnu').style.display = '';
		document.getElementById('subcategorynamesnu').style.display = 'none';
		document.getElementById('OR1').style.display = 'none';
		document.getElementById('OR2').style.display = 'none';
		document.getElementById('courseLevel').style.display = '';
            }
        else if(cat_type == 'testprep')
            {
		document.getElementById('citiesofshoshkele').style.display = '';
		document.getElementById('nationalCategoryList').style.display = 'none';
		document.getElementById('saCategoryList').style.display='none';
		document.getElementById('tp_categorynameasl').style.display = '';
		document.getElementById('locationnamesnu').style.display = 'none';
		document.getElementById('subcategorynamesnu').style.display = 'none';
		document.getElementById('statesofshoshkele').style.display = 'none';
		document.getElementById('OR1').style.display = 'none';
		document.getElementById('OR2').style.display = 'none';
		document.getElementById('courseLevel').style.display = 'none';

            }
        else if(cat_type == 'onlinetest')
            {
		document.getElementById('citiesofshoshkele').style.display = 'none';
		document.getElementById('nationalCategoryList').style.display = 'none';
		document.getElementById('saCategoryList').style.display='none';
		document.getElementById('tp_categorynameasl').style.display = '';
		document.getElementById('locationnamesnu').style.display = 'none';
		document.getElementById('subcategorynamesnu').style.display = 'none';
		document.getElementById('OR1').style.display = 'none';
		document.getElementById('OR2').style.display = 'none';
		document.getElementById('statesofshoshkele').style.display = 'none';
		document.getElementById('courseLevel').style.display = 'none';
            }
        else
            {
		document.getElementById('citiesofshoshkele').style.display = 'none';
		document.getElementById('nationalCategoryList').style.display = 'none';
		document.getElementById('saCategoryList').style.display='none';
		document.getElementById('tp_categorynameasl').style.display = 'none';
		document.getElementById('locationnamesnu').style.display = 'none';
		document.getElementById('subcategorynamesnu').style.display = 'none';
		document.getElementById('statesofshoshkele').style.display = 'none';
		document.getElementById('OR1').style.display = 'none';
		document.getElementById('OR2').style.display = 'none';
		document.getElementById('courseLevel').style.display = 'none';
            }
}

function validateselectnduseform(objform)
{
    var flagresults = validateFields(objform);
	if($('cat_type').value == "category"){
		if(document.getElementById('subcategorynamesnu').value && document.getElementById('nationalCategoryList').value){
			document.getElementById('subcategorynamesnu_error').parentNode.style.display = '';
			document.getElementById('subcategorynamesnu_error').innerHTML = "Please select either a category or subcategory.";
			flagresults = false
		}
		if(!(document.getElementById('subcategorynamesnu').value) && !(document.getElementById('nationalCategoryList').value)){
			document.getElementById('subcategorynamesnu_error').parentNode.style.display = '';
			document.getElementById('subcategorynamesnu_error').innerHTML = "Please select a category or subcategory.";
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
		

		
	}else if($('cat_type').value == "studyabroad"){
		if(!document.getElementById('saCategoryList').value){
			document.getElementById('categorynamesnu_error').parentNode.style.display = '';
			document.getElementById('categorynamesnu_error').innerHTML = "Please select a category";
			flagresults = false
		}
		if(!document.getElementById('courseLevel').value){
			document.getElementById('courseLevel_error').parentNode.style.display = 'block';
			document.getElementById('courseLevel_error').innerHTML = "Please select a course level";
			flagresults = false
		}
	}
    return flagresults;

}
try
{
    addOnBlurValidate(document.getElementById('selectnduseform'));
}
catch (ex)
{
}
function disableSubmitButtonForAddShoshkele()
{
	document.getElementById('addShoshkeleBtn').setAttribute('disabled','disabled');
}
function enableSubmitButtonForAddShoshkele()
{
	document.getElementById('addShoshkeleBtn').removeAttribute('disabled');
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
</script>