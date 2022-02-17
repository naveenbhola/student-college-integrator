<form id="formForaddNewUserset" enctype="multipart/form-data" action="/mailer/Mailer/insertUserset/" method="POST" >
	<input type="hidden" value="Activity" name="usersettype" />
	<h4>Across site users</h4>
	<div class="form-section">
		<ul class="profile-form">
			<li>
				<input type="radio" name="registration[type]" value="all" onclick="select_user_type(this);" checked="true" /> Users who register on site
			</li>
			<li>
				<input type="radio" name="registration[type]" value="long" onclick="select_user_type(this);" /><strong>Users who are leads</strong>
				<div class="spacer10 clearFix"></div>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<select class="width-130" id="registration_country" name="registration[country]" disabled="true" onchange="getCitiesForCountryId(this.value, 'registration');">
				<?php
				echo "<option value=0>Choose a Country</option>";
				foreach($country_list as $key=>$value){
					if ($value['countryID'] == 1) {
						continue;
					}
					else {
						echo "<option value=".$value['countryID'].">".$value['countryName']."</option>";
					}
				}
				?>
				</select>
				&nbsp;
				<select class="width-130" id="registration_city" name="registration[city]" disabled="true" onchange="getLocalitiesForCityId(this.value, 'registration');">
						<option value=0>Choose a City</option>
				</select>
				&nbsp;
				<select class="width-130" id="registration_locality" name="registration[locality]" disabled="true">
						<option value=0>Choose a Locality</option>
				</select>
				<div class="spacer15 clearFix"></div>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span id="wrapper_cat_registration" style="float: left; padding-left: 25px;">
					<div style="width: 104px; margin-left:8px;">
						<input id="registration_jCategory" name="registration_jCategory" disabled="true" type="text" readonly="readonly" onclick="dropdiv('registration_jCategory','registration_category', 'registration_iframe2');" class="dropdownin" value="Select Categories" style="width: 104px; padding:3px 20px 3px 3px; border-radius:3px; border: 1px solid #ccc;"/>
					</div>
					<div class="dropdiv" id="registration_category" disabled="true" style="display:none; width: 300px;">
						<div style="display:block;padding-left:5px"><input type="checkbox" onchange="checkUncheckChilds1(this, 'registration_categories_holder'); categoryOnChangeActions('registration_categories_holder','registration');" id="all_categories_registration" name="" value="-1"/>All Categories</div>
						<div id="registration_categories_holder">
						<?php
							foreach($catSubcatCourseList as $key=>$parent){ ?>
								<div style='display:block;padding-left:5px'><input name="registration[category][]" type='checkbox' onchange='uncheckElement1(this,"all_categories_registration","registration_categories_holder"); categoryOnChangeActions("registration_categories_holder","registration");' id='<?php echo $key; ?>' value="<?php echo $key; ?>"><?php echo $parent['name']; ?></input></div>
							<?php }
						?>
						</div>
					</div>
				</span>
				<span id="wrapper_subCat_registration" style="float: left; margin-left: 33px; width: 131px;">
                                        <div style="width: 104px; margin-left:8px;">
						<input id="registration_jSubCategory" name="registration_jSubCategory" disabled="true" type="text" readonly="readonly" onclick="showAllSubCategories('registration');" class="dropdownin" value="Select Sub Categories" style="width: 150px; padding:3px 20px 3px 3px; border-radius:3px; border: 1px solid #ccc;"/>
					</div>
                                        <div class="dropdiv" id="registration_subcategory" disabled="true" style="display:none; width: 300px;">
                                            <div id="registration_subcategories_holder">
                                                
                                            </div>
                                        </div>
				</span>
					
				<span id="wrapper_course_registration" style="float: left; margin-left: 65px; width: 131px;">
                                        <div style="width: 104px; margin-left:8px;">
						<input id="registration_jCourse" name="registration_jCourse" disabled="true" type="text" readonly="readonly" onclick="showAllCourses('registration');" class="dropdownin" value="Select Courses" style="width: 150px; padding:3px 20px 3px 3px; border-radius:3px; border: 1px solid #ccc;"/>
					</div>
                                        <div class="dropdiv" id="registration_ldbcourse" disabled="true" style="display:none; width: 300px;">
                                            <div id="registration_ldbcourse_holder">
                                                
                                            </div>
                                        </div>
				</span>
			</li>
			<li>
				<input type="radio" name="registration[type]" value="short" onclick="select_user_type(this);" /> Users who registered on site but are not leads
			</li>
			<li>
				<strong>Users who create responses</strong>
				<div class="spacer10 clearFix"></div>
				<input type="checkbox" id="acrosssite_all" name="response[acrosssite][from]" value="acrosssite" onclick="select_response_type(this);" /> All
				<div class="spacer10 clearFix"></div>
				<input type="checkbox" id="acrosssite_specific" name="response[acrosssite][from]" value="acrosssite" onclick="select_response_type(this);" /> Specific listing IDs
				<div class="spacer10 clearFix"></div>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="sub-label">Institute IDs</span>
				<input type="text" class="w300" listings="true" id="acrosssite_instituteId" name="response[acrosssite][instituteId]" value="Enter multiple IDs seperated by comma" onfocus="clearTextBox(this);" onblur="validListingIds(this);" disabled="true" style="color: #a5a5a5" />
				<div class="spacer10 clearFix"></div>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="sub-label">Course IDs</span>
				<input type="text" class="w300" listings="true" id="acrosssite_courseId" name="response[acrosssite][courseId]" value="Enter multiple IDs seperated by comma" onfocus="clearTextBox(this);" onblur="validListingIds(this);" disabled="true" style="color: #a5a5a5" />
				<div class="spacer10 clearFix"></div>
				<input type="checkbox" id="acrosssite_location" name="response[acrosssite][from]" value="acrosssite" onclick="select_response_type(this);" /> Location of institute and Category
				<div class="spacer10 clearFix"></div>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="sub-label">Location of institute</span>
				<select class="width-130" id="acrosssite_country" name="response[acrosssite][country]" disabled="true"  onchange="getCitiesForCountryId(this.value, 'acrosssite');">
					<?php
						echo "<option value=0>Choose a Country</option>";
						foreach($country_list as $key=>$value){
							if ($value['countryID'] == 1) {
								continue;
							}
							else {
								echo "<option value=".$value['countryID'].">".$value['countryName']."</option>";
							}
						}
					?>
				</select>
				&nbsp;
				<select class="width-130" id="acrosssite_city" name="response[acrosssite][city]" disabled="true" onchange="getLocalitiesForCityId(this.value, 'acrosssite');">
					<option value=0>Choose a City</option>
				</select>
				&nbsp;
				<select class="width-130" id="acrosssite_locality" name="response[acrosssite][locality]" disabled="true">
					<option value=0>Choose a Locality</option>
				</select>
				<div class="spacer15 clearFix"></div>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="sub-label" style="float: left; width: 151px;">Category</span>
				<span id="wrapper_cat_acrosssite" style="float: left">
					<div style="width: 104px; margin-left:8px;">
						<input id="acrosssite_jCategory" name="acrosssite_jCategory" disabled="true" type="text" readonly="readonly" onclick="dropdiv('acrosssite_jCategory','acrosssite_category', 'acrosssite_iframe2');" class="dropdownin" value="Select Categories" style="width: 104px; padding:3px 20px 3px 3px; border-radius:3px; border: 1px solid #ccc;"/>
					</div>
					<div id="acrosssite_iframe2" class="iframe" style="display:none;"><iframe style="height:300px;border: none; width:auto;" class="modalcss1"></iframe></div>
					<div class="dropdiv" id="acrosssite_category" disabled="true" style="display:none; width: 300px;">
						<div style="display:block;padding-left:5px"><input type="checkbox" onchange="checkUncheckChilds1(this, 'acrosssite_categories_holder'); categoryOnChangeActions('acrosssite_categories_holder','acrosssite');" id="all_categories_acrosssite" name="" value="-1"/>All Categories</div>
						<div id="acrosssite_categories_holder">
						<?php
							foreach($catSubcatCourseList as $key=>$parent){ ?>
								<div style='display:block;padding-left:5px'><input name="response[acrosssite][category][]" type='checkbox' onchange='uncheckElement1(this,"all_categories_acrosssite","acrosssite_categories_holder"); categoryOnChangeActions("acrosssite_categories_holder","acrosssite");' id='<?php echo $key; ?>' value="<?php echo $key; ?>"><?php echo $parent['name']; ?></input></div>
							<?php }
						?>
						</div>
					</div>
				</span>
				<span id="wrapper_subCat_acrosssite" style="float: left; margin-left: 33px; width: 115px;">
                                        <div style="width: 104px; margin-left:8px;">
						<input id="acrosssite_jSubCategory" name="acrosssite_jSubCategory" disabled="true" type="text" readonly="readonly" onclick="showAllSubCategories('acrosssite');" class="dropdownin" value="Select Sub Categories" style="width: 130px; padding:3px 20px 3px 3px; border-radius:3px; border: 1px solid #ccc;"/>
					</div>
                                        <div class="dropdiv" id="acrosssite_subcategory" disabled="true" style="display:none; width: 300px;">
                                            <div id="acrosssite_subcategories_holder">
                                                
                                            </div>
                                        </div>
				</span>
					
				<span id="wrapper_course_acrosssite" style="float: left; margin-left: 65px; width: 105px;">
                                        <div style="width: 104px; margin-left:8px;">
						<input id="acrosssite_jCourse" name="acrosssite_jCourse" disabled="true" type="text" readonly="readonly" onclick="showAllCourses('acrosssite');" class="dropdownin" value="Select Courses" style="width: 105px; padding:3px 20px 3px 3px; border-radius:3px; border: 1px solid #ccc;"/>
					</div>
                                        <div class="dropdiv" id="acrosssite_ldbcourse" disabled="true" style="display:none; width: 300px;">
                                            <div id="acrosssite_ldbcourse_holder">
                                                
                                            </div>
                                        </div>
				</span>
			</li> 
		</ul>
		<div class="clearFix"></div>
	</div>
	
	<h4>Online Application users</h4>
	<div class="form-section">
		<ul class="profile-form">
			<li>
				<input type="checkbox" id="online_incomplete" name="online[0][status]" value="profile_incomplete" /> Users who did not complete the basic profile
			</li>
			<li>
				<input type="checkbox" id="online_completed" name="online[1][status]" value="profile_completed" /> Users who completed basic profile but did not apply
			</li>
			<li>
				<strong>Users who applied but whose payment is pending</strong>
				<div class="spacer10 clearFix"></div>
				<input type="checkbox" id="online_payment_pending_all" name="online[2][status]" value="payment_pending" onclick="select_online_user_type(this);" /> All &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="checkbox" id="online_payment_pending_specific" name="online[2][status]" value="payment_pending" onclick="select_online_user_type(this);" /> Specific Institute IDs &nbsp;
				<input type="text" listings="true" id="online_payment_pending_listings" name="online[2][instituteId]" class="w300" value="Enter multiple IDs seperated by comma" onfocus="clearTextBox(this);" onblur="validListingIds(this);" disabled="true" style="color: #a5a5a5" />
			</li>
			<li>
				<strong>Users who successfully applied and paid to</strong>
				<div class="spacer10 clearFix"></div>
				<input type="checkbox" id="online_payment_success_all" name="online[3][status]" value="payment_success" onclick="select_online_user_type(this);" /> All &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="checkbox" id="online_payment_success_specific" name="online[3][status]" value="payment_success" onclick="select_online_user_type(this);" /> Specific Institute IDs &nbsp;
				<input type="text" listings="true" id="online_payment_success_listings" name="online[3][instituteId]" class="w300" value="Enter multiple IDs seperated by comma" onfocus="clearTextBox(this);" onblur="validListingIds(this);" disabled="true" style="color: #a5a5a5" />
			</li>
		</ul>
		<div class="clearFix"></div>
	</div>
	
	<h4>Listing page users</h4>
	<div class="form-section">
		<ul class="profile-form">
			<li>
				<strong>Create response</strong>
				<div class="spacer10 clearFix"></div>
				<input type="checkbox" id="listing_all" name="response[listing][from]" value="listing" onclick="select_response_type(this);" /> All
				<div class="spacer10 clearFix"></div>
				<input type="checkbox" id="listing_specific" name="response[listing][from]" value="listing" onclick="select_response_type(this);" /> Specific listing IDs
				<div class="spacer10 clearFix"></div>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="sub-label">Institute IDs</span>
				<input type="text" class="w300" listings="true" id="listing_instituteId" name="response[listing][instituteId]" value="Enter multiple IDs seperated by comma" onfocus="clearTextBox(this);" onblur="validListingIds(this);" disabled="true" style="color: #a5a5a5" />
				<div class="spacer10 clearFix"></div>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="sub-label">Course IDs</span>
				<input type="text" class="w300" listings="true" id="listing_courseId" name="response[listing][courseId]" value="Enter multiple IDs seperated by comma" onfocus="clearTextBox(this);" onblur="validListingIds(this);" disabled="true" style="color: #a5a5a5" />
				<div class="spacer10 clearFix"></div>
				<input type="checkbox" id="listing_location" name="response[listing][from]" value="listing" onclick="select_response_type(this);" /> Location of institute and Category
				<div class="spacer10 clearFix"></div>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="sub-label">Location of institute</span>
				<select class="width-130" id="listing_country" name="response[listing][country]" disabled="true" onchange="getCitiesForCountryId(this.value, 'listing');">
					<?php
						echo "<option value=0>Choose a Country</option>";
						foreach($country_list as $key=>$value){
							if ($value['countryID'] == 1) {
								continue;
							}
							else {
								echo "<option value=".$value['countryID'].">".$value['countryName']."</option>";
							}
						}
					?>
				</select>
				&nbsp;
				<select class="width-130" id="listing_city" name="response[listing][city]" disabled="true" onchange="getLocalitiesForCityId(this.value, 'listing');">
					<option value=0>Choose a City</option>
				</select>
				&nbsp;
				<select class="width-130" id="listing_locality" name="response[listing][locality]" disabled="true">
					<option value=0>Choose a Locality</option>
				</select>
				<div class="spacer15 clearFix"></div>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="sub-label" style="float: left; width: 151px;">Category</span>
				<span id="wrapper_cat_listing" style="float: left">
					<div style="width: 104px; margin-left:8px;">
						<input id="listing_jCategory" name="listing_jCategory" disabled="true" type="text" readonly="readonly" onclick="dropdiv('listing_jCategory','listing_category', 'listing_iframe2');" class="dropdownin" value="Select Categories" style="width: 104px; padding:3px 20px 3px 3px; border-radius:3px; border: 1px solid #ccc;"/>
					</div>
					<div id="listing_iframe2" class="iframe" style="display:none;"><iframe style="height:300px;border: none; width:auto;" class="modalcss1"></iframe></div>
					<div class="dropdiv" id="listing_category" disabled="true" style="display:none; width: 300px;">
						<div style="display:block;padding-left:5px"><input type="checkbox" onchange="checkUncheckChilds1(this, 'listing_categories_holder'); categoryOnChangeActions('listing_categories_holder','listing');" id="all_categories_listing" name="" value="-1"/>All Categories</div>
						<div id="listing_categories_holder">
						<?php
							foreach($catSubcatCourseList as $key=>$parent){ ?>
								<div style='display:block;padding-left:5px'><input name="response[listing][category][]" type='checkbox' onchange='uncheckElement1(this,"all_categories_listing","listing_categories_holder"); categoryOnChangeActions("listing_categories_holder","listing");' id='<?php echo $key; ?>' value="<?php echo $key; ?>"><?php echo $parent['name']; ?></input></div>
							<?php }
						?>
						</div>
					</div>
				</span>
				<span id="wrapper_subCat_listing" style="float: left; margin-left: 33px; width: 115px;">
                                        <div style="width: 104px; margin-left:8px;">
						<input id="listing_jSubCategory" name="listing_jSubCategory" disabled="true" type="text" readonly="readonly" onclick="showAllSubCategories('listing');" class="dropdownin" value="Select Sub Categories" style="width: 130px; padding:3px 20px 3px 3px; border-radius:3px; border: 1px solid #ccc;"/>
					</div>
                                        <div class="dropdiv" id="listing_subcategory" disabled="true" style="display:none; width: 300px;">
                                            <div id="listing_subcategories_holder">
                                                
                                            </div>
                                        </div>
				</span>
					
				<span id="wrapper_course_listing" style="float: left; margin-left: 65px; width: 105px;">
                                        <div style="width: 104px; margin-left:8px;">
						<input id="listing_jCourse" name="listing_jCourse" disabled="true" type="text" readonly="readonly" onclick="showAllCourses('listing');" class="dropdownin" value="Select Courses" style="width: 105px; padding:3px 20px 3px 3px; border-radius:3px; border: 1px solid #ccc;"/>
					</div>
                                        <div class="dropdiv" id="listing_ldbcourse" disabled="true" style="display:none; width: 300px;">
                                            <div id="listing_ldbcourse_holder">
                                                
                                            </div>
                                        </div>
				</span>
			</li>
			<li>
				<strong>Ask question</strong>
				<div class="spacer10 clearFix"></div>
				<input type="checkbox" id="listing_ask_question_all" name="ana[status]" value="all" onclick="select_askquestion_type(this);" /> All
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="checkbox" id="listing_ask_question_specific" name="ana[status]" value="specific" onclick="select_askquestion_type(this);" /> Specific listing IDs
				&nbsp;
				<input type="text" listings="true" id="listing_ask_question_listings" name="ana[instituteId]" class="w300" value="Enter multiple IDs seperated by comma" onfocus="clearTextBox(this);" onblur="validListingIds(this);" disabled="true" style="color: #a5a5a5" />
			</li>
		</ul>
		<div class="clearFix"></div>
	</div>
	
	<h4>Search results page users</h4>
	<div class="form-section">
		<ul class="profile-form">
			<li>
				<strong>Create response</strong>
				<div class="spacer10 clearFix"></div>
				<input type="checkbox" id="search_all" name="response[search][from]" value="search" onclick="select_response_type(this);" /> All
				<div class="spacer10 clearFix"></div>
				<input type="checkbox" id="search_specific" name="response[search][from]" value="search" onclick="select_response_type(this);" /> Specific listing IDs
				<div class="spacer10 clearFix"></div>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="sub-label">Institute IDs</span>
				<input type="text" class="w300" listings="true" id="search_instituteId" name="response[search][instituteId]" value="Enter multiple IDs seperated by comma" onfocus="clearTextBox(this);" onblur="validListingIds(this);" disabled="true" style="color: #a5a5a5" />
				<div class="spacer10 clearFix"></div>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="sub-label">Course IDs</span>
				<input type="text" class="w300" listings="true" id="search_courseId" name="response[search][courseId]" value="Enter multiple IDs seperated by comma" onfocus="clearTextBox(this);" onblur="validListingIds(this);" disabled="true" style="color: #a5a5a5" />
				<div class="spacer10 clearFix"></div>
				<input type="checkbox" id="search_location" name="response[search][from]" value="search" onclick="select_response_type(this);" /> Location of institute and Category
				<div class="spacer10 clearFix"></div>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="sub-label">Location of institute</span>
				<select class="width-130" id="search_country" name="response[search][country]" disabled="true" onchange="getCitiesForCountryId(this.value, 'search');">
					<?php
						echo "<option value=0>Choose a Country</option>";
						foreach($country_list as $key=>$value){
							if ($value['countryID'] == 1) {
								continue;
							}
							else {
								echo "<option value=".$value['countryID'].">".$value['countryName']."</option>";
							}
						}
					?>
				</select>
				&nbsp;
				<select class="width-130" id="search_city" name="response[search][city]" disabled="true" onchange="getLocalitiesForCityId(this.value, 'search');">
					<option value=0>Choose a City</option>
				</select>
				&nbsp;
				<select class="width-130" id="search_locality" name="response[search][locality]" disabled="true">
					<option value=0>Choose a Locality</option>
				</select>
				<div class="spacer15 clearFix"></div>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="sub-label" style="float: left; width: 151px;">Category</span>
				<span id="wrapper_cat_search" style="float: left">
					<div style="width: 104px; margin-left:8px;">
						<input id="search_jCategory" name="search_jCategory" disabled="true" type="text" readonly="readonly" onclick="dropdiv('search_jCategory','search_category', 'search_iframe2');" class="dropdownin" value="Select Categories" style="width: 104px; padding:3px 20px 3px 3px; border-radius:3px; border: 1px solid #ccc;"/>
					</div>
					<div id="search_iframe2" class="iframe" style="display:none;"><iframe style="height:300px;border: none; width:auto;" class="modalcss1"></iframe></div>
					<div class="dropdiv" id="search_category" disabled="true" style="display:none; width: 300px;">
						<div style="display:block;padding-left:5px"><input type="checkbox" onchange="checkUncheckChilds1(this, 'search_categories_holder'); categoryOnChangeActions('search_categories_holder','search');" id="all_categories_search" name="" value="-1"/>All Categories</div>
						<div id="search_categories_holder">
						<?php
							foreach($catSubcatCourseList as $key=>$parent){ ?>
								<div style='display:block;padding-left:5px'><input name="response[search][category][]" type='checkbox' onchange='uncheckElement1(this,"all_categories_search","search_categories_holder"); categoryOnChangeActions("search_categories_holder","search");' id='<?php echo $key; ?>' value="<?php echo $key; ?>"><?php echo $parent['name']; ?></input></div>
							<?php }
						?>
						</div>
					</div>
				</span>
				<span id="wrapper_subCat_search" style="float: left; margin-left: 33px; width: 115px;">
                                        <div style="width: 104px; margin-left:8px;">
						<input id="search_jSubCategory" name="search_jSubCategory" disabled="true" type="text" readonly="readonly" onclick="showAllSubCategories('search');" class="dropdownin" value="Select Sub Categories" style="width: 130px; padding:3px 20px 3px 3px; border-radius:3px; border: 1px solid #ccc;"/>
					</div>
                                        <div class="dropdiv" id="search_subcategory" disabled="true" style="display:none; width: 300px;">
                                            <div id="search_subcategories_holder">
                                                
                                            </div>
                                        </div>
				</span>
					
				<span id="wrapper_course_search" style="float: left; margin-left: 65px; width: 105px;">
                                        <div style="width: 104px; margin-left:8px;">
						<input id="search_jCourse" name="search_jCourse" disabled="true" type="text" readonly="readonly" onclick="showAllCourses('search');" class="dropdownin" value="Select Courses" style="width: 105px; padding:3px 20px 3px 3px; border-radius:3px; border: 1px solid #ccc;"/>
					</div>
                                        <div class="dropdiv" id="search_ldbcourse" disabled="true" style="display:none; width: 300px;">
                                            <div id="search_ldbcourse_holder">
                                                
                                            </div>
                                        </div>
				</span>
			</li>
		</ul>
		<div class="clearFix"></div>
	</div>
	
	<h4>Category page users</h4>
	<div class="form-section">
		<ul class="profile-form">
			<li>
				<strong>Create response</strong>
				<div class="spacer10 clearFix"></div>
				<input type="checkbox" id="category_all" name="response[category][from]" value="category" onclick="select_response_type(this);" /> All
				<div class="spacer10 clearFix"></div>
				<input type="checkbox" id="category_specific" name="response[category][from]" value="category" onclick="select_response_type(this);" /> Specific listing IDs
				<div class="spacer10 clearFix"></div>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="sub-label">Institute IDs</span>
				<input type="text" class="w300" listings="true" id="category_instituteId" name="response[category][instituteId]" value="Enter multiple IDs seperated by comma" onfocus="clearTextBox(this);" onblur="validListingIds(this);" disabled="true" style="color: #a5a5a5" />
				<div class="spacer10 clearFix"></div>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="sub-label">Course IDs</span>
				<input type="text" class="w300" listings="true" id="category_courseId" name="response[category][courseId]" value="Enter multiple IDs seperated by comma" onfocus="clearTextBox(this);" onblur="validListingIds(this);" disabled="true" style="color: #a5a5a5" />
				<div class="spacer10 clearFix"></div>
				<input type="checkbox" id="category_location" name="response[category][from]" value="category" onclick="select_response_type(this);" /> Location of institute and Category
				<div class="spacer10 clearFix"></div>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="sub-label">Location of institute</span>
				<select class="width-130" id="category_country" name="response[category][country]" disabled="true" onchange="getCitiesForCountryId(this.value, 'category');">
					<?php
						echo "<option value=0>Choose a Country</option>";
						foreach($country_list as $key=>$value){
							if ($value['countryID'] == 1) {
								continue;
							}
							else {
								echo "<option value=".$value['countryID'].">".$value['countryName']."</option>";
							}
						}
					?>
				</select>
				&nbsp;
				<select class="width-130" id="category_city" name="response[category][city]" disabled="true" onchange="getLocalitiesForCityId(this.value, 'category');">
					<option value=0>Choose a City</option>
				</select>
				&nbsp;
				<select class="width-130" id="category_locality" name="response[category][locality]" disabled="true">
					<option value=0>Choose a Locality</option>
				</select>
				<div class="spacer15 clearFix"></div>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="sub-label" style="float: left; width: 151px;">Category</span>
				<span id="wrapper_cat_category" style="float: left">
					<div style="width: 104px; margin-left:8px;">
						<input id="category_jCategory" name="category_jCategory" disabled="true" type="text" readonly="readonly" onclick="dropdiv('category_jCategory','category_category', 'category_iframe2');" class="dropdownin" value="Select Categories" style="width: 104px; padding:3px 20px 3px 3px; border-radius:3px; border: 1px solid #ccc;"/>
					</div>
					<div id="category_iframe2" class="iframe" style="display:none;"><iframe style="height:300px;border: none; width:auto;" class="modalcss1"></iframe></div>
					<div class="dropdiv" id="category_category" disabled="true" style="display:none; width: 300px;">
						<div style="display:block;padding-left:5px"><input type="checkbox" onchange="checkUncheckChilds1(this, 'category_categories_holder'); categoryOnChangeActions('category_categories_holder','category');" id="all_categories_category" name="" value="-1"/>All Categories</div>
						<div id="category_categories_holder">
						<?php
							foreach($catSubcatCourseList as $key=>$parent){ ?>
								<div style='display:block;padding-left:5px'><input name="response[category][category][]" type='checkbox' onchange='uncheckElement1(this,"all_categories_category","category_categories_holder"); categoryOnChangeActions("category_categories_holder","category");' id='<?php echo $key; ?>' value="<?php echo $key; ?>"><?php echo $parent['name']; ?></input></div>
							<?php }
						?>
						</div>
					</div>
				</span>
				<span id="wrapper_subCat_category" style="float: left; margin-left: 33px; width: 115px;">
                                        <div style="width: 104px; margin-left:8px;">
						<input id="category_jSubCategory" name="category_jSubCategory" disabled="true" type="text" readonly="readonly" onclick="showAllSubCategories('category');" class="dropdownin" value="Select Sub Categories" style="width: 130px; padding:3px 20px 3px 3px; border-radius:3px; border: 1px solid #ccc;"/>
					</div>
                                        <div class="dropdiv" id="category_subcategory" disabled="true" style="display:none; width: 300px;">
                                            <div id="category_subcategories_holder">
                                                
                                            </div>
                                        </div>
				</span>
					
				<span id="wrapper_course_category" style="float: left; margin-left: 65px; width: 105px;">
                                        <div style="width: 104px; margin-left:8px;">
						<input id="category_jCourse" name="category_jCourse" disabled="true" type="text" readonly="readonly" onclick="showAllCourses('category');" class="dropdownin" value="Select Courses" style="width: 105px; padding:3px 20px 3px 3px; border-radius:3px; border: 1px solid #ccc;"/>
					</div>
                                        <div class="dropdiv" id="category_ldbcourse" disabled="true" style="display:none; width: 300px;">
                                            <div id="category_ldbcourse_holder">
                                                
                                            </div>
                                        </div>
				</span>
			</li>
		</ul>
		<div class="clearFix"></div>
	</div>
	
	<h4>Mobile site users</h4>
	<div class="form-section">
		<ul class="profile-form">
			<li>
				<strong>Create response</strong>
				<div class="spacer10 clearFix"></div>
				<input type="checkbox" id="mobile_all" name="response[mobile][from]" value="mobile" onclick="select_response_type(this);" /> All
				<div class="spacer10 clearFix"></div>
				<input type="checkbox" id="mobile_specific" name="response[mobile][from]" value="mobile" onclick="select_response_type(this);" /> Specific listing IDs
				<div class="spacer10 clearFix"></div>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="sub-label">Institute IDs</span>
				<input type="text" class="w300" listings="true" id="mobile_instituteId" name="response[mobile][instituteId]" value="Enter multiple IDs seperated by comma" onfocus="clearTextBox(this);" onblur="validListingIds(this);" disabled="true" style="color: #a5a5a5" />
				<div class="spacer10 clearFix"></div>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="sub-label">Course IDs</span>
				<input type="text" class="w300" listings="true" id="mobile_courseId" name="response[mobile][courseId]" value="Enter multiple IDs seperated by comma" onfocus="clearTextBox(this);" onblur="validListingIds(this);" disabled="true" style="color: #a5a5a5" />
				<div class="spacer10 clearFix"></div>
				<input type="checkbox" id="mobile_location" name="response[mobile][from]" value="mobile" onclick="select_response_type(this);" /> Location of institute and Category
				<div class="spacer10 clearFix"></div>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="sub-label">Location of institute</span>
				<select class="width-130" id="mobile_country" name="response[mobile][country]" disabled="true" onchange="getCitiesForCountryId(this.value, 'mobile');">
					<?php
						echo "<option value=0>Choose a Country</option>";
						foreach($country_list as $key=>$value){
							if ($value['countryID'] == 1) {
								continue;
							}
							else {
								echo "<option value=".$value['countryID'].">".$value['countryName']."</option>";
							}
						}
					?>
				</select>
				&nbsp;
				<select class="width-130" id="mobile_city" name="response[mobile][city]" disabled="true" onchange="getLocalitiesForCityId(this.value, 'mobile');">
					<option value=0>Choose a City</option>
				</select>
				&nbsp;
				<select class="width-130" id="mobile_locality" name="response[mobile][locality]" disabled="true">
					<option value=0>Choose a Locality</option>
				</select>
				<div class="spacer15 clearFix"></div>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="sub-label" style="float: left; width: 151px;">Category</span>
				<span id="wrapper_cat_mobile" style="float: left">
					<div style="width: 104px; margin-left:8px;">
						<input id="mobile_jCategory" name="mobile_jCategory" disabled="true" type="text" readonly="readonly" onclick="dropdiv('mobile_jCategory','mobile_category', 'mobile_iframe2');" class="dropdownin" value="Select Categories" style="width: 104px; padding:3px 20px 3px 3px; border-radius:3px; border: 1px solid #ccc;"/>
					</div>
					<div id="mobile_iframe2" class="iframe" style="display:none;"><iframe style="height:300px;border: none; width:auto;" class="modalcss1"></iframe></div>
					<div class="dropdiv" id="mobile_category" disabled="true" style="display:none; width: 300px;">
						<div style="display:block;padding-left:5px"><input type="checkbox" onchange="checkUncheckChilds1(this, 'mobile_categories_holder'); categoryOnChangeActions('mobile_categories_holder','mobile');" id="all_categories_mobile" name="" value="-1"/>All Categories</div>
						<div id="mobile_categories_holder">
						<?php
							foreach($catSubcatCourseList as $key=>$parent){ ?>
								<div style='display:block;padding-left:5px'><input name="response[mobile][category][]" type='checkbox' onchange='uncheckElement1(this,"all_categories_mobile","mobile_categories_holder"); categoryOnChangeActions("mobile_categories_holder","mobile");' id='<?php echo $key; ?>' value="<?php echo $key; ?>"><?php echo $parent['name']; ?></input></div>
							<?php }
						?>
						</div>
					</div>
				</span>
				<span id="wrapper_subCat_mobile" style="float: left; margin-left: 33px; width: 115px;">
                                        <div style="width: 104px; margin-left:8px;">
						<input id="mobile_jSubCategory" name="mobile_jSubCategory" disabled="true" type="text" readonly="readonly" onclick="showAllSubCategories('mobile');" class="dropdownin" value="Select Sub Categories" style="width: 130px; padding:3px 20px 3px 3px; border-radius:3px; border: 1px solid #ccc;"/>
					</div>
                                        <div class="dropdiv" id="mobile_subcategory" disabled="true" style="display:none; width: 300px;">
                                            <div id="mobile_subcategories_holder">
                                                
                                            </div>
                                        </div>
				</span>
					
				<span id="wrapper_course_mobile" style="float: left; margin-left: 65px; width: 105px;">
                                        <div style="width: 104px; margin-left:8px;">
						<input id="mobile_jCourse" name="mobile_jCourse" disabled="true" type="text" readonly="readonly" onclick="showAllCourses('mobile');" class="dropdownin" value="Select Courses" style="width: 105px; padding:3px 20px 3px 3px; border-radius:3px; border: 1px solid #ccc;"/>
					</div>
                                        <div class="dropdiv" id="mobile_ldbcourse" disabled="true" style="display:none; width: 300px;">
                                            <div id="mobile_ldbcourse_holder">
                                                
                                            </div>
                                        </div>
				</span>
			</li>
		</ul>
		<div class="clearFix"></div>
	</div>
	
	<?php $this->load->view('mailer/filterbytime'); ?>
