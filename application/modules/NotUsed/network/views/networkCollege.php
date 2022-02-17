<!--Start_Mid_Panel-->
<div id="mid_Panel_group">
					
									<div class="lineSpace_10">&nbsp;</div>
	<div>
					<div>
						<div class="raised_sky"> 
						<b class="b1"></b><b class="b2"></b><b class="b3" style="background:#FFFFFF"></b><b class="b4" style="background:#FFFFFF"></b>
						<div class="boxcontent_skyCollegeGrp">
							<div class="mar_full_10p">
								<div style="float:left; width:67%; border-right:1px dotted #77C8DB">
									<div class="fontSize_14p bld"><span class = "OrgangeFont fontSize_16p bld" id = "NetworkName"></span>
                                    <?php if($groupCategory != "TestPreparation") { ?>
                                    <span id = "ResultsFor">India</span><img style = "padding-left:5px;" height = "17" width = "27" align = "absmiddle" src = "/public/images/india.gif" id = "countryFlag"/>
                                    <?php } ?>
                                    </div>
									<div class="lineSpace_10">&nbsp;</div>
									<div class="fontSize_12p">
<?php 
                                    $screen_res = $_COOKIE['client'];
                                    ?>
										<span class = "fontSize_12p bld" id = "showresults"> Showing Results For  </span><?php if($screen_res < 1024){ ?> <div class="lineSpace_10">&nbsp;</div>
                                        <?php } ?>
										<span>
                                        <select style = "width:100px" id = "countryforGroups" onChange = "selectCountry(this.value,1);">
                                        <?php 
                                        global $countries;
                                                 if($institute == 2){?>
                                                <option value = "2" name = "India" flag = "/public/images/india.gif">India</option>
                                                <?php }else { 
                                        foreach($countries as $countryId => $country) {
                                            $countryFlag = isset($country['flagImage']) ? $country['flagImage'] : '';
                                            $countryName = isset($country['name']) ? $country['name'] : '';
                                            $country_Id = isset($country['id']) ? $country['id'] : '';
                                            if($groupCategory == "Study Abroad" && $countryName == "India")
                                            {
                                            }
                                            else
                                            {
                                            ?>
                                                <option value = "<?php echo $country_Id?>" name = "<?php echo $countryName?>" flag = "<?php echo $countryFlag?>"><?php echo $countryName?></option>
                                                <?php }}} ?>
                                                </select>

                                        
                                        </span>
										<span>
                                        <select style = "width:170px" id = "citiesforGroups" onChange = "selectCityforCollege(<?php echo $institute?>);">
                                        </select>
                                         <?php if($institute == 1){?>
                                         <script>
                                         getCitiesForCountry('',true,'forGroups');
                                         </script>
                                         <?php } 
                                         else
                                         {?>
                                         <script>
                                         getCitiesForCountry('<?php echo $groupCategory?>',true,'forGroups');
                                         </script>
                                         <?php } ?>

                                        </span>
                                    <?php if($groupCategory == "TestPreparation") { ?>
                                    <script>
                                    document.getElementById('showresults').style.display = 'none';
                                    document.getElementById('countryforGroups').style.display = 'none';
                                    document.getElementById('citiesforGroups').style.display = 'none';
                                    </script>
										<div class="lineSpace_10">&nbsp;</div>
                                    <?php }?>
									</div>
									<div style="line-height:23px">&nbsp;</div>
								</div>
								<div style="float:left; width:31%">
									<div class="mar_left_10p">
										<div class="fontSize_14p bld OrgangeFont">Statistics</div>
										<div class="lineSpace_5">&nbsp;</div>
                                        <?php if($institute == 1)  
                                        {
                                        $countinstitute = $insticount . " Institute Groups ";
                                        $countmember = $membercount . " Institute Group Members";
                                        }
                                        else 
                                        {
                                        $countinstitute = $insticount . " School Groups";
                                        $countmember = $membercount . " School Group Members";
                                        }
                                        ?>
										<div class="arrowBullets fontSize_12p"><?php echo $countinstitute ?></div>
										<div class="lineSpace_5">&nbsp;</div>
										<div class="arrowBullets fontSize_12p"><?php echo $countmember?></div>
									</div>
								</div>
								<div class="clear_L"></div>
							</div>
						</div>
						<b class="b1b" style="margin:0;"></b>
						</div>				
					</div>
					<div class="lineSpace_15">&nbsp;</div>
					<div class="bgAlphabetImg brdalpha" style="height:23px;">
						<div style="margin-left:10px; line-height:19px" id = "alphaID">                                                                                   
								<a href="#" id = "Allalpha" onClick = "return selectAlphabet('Allalpha',<?php echo $institute?>,1)" onFocus="if(this.blur) this.blur()">All</a>
								<?php for($i = 65;$i <= 90 ;$i++)
								{
									$char = chr($i);
								?>
								<a href="#" id = "<?php echo $char?>" onClick = "return selectAlphabet('<?php echo $char?>',<?php echo $institute?>,1)" onFocus="if(this.blur) this.blur()"><?php echo $char?></a>
								<?php } ?>
						</div>
					</div>
					<div class="lineSpace_5">&nbsp;</div>
                    <div class="mar_full_5p">
                                   <?php if($screen_res < 1024){
                                   $screenWidth = 30;
                                   $screenWidth1 = 70;
                                   }
                                   else
                                   {
                                   $screenWidth = 35;
                                   $screenWidth1 = 65;
                                   }
                                   ?>
								<div style="float:left; width:<?php echo $screenWidth?>%;" class = "bld fontSize_12p" id = "networkmsg1">
                                </div>
								<div style="float:left; width:<?php echo $screenWidth1?>%;" align = "right">
                        <div class="pagingID" id="paginataionPlace1" style="position:relative; top:5px"></div>
                        </div>
								<div class="clear_L"></div>
                        <div class="lineSpace_11">&nbsp;</div>
                    </div>           
					 <div class="lineSpace_5">&nbsp;</div>
					<div style="width:99%">
						<div id = "networkofColleges"></div>
						<div class = "fontSize_12p">
							<div id = "nocollegesmsg" class = "txt_align_c"></div>
						</div>
						<div class = "mar_full_5p">
								<div style="float:left; width:<?php echo $screenWidth?>%;" class = "bld fontSize_12p" id = "networkmsg2">
                                </div>
								<div style="float:left; width:<?php echo $screenWidth1?>%" align = "right">
							<div class="pagingID" id="paginataionPlace2" style = "position:relative;top:5px"></div>
                            </div>
								<div class="clear_L"></div>
						</div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="lineSpace_10">&nbsp;</div>
						<?php 
							$bannerProps = array('pageId'=>'GROUP', 'pageZone'=>'FOOTER');
							$this->load->view('common/banner',$bannerProps);
						?>
			        </div>
	</div>
</div>
<br clear="all" />
