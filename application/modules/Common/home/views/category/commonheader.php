    <div>
        <!--Start_SearchPanel-->
        <div style="width:100%">
            <div class="shik_bgSearchPanel" style="height:108px">
                <div style="width:100%">
                    <div style="width:238px;height:92px;position:relative" class="float_L">
                        <!--Start-LeftSide_Logo-->
                        <span id = "imagespace" style="width:235px;display:block;position:absolute;left:0pt;bottom: 0pt;">
                        </span>
                        <!--End_LeftSide_Logo-->
                    </div>
                    <div style="margin-left:238px">
                        <div style="width:100%" class="float_L">
                            <div class="shik_bgSearchPanel_2">
								<?php $this->load->view('home/commonSearchPanel');?>
                                <?php 
									global $countriesForStudyAbroad;
									$countryFlag = isset($countriesForStudyAbroad[$countrySelected]) ? $countriesForStudyAbroad[$countrySelected]['flagImage'] : '';
									if($countryNameSelected == 'UK-ireland'){//Hack for studyabroad page region name.
										$countryNameSelected = 'UK-Ireland';
									}
								?>
                                <!--Start_CategoryPage_Title-->
                                <div style="width:100%">
                                    <div class="defaultAdd lineSpace_3">&nbsp;</div>
                                    <div>
                                        <h1><span class="Fnt18 bld float_L fcblk" style="padding-top:8px">Study in <?php echo $countryNameSelected; ?></span></h1>
										<span class = "float_L shik_allFlagSpirit1 f<?php echo str_replace(' ','',$countryNameSelected);?>" style="height:20px;padding:0 0 15px 48px">&nbsp;</span>
                                        <span class="Fnt12 fcGya float_L" style="padding-top:12px">
                                            <b>[</b> <a href="#" style="" onClick = "drpdwnOpen(this, 'userPreferenceCategoryCity',6);document.getElementById('userPreferenceCategoryCity').style.left = obtainPostitionX(this) + 'px';document.getElementById('userPreferenceCategoryCity').style.top = obtainPostitionY(this) + 15 + 'px';return false;">Change Location <span class="orangeColor">&#9660;</span></a> <b>]</b>
                                        </span>                                        
                                    </div>
                                </div>
                                <!--End_CategoryPage_Title-->
                            </div>
                        </div>
                    </div>
                    <div class="clear_L">&nbsp;</div>
                </div>
            </div>
        </div>
        <!--End_SearchPanel-->
		<div class="lh10"></div>
	</div>        