</form>
<script>
    var isCatChange = new Object();
    isCatChange['registrationsubcats'] = false;
    isCatChange['registrationldbcourses'] = false;
	function categoryOnChangeActions(holderId, property){
            window.isCatChange[property+'subcats'] = true;
            window.isCatChange[property+'ldbcourses'] = true;
            $j('#'+property+'_subcategories_holder').html('');
            subcategoryOnChange(property);
            $(property+'_jSubCategory').disabled = false;
            $(property+'_jCourse').disabled = false;
		var noOfCheckedCategories = 0;
		var checks =  document.getElementById(holderId).getElementsByTagName("input");
		var boxLength = checks.length;
		for ( i=0; i < boxLength; i++ )
		{
		    if ( checks[i].checked == true )
		    {
			noOfCheckedCategories++;
			continue;
		    }
		    else
		    {
			continue;
		    }
		}
		if (noOfCheckedCategories > 0){
			document.getElementById(property+'_jCategory').value = 'Categories ('+noOfCheckedCategories+')';
			document.getElementById(property+'_jSubCategory').value = 'All SubCategories';
			document.getElementById(property+'_jCourse').value = 'All Courses';
		}
		else{
			document.getElementById(property+'_jCategory').value = 'Categories ('+noOfCheckedCategories+')';
			document.getElementById(property+'_jSubCategory').value = 'SubCategories';
			document.getElementById(property+'_jCourse').value = 'Courses';
		}
		return true;
	}
        
        function subcategoryOnChange(property){
            isCatChange[property+'ldbcourses'] = true;
            document.getElementById(property+'_jCourse').value = 'All Courses';
            $j('#'+property+'_ldbcourse_holder').html('');
        }
	
	function showAllSubCategories(property) {
            if(window.isCatChange[property+'subcats'] == false){
                dropdiv(property+'_jSubCategory',property+'_subcategory');
                return;
            }
		var checkedCategories = [];
		var checks =  document.getElementById(property+'_categories_holder').getElementsByTagName("input");
		var boxLength = checks.length;
		var checkedCategoriesCount = 0;
		for ( i=0,j=0; i < boxLength; i++ )
		{
			if ( checks[i].checked == true ) {
			    checkedCategories[j] = checks[i].value;
			    checkedCategoriesCount++;
			    j++;
			}
			else {
			    continue;
			}
		}
		if (checkedCategoriesCount == 0) {
			return false;
		}
		else{
			$j.ajax({
				type: 'POST',
				url : '/mailer/Mailer/getSubcategoriesCoursesList/'+property+'/'+checkedCategories+'/',	
				success : function(response) {
                                    $j('#'+property+'_subcategories_holder').html(response);
                                    dropdiv(property+'_jSubCategory',property+'_subcategories_holder');
                                    window.isCatChange[property+'subcats'] = false;
                                    window.isCatChange[property+'ldbcourses'] = true;
//					showListingsOverlay(340,500,'',response);
//					overlayParentListings = $('Main_'+property);
//					$('Main_'+property).style.display = 'block';
				}
			});
			return true;
		}
	}
	   
	function saveSubCategoriesAndCourses(property) {
		
		var subcatChecks = $j('#'+property+'_all_subcats_holder').find("input[metacommon='subcategory']:checked");
		var courseChecks = $j('#'+property+'_all_subcats_holder_courses').find("input[metacommon='ldbcourse']:checked");
		
		var checkedSubCatLength = subcatChecks.length;
		
		var checkedCourseLength = courseChecks.length;
		
		if (checkedCourseLength > 0) {
			document.getElementById(property+'_jCourse').value = 'Courses ('+checkedCourseLength+')';
		}
		else{
			if (checkedSubCatLength == 0) {
				document.getElementById(property+'_jSubCategory').value = 'All SubCategories';
			}
			else{
				document.getElementById(property+'_jSubCategory').value = 'SubCategories ('+checkedSubCatLength+')';
			}
			document.getElementById(property+'_jCourse').value = 'All Courses';
		}
	}
	
	function showAllCourses(property) {
            if(window.isCatChange[property+'ldbcourses'] == false){
                dropdiv(property+'_jCourse',property+'_ldbcourse_holder');
                return;
            }
		var checkedCategories = [];
		var checkedSubCategories = [];
		var checks = $j('#'+property+'_subcategories_holder').find("input[metacommon='subcategory']:checked");
		var boxLength = checks.length;
		var checkedSubCategoriesCount = 0;
		for ( i=0,j=0; i < boxLength; i++ )
		{
			if ( checks[i].checked == true ) {
			    checkedSubCategories[j] = checks[i].value;
			    checkedSubCategoriesCount++;
			    j++;
			}
			else {
			    continue;
			}
		}
			
		var catChecks =  document.getElementById(property+'_categories_holder').getElementsByTagName("input");
		var catBoxLength = catChecks.length;
		var checkedCategoriesCount = 0;
		for ( i=0,j=0; i < catBoxLength; i++ )
		{
			if ( catChecks[i].checked == true ) {
			    checkedCategories[j] = catChecks[i].value;
			    checkedCategoriesCount++;
			    j++;
			}
			else {
			    continue;
			}
		}
		if (checkedCategoriesCount == 0 || checkedSubCategoriesCount == 0) {
			return false;
		}
		else{
			$j.ajax({
				type: 'POST',
				url : '/mailer/Mailer/getSubcategoriesCoursesList/'+property+'/'+checkedCategories+'/'+checkedSubCategories+'/',	
				success : function(response) {
                                    $j('#'+property+'_ldbcourse_holder').html(response);
                                    dropdiv(property+'_jCourse',property+'_ldbcourse_holder');
                                    window.isCatChange[property+'ldbcourses'] = false;
//					showListingsOverlay(340,500,'',response);
//					overlayParentListings = $('Main_'+property);
//					$('Main_'+property).style.display = 'block';
//					$('layer_title').innerHTML = '<b>Select Courses</b>';
				}
			});
			return true;
		}
	}

	function showMMMOverlay(overlayWidth, overlayHeight, overlayTitle, overlayContent, modalLess, left, top) {
		if(trim(overlayContent) == '')
			return false;
		var body = document.getElementsByTagName('body')[0];
		$('overlayTitle').innerHTML = overlayTitle;
		if(trim(overlayTitle) == '') {
			$('overlayTitle').parentNode.style.display = 'none';
		} else {
			$('overlayTitle').parentNode.style.display = '';
		}
		overlayWidth *= 2;
		$('genOverlay').style.width = overlayWidth + 'px';
		$('genOverlay').style.height = overlayHeight + 'px';
		$('genOverlayContents').innerHTML = overlayContent;
			
		var divY = parseInt(screen.height)/2;
		var divX;
		if(typeof left != 'undefined') {
			divX = left;
		} else {
			divX = (parseInt(body.offsetWidth)/2) - (overlayWidth/2);
		}
			
		if(typeof top != 'undefined') {
			divY = top;
		} else {
			divY = parseInt(divY - parseInt(overlayHeight/2)) - 70;
		}
		h = document.body.scrollTop;
		var  h1 = document.documentElement.scrollTop;
		h = h1 > h ? h1 : h;
		divY = divY + h;
		if(typeof modalLess == 'undefined' || modalLess === false ) {
			$('dim_bg').style.height = body.scrollHeight + 'px';
			$('dim_bg').style.display = 'inline';
			if($('dim_bg').offsetWidth < body.offsetWidth) {
				$('dim_bg').style.width = body.offsetWidth + 'px'
			}
		}
		if($('genOverlay').scrollHeight < body.offsetHeight) {
			$('genOverlay').style.left = divX + 'px';
		} else {
			$('genOverlay').style.left = divX + 'px';
			$('dim_bg').style.height = ($('genOverlay').scrollHeight + 100)+'px';
		}	
		overlayHackLayerForIE('genOverlay', body);
		$('overlayCloseCross').className = 'cssSprite1 allShikCloseBtn';
		$('genOverlay').style.display = 'inline';
		return true;
	}
	    
	var currentlyShowing = null;
		
	function dropdiv(textBoxId, divOverlayId, iframeId)
	{
		objTextBoxId =  document.getElementById(textBoxId);
		objDivOverlayId =  document.getElementById(divOverlayId);
		if(document.getElementById('genOverlay').style.display !="inline" && currentlyShowing != divOverlayId){
			attachOutMouseClickEventForActivityPage(document.getElementById('genOverlay'),'hideLocationLayerOverlay()');
			showMMMOverlay(objTextBoxId.offsetWidth ,parseInt(objDivOverlayId.style.height),'',objDivOverlayId.innerHTML,true,obtainPostitionX(objTextBoxId)- 13,obtainPostitionY(objTextBoxId) - document.documentElement.scrollTop + 16);
				layerTopPos = obtainPostitionY(objTextBoxId)+18;
				$('genOverlay').style.top = layerTopPos + 'px';
			overlayHackLayerForIE('genOverlay',document.getElementById('genOverlayContents'));
			document.getElementById('genOverlayContents').innerHTML = '';
			for(var contentNode, childCount=0; contentNode = objDivOverlayId.childNodes[childCount++];) {
				try{
					var contentNodeC =contentNode.cloneNode(true);
					document.getElementById('genOverlayContents').appendChild(contentNodeC);
				} catch(e) {}
			}
			syncLocationsForOverlays(document.getElementById(divOverlayId) , document.getElementById('genOverlayContents'));
//			document.getElementById('genOverlayContents').style.border = '1px solid';
			document.getElementById('genOverlayContents').style.marginTop= '0px';
			document.getElementById('genOverlayContents').style.position= 'relative';
			document.getElementById('genOverlayContents').style.top= '-10px';
			document.getElementById('genOverlayContents').style.background= '#FFF';
			document.getElementById('genOverlayContents').style.height = (objDivOverlayId.style.height);
			document.getElementById('genOverlayContents').style.overflow= 'auto';
			currentlyShowing = divOverlayId;
		} else {
			hideLocationLayerOverlay();
		}	
		try {
			$("genOverlayTitleCross_hack").style.display = "none";
			$("genOverlayHolderDiv").style.border = '1px solid #000';
			$("genOverlayHolderDiv").style.paddingTop = '8px';
			$("genOverlayHolderDiv").style.paddingLeft = '5px';
                        document.getElementById('genOverlayHolderDiv').style.padding = '13px 4px 0 5px';
		}catch(e) {}
	}
        
    // Duplicate
    function attachOutMouseClickEventForActivityPage(elementDiv,functionToCloseOverlay){
	document.onmousedown=clickAction;
	function clickAction(e){
		if (!e) { var e = window.event; }
		if(e.target && e.target.onclick) {
			e.target.onclick.apply();
		}
		if(elementDiv){
		  if($('iframe_div') && $('iframe_div').style.display != 'none' && $('iframe_div').style.zIndex > elementDiv.style.zIndex){ return true;}
		  if(elementDiv.style.display!=='none'){
		  var posx = 0;
		  var posy = 0;
		  posx = e.pageX || (e.clientX + (document.body.scrollLeft || document.documentElement.scrollLeft));
		  posy = e.pageY || (e.clientY + (document.body.scrollTop || document.documentElement.scrollTop));
		  var leftBoundry = (obtainPostitionX(elementDiv) +10);
		  var rightBoundry = (obtainPostitionX(elementDiv) + elementDiv.offsetWidth - 10);
		  var topBoundry = (obtainPostitionY(elementDiv) +10);
		  var bottonBoundry = (obtainPostitionY(elementDiv) + elementDiv.offsetHeight -10);
		  // alert("HERE :: "+posx+" HERE :: "+posy+" "+e+" "+leftBoundry+"
			// HERE "+rightBoundry+" HERE "+topBoundry+" HERE "+bottonBoundry);
		  if((posx < leftBoundry) || (posx > rightBoundry) || (posy < topBoundry) || (posy > bottonBoundry)){
			  document.onmousedown=null;// 'undefined';
			  eval(functionToCloseOverlay);
			}
		  }
		}
		return true;
            }
            return true;
    }
    // Functions to close overlay without gray background end
</script>