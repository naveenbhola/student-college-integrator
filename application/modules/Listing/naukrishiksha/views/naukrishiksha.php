<?php $headerComponents = array(
                          'css'   => array(
                                          'header',
                                          'mainStyle',
                                          'raised_all',
                                          'footer'
                                          ),
                          'js'    => array(
                                          'common',
                                          'cityList',
                                          'naukrishiksha',
                                          'header'
                                           ),
                          'title' => 'Naukri Shiksha',
                          'tabName' => '', 
                          'product' => 'naukrishikshahome', 
                          'bannerProperties' => array(),
                          'metaKeywords'  =>"",
                          'metaDescription'  =>"",
                          'currentPageName' => "NAUKRISHIKSHA_HOMEPAGE"
                         );
$this->load->view('naukrishiksha/homepage', $headerComponents);
?>
<?php
require 'bestplacesarray.php';
    global $NauCatMap;
    $NauCatMap = json_decode($array1,true);
?>

<div style="margin:0 10px">
    <!--Start_OuterBorder-->
    <div style="margin:0 10px">
	<div style="width:100%">
	<div class="raised_lgraynoBG"> 
       <b class="b2"></b><b class="b3"></b><b class="b4"></b>
            <div class="boxcontent_lgraynoBG">
            	<div style="width:100%">
					<div style="margin:0 10px">
					<div style="width:100%">
					<!--Start_Inside_Box_Top_GetStarted-->
					<div>
						<div class="bld fontSize_20p row">Give wings to your career with higher education</div>
						<div class="lineSpace_2 row">&nbsp;</div>
						<div class="fontSize_14p row" style="line-height:18px">
							In a competitive job market, an additional qualification can boost your career prospects multifold. It will help in upgrading your skills and give you an edge over others. Shiksha.com, a Naukri.com venture, with over 92,000 courses across 25,000 institutes in India and abroad, helps you find the right courses & institutes to take your career to the next level.
						</div>
						<div class="lineSpace_10">&nbsp;</div>
						<div style="background:url(/public/images/ns_mc.jpg) repeat-x left top;width:100%">
							<div class="float_R" style="width:18px;height:102px"><img src="/public/images/ns_rc.jpg" /></div>
							<div class="float_L" style="width:18px;height:102px"><img src="/public/images/ns_lc.jpg" /></div>
							<div style="margin:0 18px;height:102px;">
								<div class="row">
									<div style="padding:15px 0 10px 0"><span class="fontSize_18p">Get Started Now!</span> <span class="fontSize_14p bld" style="color:#79706b">Please make your selections below:</span></div>
									<div class="row">
										<?php global $categoryParentMap;?>
										<div class="float_L" style="width:29%;padding-bottom:2px"><label class="bld">Field of Interest :</label><br /><select style="width:90%" id = "catselect" onChange = "changeCatSubCat(this.value);">
											<option value = ''>Select</option>
											<?php foreach($categoryParentMap as $key=>$value) { 
											if($value['id'] == $selectedcategoryId){ ?>
											<option selected = 'true' value = "<?php echo $value['id']?>"><?php echo $key?></option>
											<?php } else { ?>
											<option value = "<?php echo $value['id']?>" ><?php echo $key?></option>
											<?php } } ?>
											</select>
											<div class="errorPlace" style="margin-top:2px;">
												<div class="errorMsg" id= "catselect_error"></div>
											</div>
										</div>
										<div class="float_L" style="width:37%;padding-bottom:2px"><label class="bld">Subject of Interest :</label><br />
											<select style="width:91%" id = "subcatselect" onClick = "checkcat()">
											<option value = "0">Select</option>
											</select>
											<div class="errorPlace" style="margin-top:2px;">
												<div class="errorMsg" id= "subcatselect_error"></div>
											</div>
										</div>
										<div class="float_L" style="width:20%;padding-bottom:2px"><label class="bld">Preferred Location :</label><br />
											<select style="width:90%" id = "locationselect" onChange = "if(this.value == ''){ document.getElementById('locationselect_error').innerHTML = 'Please select your city'; document.getElementById('locationselect_error').parentNode.className = '';}else{ document.getElementById('locationselect_error').innerHTML = '';document.getElementById('locationselect_error').parentNode.className = 'errorPlace';}"">
											<option value = ''>Select</option>
											<?php for($j = 0;$j < count($cities); $j++) {?> 
											<option value = "<?php echo $cities[$j]['cityId']?>" title="<?php echo $cities[$j]['cityName']?>"><?php echo $cities[$j]['cityName']?></option>				
											<?php } ?>
											</select>
											<div class="errorPlace" style="margin-top:2px;">
												<div class="errorMsg" id= "locationselect_error"></div>
											</div>
										</div>										
										<div class="float_L" style="width:13%;padding-bottom:2px;height:35px">
										<div style="line-height:3px;height:3px;font-size:1px;overflow:hidden">&nbsp;</div>
										<input type="button" class="continueBtn"  value="Find" onClick = "checkandfind('bestplaces');" style="cursor:pointer" />
										</div>										
										<div class="clear_L withClear">&nbsp;</div>        
									</div>
								</div>
							</div>
						<div class="clear_B withClear">&nbsp;</div>
						</div>
						<div class="lineSpace_10">&nbsp;</div>
					</div>
					<!--End_Inside_Box_Top_GetStarted-->
					</div>
					</div>
				</div>
				
				<!--Start_Left_And_Right_Panel-->
				<div style="width:100%">
					<div style="margin:0 10px">
						<div style="width:100%">
							<div class="float_L" style="width:158px">
								<div style="width:100%">
									<div class="raised_lgraynoBG"> 
										<b class="b2"></b><b class="b3"></b><b class="b4"></b>
											<div class="boxcontent_lgraynoBG" style="background:url(/public/images/bg-ns.gif) repeat-x left bottom">
												<div style="line-height:26px;" class="bld"> &nbsp; Top Institutes</div>
											</div>
										<b class="b1b" style="margin:0"></b>
									</div>
									<div class="lineSpace_5">&nbsp;</div>
									<div align="center">
															<?php $bannerProperties = array('pageId'=>'NAUKRISHIKSHA', 'pageZone'=>'TOP');
															$this->load->view('common/banner',$bannerProperties); ?>
                                    
                                    </div>
								</div>
							</div>
							<div style="margin-left:170px">
								<div style="float:left;width:100%">
									<div style="width:100%">
										<div style="width:100%">
											<div class="raised_lgraynoBG">
												<b class="b2"></b><b class="b3"></b><b class="b4"></b>
												<div class="boxcontent_lgraynoBG" style="background:url(/public/images/bg-ns.gif) repeat-x left bottom">
													<div style="line-height:26px;" class="bld"> &nbsp; Best Places to Study</div>
												</div>
												<b class="b1b" style="margin:0"></b>
											</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<div style="width:100%">

											<?php 
                                            $i = 0; foreach($NauCatMap as $catname => $maincat) { ?>
												<div class="float_L" style="width:22%;height:345px;overflow:hidden">
													<div style="width:100%">
														<div style="padding:0 10px;">
															<div style="width:100%">
																<div class="OrgangeFont bld" style="padding-bottom:4px"><?php echo $catname ?></div>
																<?php for($i =0;$i<count($maincat);$i++) {
                                                                $insti = $maincat[$i];
                                                                $instiName = strlen($insti['name']) > 42 ? substr($insti['name'],0,39).'...' : $insti['name'];
                                                                if(strpos($insti['url'],'http') === 0)
                                                                {
                                                                  $instiUrl = $insti['url'];
                                                                }
                                                                else
                                                                $instiUrl = SHIKSHA_HOME.$insti['url'];
								$instiUrl = str_replace('listing/Listing/getDetailsForListingNaukriShiksha','getListingDetail',$instiUrl);
                                                                ?>
																	<div style="padding:3px 0">
																		<a href="#" onClick = "showShikshaConfirmation('<?php echo $instiUrl ?>');return false;" title="<?php echo $insti['name']?>" class="font_size_11"><?php echo $instiName ?></a><br />
																	</div>
																<?php } ?>
															</div>
														</div>
													</div>
												</div>
											<?php } ?>
											<div class="clear_L withClear">&nbsp;</div>
										</div>
									</div>
								</div>
							</div>
							<div style="clear:left">&nbsp;</div>
						</div>
					</div>
				</div>
				<!--End_Left_And_Right_Panel-->	
            </div>
        <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
    </div>
    </div>
	</div>
    <!--Start_OuterBorder-->
</div>
<script>
var categoryList = eval(<?php echo json_encode($categoryList); ?>);
</script>
<?php $this->load->view('naukrishiksha/footer'); ?>
