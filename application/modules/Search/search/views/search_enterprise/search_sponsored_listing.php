<?php
	$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style', 'marketing'),
        'js'    =>  array('common','enterprise'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  'Search sponsored listings',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>''
        );
	
	$this->load->view('enterprise/headerCMS', $headerComponents);
	$this->load->view('enterprise/cmsTabs');
    
$validSubscriptions = array();
foreach($subscriptionDetails as $subscriptionId => $subscription){
    if(in_array($subscription['BaseProductId'], $sponsoredProductIds)){
        $validSubscriptions[$subscriptionId] = $subscription;
    }
    if(in_array($subscription['BaseProductId'], $featuredProductIds)){
        $validSubscriptions[$subscriptionId] = $subscription;
    }
	if(in_array($subscription['BaseProductId'], $bannerProductIds)){
        $validSubscriptions[$subscriptionId] = $subscription;
    }
}
$currentYear = $current_year;
if($landing_error == "true"){
 ?>
	<div class="mmp_main_container" style="float:left;width:96%;padding:10px;background-color:none !important;">
		<div class="mmp_note_text_container" style="float:left;width:98%;padding:8px;">
			<div style="width:100%;float:left;color:#666;">
				No client id specified, <a href="/enterprise/Enterprise/searchUserForListingPost/">click here</a> specify valid client id.
			</div>
		</div>
	</div>
	<div style="clear:both;"></div>
 <?php
} else {
?>
<div class="mmp_main_container">
	<div style="clear:both;"></div>
    <div class="mmp_note_text_container" style="float:left;width:97%;border-style:dotted;">
        <div style="margin-bottom:10px;width:90%;float:left;">
            <span style="font-weight:bold;font-size:15px;">Client Information</span>
        </div>
        <div style="width:98%;float:left;">
            <div style="width:10%;float:left;">
                <span style="font-weight:bold;">Email</span>
            </div>
            <div style="width:2%;float:left;">:</div>
            <div style="width:70%;float:left;color:#666;">
                <?php echo $userDetails['email'];?>
            </div>
        </div>
        <div style="width:98%;float:left;">
            <div style="width:10%;float:left;">
                <span style="font-weight:bold;">Client Id</span>
            </div>
            <div style="width:2%;float:left;">:</div>
            <div style="width:70%;float:left;color:#666;">
                <?php echo $userDetails['clientUserId'];?>
            </div>
        </div>
        <div style="width:98%;float:left;">
            <div style="width:10%;float:left;">
                <span style="font-weight:bold;">Display name</span>
            </div>
            <div style="width:2%;float:left;">:</div>
            <div style="width:70%;float:left;color:#666;">
                <?php echo $userDetails['displayname'];?>
            </div>
        </div>
    </div>
    <div style="clear:both;"></div>
	<fieldset class="mmp_main_fieldset">
		<legend class="mmp_main_fieldsetlegend">Lising details</legend>
        <?php
		$heightStyle = "height:auto;";
        $instituteListing = array();
        foreach($clientListings as $listing){
            if($listing['listing_type'] == "institute"){
                $instituteListing[] = $listing;
            }
        }
        if(count($instituteListing) > 10){
            $heightStyle = "height:250px;";   
        }
        if(count($instituteListing) > 0){
        ?>
            <div style="width:99%;border:1px dotted #C3C3C3;float:left;padding:10px 5px 10px 5px;">
                <div class="mmp_listing" style="<?php echo $heightStyle;?>overflow:auto;width:100%;">
                    <table class="mmp_listing_table">
                        <tr>
                            <th class="mmp_listing_col_heading" style="font-size:12px;" align="left">Choose</th>
                            <th class="mmp_listing_col_heading" style="font-size:12px;" align="left">Listing ID</th>
                            <th class="mmp_listing_col_heading" style="font-size:12px;" align="left">Listing Type</th>
                            <th class="mmp_listing_col_heading" style="font-size:12px;" align="left">Listing Title</th>
                            <th class="mmp_listing_col_heading" style="font-size:12px;" align="left">Expiry date<br/><span style='font-size:10px;color:#B1B1B1;font-weight:normal;'><?php echo wordwrap('(YYYY-MM-DD HH-MM-SS)', 30, "<br/>", true); ?></span></th>
                        </tr>
                        <?php
                        foreach($instituteListing as $listingDetails){
                            if($listingDetails['listing_type'] == "course" ){
                                continue;
                            }
                        ?>
                            <tr>
                                <td class="mmp_listing_col_style">
                                    <input onclick="showProductDropdown(this);" type="radio" class="<?php echo $listingDetails['listing_title'];?>" name="choose_listing" value="<?php echo $listingDetails['listing_type_id'];?>"/>
                                </td>
                                <td class="mmp_listing_col_style">
                                    <a target="_blank" href="<?php echo site_url("getListingDetail/".$listingDetails['listing_type_id']);?>"><?php echo $listingDetails['listing_type_id']; ?></a>
                                </td>
                                <td class="mmp_listing_col_style">
                                    <?php echo $listingDetails['listing_type']; ?>
                                </td>
                                <td class="mmp_listing_col_style">
                                    <?php echo $listingDetails['listing_title']; ?>
                                </td>
                                <td class="mmp_listing_col_style">
                                    <?php echo $listingDetails['expiry_date']; ?>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>
                </div>
            </div>
        <?php
        } else {
            ?>
            <div class="mmp_note_text_container" style="float:left;width:97%;">
                <div style="width:96%;float:left;color:#666;">
                    No Listings to display for this client.
                </div>
            </div>
            <?php
        }
        ?>
        <div style="clear:both;"></div>
        <div id="product_dd_cnt" class="mmp_note_text_container" style="display:none;padding-top:10px;margin-top:10px;float:left;width:98%;background:#E5EECC;">
            <div style="width:16%;float:left;padding-top:5px;">
                <span style="font-weight:bold;">Choose Subscription</span>:
            </div>
            <?php
            if(count($validSubscriptions) > 0){
                    ?>
                    <div style="width:50%;float:left;color:#666;">
                        <select name="product_dd" id="product_dd" onchange="showSubscriptionDetails(this);" style="width:400px;">
                            <option value="default">select subscription</option>
                            <?php
                            foreach($validSubscriptions as $subscriptionId => $subscription){
                                ?>
                                <option value="<?php echo $subscriptionId; ?>" >
                                    <?php echo $subscription['BaseProdCategory'],"-",$subscription['BaseProdSubCategory']," : ",$subscriptionId; ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
					<div style="padding-left:5px;width:10%;float:right;padding-top:5px;">
						<a href="javascript:void(0);" onclick="showBMSContainer();">Show BMS Keys</a>
					</div>
					<div style="width:6%;float:right;padding-top:5px;">
						<a href="javascript:void(0);" onclick="refreshPage();">Refresh</a>
					</div>
					
                    <?php
            } else {
                ?>
                <div style="width:75%;float:left;color:#666;padding-top:5px;">
                    No valid search product subscription found for this client
                </div>
                <?php
            }
            ?>
        </div>
		
		<div id="bms_details" class="mmp_note_text_container" style="display:none;padding-top:10px;margin-top:10px;float:left;width:98%;">
            <div style="width:16%;float:left;">
                <span style="font-weight:bold;">NONE</span>:
            </div>
            <div class="spacer20"></div>
		</div>
		
        <div id="product_details" class="mmp_note_text_container" style="display:none;padding-top:10px;margin-top:10px;float:left;width:98%;">
            <div style="width:16%;float:left;">
                <span style="font-weight:bold;">Institute selected</span>:
            </div>
            <div style="width:75%;float:left;font-size:14px;" id="sh_insti_details">
            </div>
            <div class="spacer20"></div>
            <div style="width:16%;float:left;">
                <span style="font-weight:bold;">Subscription Details</span>:
            </div>
            <div style="width:75%;float:left;color:#666;">
                    <div style="width:25%;float:left;font-weight:bold;">Subscription Id</div>
                    <div style="float:left;width:1%;margin-right:5px;">:</div>
                    <div style="width:70%;float:left;" id="sh_sub_id"></div>
                    
                    <div style="width:25%;float:left;font-weight:bold;">Base Product Category</div>
                    <div style="float:left;width:1%;margin-right:5px;">:</div>
                    <div style="width:70%;float:left;" id="sh_bpc"></div>
                    
                    <div style="width:25%;float:left;font-weight:bold;">Base Product SubCategory</div>
                    <div style="float:left;width:1%;margin-right:5px;">:</div>
                    <div style="width:70%;float:left;" id="sh_bpsc"></div>
                    
                    <div style="width:25%;float:left;font-weight:bold;">Total Quantity</div>
                    <div style="float:left;width:1%;margin-right:5px;">:</div>
                    <div style="width:70%;float:left;" id="sh_tbpq"></div>
                    
                    <div style="width:25%;float:left;font-weight:bold;">Remaining Quantity</div>
                    <div style="float:left;width:1%;margin-right:5px;">:</div>
                    <div style="width:70%;float:left;" id="sh_tbrq"></div>
                    
                    <div style="width:25%;float:left;font-weight:bold;">Start Date</div>
                    <div style="float:left;width:1%;margin-right:5px;">:</div>
                    <div style="width:70%;float:left;" id="sh_ssd"></div>
                    
                    <div style="width:25%;float:left;font-weight:bold;">End Date</div>
                    <div style="float:left;width:1%;margin-right:5px;">:</div>
                    <div style="width:70%;float:left;" id="sh_sed"></div>
            </div>
        </div>
        <div id="product_details_error" class="mmp_note_text_container" style="display:none;padding-top:10px;margin-top:10px;float:left;width:98%;">
        </div>
        <div id="sponsored_container" style="display:none;">
            <div id="main_cat_cont"></div>
        </div>
	</fieldset>
</div>
<script type="text/javascript">

    var subscriptionDetails = eval(<?php echo json_encode($validSubscriptions); ?>);
    var clientUserId = "<?php echo $userDetails['clientUserId'];?>";
    var categories;
    var sp_cities;
    var sp_countries;
    var sp_listing_id = "";
    var sp_listing_name = "";
    var sp_client_userid = "<?php echo $userDetails['clientUserId'];?>";
    var sp_subscription_id = "";
    var currentYear = <?php echo $currentYear;?>;
    var sp_start_day = "";
    var sp_start_month = "";
    var sp_start_year = "";
	var sp_location_id;
	var sp_category_id;
	var sp_product_reach;
	var sp_base_product_id;
	var sp_course_list;
	var sp_form_posted = false;
	var sp_interval;
    var showProductDropdown = function(obj){
        if(obj.value != undefined){
            sp_listing_id = obj.value;
            sp_listing_name = obj.className;
            var ele_sh_insti_details = document.getElementById("sh_insti_details");
            ele_sh_insti_details.innerHTML = "<a target='_blank' href='/getListingDetail/"+sp_listing_id+"/'>"+sp_listing_name+"</a>";
            var catDDEle = document.getElementById('cat_dd');
            if(catDDEle){
                catDDEle.selectedIndex = 0;
            }
			
			var bmsCont = document.getElementById("bms_details");
			if(bmsCont){
				bmsCont.style.display = "none";
                bmsCont.innerHTML = "";    
            }
			
            var subCatContEle = document.getElementById("subcat_cont");
            if(subCatContEle){
                subCatContEle.innerHTML = "";    
            }
            var locEle = document.getElementById("loc_cont");
            if(locEle){
                locEle.innerHTML = "";    
            }
            var errorEle = document.getElementById("error_msg_cont");
            if(errorEle){
                errorEle.innerHTML = "";    
            }
            var sbNationalBtn = document.getElementById("sb_national_btn");
            if(sbNationalBtn){
                sbNationalBtn.innerHTML = "";    
            }
            var courseEle = document.getElementById("sb_course_cont");
            if(courseEle){
                courseEle.innerHTML = "";    
            }
            var courseSbmErrEle = document.getElementById("sb_course_error_cont");
            if(courseSbmErrEle){
                courseSbmErrEle.innerHTML = "";    
            }
            var courseSubmitEle = document.getElementById("sb_course_submit_cont");
            if(courseSubmitEle){
                courseSubmitEle.innerHTML = "";    
            }
			var finalMsgEle = document.getElementById("final_msg_cont");
            if(finalMsgEle){
                finalMsgEle.innerHTML = "";    
            }
			var dateContEle = document.getElementById("date_cont");
			if(dateContEle){
                dateContEle.innerHTML = "";    
            }
            
            
            var ele = document.getElementById("product_dd_cnt");
            ele.style.display = "block";
        }
    }
    
    var showSubscriptionDetails = function(obj){
        if(obj.value != undefined && obj.value != "default"){
            var subIds = ShikshaHelper.getDictionaryKeys(subscriptionDetails);
            var subscriptionId = obj.value;
            if(ShikshaHelper.in_array(subscriptionId, subIds)){
				sp_subscription_id = subscriptionId;
                var cnt = document.getElementById('product_details');
				cnt.style.display = "block";
                var errorContainer = document.getElementById('product_details_error');
                errorContainer.style.display = "none";
				errorContainer.innerHTML = "";
                
                var subscription = subscriptionDetails[obj.value];
                var ele_sub_id = document.getElementById('sh_sub_id');
                var ele_sh_bpc = document.getElementById('sh_bpc');
                var ele_sh_bpsc = document.getElementById('sh_bpsc');
                var ele_sh_tbpq = document.getElementById('sh_tbpq');
                var ele_sh_tbrq = document.getElementById('sh_tbrq');
                var ele_sh_ssd = document.getElementById('sh_ssd');
                var ele_sh_sed = document.getElementById('sh_sed');
                
                ele_sub_id.innerHTML = subscription.SubscriptionId;
                ele_sh_bpc.innerHTML = subscription.BaseProdCategory;
                ele_sh_bpsc.innerHTML = subscription.BaseProdSubCategory;
                ele_sh_tbpq.innerHTML = subscription.TotalBaseProdQuantity;
                ele_sh_tbrq.innerHTML = subscription.BaseProdRemainingQuantity;
                ele_sh_ssd.innerHTML = subscription.SubscriptionStartDate;
                ele_sh_sed.innerHTML = subscription.SubscriptionEndDate;
                sp_base_product_id = subscription.BaseProductId;
                var ele_sh_insti_details = document.getElementById("sh_insti_details");
                ele_sh_insti_details.innerHTML = "<a target='_blank' href='/getListingDetail/"+sp_listing_id+"/'>"+sp_listing_name+"</a>";
                getCityCategoryInformation(subscriptionId, clientUserId, "handleCityCategoryInformation");
            } else {
                var cnt = document.getElementById('product_details');
                cnt.style.display = "none";
                var cnt = document.getElementById('product_details_error');
                cnt.style.display = "block";
				cnt.innerHTML = getProductErrorHTML();
				
                var spnsored_cont = document.getElementById('sponsored_container');
                spnsored_cont.style.display = "none";
            }
        } else {
            var cnt = document.getElementById('product_details');
            cnt.style.display = "none";
            var cnt = document.getElementById('product_details_error');
            cnt.style.display = "block";
			cnt.innerHTML = getProductErrorHTML();
				
            var spnsored_cont = document.getElementById('sponsored_container');
            spnsored_cont.style.display = "none";
        }
		var bmsDetailsCont = document.getElementById('bms_details');
		if(bmsDetailsCont){
			bmsDetailsCont.style.display = "none";	
		}
    }
    
    var getCityCategoryInformation = function(subscriptionId, clientUserId, callback){
        var subIds = ShikshaHelper.getDictionaryKeys(subscriptionDetails);
        if(ShikshaHelper.in_array(subscriptionId, subIds)){
            var requestUrl = "/search/SearchEnterprise/getCityCategoryForSubscription/"+subscriptionId+"/"+clientUserId;
            var mysack = new sack();
            mysack.requestFile = requestUrl;
            mysack.method = 'POST';
            mysack.onError = function(){};
            mysack.onLoading = function(){
            };
            mysack.onCompletion = function() {
                
                if(typeof callback != "undefined"){
                    var func = window[callback];
                    if(typeof func === 'function') {
                        var response = eval('(' + mysack.response + ')');
                        func(response);
                    }
                }
            };
            mysack.runAJAX();
        }
    }
    
    var handleCityCategoryInformation = function(response){
        if(response.flag){
            categories = response.categories;
            sp_cities = response.cities;
            sp_countries = response.countries;
            hideMainCategoryContainer('all');
            var manCatContEle = document.getElementById("main_cat_cont");
            if(response.flag == "national"){
                var html = getMainCatHTML(categories, 'national');
            } else if(response.flag == "studyabroad"){
                var html = getMainCatHTML(categories, 'studyabroad');
            }
            if(manCatContEle){
                manCatContEle.innerHTML = html;
            }
        }
    }
    
    var getMainCatHTML = function(categories, flag){
        var mainCatKeys = ShikshaHelper.getDictionaryKeys(categories);
        var html = '<div style="clear:both;"></div>';
                html = '<div id="sp_main_cat_cont" class="mmp_note_text_container" style="display:block;padding-top:10px;margin-top:10px;float:left;width:98%;">';
                    html += '<div style="width:16%;float:left;padding-top:5px;">';
                        html += '<span style="font-weight:bold;">Choose Category</span>:';
                    html += '</div>';
                    html += '<div style="width:75%;float:left;color:#666;">';
                        html += '<select name="cat_dd" id="cat_dd" onchange="showSubCatDD(this, \''+flag+'\');" style="width:200px;">';
                            html += '<option value="defaultcat">select category</option>';
                            for(var key in mainCatKeys){
                                var catKey = mainCatKeys[key];
                                var maincatId = categories[catKey].parent_category_id;
                                var maincatName = categories[catKey].parent_category_name;
                            html += '<option value="'+ maincatId +'">'+ maincatName +'</option>';
                            }
                        html += '</select>';
                    html += '</div>';
                    html += '<div id="subcat_cont"></div>';
                    html += '<div id="loc_cont"></div>';
                    html += '<div id="date_cont"></div>';
                    html += '<div id="error_msg_cont"></div>';
                    html += '<div id="sb_national_btn"></div>';
                    html += '<div id="sb_course_cont"></div>';
                    html += '<div id="sb_course_error_cont"></div>';
                    html += '<div id="sb_course_submit_cont"></div>';
                    html += '<div id="final_msg_cont"></div>';
                html += '</div>';
        return html;
    }
    
    var showSubCatDD = function(obj, flag){
        var html = "";
        if(flag == "national"){
            if(obj.value != "defaultcat"){
                var mainCatId = obj.value;
                html = '<div id="sp_subcat_cont" style="display:block;margin-top:10px;float:left;width:100%;">';
                    html += '<div style="width:16%;float:left;padding-top:5px;">';
                        html += '<span style="font-weight:bold;">Choose Sub Category</span>:';
                    html += '</div>';
                    html += '<div style="width:75%;float:left;color:#666;">';
                        html += '<select name="subcat_dd" id="subcat_dd" style="width:200px;">';
                            html += '<option value="defaultsubcat">select sub category</option>';
                            var subcategories = categories[mainCatId].subcategories;
                            for(var key in subcategories){
                                var subcatid = key;
                                var subcatname = subcategories[key];
                            html += '<option value="'+ subcatid +'">'+ subcatname +'</option>';
                            }
                        html += '</select>';
                    html += '</div>';
                html += '</div>';
            }
        }
        
        hideMainCategoryContainer(); // Hide containers
        var subCatContEle = document.getElementById("subcat_cont");
        if(subCatContEle && flag == "national"){
            subCatContEle.innerHTML = html;
        }
        if(obj.value != "defaultcat"){
            var locEle = document.getElementById("loc_cont");
            var sbNationalBtn = document.getElementById("sb_national_btn");
            var dateContEle = document.getElementById("date_cont");
            
            if(locEle){
                var locationHTML = getLocationDD(flag);
                locEle.innerHTML = locationHTML;
            }
            if(dateContEle){
                //var dateHTML = getDateHTML(flag);
                //dateContEle.innerHTML = dateHTML;
            }
            
            if(sbNationalBtn){
                var submitBtnHTML = getSubmitBtnHtml(flag);
                sbNationalBtn.innerHTML = submitBtnHTML;    
            }
        }
    }
    
    var getDateHTML = function(){
        var html = "";
        var 
        html = '<div style="clear:both;"></div>';
            html += '<div id="start_date_cont" style="display:block;margin-top:10px;float:left;width:100%;">';
                html += '<div style="width:16%;float:left;padding-top:5px;">';
                    html += '<span style="font-weight:bold;">Start date</span>:';
                html += '</div>';
                html += '<div style="width:75%;float:left;color:#666;">';
                    html += '<select name="day_dd" id="day_dd" style="width:50px;margin-right:10px;">';
                        html += '<option value="defaultday">day</option>';
                        for(var i= 1; i <= 31; i++){
                            html += '<option value="'+ i +'">'+ i +'</option>';    
                        }
                    html += '</select>';
                    html += '<select name="month_dd" id="month_dd" style="width:60px;margin-right:10px;">';
                        html += '<option value="defaultmonth">month</option>';
                        var monthList = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                        for(var index in monthList){
                            var k = parseInt(index) + 1;
                            html += '<option value="'+ k +'">'+ monthList[index] +'</option>';
                        }
                    html += '</select>';
                    html += '<select name="year_dd" id="year_dd" style="width:70px;margin-right:10px;">';
                        html += '<option value="defaultyear">year</option>';
                        for(var i = parseInt(currentYear); i < parseInt(currentYear) + 20; i++ ){
                            html += '<option value="'+ i +'">'+ i +'</option>';
                        }
                    html += '</select>';
                html += '</div>';
            html += '</div>';
        html += '</div>';
        return html;
    }
    
    
    var getLocationDD = function(flag){
        var html = "";
        if(flag != undefined){
            if(flag == "national"){
                html = '<div style="clear:both;"></div>';
                    html = '<div id="sp_cities_cont" style="display:block;margin-top:10px;float:left;width:100%;">';
                        html += '<div style="width:16%;float:left;padding-top:5px;">';
                            html += '<span style="font-weight:bold;">Choose City</span>:';
                        html += '</div>';
                        html += '<div style="width:75%;float:left;color:#666;">';
                            html += '<select name="cities_dd" id="cities_dd" style="width:200px;">';
                                html += '<option value="defaultcity">select city</option>';
                                for(var key in sp_cities){
                                    var cityId = key;
                                    var cityName = sp_cities[key];
                                html += '<option value="'+ cityId +'">'+ cityName +'</option>';
                                }
                            html += '</select>';
                        html += '</div>';
                    html += '</div>';
                html += '</div>';
            } else if(flag == "studyabroad"){
                html = '<div style="clear:both;"></div>';
                    html = '<div id="sp_cities_cont" style="display:block;margin-top:10px;float:left;width:100%;">';
                        html += '<div style="width:16%;float:left;padding-top:5px;">';
                            html += '<span style="font-weight:bold;">Choose Country</span>:';
                        html += '</div>';
                        html += '<div style="width:75%;float:left;color:#666;">';
                            html += '<select name="cities_dd" id="cities_dd" style="width:200px;">';
                                html += '<option value="defaultcity">select country</option>';
                                for(var key in sp_countries){
                                    var countryId = key;
                                    var countryName = sp_countries[key];
                                html += '<option value="'+ countryId +'">'+ countryName +'</option>';
                                }
                            html += '</select>';
                        html += '</div>';
                    html += '</div>';
                html += '</div>';
            }
        }
        return html;
    }
    
    var getSubmitBtnHtml = function(flag){
        var html = "";
        html = '<div style="clear:both;"></div>';
            html = '<div id="national_submit_cont" style="display:block;margin-top:10px;float:left;width:100%;">';
                html += '<div style="width:16%;float:left;padding-top:5px;">';
                    html += '<span style="font-weight:bold;">&nbsp;</span>';
                html += '</div>';
                html += '<div style="width:75%;float:left;color:#666;">';
                    html += '<input type="button" value="submit" onclick="submitDetails(\''+flag+'\');" style="height:25px;width:75px;background-color:#F78640"/>';
                html += '</div>';
            html += '</div>';
        html += '</div>';
        return html;
    }
    
    var getCourseSubmitBtnHtml = function(flag){
        var html = "";
        html = '<div style="clear:both;"></div>';
            html = '<div id="national_submit_cont" style="display:block;margin-top:10px;float:left;width:100%;">';
                html += '<div style="width:16%;float:left;padding-top:5px;">';
                    html += '<span style="font-weight:bold;">&nbsp;</span>';
                html += '</div>';
                html += '<div style="width:75%;float:left;color:#666;">';
                    html += '<input type="button" value="submit" onclick="submitCourseDetails(\''+flag+'\');" style="height:25px;width:75px;background-color:#F78640;"/>';
                html += '</div>';
            html += '</div>';
        html += '</div>';
        return html;
    }
    
    var submitDetails = function(flag){
        if(flag){
			var errorMsgContEle = document.getElementById("error_msg_cont");
			if(errorMsgContEle){
                errorMsgContEle.innerHTML = getErrorMsgHtml("<img src='/public/images/loader_hpg.gif' height='20px' width='20px' />");
            }
			//Hide second level dom elements
            var courseEle = document.getElementById("sb_course_cont");
            if(courseEle){
                courseEle.innerHTML = "";    
            }
            var courseSubmitEle = document.getElementById("sb_course_submit_cont");
            if(courseSubmitEle){
                courseSubmitEle.innerHTML = "";    
            }
            var courseSbmErrEle = document.getElementById("sb_course_error_cont");
            if(courseSbmErrEle){
                courseSbmErrEle.innerHTML = "";    
            }
            var finalMsgContEle = document.getElementById("final_msg_cont");
            if(finalMsgContEle){
                finalMsgContEle.innerHTML = "";    
            }
			
            var mainCatDD = document.getElementById("cat_dd");
            var subCatDD = document.getElementById("subcat_dd");
            var locationDD = document.getElementById("cities_dd");
            
            var cityId = locationDD.value;
            var instituteId = sp_listing_id;
            var mainCatId = mainCatDD.value;
            sp_product_reach = flag;
			
            var errorMsgContEle = document.getElementById("error_msg_cont");
            if(flag == "national"){
                var subCatId = subCatDD.value;
				if(mainCatDD && subCatDD && locationDD){
                    if(subCatId != "defaultsubcat" && cityId != "defaultcity" && instituteId != ""){
						sp_category_id = subCatId;
						sp_location_id = cityId;
                        getCourses(instituteId, cityId, subCatId, flag, "handleNationalCoursesCallback");
                    } else {
                        var errorMsgHTML = getErrorMsgHtml("Dropdown fields cannot have default value");
                        if(errorMsgContEle){
                            errorMsgContEle.innerHTML = errorMsgHTML;
                        }
                    }
                }
            } else if(flag == "studyabroad"){
                if(mainCatDD && locationDD){
                    if(mainCatId != "defaultcat" && cityId != "defaultcity" && instituteId != ""){
						sp_category_id = mainCatId;
						sp_location_id = cityId;
                        getCourses(instituteId, cityId, mainCatId, flag, "handleNationalCoursesCallback");
                    } else {
                        var errorMsgHTML = getErrorMsgHtml("Dropdown fields cannot have default value");
                        if(errorMsgContEle){
                            errorMsgContEle.innerHTML = errorMsgHTML;
                        }
                    }
                }
            }
        }
    }
    
	var resetFormPost = function(){
		sp_form_posted = false;
		clearInterval(sp_interval);
	}
	
    var submitCourseDetails = function(){
		if(sp_form_posted == false){
			var errorMsgContEle = document.getElementById("sb_course_error_cont");
			if(errorMsgContEle){
                errorMsgContEle.innerHTML = getErrorMsgHtml("<img src='/public/images/loader_hpg.gif' height='20px' width='20px' />");
            }
			var finalMsgCont = document.getElementById("final_msg_cont");
			if(finalMsgCont){
				finalMsgCont.innerHTML = "";
			}
			
			sp_form_posted = true;
			sp_interval = setInterval(function(){resetFormPost();}, 2000);
			var elements = document.getElementsByName("course_ids");
			var courseCount = 0;
			var courseStr = "";
			for(var i=0; i< elements.length;i++){
				if(elements[i].type == 'checkbox' && elements[i].checked) {
					courseStr += elements[i].value + ",";
					courseCount++;
				}
			}
			if(courseCount > 0){
				var formFields = {};
				formFields['courses'] = courseStr;
				formFields['sp_client_userid'] = sp_client_userid;
				formFields['sp_listing_id'] = sp_listing_id;
				formFields['sp_subscription_id'] = sp_subscription_id;
				formFields['sp_product_reach'] = sp_product_reach;
				formFields['sp_location_id'] = sp_location_id;
				formFields['sp_category_id'] = sp_category_id;
				formFields['sp_search_type'] = 'course';
				formFields['sp_base_product_id'] = sp_base_product_id;
				consumeSearchProductSubscription(formFields);
			} else {
				var errorMsg = "";
				if(courseCount <= 0){
					errorMsg += "Please select at least one course";
				}
				var errorMsgHTML = getErrorMsgHtml(errorMsg);
				if(errorMsgContEle){
					errorMsgContEle.innerHTML = errorMsgHTML;
				}
			}
		}
    }
    
    var getErrorMsgHtml = function(errorMsg){
        html = '<div style="clear:both;"></div>';
            html += '<div id="error_msg" style="display:block;margin-top:10px;float:left;width:100%;">';
                html += '<div style="width:16%;float:left;padding-top:5px;">';
                    html += '<span style="font-weight:bold;">&nbsp;</span>';
                html += '</div>';
                html += '<div style="width:75%;float:left;color:red;">';
                    html += errorMsg;
                html += '</div>';
            html += '</div>';
        html += '</div>';
        return html;
    }
    
    var handleNationalCoursesCallback = function(response){
        var html = "";
        var subscription_type  = response.subscription_type;
        if(response.count > 0){
            var courseList = response.courselist;
			sp_course_list = courseList;
            html = '<div style="clear:both;"></div>';
            html += '<form enctype="multipart/form-data" accept-charset="utf-8" id="form_search_sponsored" name="form_search_sponsored" method="post" action="/search/SearchEnterprise/updateSearchSponsoredData/" onSubmit="return submitCourseDetails();">';
                html += '<div id="sp_courses_cont" style="display:block;margin-top:20px;float:left;width:100%;">';
                    html += '<div style="width:16%;float:left;padding-top:5px;">';
                        html += '<span style="font-weight:bold;">Choose courses</span>:';
                    html += '</div>';
                    html += '<div style="width:75%;float:left;color:#666;">';
                                for(var key in courseList){
                                    html += '<input type="checkbox" name="course_ids" value="'+key+'"/><span style="text-align:middle;">'+courseList[key]+'</span><br/>';
                                }
                    html += '</div>';
                        
                        html += '<input type="hidden" name="sp_subscription_id" value="'+sp_subscription_id+'"/>';
                        html += '<input type="hidden" name="sp_client_userid" value="'+sp_client_userid+'"/>';
                        html += '<input type="hidden" name="sp_listing_id" value="'+sp_listing_id+'"/>';
                        
                        html += '<div style="clear:both;"></div>';
                        html += '<div id="national_submit_cont" style="display:block;margin-top:10px;float:left;width:100%;">';
                            html += '<div style="width:16%;float:left;padding-top:5px;">';
                                html += '<span style="font-weight:bold;">&nbsp;</span>';
                            html += '</div>';
                            html += '<div style="width:75%;float:left;color:#666;">';
                                html += '<input type="button" value="submit" onclick="submitCourseDetails();" style="height:25px;width:75px;background-color:#F78640;"/>';
                            html += '</div>';
                        html += '</div>';
                    html += '</div>';
                html += '</div>';
            html += '</form>';
        }
        
        var courseEle = document.getElementById("sb_course_cont");
        if(html != "" && response.count > 0){
            if(courseEle){
                courseEle.innerHTML = html;
            }
        } else {
            var errorMsgHTML = getErrorMsgHtml("No course present for this (sub)category/location pair, Try different pair");
            if(courseEle){
                courseEle.innerHTML = errorMsgHTML;
            }
        }
    }
    
	var getErrorMessageForErrorKey = function(responseObject){
		var errorFlag = 0;
		var successFlag = 0;
		var errorKeys;
		var sponsorType;
		if(responseObject.error){
			errorFlag = responseObject.error;
		}
		if(responseObject.success){
			successFlag = responseObject.success;
		}
		if(responseObject.error_type){
			errorKeys = responseObject.error_type;
		}
		if(responseObject.sponsor_type){
			sponsorType = responseObject.sponsor_type;
		}
		var errorMsg = "";
		for(var errKey in errorKeys){
			switch(errorKeys[errKey]){
				case 'LISTING_ALREADY_SPONSORED_SET':
					var count = 0;
					var courseNameStr = "";
					var sponsoredListings = [];
					if(responseObject.sponsored_listing){
						sponsoredListings = responseObject.sponsored_listing;
						for(var ckey in sponsoredListings){
							if(sp_course_list[sponsoredListings[ckey]]){
								courseNameStr += sp_course_list[sponsoredListings[ckey]] + ", ";
								count++;
							}
						}
					}
					courseNameStr = ShikshaHelper.trim(courseNameStr);
					courseNameStr = courseNameStr.replace(/,+$/,"");
					var coursesStr = count + " courses("+ courseNameStr +") have already been set as "+ sponsorType+".";
					if(count == 1){
						var coursesStr = count + " course("+ courseNameStr +") has already been set as "+sponsorType+".";
					}
					errorMsg += "<br/>" + coursesStr;
					break;
				
				case 'INSERT_SPONSORED_LISTING_FAILED':
					errorMsg += "<br/> DB insertion failed. Please try again later.";
					break;
				case 'DATA_IMPROPER':
					errorMsg += "<br/> Data provided for sponsored listing is not proper. Please provide valid data.";
					break;
				case 'NOT_ENOUGH_SUBSCRIPTION_POINTS':
					errorMsg += "<br/> User don't have enough subscription points.";
					break;
				case 'MAX_LIMIT_WILL_REACH':
					errorMsg += "<br/> Course count is greater than max limit for this (sub)category/location pair. Please try with less number of courses.";
					break;
				case 'MAX_LIMIT_REACHED_FOR_SPONSOR_TYPE':
					errorMsg += "<br/> Max limit reached for this (sub)category/location pair. Please try with some other (sub)category/location pair.";
					break;
				case 'NOT_ALL_REQD_PARAMS':
					errorMsg += "<br/> Please provide all the required parameters required for this operation.";
					break;
				case 'INVALID_LOCATION_FOR_SUBSCRIPTION':
					errorMsg += "<br/> Location provided is not valid for this type of subscription.";
					break;
				case 'INVALID_CATEGORY_FOR_SUBSCRIPTION':
					errorMsg += "<br/> Category provided is not valid for this type of subscription.";
					break;
				case 'SUBSCRIPTION_EXPIRED':
					errorMsg += "<br/> User subscription has been expired.";
					break;
				case 'USER_DONT_HAVE_SUBSCRIPTION':
					errorMsg += "<br/> User don't have this subscription.";
					break;
				case 'SUBSCRIPTION_NOT_STARTED_YET':
					errorMsg += "<br/> Subscription has not yet started.";
					break;
				
				default:
					errorMsg += "<br/> Server side error occured, please try again!";
			}	
		}
		
		var successMsg = "";
		if(successFlag == "1"){
			var count = 0;
			if(responseObject.count){
				count = responseObject.count;
			}
			if(count == 1){
				successMsg = count + " course set as " + sponsorType + " successfully.";	
			} else {
				successMsg = count + " courses set as " + sponsorType + " successfully.";	
			}
			if(responseObject.remainingQuantity){
				var qty = parseInt(responseObject.remainingQuantity);
				if(qty <= 0){
					qty = 0;
				}
				successMsg += "<br/>Remaining subscription quantity:<b> "+ qty+"</b>";
			}
			
			if(sponsorType == "featured" || sponsorType == "banner"){
				var bmsKey = "";
				if(responseObject.bmskey){
					bmsKey = responseObject.bmskey;
				}
				successMsg += "<br/>Please use BMSKey listed under <h3 onclick='showBMSContainer();'>Show BMS Keys</h3> link to upload "+sponsorType+" image.";
			}
		}
		
		var finalMsgCont = document.getElementById("final_msg_cont");
		if(errorFlag == "1"){
		    if(finalMsgCont){
		        finalMsgCont.innerHTML = getFinalDisplayMessage("<h3>Error</h3>:" + errorMsg, "error");
		    }
		} else if(successFlag == "1"){
			if(finalMsgCont){
		        finalMsgCont.innerHTML = getFinalDisplayMessage("<h3>Success</h3>:<br/>" + successMsg);
		    }
		}
	}
	
    var getCourses = function(instituteId, cityId, categoryId, flag, callback){
        if(instituteId && cityId && categoryId){
            if(flag == "national"){
                var requestUrl = "/search/SearchEnterprise/getCoursesByLocationCategory/"+sp_subscription_id+"/"+instituteId+"/"+cityId+"/"+categoryId+"/";
            } else if(flag == "studyabroad"){
                var requestUrl = "/search/SearchEnterprise/getStudyAbroadCoursesByLocationCategory/"+sp_subscription_id+"/"+instituteId+"/"+cityId+"/"+categoryId+"/";
            }
            
            var mysack = new sack();
            mysack.requestFile = requestUrl;
            mysack.method = 'POST';
            mysack.onError = function(){};
            mysack.onLoading = function(){
            };
            mysack.onCompletion = function() {
				var errorMsgContEle = document.getElementById("error_msg_cont");
				if(errorMsgContEle){
					errorMsgContEle.innerHTML = "";
				}
                if(typeof callback != "undefined"){
                    var func = window[callback];
                    if(typeof func === 'function') {
                        var response = eval('(' + mysack.response + ')');
                        func(response);
                    }
                }
            };
            mysack.runAJAX();
        }
    }
    
    var consumeSearchProductSubscription = function(formFields){
        var postParamString = "";
        for(var param in formFields){
            postParamString += param + "=" + formFields[param] + "&";
        }
        var requestUrl = "/search/SearchEnterprise/consumeSearchProductSubscription/" + "?" + postParamString;
        var mysack = new sack();
        mysack.requestFile = requestUrl;
        mysack.method = 'POST';
        mysack.onError = function(){};
        mysack.onLoading = function(){};
        mysack.onCompletion = function() {
			var errorMsgContEle = document.getElementById("sb_course_error_cont");
			if(errorMsgContEle){
                errorMsgContEle.innerHTML = "";
            }
			var response = eval('(' + mysack.response + ')');
			if(response.updatedSubscriptionDetails){
				var updatedSubscriptionDetails = response.updatedSubscriptionDetails;
				subscriptionDetails = updatedSubscriptionDetails;
			}
			getErrorMessageForErrorKey(response);
        };
        mysack.runAJAX();
    }
    
    var getFinalDisplayMessage = function(msg, flag){
		if(flag == "error"){
			var backgroundStyle = "background: #FCDFFF;";
			var borderStyle = "border:1px solid pink;"
		} else {
			var backgroundStyle = "background: #E5EECC;";
			var borderStyle = "border:1px solid #C3C3C3;"
		}
        html = '<div style="clear:both;"></div>';
		
            html += '<div class="mmp_note_text_container" style="'+backgroundStyle+borderStyle+'margin-top:12px;padding:10px 5px;float:left;width:99%;">';
                html += '<div style="width:100%;float:left;">';
                    html += msg;
					//if(flag != "error"){
					//	html += " <a href='/search/SearchEnterprise/setSponsoredListing?clientuid="+sp_client_userid+"&from=url'>Click here to refresh page to see changes in subscription details</a>";
					//}
                html += '</div>';
            html += '</div>';
        html += '</div>';
        return html;
    }
    
    var hideMainCategoryContainer = function(flag){
        var parentContEle = document.getElementById("sponsored_container");
        var manCatContEle = document.getElementById("main_cat_cont");
        var subCatContEle = document.getElementById("subcat_cont");
        var errorEle = document.getElementById("error_msg_cont");
        var sbNationalBtn = document.getElementById("sb_national_btn");
        var locEle = document.getElementById("loc_cont");
        var courseEle = document.getElementById("sb_course_cont");
        var courseSubmitEle = document.getElementById("sb_course_submit_cont");
        var courseSbmErrEle = document.getElementById("sb_course_error_cont");
        var dateContEle = document.getElementById("date_cont");
        var finalMsgCont = document.getElementById("final_msg_cont");
        
        if(parentContEle){
            parentContEle.style.display = "block";
        }
        if(flag == "all"){
            if(manCatContEle){
                manCatContEle.innerHTML = "";    
            }    
        }
        if(subCatContEle){
            subCatContEle.innerHTML = "";    
        }
        if(errorEle){
            errorEle.innerHTML = "";    
        }
        if(sbNationalBtn){
            sbNationalBtn.innerHTML = "";    
        }
        if(locEle){
            locEle.innerHTML = "";    
        }
        if(courseEle){
            courseEle.innerHTML = "";    
        }
        if(courseSubmitEle){
            courseSubmitEle.innerHTML = "";    
        }
        if(courseSbmErrEle){
            courseSbmErrEle.innerHTML = "";    
        }
        if(dateContEle){
            dateContEle.innerHTML = "";
        }
        if(finalMsgCont){
            finalMsgCont.innerHTML = "";
        }
        
    }

	var showBMSContainer = function(){
		hideMainCategoryContainer();
		var productCont = document.getElementById("product_details");
		if(productCont){
			productCont.style.display = "none";
		}
		var productErrorContainer = document.getElementById('product_details_error');
		if(productErrorContainer){
			productErrorContainer.style.display = "none";
			productErrorContainer.innerHTML = "";	
		}
				
		var sponsoredContEle = document.getElementById("sponsored_container");
		if(sponsoredContEle){
			sponsoredContEle.style.display = "none";
		}
		
		var productDDEle = document.getElementById('product_dd');
		if(productDDEle){
			productDDEle.selectedIndex = 0;
		}
			
		var bmsCont = document.getElementById("bms_details");
		if(bmsCont){
			bmsCont.style.display = "block";
		}
		getBMSKeys();
	}

	var displayBMSKeysData = function(response){
		var bannerCourses = {};
		var featuredCourses = {};
		if(response.banner){
			bannerCourses = response.banner;
		}
		if(response.featured){
			featuredCourses = response.featured;
		}
		
		var html = "";
			html += '<div style="clear:both;"></div>';
			html += '<div class="mmp_note_text_container" style="float:left;width:97%;border:none;">';
				html += '<div style="margin-bottom:10px;width:90%;float:left;">';
					html += '<span style="font-weight:bold;font-size:15px;">BMSKeys Information</span>';
				html += '</div>';
				html += '<div style="margin-bottom:10px;width:90%;float:left;">';
					html += '<span style="font-size:13px;">Institute Name: <a target="_blank" href="/getListingDetail/'+sp_listing_id+'/">'+sp_listing_name+'</a></span>';
				html += '</div>';
				if(featuredCourses.length > 0){
					html += '<div style="margin-bottom:10px;width:90%;float:left;">';
						html += '<span style="font-size:13px;font-weight:bold;">Featured BMS Keys:</span>';
					html += '</div>';
					var indexLength = featuredCourses.length;
					for(var i=0; i < indexLength; i++){
						var course = featuredCourses[i];
						var courseKeys = ShikshaHelper.getDictionaryKeys(course);
						var courseId = course['course_id'];
						var courseName = course['course_name'];
						var bmsKey = course['bmskey'];
						var categoryName = course['category_name'];
						var locationName = course['location_name'];
						html += '<div style="width:98%;float:left;margin-bottom:5px;">';
							html += '<div style="width:21%;float:left;">';
								html += '<span style="font-weight:normal;"><a target="_blank" href="/getListingDetail/'+courseId+'/course/">'+courseName+'</a></span><span style="color:#666;"> ('+courseId+')'+'</span>';
							html += '</div>';
							html += '<div style="width:12%;float:left;">';
								html += '<span style="font-weight:normal;color:#666;font-size:12px;">'+locationName+'</span>';
							html += '</div>';
							html += '<div style="width:15%;float:left;">';
								html += '<span style="font-weight:normal;color:#666;font-size:12px;">'+categoryName+'</span>';
							html += '</div>';
							html += '<div style="width:49%;float:left;color:#666;text-align:right;font-size:13px;">';
								html += '<b>' + bmsKey + '</b>';
							html += '</div>';
						html += '</div>';
					}
				} else {
					html += '<div style="width:98%;float:left;margin-top:10px;">';
						html += 'No information available for <b>Featured BMS Keys</b>';
					html += '</div>';
				}
				if(bannerCourses.length > 0){
					html += '<div style="margin-bottom:10px;width:90%;float:left;margin-top:5px;">';
						html += '<span style="font-size:13px;font-weight:bold;">Banner BMS Keys:</span>';
					html += '</div>';
					var indexLength = bannerCourses.length;
					for(var i=0; i < indexLength; i++){
						var course = bannerCourses[i];
						var courseKeys = ShikshaHelper.getDictionaryKeys(course);
						var courseId = course['course_id'];
						var courseName = course['course_name'];
						var bmsKey = course['bmskey'];
						var categoryName = course['category_name'];
						var locationName = course['location_name'];
						html += '<div style="width:98%;float:left;margin-bottom:5px;">';
							html += '<div style="width:21%;float:left;">';
								html += '<span style="font-weight:normal;"><a target="_blank" href="/getListingDetail/'+courseId+'/course/">'+courseName+'</a></span><span style="color:#666;"> ('+courseId+')'+'</span>';
							html += '</div>';
							html += '<div style="width:12%;float:left;">';
								html += '<span style="font-weight:normal;color:#666;font-size:12px;">'+locationName+'</span>';
							html += '</div>';
							html += '<div style="width:15%;float:left;">';
								html += '<span style="font-weight:normal;color:#666;font-size:12px;">'+categoryName+'</span>';
							html += '</div>';
							html += '<div style="width:49%;float:left;color:#666;text-align:right;font-size:13px;">';
								html += '<b>' + bmsKey + '</b>';
							html += '</div>';
						html += '</div>';
					}
				} else {
					html += '<div style="width:98%;float:left;margin-top:10px;">';
						html += 'No information available for <b>Banner BMS Keys</b>';
					html += '</div>';
				}
			html += '</div>';
		return html;
	}
	
	var getBMSKeys = function(callback){
		var requestUrl = "/search/SearchEnterprise/getBMSKeysByInstitute/" + sp_listing_id;
        var mysack = new sack();
        mysack.requestFile = requestUrl;
        mysack.method = 'POST';
        mysack.onError = function(){};
        mysack.onLoading = function(){
        };
        mysack.onCompletion = function() {
            var response = eval('(' + mysack.response + ')');
			var html = displayBMSKeysData(response);
			var bmsCont = document.getElementById("bms_details");
			if(bmsCont){
				bmsCont.style.display = "block";
				bmsCont.innerHTML = html;
			}
        };
        mysack.runAJAX();
	}
	
	var getProductErrorHTML = function(){
		var html = "";
		html += '<div style="width:100%;float:left;">';
               html += '<span style="font-weight:bold;">Please select valid subscription</span>';
        html += '</div>';
		return html;
	}
	
	var refreshPage = function(){
		window.location = "/search/SearchEnterprise/setSponsoredListing/?from=url&clientuid=" + clientUserId;
	}
</script>
<?php
}
?>
<div class="clearFix"></div>
<style>
	/**-- Marketing.css has background color in it, so applying hack here for footer bg **/
	#footer{background-color:#285c9b !important;}
</style>
<?php
$this->load->view('common/footer');
?>
