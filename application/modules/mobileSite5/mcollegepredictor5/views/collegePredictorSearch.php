		    <section id="searchFormSection" class="content-wrap2 clearfix">
		            <div class="tupple-wrap clearfix" style="padding-top:22px; padding-bottom:15px">
		            
		            <ol class="form-item">
							<script type="text/javascript">
							var tab = '<?=$tab?>';
							</script>
		                        <?php if($tab=="3"){ ?>
					                <li>
								    <a class="selectbox" data-transition="slide" data-rel="dialog" data-inline="true" href="#branchDiv">
									<p>
									 <span id="branchText">Select Preferred Branch</span>
									 <i class="icon-select2"></i>
									</p>
								    </a>			    
					                            <div class="errorMsg" id="error_userBranch" style="display: none;"></div>
					                </li>
								<?php } ?>
					
		                        <?php if($tab=="1" || $tab=="3"){ ?>
			                        <li data-enhance="false">
			                            <div class="textbox" style="background:#fff;">
			                                <input minlength="1" maxlength="7" inputType = <?=$inputType;?> id="userRank" style="padding:8px 4px 8px 6px" type="text" placeholder="Enter <?=$inputType?> / Predicted <?=$inputType?>">
			                            </div>
			                            <div class="errorMsg" id="error_userRank" style="display: none;"></div>
			                        </li>
									<?php $style = ($examSettingsArray['showKnowYourRank'] == 'NO')?'display:none;':'';?>
									<li style="font-size: 0.8em;margin-top: -0.7em; <?php echo $style;?>">

						                        <?php if(strtoupper($examName)!='JEE-MAINS' && strtoupper($examName) !='COMEDK'){ ?>
							                    <a href="javascript:void(0)" onClick="$('#rankPredictorPopupLink').click();">Find Your Rank</a>
									<?php }else if(strtoupper($examName)=='COMEDK'){?>
									    <a href="<?php echo COLLEGE_PREDICTOR_BASE_URL.'/comedk-rank-predictor'?>">Find Your Rank</a>
									
						                        <?php }else{ ?>
						                            <a href="<?php echo COLLEGE_PREDICTOR_BASE_URL.'/jee-main-rank-predictor'?>">Find Your Rank</a>
						                        <?php } ?>
		
									</li>
					
		                        <?php }else if($tab=="2"){ ?>
			                        <li>
									    <a class="selectbox" data-transition="slide" data-rel="dialog" data-inline="true" href="#instituteDiv">
										<p>
										 <span id="instituteText">Select Preferred College</span>
										 <i class="icon-select2"></i>
										</p>
									    </a>			    
			                            <div class="errorMsg" id="error_userCollege" style="display: none;"></div>
			                        </li>
		                        <?php } ?>
		                        
		                        <?php if($tab=="1" || $tab=="3"){ ?>
		                        <?php if($examSettingsArray['showRankType'] == 'YES'):?>
			                        <?php 
			                        if($examSettingsArray['examName'] == 'MHCET'){
			                        ?>
			                        <li>
										<select id="userRankType" onchange="toggleState();">
										    <option value="Other">Rank Type - All India Rank</option>
										    <option value="HomeUniversity">Home University</option>
										    <option value="OtherThanHome">Other Than Home University</option>
										    <option value="StateLevel">State Level</option>
										</select>
			                        </li>
			                        <?php 
			                        }else if($examSettingsArray['examName'] == 'KCET'){
			                        ?>
			                        <li>
										<select id="userRankType" onchange="toggleState();">
										    <option value="KCETGeneral">General</option>
										    <option value="HyderabadKarnatakaQuota">Hyderabad-Karnataka Quota</option>
										</select>
			                        </li>
			                        <?php 
			                        }else{
			                        ?>
			                        <li>
										<select id="userRankType" onchange="toggleState();">
										    <option value="Other">Rank Type - All India Rank</option>
										    <option value="Home">Home State Rank</option>
										</select>
			                        </li>
									<?php 
									}
									?>
			                        <li style="display: none;" id="userStateSection">
			                            <select id="userState">
			                                <option value="">Select State</option>
			                                <?php foreach ($stateArr as $state){ ?>
    		                                <?php if(!empty($state['stateName'])) { ?>
												 <option value="<?=$state['stateName']?>"><?=$state['stateName']?></option>                                      
			                                <?php } } ?>
			                            </select>
			                            <div class="errorMsg" id="error_userState" style="display: none;"></div>
			                        </li>
		                        
		                        <?php else :?>
			                        <li style="display:none;">
										<select id="userRankType" onchange="toggleState();">
										    <option value="<?=$examSettingsArray['rankType'];?>" selected="selected">Home State Rank</option>
										</select>
			                        </li>
			                        
			                        <li style="display:none;">
			                            <select id="userState">
			                                <option value="<?php echo $examSettingsArray['stateName'];?>" selected="selected">Select State</option>
			                            </select>
			                            <div class="errorMsg" id="error_userState" style="display: none;"></div>
			                        </li>
		                        <?php endif;?>
					<?php } ?>
								<?php if(!empty($examSettingsArray['categoryData'])):?>
									<?php if(count($examSettingsArray['categoryData']) == 1): ?>
				                        <li style="display: none;">
											<select id="userCategory">
												<?php foreach ($examSettingsArray['categoryData'] as $categoryVal => $categoryName ):?>
											    <option value="<?php echo $categoryVal;?>" selected="selected">Category - <?php echo $categoryName;?></option>
											    <?php endforeach;?>
											</select>
				                        </li>
									<?php else : ?>
				                        <li>
											<select id="userCategory">
												<?php foreach ($examSettingsArray['categoryData'] as $categoryVal => $categoryName ):?>
											    <option value="<?php echo $categoryVal;?>">Category - <?php echo $categoryName;?></option>
											    <?php endforeach;?>
											</select>
				                        </li>
									<?php endif;?>
								<?php endif;?>		
					<?php if($tab =="2"){ ?>
					 			<?php if($examSettingsArray['showRankType'] == 'YES'):?>
		                		<li>
									<select id="userRankType" onchange="toggleState();">
									    <option value="Other">Avail State Quota - No</option>
									    <option value="Home">Avail State Quota - Yes</option>
									</select>
		                        </li>
		
		                        <li style="display: none;" id="userStateSection">
		                            <select id="userState">
		                                <option value="">Select State</option>
		                                <?php foreach ($stateArr as $state){ ?>
		                                <?php if(!empty($state['stateName'])) { ?>
		                                    <option value="<?=$state['stateName']?>"><?=$state['stateName']?></option>                                      
		                                <?php } } ?>
		                            </select>
		                            <div class="errorMsg" id="error_userState" style="display: none;"></div>
		                        </li>
		                        <?php else: ?>
		                		<li style="display: none;">
									<select id="userRankType" >
									    <option value="<?=$examSettingsArray['rankType'];?>" selected="selected" >Avail State Quota - Yes</option>
									</select>
		                        </li>
		
		                        <li style="display:none;">
		                            <select id="userState">
		                                <option value="<?php echo $examSettingsArray['stateName'];?>" selected="selected">Select State</option>
		                            </select>
		                            <div class="errorMsg" id="error_userState" style="display: none;"></div>
		                        </li>
		                        
		                        <?php endif;?>
					<?php } ?>
						
						<?php if(!empty($examSettingsArray['roundData'])):?>
							<?php if(count($examSettingsArray['roundData']) == 1 ):?>
			                <li style="display: none;">
								<select id="userRound">
									<option value="1" selected="selected"></option>
								</select>
			                </li>
			                <?php else :?>
			                <li>	
								<select id="userRound">
									<?php foreach ($examSettingsArray['roundData'] as $roundVal => $roundName):?>
									    <option value="<?php echo $roundVal;?>"><?php echo $roundName;?></option>
								    <?php endforeach;?>
								</select>
			                </li>
						    <?php endif;?>
						<?php endif;?>		                        
                        <li data-enhance="false">
                            <input id="searchButton" type="button" class="button yellow" value="Search" onClick="trackEventByGAMobile('HTML5_College_Predictor_SearchButton'); try{_gaq.push(['_trackPageview']);}catch(e){} searchCall='true';resetFilters();searchCollegePredictor(0,'<?=$tab?>','','<?=$trackingPageKeyId?>','<?php echo strtoupper($examName)?>', '<?=$directoryName;?>')">
			    			&nbsp;<img id="searchLoader" style="display:none;" border=0 alt="" src="/public/images/loader_small_size.gif" />
                        </li>
		                    </ol>
		        </div>
			    
			 <form style="display: none;" id="setSearchFormCP" action="<?=SHIKSHA_HOME?>/muser5/MobileUser/register" method="post">
				<input type="hidden" value="<?=base64_encode(SHIKSHA_HOME.'/jee-mains-college-predictor');?>" name="current_url">
				<input type="hidden" value="setResultFromCP" name="from_where">
				<input type="hidden" name="tracking_keyid" id="tracking_keyid" value='<?=$trackingPageKeyId?>'>
			 </form>
		    
		    </section>
		
		
		<script>
		var SHIKSHAHOME='<?=SHIKSHA_HOME;?>';
		var branchList = '';
		var instituteList = '';
		<?php if($showResults=='true'){
		    echo "var startingPoint = 0;";
		}
		else{
		    echo "var startingPoint = -1;";    
		}
		?>
		
		function setTotalResults(){
		    <?php if( $totalResults>=0 ){ ?>
		    $('#totalResultsSection').html('<?php echo $totalResults; ?> options available');
		    $("#totalResultsSection").show();
		    <?php } ?>
		}
		
		function trackReqEbrochureClick(courseId){
		try{
		        _gaq.push(['_trackEvent', 'HTML5_College_Predictor_Page_Request_Ebrochure', 'click',courseId]);
		}catch(e){}
		}
		

		
		function clearFiltersOnLayer(){
		    $('#state-list input:checked').each(function() {
		        $(this).attr('checked',false);
		    });    
		}
		</script>
		
		<?php
		    if(isset($showResults) && $showResults=='true'){
			echo "<script>showResultsList();</script>";	
		    }
		?>
		
		<script>
		$(document).ready(function(){
		    
		    <?php
			//Also, if we have got the Search parameters, we need to change the default values in the Search forms
			if(isset($searchParameters) && is_array($searchParameters)){
			    if(isset($searchParameters['rankType'])){
				echo "$('#userRankType').val('".$searchParameters['rankType']."').change();";
			    }
			    if(isset($searchParameters['categoryName'])){
				echo "$('#userCategory').val('".$searchParameters['categoryName']."').change();";
			    }
			    if(isset($searchParameters['round'])){
				echo "$('#userRound').val('".$searchParameters['round']."').change();";
			    }
			    if(isset($searchParameters['stateName']) && $searchParameters['stateName']!=''){
				echo "$('#userState').val('".$searchParameters['stateName']."').change();";
				echo "toggleState();";
			    }
			    if(isset($searchParameters['rank']) && $searchParameters['rank']>0){
				echo "$('#userRank').val('".$searchParameters['rank']."');";
			    }
			    if(isset($searchParameters['branchAcronym']) && $searchParameters['branchAcronym']!=''){
				$list = implode(',',$searchParameters['branchAcronym']);
				echo "branchList = '".$list."';";
				$count = count($searchParameters['branchAcronym']);
				echo "$('#branchText').html('Selected (".$count.")');";
		
				foreach ($searchParameters['branchAcronym'] as $branchAcronym){
				    echo "$('#".preg_replace('/(\s)+/', '', strtolower($branchAcronym))."').prop('checked', true);";   
				}		
			    }
			    if(isset($searchParameters['institutesOptions']) && $searchParameters['institutesOptions']!=''){
				$list = implode(',',$searchParameters['institutesOptions']);
				echo "instituteList = '".$list."';";
				$count = count($searchParameters['institutesOptions']);
				echo "$('#instituteText').html('Selected (".$count.")');";
		
				foreach ($searchParameters['institutesOptions'] as $institute){
				    echo "$('#".strtoupper($institute)."').prop('checked', true);";   
				}		
			    }
			    else if(isset($searchParameters['instituteId']) && $searchParameters['instituteId']!=''){
				$list = implode(',',$searchParameters['instituteId']);
				echo "instituteList = '".$list."';";
				$count = count($searchParameters['instituteId']);
				echo "$('#instituteText').html('Selected (".$count.")');";
		
				foreach ($searchParameters['instituteId'] as $institute){
				    echo "$('#".$institute."').prop('checked', true);";   
				}		
			    }
			}
		    ?>
		    
		});
		</script>

                        <?php
                            //Add Banner on JEE Main College Predictor
                            if(strtoupper($examName) == 'JEE-MAINS') {
                                 $bannerProperties1 = array('pageId'=>'JEE_COLLEGE_PREDICTOR', 'pageZone'=>'MOBILE');
                                 echo '<section class="clearfix" style="margin-bottom:10px;margin-top:10px;" id="bannerSearch">';
                                 $this->load->view('common/banner',$bannerProperties1);
                                 echo '</section>';
                            }
                        ?>

