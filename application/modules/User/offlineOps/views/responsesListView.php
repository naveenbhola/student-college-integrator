<?php /*
    An InfoEdge Limited Property
    ---------------------------
 */ ?>

<div style="width:100%">
    <div style="width:100%">
        <!--Start_Margin_10px-->
        <div style="margin:0 10px">
            <div id="searchFormSubContents" style="display:none">
                <input type="hidden" id="startOffSetSearch" name="startOffSetSearch" value="<?php echo $startOffset;?>"/>
                <input type="hidden" id="countOffsetSearch" name="countOffsetSearch" value="<?php echo $countOffset;?>"/>
                <input type="hidden" id="methodName" name="methodName" value="getResponsesForCriteria"/>
                <input type="hidden" id="searchCriteria" name="searchCriteria" value="<?php echo isset($searchCriteria)?$searchCriteria:'india';?>"/>
                <input type="hidden" id="endDateSearch" name="endDateSearch" value="<?php echo $endDate;?>"/>
                <input type="hidden" id="timeInterval" name="timeInterval" value="<?php echo $timeInterval;?>"/>
                <input type="hidden" id="catSearch" name="catSearch" value="<?php echo $cat;?>"/>
                <input type="hidden" id="subCatSearch" name="subCatSearch" value="<?php echo $subCat;?>"/>
                <input type="hidden" id="ldbCourseSearch" name="ldbCourseSearch" value="<?php echo $ldbCourse;?>"/>
                <input type="hidden" id="preferredCity" name="preferredCity" value="<?php echo $preferredCity;?>"/>
                <input type="hidden" id="emailSearch" name="emailSearch" value="<?php echo $email;?>"/>
            </div>
            <!--Start_ShowResutlCount-->
            <div style="width:100%">
                <div style="font-size:18px">Shiksha Operator Response Viewer</div>
                <div class="lineSpace_10">&nbsp;</div>
                <div style="width:100%">
                    <!-- Filter html START -->
                    <div id="course-cms-wrapper clear-width">
                        <ul class="primenu">
                            <li class="active"><a href="javascript:void(0);">Fresh responses</a></li>
                            <li><a href="javascript:void(0);" target="_blank" id="myLeadsLink" onclick="viewLeads();">Lead generated</a></li>
                            <div style="clear:both;"></div>
                        </ul>
                        <div class="response-content">
                            <div class="response-title">
                                <p  style="font-size: 15px;">Filter by:</p>
                            </div>
                            <div class="response-form">
                                <div class="response-field flLt">
                                    <label>From : </label>
                                    <input type="text" id="timerange_from" name="timerange_from" class="response-textfield" style="width:90px;" value="" placeholder="YYYY-MM-DD"/>
                                    <img id="timerange_from_img" onclick="timerange('from');" src="/public/images/calendar.jpg" width="15" height="15" alt="calendar" class="calendar-image"/>
                                    <label style="width:40px;">To : </label>
                                    <input type="text" id="timerange_till" name="timerange_till" class="response-textfield" style="width:90px;" value="" placeholder="YYYY-MM-DD"/>
                                    <img id="timerange_till_img" onclick="timerange('till');" src="/public/images/calendar.jpg" width="15" height="15" alt="calendar" class="calendar-image"/>
                                </div>
                                <div class="response-field flLt">
                                    <label>Category : </label>
                                    <?php if($searchCriteria == 'india'){
                                    ?>
                                    <select id="categories_holder" name="categories_holder" class="response-textfield">
                                        <?php
											echo "<option value=''>Choose a Category</option>";
											foreach($categoryList as $key=>$parent){
												echo "<option id=".$key." value=".$key.">".$parent['name']."</option>";
											}
										?>
                                    </select>
                                    <?php }
	                                    else if($searchCriteria == 'abroad'){
	                                    ?>
                                    <select id="categories_holder" name="categories_holder" class="response-textfield">
                                        <?php
											echo "<option value=''>Choose a Category</option>";
											foreach($categoryList as $key=>$parent){
												echo "<option id=".$key." value=".$key.">".$parent['name']."</option>";
											}
										?>
                                    </select>
                                    <?php } ?>
                                </div>
                                <div class="response-field flLt">
                                    <label>Preferred County : </label>
                                    <input type="radio" id="prefCountIndia" class="flLt radio-pos" name="prefCountrySelect" checked="checked" onchange="changePrefCountry('india');"/><label for="prefCountIndia" style="text-align:center; width:50px">India </label>
                                    <input type="radio" id="prefCountAbroad" class="flLt radio-pos" name="prefCountrySelect" onchange="changePrefCountry('abroad');"/><label for="prefCountAbroad" style="text-align:center; width:50px">Abroad </label>
                                </div>
                                <div class="response-field flLt">
                                    <label>Sub Category : </label>
                                    <div id="sub-categories-div">
                                        <select class="response-textfield" id="subcat_holder" name="subcat_holder">
                                            <option value=''>Choose a Sub Category</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="response-field flLt">
                                    <label>Email : </label>
                                    <input type="text" id="email" name="email" class="response-textfield" style="width:250px;" value="" placeholder="Enter Email Id"/>
                                    </select>
                                </div>
                                <div id="ldb_courses" class="response-field flLt">
                                    <label>LDB Course : </label>
                                    <div id="ldb-course-div">
                                        <select id="ldb_course_holder" name="ldb_course_holder" class="response-textfield">
                                            <option value=''>Choose an LDB Course</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="pref_city" class="response-field flLt">
                                    <label>Preferred City : </label>
                                    <div id="pref-city-div">
                                        <select id="pref_cities_holder" name="pref_cities_holder" class="response-textfield">
                                            <?php   echo "<option value=''>Choose a City</option>";
                                                    echo "<optgroup label='Metro Cities'>";
                                                    foreach($cityList_tier1 as $list) {
                                                        echo "<option id=".$list['cityId']." value=".$list['cityId'].">".$list['cityName']."</option>";
                                                    }
                                                    echo "</optgroup>";
                                            ?>
                                            <?php
                                            foreach($country_state_city_list as $list)
                                            {
                                                    if($list['CountryId'] == 2)
                                                    {
                                                            foreach($list['stateMap'] as $list2)
                                                            {
                                                                    echo '<optgroup id="'.$list2['StateId'].'" label="'.$list2['StateName'].'">';
                                                                    foreach($list2['cityMap'] as $list3) {
                                                                            echo "<option id=".$list3['CityId']." value=".$list3['CityId'].">".$list3['CityName']."</option>";
                                                                    }
                                                                    echo '</optgroup>';
                                                            }
                                                    }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <!--<div id="pref-city-div">
                                        <select id="pref_cities_holder" name="pref_cities_holder" class="response-textfield">
                                            <!?php
                                                echo "<option value=''>Choose a City</option>";
                                                foreach($cityList as $cityId=>$cityName){
                                                    echo "<option id=".$cityId." value=".$cityId.">".$cityName."</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>-->
                                </div>
                                <a href="#" onclick="saveResponsesFilterSearchCriteria();" class="orange-button filter-btn">GO</a>
                            </div>
                            <div class="clearFix"></div>
                        </div>
                    </div>
                    <!-- Filter html END -->
                </div>
                <div class="lineSpace_10">&nbsp;</div>
                
                <div style="width: 100%; border: 1px solid #CCCCCC;padding: 10px;" id="search_result_display" class="response-field flLt">Searched Criteria:
                            <?php
                                    $searchVariableArray = array();
                                    if($endDate) {
                                            $timeInterval = $timeInterval-1;
                                    	    $startDate = date('Y-m-d', strtotime($endDate. " - ".$timeInterval." days"));
                                    	    array_push($searchVariableArray,"<b>Start Date</b>: ".$startDate);
                                            array_push($searchVariableArray,"<b>End Date</b>: ".$endDate);
                                    }
                                    else {
	                                    $timeInterval = $timeInterval-1;
                                    	    $calcEndDate = date('Y-m-d');
	                                    $startDate = date('Y-m-d', strtotime(" - ".$timeInterval." days"));
	                                    array_push($searchVariableArray,"<b>Start Date</b>: ".$startDate);
                                            array_push($searchVariableArray,"<b>End Date</b>: ".$calcEndDate);
                                    }
                                    if(isset($searchCriteria) && $searchCriteria!="") {
                                            array_push($searchVariableArray,"<b>Preferred Country</b>: ".$searchCriteria);
                                    }
                                    if(isset($email) && $email!=="0") {
                                            array_push($searchVariableArray,"<b>Email</b>: ".$email);
                                    }
                                    else {
                                        if(isset($preferredCity) && $preferredCity!=="0") {
                                            $prefCityName = null;
                                            foreach($cityList_tier1 as $list){
                                                if($list['cityId'] == $preferredCity){
                                                    $prefCityName = $list['cityName'];
                                                }
                                            }
                                            if(!$prefCityName){
                                                foreach($country_state_city_list as $list)
                                                {
                                                        if($list['CountryId'] == 2)
                                                        {
                                                                foreach($list['stateMap'] as $list2)
                                                                {
                                                                        foreach($list2['cityMap'] as $list3) {
                                                                            if($list3['CityId'] == $preferredCity){
                                                                                $prefCityName = $list3['CityName'];
                                                                            }
                                                                        }
                                                                }
                                                        }
                                                }
                                            }
                                            array_push($searchVariableArray,"<b>Preferred City</b>: ".$prefCityName);
                                        }
                                        if(isset($cat) && $cat) {
                                            foreach($categoryList as $key=>$parent){
                                                if($key == $cat){
                                                    array_push($searchVariableArray,"<b>Category</b>: ".$parent['name']);
                                                    if(isset($subCat) && $subCat){
                                                        foreach($parent['subcategories'] as $catId=>$subcategory){
                                                            if($catId == $subCat){
                                                                array_push($searchVariableArray,"<b>Sub Category</b>: ".$subcategory['catName']);
                                                                if(isset($ldbCourse) && $ldbCourse){
                                                                    foreach($subcategory['courses'] as $id=>$course){
                                                                        if($course['SpecializationId'] == $ldbCourse){
                                                                            array_push($searchVariableArray,"<b>LDB Course</b>: ".$course['CourseName']);
                                                                        }
                                                                        else{
                                                                            continue;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            else{
                                                                continue;
                                                            }
                                                        }
                                                    }
                                                }
                                                else{
                                                    continue;
                                                }
                                            }
                                        }
                                    }
                                    $searchParamTitle = implode("&nbsp;<i>|</i>&nbsp;",$searchVariableArray);
                                    echo $searchParamTitle;
                            ?>

                </div>
                <?php if(isset($resultResponse['numrows'])) {
                            $studentCount = 'Only 1 responses';
                            if($resultResponse['numrows'] > 1) {
                                $studentCount = 'Total <span class="OrgangeFont">'. $resultResponse['numrows'] .'</span> responses';
                            }
                            if($resultResponse['numrows'] == 0) {
                                $studentCount = 'No responses';
                            }
                ?>
                <div style="width: 100%;">
                    <div style="width:70%">
                        <div style="width:100%">
                            <div class="fontSize_1xi68p" style="padding-bottom:7px"><span id="resultCount" style="font-size:18px"><?php echo $studentCount; ?> found</span></div>
                        </div>
                    </div>
                </div>
                <?php }
                    else if (isset($resultResponse['error'])){
                        echo '<div class="fontSize_18p" style="padding-bottom:7px">'.$resultResponse['error'].'</div>';
                    }
                    else { ?>
                        <div class="fontSize_18p" style="padding-bottom:7px">There are no matching students as per your criteria.</div>
                <?php } ?>
                <!--End_ShowResutlCount-->
                
                <!--Start_NavigationBar-->
                <div style="width:100%;<?php if(isset($resultResponse['numrows']) && $resultResponse['numrows'] <1) { echo 'display:none';} ?>">
                    <div class="cmsSResult_pagingBg">
                	<div style="margin:0 10px">
                            <div style="line-height:6px">&nbsp;</div>
                            <div style="width:100%">
                        	<div class="float_L">
                                    <div style="width:100%">
                                        <div style="height:22px">
                                            <span>
                                                <span class="pagingID" id="paginationPlace1"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="float_R" style="width:25%">
                                    <div style="width:100%">
                                        <div style="height:22px" class="txt_align_r">
                                            <!--span class="normaltxt_11p_blk bld pd_Right_6p">View:
                                                <select class="selectTxt" name="countOffset" id="countOffset_DD1" onChange= "updateCountOffset(this,'startOffSetSearch','countOffsetSearch');">
                                                <option value="10">10</option>
                                                <option value="15">15</option>
                                                <option value="20">20</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="75">75</option>
                                                <option value="100">100</option>
                                                </select>
                                            </span-->
                                        </div>
                                    </div>
                                </div>
                                <div class="cmsClear">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lineSpace_10">&nbsp;</div>
                <!--End_NavigationBar-->
                
                <!--Start_MainDateRowContainer---->
                <div style="width:100%" id="searchResultContainer" >

                    <!--Start_DateRow_1------>
                    <?php $rowCount=0; ?>
                    <?php foreach($resultResponse['responses'] as $response) {
                        $dataX = array("response" => $response,"rowCount" => $rowCount);
                        $this->load->view("offlineOps/unitResponseView",$dataX);

                        $rowCount++;
                    }
                    ?>
                    <!--End_DateRow_1------>

                </div>
                <div class="lineSpace_10">&nbsp;</div>
                <!--End_MainContainerDateRow----->
                
                <!--Start_NavigationBar-->
                <div style="width:100%">
                    <div class="cmsSResult_pagingBg">
                	<div style="margin:0 10px">
                            <div style="line-height:6px">&nbsp;</div>
                            <div style="width:100%">
                        	<div class="float_L" style="width:41%">
                                    <div style="width:100%">
                                        <div style="height:22px">
                                            <span>
                                                <span class="pagingID" id="paginationPlace2"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="cmsClear">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                </div>
                        
                <!--End_NavigationBar-->
            </div>
        </div>
    </div>
</div>

<script>
    var categoryList = <?php echo json_encode($categoryList); ?>;
    var searchCriteria = document.getElementById('searchCriteria').value;
    if (searchCriteria == 'abroad') {
        //abroadCategoryList = <?php echo json_encode($categoryList); ?>;
        document.getElementById('pref_city').style.display = 'none';
        document.getElementById('ldb_courses').style.display = 'none';
        document.getElementById('prefCountAbroad').checked = true;
        document.getElementById('prefCountIndia').checked = false;
    }
    
    function getResponsesForCriteria() {
        var startOffset = document.getElementById('startOffSetSearch').value;
        var countOffset = document.getElementById('countOffsetSearch').value;
        var url = '/offlineOps/index/dashboard';
        var timeInterval = document.getElementById('timeInterval').value;
        var searchCriteria = document.getElementById('searchCriteria').value;
        var endDateSearch = document.getElementById('endDateSearch').value;
        var catSearch = document.getElementById('catSearch').value;
        var subCatSearch = document.getElementById('subCatSearch').value;
        var ldbCourseSearch = document.getElementById('ldbCourseSearch').value;
        var preferredCity = document.getElementById('preferredCity').value;

        url +=  "/"+searchCriteria +"/"+timeInterval +'/'+ startOffset +'/'+ countOffset+'/'+endDateSearch+'/'+catSearch+'/'+subCatSearch+'/'+ldbCourseSearch+'/'+preferredCity;
        location.replace(url);
    }

    doPagination('<?php echo $resultResponse['numrows']; ?>','startOffSetSearch','countOffsetSearch','paginationPlace1','paginationPlace2','methodName',4);
    
    function addDetails(id){
        
        var userid = id.split('_')[1];
        if(!userid){ return false;}
        data = {};
        data['userid'] = userid;
        var searchCriteria = document.getElementById('searchCriteria').value;
//        if (document.getElementById('prefCountAbroad').checked) {
//            data['registrationDomain'] = 'studyAbroad';
//        }else
//            data['registrationDomain'] = 'india';
        
        if(searchCriteria === 'studyAbroad'){
            data['registrationDomain'] = 'studyAbroad';
            registrationOverlayComponent.showOverlay('/offlineOps/index/addDetailsLayer', 710, 655, data);
        }
        else{
            data['registrationDomain'] = 'india';
            registrationOverlayComponent.showOverlay('/offlineOps/index/addDetailsLayer',420,550,data);
        }
        
        $j('#DHTMLSuite_modalBox_transparentDiv').css('height',documentHeight);

    }
    
    function viewContact(id){
        var userid = id.split('_')[1];
        if(!userid){ return false;}
        $j.ajax({
            url: window.location.protocol+'//'+window.location.hostname+'/offlineOps/index/getContact/',
            type: 'POST',
            data: {'userid':userid},
            success: function(result){
                if(result === 'Already assigned'){
                    document.getElementById('viewMobileDiv_'+userid).innerHTML = "<span class='darkgray'>Mobile:</span> <b>"+result+"</b>";
                }else{
                    document.getElementById('viewMobileDiv_'+userid).innerHTML = "<span class='darkgray'>Mobile:</span> "+result+" <i style='color:#ff0000'>Verified </i>";
                }
            },
            error: function(){
                
            }
        });
    }
    
    function viewLeads(){
        $j('#myLeadsLink').attr('href',window.location.protocol+'//'+window.location.host+'/offlineOps/index/myleads/');
        //$j('#myLeadsLink').click();
    }
    
    var offlineOpsActive = true;
   
    document.getElementById('categories_holder').onchange = function(){
        $j("#subcat_holder").empty();
        $j('#subcat_holder').html("<option value=''>Choose a Sub Category</option>");
        $j("#ldb_course_holder").empty();
        $j('#ldb_course_holder').html("<option value=''>Choose an LDB Course</option>");
        if(searchCriteria == 'india') {
            var cat = document.getElementById('categories_holder').value;
            returnHTML = "<option value=''>Choose a Sub Category</option>";
            if (cat == 14) {
                for (var subcat in categoryList[cat]['exams']) {
                    returnHTML += '<option id="' + subcat + '_all_subcategories" value="' + subcat + '"/>';
                    returnHTML += subcat+'</option>';
                }
            }
            else if (cat != 0) {
                for (var subcat in categoryList[cat]['subcategories']) {
                    returnHTML += '<option id="' + subcat + '_all_subcategories" value="' + subcat + '"/>';
                    returnHTML += categoryList[cat]['subcategories'][subcat]['catName']+'</option>';
                }
            }
            else {
                returnHTML += 'Choose an LDB Course';
            }
            $("subcat_holder").innerHTML = returnHTML;
        }
        else {
            var selectedCategory = document.getElementById('categories_holder').value;
            returnHTML = "<option value=''>Choose a Sub Category</option>";
            if (selectedCategory != 0) {
                for (var subCategory in categoryList[selectedCategory]['subcategory']) {
                    var subCatName = categoryList[selectedCategory]['subcategory'][subCategory]['name'];
                    returnHTML += '<option id="' + subCategory + '_all_courses" value="' + subCategory + '"/>';
                    returnHTML += subCatName + '</option>';
                }
            }
            $("subcat_holder").innerHTML = returnHTML;
        }
    };
    
    document.getElementById('subcat_holder').onchange = function(){
        $j("#ldb_course_holder").empty();
        $j('#ldb_course_holder').html("<option value=''>Choose an LDB Course</option>");
        if(searchCriteria == 'india') {
            
            var selectedCategory = document.getElementById('categories_holder').value;
            var selectedSubCategory = document.getElementById('subcat_holder').value;
            returnHTML = "<option value=''>Choose an LDB Course</option>";
            if (selectedCategory == 14) {
                for (var course in categoryList[selectedCategory]['exams'][selectedSubCategory]) {
                    returnHTML += '<option id="' + course + '_all_courses" value="' + course + '"/>';
                    returnHTML += categoryList[selectedCategory]['exams'][selectedSubCategory][course] + '</option>';
                }
            }
            else if (selectedCategory != 0) {
                for (var course in categoryList[selectedCategory]['subcategories'][selectedSubCategory]['courses']) {
                    if (categoryList[selectedCategory]['subcategories'][selectedSubCategory]['courses'][course]['SpecializationId'] == "undefined") {
                        continue;
                    }
                    var courseSpecializationId = categoryList[selectedCategory]['subcategories'][selectedSubCategory]['courses'][course]['SpecializationId'];
                    var coursename = categoryList[selectedCategory]['subcategories'][selectedSubCategory]['courses'][course]['CourseName'];
                    returnHTML += '<option id="' + courseSpecializationId + '_all_courses" value="' + courseSpecializationId + '"/>';
                    returnHTML += coursename + '</option>';
                }
            }
            else {
                returnHTML += 'Choose an LDB Course';
            }
            $("ldb_course_holder").innerHTML = returnHTML;
        }
    };
    
    function changePrefCountry(criteria)
    {
        var url = null;
        searchCriteria = criteria;
        $j("#categories_holder").empty();
        $j('#categories_holder').html("<option value=''>Choose a Category</option>");
        $j("#subcat_holder").empty();
        $j('#subcat_holder').html("<option value=''>Choose a Sub Category</option>");
        $j("#ldb_course_holder").empty();
        $j('#ldb_course_holder').html("<option value=''>Choose an LDB Course</option>");
        if (searchCriteria == 'abroad') {
            $j('#ldb_courses').hide();
            $j('#pref_city').hide();
        }
        else if(searchCriteria == 'india') {
            $j('#ldb_courses').show();
            $j('#pref_city').show();
        }
        url = '/offlineOps/index/getCategories/'+searchCriteria+'/';
        $j.ajax({
            type: "POST",
            url: url,
            success: function (data) {
                categoryList = $j.parseJSON(data);
                returnHTML = "<option value=''>Choose a Category</option>";
                for(var category in categoryList) {
                    returnHTML+='<option id="'+category+'" value="'+category+'">'+categoryList[category]["name"]+'</option>';
                }
                $j('#categories_holder').html(returnHTML);
            },
            error: function(data){
                alert('Something went wrong...');
            }
        });
    }
    
    function timerange(timeFlag)
    {
	var calMain = new CalendarPopup('calendardiv');
	calMain.select($('timerange_'+timeFlag),'timerange_'+timeFlag+'_img','yyyy-mm-dd');
	return false;
    }
    
    function getTimeInterval(timerange_from,timerange_till)
    {
        var oldDate = new Date(timerange_from);
        var newDate = new Date(timerange_till);
        var timeInterval = ((newDate.getTime()-oldDate.getTime())/(1000*60*60*24)) + 1;
        if (timeInterval < 0 || timeInterval == '' || isNaN(timeInterval)) {
            timeInterval = 7;
        }
        return timeInterval;
    }
    
    function saveResponsesFilterSearchCriteria()
    { 
        var timerange_from = $('timerange_from').value;
        var timerange_till = $('timerange_till').value;
        var timeInterval = null;
        if (timerange_from !== '' && timerange_till !== '') {
            if (validatetimeRange()) {
                timeInterval = getTimeInterval(timerange_from,timerange_till);
            }
            else {
                $('timerange_from').value = '';
                $('timerange_till').value = '';
                alert("Please select a 'TO' date greater than the 'FROM' date.");
                return false;
            }   
        }
        else if (timerange_from !== '' && timerange_till == '') {
            timeInterval = 7;
            timerange_till = new Date(timerange_from);
            timerange_till.setDate(timerange_till.getDate() + (timeInterval-1));
            timerange_till = timerange_till.yyyymmdd();
        }
        else if (timerange_from == '' && timerange_till == '') {
            timerange_till = 0;
            timeInterval = 7;
        }
        else {
            timeInterval = 7;
        }
        if (timeInterval > 30) {
            alert("Date range must not exceed 30 days.");
            return false;
        }
        var emailId = null;
        emailId = document.getElementById('email').value;
        if (emailId !== '') {
            if (!validateEmail()) {
                alert("Please enter a valid Email ID.");
                return false;
            }
        }
        var searchCriteria = null;
        if(document.getElementById('prefCountAbroad').checked){
            searchCriteria = 'abroad';
        }else{
            searchCriteria = 'india';
        }
        var startOffset = 0;//document.getElementById('startOffSetSearch').value;
        var countOffset = document.getElementById('countOffsetSearch').value;
        var preferredCity = (document.getElementById('pref_cities_holder').value) ? (document.getElementById('pref_cities_holder').value) : 0;
        var category = (document.getElementById('categories_holder').value) ? (document.getElementById('categories_holder').value) : 0;
        var subCategory = document.getElementById('subcat_holder').value ? (document.getElementById('subcat_holder').value) : 0;
        var ldbCourse = document.getElementById('ldb_course_holder').value ? (document.getElementById('ldb_course_holder').value) : 0;
        var url = '/offlineOps/index/dashboard/'; 
        url += searchCriteria +"/"+timeInterval +'/'+ startOffset +'/'+ countOffset+'/'+timerange_till;
        url += "/"+category+"/"+subCategory+"/"+ldbCourse+"/"+preferredCity+"/"+emailId;
        
        location.replace(url);
    }
    
    function validatetimeRange()
    {
        var startDate = $('timerange_from').value;
        var endDate = $('timerange_till').value;
        var fromdate = startDate.replace( /(\d{4})-(\d{2})-(\d{2})/, "$2/$3/$1");
        var todate = endDate.replace( /(\d{4})-(\d{2})-(\d{2})/, "$2/$3/$1");
            
        if (Date.parse(todate) >= Date.parse(fromdate)) {
                return true;
        }
        else {
                return false;
        }
	return true;
    }
    
    function validateEmail()
    {
        var emailId = document.getElementById('email').value;
	if(emailId) {
		var filter = /^((([a-z]|[A-Z]|[0-9]|\-|_)+(\.([a-z]|[A-Z]|[0-9]|\-|_)+)*)@((((([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.))*([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/
		if(!filter.test(emailId)) {
			return false;
		}
	}
	return true;
    }
    
    Date.prototype.yyyymmdd = function() {         
         
        var yyyy = this.getFullYear().toString();                                    
        var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based         
        var dd  = this.getDate().toString();             
            
        return yyyy + '-' + (mm[1]?mm:"0"+mm[0]) + '-' + (dd[1]?dd:"0"+dd[0]);
    };
</script>