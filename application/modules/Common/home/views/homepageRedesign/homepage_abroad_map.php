<?php 		global $ulr_array;
            global $countriesForStudyAbroad;
            foreach($countriesForStudyAbroad as $countryId => $country){
                if(strtolower($countryId) == 'india') continue;
                $countryFlag = isset($country['flagImage']) ? $country['flagImage'] : '';
                $countryname = isset($country['name']) ? $country['name'] : '';
				
				$constantLabel = str_replace('&','_',$countryId);
				$constantLabel = str_replace(',','_',$constantLabel);
			
                $linkUrl = constant('SHIKSHA_'. strtoupper($constantLabel) .'_HOME');
                $ulr_array[$countryId] = $linkUrl;
                }
            ?> 
<div class="box-shadow">
                    <div class="contents2" style="position: relative;">
		    <i class="common-sprite new-icon-2"></i>
                        <h4>Studying Abroad</h4>
                        <p>Explore universities in popular abroad countries</p>
                        <div id="study-ab-locations">
                        	<ul>
				    <li>
						<div class="flag-left-wrapper" onmouseover="showStudyAbroadMap(this,'usa')" onmouseout="hideStudyAbroadMap(this,'usa');">	
							    <div class="inactive-country" id="usa_flag-left-wrapper_lebel" style="cursor: pointer" onClick="window.location='<?php echo SHIKSHA_STUDYABROAD_HOME."/study-in-usa-countryhome"; ?>';">
									<span class="flags usa flLt"></span>
									<a href="<?php echo SHIKSHA_STUDYABROAD_HOME."/study-in-usa-countryhome"; ?>" class="fUnitedStates" title="USA" onclick="trackEventByGA('CountryClick',this.innerHTML);">United States</a>
							    </div>						
						</div>
						<div class="flag-right-wrapper" onmouseover="showStudyAbroadMap(this,'canada')" onmouseout="hideStudyAbroadMap(this,'canada');">
							    <div class="inactive-country" id="canada_flag-left-wrapper_lebel" style="cursor: pointer" onClick="window.location='<?php echo SHIKSHA_STUDYABROAD_HOME."/study-in-canada-countryhome"; ?>';">
									<span class="flags canada flLt"></span>
									<a href="<?php echo SHIKSHA_STUDYABROAD_HOME."/study-in-canada-countryhome"; ?>" class="fSouthEastAsia" title="Canada" onclick="trackEventByGA('CountryClick',this.innerHTML);">Canada</a>
							    </div>
						</div>
				    </li>
				    
				    <li>
						<div class="flag-left-wrapper" onmouseover="showStudyAbroadMap(this,'australia')" onmouseout="hideStudyAbroadMap(this,'australia');">                                	
							    <div class="inactive-country" id="australia_flag-left-wrapper_lebel" style="cursor: pointer" onClick="window.location='<?php echo SHIKSHA_STUDYABROAD_HOME."/study-in-australia-countryhome"; ?>';">
									<span class="flags australia flLt"></span>
									<a href="<?php echo SHIKSHA_STUDYABROAD_HOME."/study-in-australia-countryhome"; ?>" class="fEurope" title="Australia" onclick="trackEventByGA('CountryClick',this.innerHTML);">Australia</a>
							    </div>
						</div>
						<div class="flag-right-wrapper" onmouseover="showStudyAbroadMap(this,'uk')" onmouseout="hideStudyAbroadMap(this,'uk');">
							    <div class="inactive-country" id="uk_flag-left-wrapper_lebel" style="cursor: pointer" onClick="window.location='<?php echo SHIKSHA_STUDYABROAD_HOME."/study-in-uk-countryhome"; ?>';">
									<span class="flags uk flLt"></span>
									<a href="<?php echo SHIKSHA_STUDYABROAD_HOME."/study-in-uk-countryhome"; ?>" class="fAustralia" title="UK" onclick="trackEventByGA('CountryClick',this.innerHTML);">UK</a>
							    </div>						
						</div>
				    </li>
				    
				    <li>
						<div class="flag-left-wrapper" onmouseover="showStudyAbroadMap(this,'germany')" onmouseout="hideStudyAbroadMap(this,'germany');">
							    <div class="inactive-country" id="germany_flag-left-wrapper_lebel" style="cursor: pointer" onClick="window.location='<?php echo SHIKSHA_STUDYABROAD_HOME."/study-in-germany-countryhome"; ?>';">
									<span class="flags germany flLt"></span>
									<a href="<?php echo SHIKSHA_STUDYABROAD_HOME."/study-in-germany-countryhome"; ?>" class="fMiddleEast" title="Germany" onclick="trackEventByGA('CountryClick',this.innerHTML);">Germany</a>
							    </div>							
						</div>
						<div class="flag-right-wrapper" onmouseover="showStudyAbroadMap(this,'singapore')" onmouseout="hideStudyAbroadMap(this,'singapore');">
							    <div class="inactive-country" id="singapore_flag-left-wrapper_lebel" style="cursor: pointer" onClick="window.location='<?php echo SHIKSHA_STUDYABROAD_HOME."/study-in-singapore-countryhome"; ?>';">
									<span class="flags singapore flLt"></span>
									<a href="<?php echo SHIKSHA_STUDYABROAD_HOME."/study-in-singapore-countryhome"; ?>" class="fNewZealand" title="Singapore" onclick="trackEventByGA('CountryClick',this.innerHTML);">Singapore</a>
							    </div>
						</div>
				    </li>
				    
				    <li style="margin-bottom:0">
						<div class="flag-left-wrapper" onmouseover="showStudyAbroadMap(this,'newzealand')" onmouseout="hideStudyAbroadMap(this,'newzealand');">	
							    <div class="inactive-country" id="newzealand_flag-left-wrapper_lebel" style="cursor: pointer" onClick="window.location='<?php echo SHIKSHA_STUDYABROAD_HOME."/study-in-new-zealand-countryhome"; ?>';">
									<span class="flags newzealand flLt"></span>
									<a href="<?php echo SHIKSHA_STUDYABROAD_HOME."/study-in-new-zealand-countryhome"; ?>" class="fUK-ireland" title="New Zealand" onclick="trackEventByGA('CountryClick',this.innerHTML);">New Zealand</a>
							    </div>
						</div>
				    </li>
                            </ul>
                            <div class="clearFix"></div>
                        </div>
                        
                     
                </div>
            </div>

<script> 
function showStudyAbroadMap(element,type) { 
        //alert(type); 
        element.style.position = "relative"; 
        $(escape(type)+'_flag-left-wrapper_lebel').className= "inactive-country active-country"; 
        //$j('#'+escape(type)+'_country-sub-layer').show(); 
} 
 
function hideStudyAbroadMap(element,type) { 
        //alert(type); 
        element.style.position = "static"; 
        $(escape(type)+'_flag-left-wrapper_lebel').className= "inactive-country"; 
        //$j('#'+escape(type)+'_country-sub-layer').hide(); 
} 
</script> 
