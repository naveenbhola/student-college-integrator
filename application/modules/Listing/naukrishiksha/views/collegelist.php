<?php $headerComponents = array(
                          'css'   => array(
                                          'mainStyle',
                                          'raised_all',
                                          'modal-message'
                                          ),
                          'js'    => array(
                                          'common',
                                          'cityList',
                                          'lazyload',
					  'header',
                                          'multipleapply',
                                          'naukrishiksha'
                                           ),
                          'title' => 'Naukri Shiksha Search',
                          'tabName' => '', 
                          'product' => 'naukrishikshahome', 
                          'bannerProperties' => array(),
                          'metaKeywords'  =>$selectedCategoryName.' '.$selectedSubCategoryName.' '.$selectedCityName,
                          'metaDescription'  =>"",
                          'currentPageName' => "NAUKRISHIKSHA_DETAILPAGE"
                         );
$this->load->view('naukrishiksha/homepage', $headerComponents);
if(empty($resultSet))
{
$countArray = array();
}
?>
<script>
 LazyLoad.loadOnce([
        '/public/js/<?php echo getJSWithVersion("tooltip"); ?>',
        '/public/js/<?php echo getJSWithVersion("ajax-api"); ?>',
        '/public/js/<?php echo getJSWithVersion("user"); ?>'
    ],callbackfn);
</script>

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
											<div class="lineSpace_10">&nbsp;</div>
											<div style="background:url(/public/images/ns_mc.jpg) repeat-x left top;width:100%">
												<div class="float_R" style="width:18px;height:102px"><img src="/public/images/ns_rc.jpg" /></div>
												<div class="float_L" style="width:18px;height:102px"><img src="/public/images/ns_lc.jpg" /></div>
												<div style="margin:0 18px;height:102px;">
													<div class="row">
														<div style="padding:15px 0 8px 0"><span class="fontSize_18p">Find Institutes/Courses</span> <span class="fontSize_14p bld" style="color:#79706b">Please make your selections from the dropdowns below:</span></div>
														<div class="row">                   
														<?php global $categoryParentMap;?>
															<div class="float_L" style="width:29%;padding-bottom:2px"><label class="bld">Field of Interest :</label><br /><select style="width:90%" id = "catselect" onChange = "changeSelection('category',this.value);">
																<option value = ''>Select</option>
																<?php foreach($categoryParentMap as $key=>$value) {
																if($value['id'] == $selectedcategoryId){
																$selectedPage = $value['page'];
																?>
																<option selected = 'true' value = "<?php echo $value['id']?>"><?php echo $key?></option>
																<?php $selectedCategoryName = $key;} else { ?>
																<option value = "<?php echo $value['id']?>" ><?php echo $key?></option>
																<?php }
																}
																?>
																
																</select>
																	<div class="errorPlace" style="margin-top:2px;">
																		<div class="errorMsg" id= "catselect_error"></div>
																	</div>
															</div>
															<div class="float_L" style="width:37%;padding-bottom:2px"><label class="bld">Subject of Interest :</label><br /><select style="width:91%" id = "subcatselect" onClick = "checkcat();" onChange = "changeSelection('subcategory',this.value);"><option value = '0'>Select</option>
																<?php for($i = 0;$i < count($categoryList);$i++) { if($categoryList[$i]['parentId'] == $selectedcategoryId) { if($categoryList[$i]['categoryID'] == $selectedsubcategoryId) { ?>
																<option selected = 'true' value = "<?php echo $categoryList[$i]['categoryID']?>"><?php echo $categoryList[$i]['categoryName']?></option>
																<?php $selectedSubCategoryName = $categoryList[$i]['categoryName'];} else { ?>
																<option value = "<?php echo $categoryList[$i]['categoryID']?>"><?php echo $categoryList[$i]['categoryName']?></option>
																<?php }}} ?>
																</select>
																<div class="errorPlace" style="margin-top:2px;">
																	<div class="errorMsg" id= "subcatselect_error"></div>
																</div>
															</div>
															<div class="float_L" style="width:20%;padding-bottom:2px"><label class="bld">Preferred Location :</label><br /><select style="width:90%" id = "locationselect" onChange = "changeSelection('location',this.value);"><option value = ''>Select</option>
																<?php for($j = 0;$j < count($cities); $j++) { 
																if($cities[$j]['cityId'] == $selectedcity) {
																?>
																<option selected = "true" title = "<?php echo $cities[$j]['cityName']?>" value = "<?php echo $cities[$j]['cityId']?>"><?php echo $cities[$j]['cityName']?></option>				
																<?php $selectedCityName = $cities[$j]['cityName']; } else {?>
																<option title = "<?php echo $cities[$j]['cityName']?>" value = "<?php echo $cities[$j]['cityId']?>"><?php echo $cities[$j]['cityName']?></option>				
																<?php }}?></select>
																<div class="errorPlace" style="margin-top:2px;">
																	<div class="errorMsg" id= "locationselect_error"></div>
																</div>
															</div>
															<div class="float_L" style="width:13%;padding-bottom:2px;height:35px">
																<div style="line-height:3px;height:3px;font-size:1px;overflow:hidden">&nbsp;</div>
																<input type="button" class="continueBtn"  value="Find" onClick = "checkandfind('naukrishiksha');" style="cursor:pointer" />
															</div>															
															<div class="clear_L withClear">&nbsp;</div>        
														</div>
													</div>
												</div>
												<div class="clear_B withClear">&nbsp;</div>
											</div>
											<div class="lineSpace_10">&nbsp;</div>
											<div>
												<div style="display:block;float:left">
													<div class="fontSize_14p bld"><span class="OrgangeFont" id = "totalfoundcount"></span>&nbsp;results for <span class="OrgangeFont" id = "selectedcategoryName"></span>: <span class="OrgangeFont" id = "selectedsubcategoryName"></span><span id = 'blankcolon'>:</span> <span class="OrgangeFont" id = "selectedlocation"></span></div>
													<div class="lineSpace_5">&nbsp;</div>
												</div>
												<div class="clear_L withClear">&nbsp;</div>
											</div>
											<div class="lineSpace_5">&nbsp;</div>
										<?php if(!empty($countArray)) { ?>	
											<div id = "uppertabs" style="width:100%">
											<div class="tabNs">
												<div id="shikshaNaukriTab">
													
													<?php $i = 0;$allcount = 0;
													$flag = 0;
													foreach($countArray as $resultrow => $resultkey) 
																					{
													if(!empty($resultrow) && is_array($resultkey)) {
                                                    if($resultrow != 'mainids' && $resultrow != 'categoryselector')
                                                    {
														$i++;$j = 0;
														$coursecount = 0;
														foreach($resultkey as $result=>$count)
														{
															$coursecount += $count;
														}
														if($resultrow == "All")
														$allcount = $coursecount;
														$newstring = $resultrow;
														$courselevel = '';
														$courselevel_1 = '';
														if(strpos($resultrow,"UG") !== false)
														{
															$newstring = $resultrow;

															$courselevel = substr($resultrow,(strpos($resultrow,"UG") + strlen("UG")));
															$courselevel_1 = "Under Graduate";
														}
														if(strpos($resultrow,"PG") !== false)
														{
															$newstring = $resultrow;
															$courselevel = substr($resultrow,(strpos($resultrow,"PG") + strlen("PG")));
															$courselevel_1 = "Post Graduate";
														}
														if($courselevel_1 == '' && $courselevel == '')
														{
															$courselevel = $resultrow;
															if($courselevel == "Diploma")
															{
															$courselevel_1 = "Diploma";	
															$newstring = 'UG Diploma';
															}
															else
															$courselevel_1 = 'NULL';
														}
														if($resultrow == $countArray['selectedCluster'])
														{
														$classsel = "selected_Ns";
														$flag = 1;
														$val = $i;
														$countval = $coursecount;
														$tabname = 'tab'.$i;
														}
														else
														{
															$classsel = "";

														}
														$margin = '';
														if($resultrow == 'All')
														$margin = 'margin-left:10px;';
														?>
														<div class="tabHolder">
															<a href="#" id = "<?php echo 'tab'.$i?>" class = "<?php echo $classsel?>" onClick = "document.getElementById('nsstartoffset').value = 0;document.getElementById('nscourselevel').value = '<?php echo $courselevel?>';document.getElementById('nscourselevel1').value = '<?php echo $courselevel_1?>';document.getElementById('nscoursetype').value = 'All';sendReqForNaukriShiksha();"  style="cursor:pointer;<?php echo $margin?>">
															<span style="cursor:pointer"><label style="cursor:pointer"><?php echo $newstring ?> <b>(<?php echo $coursecount?>)</b></label></span>
															</a>
														</div>						
						
													<?php 
													}}}?> 
												</div>
												<div class="clear_L withClear">&nbsp;</div>
												</div>
											</div>
                                            
											<?php } if($flag == 1)
											{
												$classsel = '';
											}	
											else
											{
												$classsel = 'selected_Ns';
											}?>
											<script>
												var BASE_URL = '<?php echo site_url(); ?>';
												var categoryList = eval(<?php echo json_encode($categoryList); ?>);
												var totalcount = <?php echo empty($countArray) ? 0 : $allcount; ?>;
											</script>

                                            <?php if(!empty($countArray)) { ?>
											<div class="subtabNs" id = "lowertabs">
												<div class="lineSpace_5">&nbsp;</div>
												<div id = "browsecourse">

													<?php $i = 0;if($allcount != 0) { $allcount = 0; foreach($countArray as $resultrow => $resultkey) 
													{
														if(is_array($resultkey))
														{                                    
														$i++;$j = 0;
														$coursecount = 0;
														foreach($resultkey as $result=>$count)
														{
															$coursecount += $count;
														}
														if($resultrow == "All")
														$allcount = $coursecount;
														$newstring = $resultrow;
														$courselevel = '';
														$courselevel_1 = '';
														if(strpos($resultrow,"UG") !== false)
														{
//															$newstring = str_replace("Under Graduate","UG",$resultrow);
						
															$courselevel = substr($resultrow,(strpos($resultrow,"UG") + strlen("UG")));
															$courselevel_1 = "Under Graduate";
														}
														if(strpos($resultrow,"PG") !== false)
														{
//															$newstring = str_replace("Post Graduate","PG",$resultrow);
															$courselevel = substr($resultrow,(strpos($resultrow,"PG") + strlen("PG")));
															$courselevel_1 = "Post Graduate";
														}
														if($courselevel_1 == '' && $courselevel == '')
														{
															$courselevel = $resultrow;
															if($courselevel == "Diploma")
															$courselevel_1 = "Diploma";	
															else
															$courselevel_1 = 'NULL';
														}
														if($resultrow == $countArray['selectedCluster'])
														{
														$displayflag = '';
													   if($selcoursetype == 'All')
													   {
													   $coursetypeclass = 'selected';
													   }
													   else
													   {
													   $coursetypeclass = '';
													   }
														}
														else
														{
														$displayflag = 'display:none;';
														}
													?>
														<div style="<?php echo $displayflag?>float:left;" id = "<?php echo 'subtabmain'.$i?>">                                    <div id = "<?php echo 'setmargin'.$i?>">
														<ul class="browseCities">
													   <li class="<?php echo $coursetypeclass?>" id = "<?php echo 'subtab'.$i.$j?>" style="width:auto;white-space:nowrap;color:rgb(162,162,162);"><a href="#"  id = '<?php echo 'subtabatag'.$i.$j?>' class="<?php echo $coursetypeclass?>" style = "padding:1px 10px 5px 5px" onClick = "document.getElementById('nsstartoffset').value = 0;document.getElementById('nscourselevel').value = '<?php echo $courselevel?>';document.getElementById('nscourselevel1').value = '<?php echo $courselevel_1?>';document.getElementById('nscoursetype').value = 'All';sendReqForNaukriShiksha();">All&nbsp;<b>(<?php echo $coursecount?>)</b></a>&nbsp;&nbsp;|&nbsp;</li>
													   <?php foreach($resultkey as $result => $resultk) { 
													   $j++;
													   if(trim($result) == trim($selcoursetype) && trim($resultrow) == trim($countArray['selectedCluster']))
													   {
													   $coursetypeclass = 'selected';
													   $countval = $resultk;
													   }
													   else
													   {
													   $coursetypeclass = '';
													   }
													   ?><li class = '<?php echo $coursetypeclass?>' id = "<?php echo 'subtab'.$i.$j?>" style="width:auto;white-space:nowrap;color:rgb(162,162,162);"><a href="#" class = '<?php echo $coursetypeclass?>' id = "<?php echo 'subtabatag'.$i.$j?>" style = "padding:1px 10px 5px 5px" onClick = "document.getElementById('nsstartoffset').value = 0;document.getElementById('nscourselevel').value = '<?php echo $courselevel?>';document.getElementById('nscourselevel1').value = '<?php echo $courselevel_1?>';document.getElementById('nscoursetype').value = '<?php echo $result?>';sendReqForNaukriShiksha();"><?php echo
													   $result?>&nbsp;&nbsp;<b>(<?php echo $resultk?>)</b></a><?php if($j != (count($resultkey))) echo "&nbsp;&nbsp;|" ?>&nbsp;&nbsp;</li><?php }?></ul></div></div><?php	}}}} 
																					?>
											</div>
												<div class="clear_L withClear">&nbsp;</div>
											</div>					
<script>
function setallcounts()
{
document.getElementById('totalfoundcount').innerHTML = totalcount == 0 ? 'No' : totalcount;
document.getElementById('selectedcategoryName').innerHTML = document.getElementById('catselect').options[document.getElementById('catselect').selectedIndex].innerHTML;
var subcatvalue = document.getElementById('subcatselect').options[document.getElementById('subcatselect').selectedIndex].innerHTML;
document.getElementById('selectedsubcategoryName').innerHTML = subcatvalue == "Select" ? '':subcatvalue;
document.getElementById('blankcolon').innerHTML = subcatvalue == "Select" ? '' : ':';
document.getElementById('subcatselect').options[document.getElementById('subcatselect').selectedIndex].innerHTML;
document.getElementById('selectedlocation').innerHTML = document.getElementById('locationselect').options[document.getElementById('locationselect').selectedIndex].innerHTML;

}
function showTabs(tabname,val)
{
    if(val != 0)
    {
	var divX = obtainPostitionX(document.getElementById(tabname));
	var width = (document.getElementById(tabname).offsetWidth);
	document.getElementById('subtabmain' + val).style.display = '';
	var position = (divX + width/2) - ((document.getElementById('setmargin' + val).offsetWidth)/2) - 50;
	position = position < 0 ? 10 : position;
	document.getElementById('setmargin' + val).style.marginLeft = position + 'px';
    }
}
setallcounts();
<?php if(!empty($countArray)) { ?>
showTabs('<?php echo $tabname?>','<?php echo $val?>');
<?php } ?>
</script>
											<input type = "hidden" value = "<?php echo $countval?>" id = "selectedcount"/>
											<input type = "hidden" value = "<?php echo $start?>" id = "nsstartoffset"/>
											<input type = "hidden" value = "<?php echo $countoffset?>" id = "nscountoffset"/>
											<input type = "hidden" value = "<?php echo $selectedcategoryId?>" id = "nscategoryid"/>
											<input type = "hidden" value = "<?php echo $selectedsubcategoryId?>" id = "nssubcategoryid"/>
											<input type = "hidden" value = "<?php echo $selcourselevel?>" id = "nscourselevel"/>
											<input type = "hidden" value = "<?php echo $selcourselevel1?>" id = "nscourselevel1"/>
											<input type = "hidden" value = "<?php echo $selcoursetype?>" id = "nscoursetype"/>
											<input type = "hidden" value = "<?php echo $selectedcity?>" id = "nscity"/>
											<input type="hidden" id="categorypage_unified_thankslayer_identifier" value=""/>
											<!--<input type = "hidden" value = "sendCourseRequest" id = "methodName"/>-->
											<input type = "hidden" value = "sendReqForNaukriShiksha" id = "methodName"/>
											
											<div class="lineSpace_2">&nbsp;</div>
											<div style="width:100%">												
												<div style="margin-right:205px">
													<div style="float:right;width:400px">
														<div style="line-height:30px;">
                                                        <?php if(!empty($countArray) && $countval > 30) { ?>
															<div class="float_R" id = "paginationviewcount">
																<div style="*margin-top:4px">
																<span>View: <select id = "View1" onChange = "document.getElementById('nscountoffset').value = this.value;document.getElementById('nsstartoffset').value = 0;return sendReqForNaukriShiksha();">
																<option value = "30">30</option>
																<option value = "40">40</option>
																<option value = "50">50</option>
																<option value = "60">60</option>
																<option value = "70">70</option>
																<option value = "80">80</option>
																<option value = "90">90</option>
																<option value = "100">100</option>
																</select></span>
																</div>
															</div>
                                                            <?php } ?>
															<div class="float_R" style="line-height:30px;position:relative;top:-5px">
																<div style="margin-right:20">
																	<div class="float_R">
																		<div class="pagingID" id="paginataionPlace1" style="position:relative; top:5px">
																		</div>
																	</div>
																	<div class="clear_L withClear">&nbsp;</div>
																</div>           
															</div>
															<div class="clear_R withClear">&nbsp;</div>
														</div>
													</div>

													<?php if(!empty($countArray)){ ?>
													<div style="float:left;width:200px;line-height:30px" id = "pagirow">
														<div id = "displaycount" style = "display:<?php echo $displaystyle?>">Displaying <?php echo $start + 1?> to <span id = "upperlimit">&nbsp;</span> of <?php echo $countval?></div>
														<script>
															if(document.getElementById('selectedcount').value < parseInt(document.getElementById('nsstartoffset').value) + parseInt(document.getElementById('nscountoffset').value)) { 
																document.getElementById('upperlimit').innerHTML = document.getElementById('selectedcount').value;
															} else {
																document.getElementById('upperlimit').innerHTML = parseInt(document.getElementById('nsstartoffset').value) + parseInt(document.getElementById('nscountoffset').value) ;
															}
														</script>
													</div>
													<?php } ?>

													<div class="clear_B withClear">&nbsp;</div>
												</div>
											</div>
											<div class="lineSpace_2">&nbsp;</div>
																	
                            <div id = "hack_ie_operation_aborted_error">
                            </div>
											<?php if(!empty($countArray)){ ?>
											<div id = "selectalldiv" style="height:30px;line-height:30px;background:#f1f1f3;">
												<input type="checkbox" style = 'display:none' checked onclick="checkAllFields(1);" id="checkAll"  style="position:relative;top:1px;*top:-3px" /> 
												<span style="position:relative;top:-1px;*top:-4px"><a style = 'display:none' id="hrefcheckall" onclick ="var chkAll = document.getElementById('checkAll'); if (chkAll.checked == false) { chkAll.checked = true ; checkAllFields(1); }" href="javascript:void(0);" >Select All</a>&nbsp;<span style="color:#a2a2a2"></span>&nbsp;<!-- <a href="javascript:void(0);" onclick="var chkAll = document.getElementById('checkAll'); chkAll.checked = false ; checkAllFields(1);" id="hrefcheckall">Clear All</a>--></span>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" onclick="var abc = checkformMultipleApply('NAUKRISHIKSHA_SEARCHPAGE_MIDDLE_REQUESTEBROCHURE');if(!(totalChecked == 0 || totalChecked == undefined)) {document.getElementById('catselect').style.display = 'none';document.getElementById('subcatselect').style.display = 'none';document.getElementById('locationselect').style.display = 'none';if(document.getElementById('View1')) {document.getElementById('View1').style.display =
                                                'none';document.getElementById('View2').style.display = 'none';}}return abc;" class="doneBtnReqOrgn" value="Request E-Brochure" />
											</div>
											<?php } ?>

											<div class="lineSpace_10">&nbsp;</div>                        
										</div>
										<!--End_Inside_Box_Top_GetStarted-->
								</div>
								
								<div style="width:100%">
									<div style="width:100%">
											<div class="float_R" style="width:196px" id = "rightPanelNaukri">
												<div style="float:left;width:100%">
													<div style="width:100%">
														<!--Start_Featured_College-->
														<?php 
															if(!empty($countArray)){ 
															$criteriaArray = array(
															'category' => $selectedcategoryId,
															'country' => 2,
															'city' => $selectedcity,
															'keyword'=>''
															);
															$bannerProperties = array('pageId'=>'NAUKRISHIKSHA', 'pageZone'=>$selectedPage.'_SMALLSCRAPPER', 'shikshaCriteria' => $criteriaArray);
														?>
														<div style="width:100%">
															<div class="raised_skyWithBGW">
																<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
																<div class="boxcontent_skyWithBGW">
																	<div style="padding:0 10px">
																		<div class="lineSpace_5">&nbsp;</div>
																		<div style="font-size:15px" class="bld">Featured Institutes</div>
																		<div style="padding:10px 0" align="center">
																			<?php $this->load->view('common/banner.php', $bannerProperties); ?>
																		</div>
																	</div>																						
																</div>
																<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
															</div>                                
														</div>
														<?php } ?>
														<!--End_Featured_College-->
														<!--Start_JoinShiksha_today-->
														<?php if(!isset($validateuser[0]['userid'])) { ?>
															<div style="width:100%">
																<div class="lineSpace_10">&nbsp;</div>
																<div><img src="/public/images/bgSp_t.gif" /></div>
																<div style="background:url(/public/images/bgSp_m.gif) repeat-y left top;width:196px">										
																	<div style="background:url(/public/images/bgMid.gif) repeat-x left bottom;margin:0 3px">
																		<div style="padding:0 10px">
																			<div style="width:100%">
																				<div style="float:left;width:170px">
																					<div style="width:100%">
																						<div class="bld fontSize_14p">Join Shiksha Today</div>
																						<div style="padding:5px 0">Be eligible for high-paying &amp; better jobs</div>
																						<div class="lineSpace_5">&nbsp;</div>
																						<div class="orgSmallBullet">Choose from thousands of advanced professional courses</div>
																						<div class="lineSpace_5">&nbsp;</div>
																						<div class="orgSmallBullet">Apply directly to institutes; download application forms</div>	
																						<div class="lineSpace_5">&nbsp;</div>
																						<div class="orgSmallBullet">Get all your career queries resolved</div>	 
																						<div class="lineSpace_5">&nbsp;</div>
																						<div class="orgSmallBullet">Explore options to study abroad</div>	 
																						<div class="txt_align_c">
																							<button type="button" onclick = "redirecttoShiksha('<?php echo SHIKSHA_HOME?>/user/Userregistration/index/');" value="" class="btn-submit19 w20">
																							<div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;" class="btn-submit20 btnTxtBlog">Join Shiksha Now </p></div>
																							</button>
																						</div>
																					</div>
																				</div>
																			</div>
																			<div style="clear:left;font-size:1px;line-height:1px;height:1px;overflow:hidden">&nbsp;</div>
																		</div>
																	</div>
																</div>
																<div><img src="/public/images/bgGril.gif" /></div>
																<div><img src="/public/images/bgSp_b.gif" /></div>                            
															</div>
														<?php } ?>
														<div class="lineSpace_10">&nbsp;</div>
														<!--End_JoinShiksha_today-->
														<?php 
															echo '<script>var globalAdsContainer = "rightpanelads";</script>';
															$bannerProperties = array('pageId'=>'NAUKRISHIKSHA', 'pageZone'=>'SIDE');
															$this->load->view('common/banner',$bannerProperties); 
														?>
													</div>
												</div>
												<div style="clear:left;font-size:1px;height:1px;line-height:1px;overflow:hidden">&nbsp;</div>
											</div>
											<div style="margin-right:206px" id = "mainInstitutesDiv">
												<div style="float:left;width:100%">
													<div style="width:100%">
														<div id="institutesDiv">
														<?php $selectall =0;	for($i = 0;$i < count($resultSet);$i++) {
                                                        $institutearr = $resultSet[$i]['institute'];
                                                        $coursearr = $resultSet[$i]['courses'][0];
														if($institutearr['duration'] == null || trim($institutearr['duration']) == '')
														{
														$institutearr['duration'] = '';
														}
														else
														{    
														$institutearr['duration'] = $institutearr['duration'] . '<span style="color:#a2a2a2">&nbsp;|&nbsp;</span>';
														}
														if($coursearr['course_level'] == null || trim($coursearr['course_level']) == '')
														$coursearr['course_level'] = '-';
														if($coursearr['course_type'] == null || trim($coursearr['course_type']) == '')
														$coursearr['course_type'] = '-';

														if($coursearr['course_level'] == "Diploma")
														$coursearr['course_level'] = 'Under Graduate Diploma';
														if(strlen($institutearr['institute_Name']) > 50)
														{
														$instiName = substr($institutearr['institute_Name'],0,50) . '..';
														}
														else
														{
														$instiName = $institutearr['institute_Name'];
														}
														if(strlen($coursearr['course_title']) > 50)
														{
														$courseName = substr($coursearr['course_title'],0,50) . '..';
														}
														else
														{
														$courseName = $coursearr['course_title'];
														}
                                                        
                                                        $courseName = htmlspecialchars($courseName);
                                                        ?>
														<div style="padding:10px 0;border-bottom:1px solid #CCCCCC"><div><div class="float_R" style="width:160px">
														<?php if($institutearr['isSendQuery'] == 1){
														$selectall = 1;?>
														<input type="button" style="cursor:pointer" value="Request E-Brochure" class="doneBtn1"  onclick="document.getElementById('catselect').style.display = 'none';document.getElementById('subcatselect').style.display = 'none';document.getElementById('locationselect').style.display = 'none';if(document.getElementById('View1')){document.getElementById('View1').style.display = 'none';document.getElementById('View2').style.display = 'none';}return calloverlayInstitute(<?php echo $institutearr['id']?>,'NAUKRISHIKSHA_SEARCHPAGE_MIDDLE_REQUESTEBROCHURE');" />
														<?php }
														else
														{?>
														&nbsp;
														<?php } ?>

														</div><div style="margin-right:170px"><div><div class="float_L" style="width:90px">


														<?php if($institutearr['isSendQuery'] == 1){ ?>
														<input type="checkbox" name = "reqEbr[]" onClick = "checkAllFields(2);" value ='<?php echo $institutearr['id'] ?>' />&nbsp;
														<?php $idname = 'reqEbr_' . $institutearr['id'];
														}
														else
														{?>
														<span style = "padding-left:15px">&nbsp;</span>
														<?php } 

														//$naukurl = str_replace('getListingDetail','listing/Listing/getDetailsForListingNaukriShiksha',$institutearr['url']);
														//$courseurl = str_replace('getListingDetail','listing/Listing/getDetailsForListingNaukriShiksha',$institutearr['url']);
														//$url_mail = str_replace('listing/Listing/getDetailsForListingNaukriShiksha','listing/Listing/getDetailsForListingNew',$institutearr['url']);
														$naukurl = $institutearr['url'];
														$courseurl = $institutearr['url'];
														$url_mail = $institutearr['url'];
														?>
														<img src="<?php echo getSmallImage($institutearr['logo_link']) ?>" onClick = "return showShikshaConfirmation('<?php echo $naukurl?>');" border="0" align="top" /></div>
														<div style="margin-left:90px;"><div class="float_L row"><div class="float_L row"><div url = "<?php echo $url_mail?>" title="<?php echo $institutearr['institute_Name'] ?>" type="institute" displayname="<?php echo $institutearr['institute_Name'] ?>" locationname="<?php echo $institutearr['city']?>" id="<?php echo $idname ?>">
														      <a href="#" onClick = "return showShikshaConfirmation('<?php echo $naukurl ?>');"><?php echo $instiName ?></a></div><div>
														      <a href = "#" onClick = "return showShikshaConfirmation('<?php echo $courseurl ?>');" class = "blackFont" title = "<?php echo $coursearr['course_title']?>"><?php echo $courseName?></a></div></div><div><div style="width:100px" class="float_R"><?php echo $institutearr['city']?></div><div style="margin-right:120px"><?php echo
														      $institutearr['duration'] . $coursearr['course_type']?><span style="color:#a2a2a2">&nbsp;|&nbsp;</span><?php echo
														      $coursearr['course_level']?></div></div></div></div><div class="clear_L withClear">&nbsp;</div></div></div><div class="clear_R withClear">&nbsp;</div></div></div>


														<?php 		} 
														?>

														</div>

													</div>
													<div>
														<div>
															<div style="line-height:30px">
                                                            <?php if(!empty($countArray) && $countval > 30) { ?>
																<div class="float_R" id = "paginationviewcount1">
																	<div style="*margin-top:4px">
																	<span>View: <select id = "View2" onChange = "document.getElementById('nscountoffset').value = this.value;document.getElementById('nsstartoffset').value = 0;return sendReqForNaukriShiksha();">
																	<option value = "30">30</option>
																	<option value = "40">40</option>
																	<option value = "50">50</option>
																	<option value = "60">60</option>
																	<option value = "70">70</option>
																	<option value = "80">80</option>
																	<option value = "90">90</option>
																	<option value = "100">100</option>
																	</select></span>
																	</div>
																</div>
                                                                <?php  } ?>
                                                                <?php if(!empty($countArray) && $countval > 30) { ?>
																	<script>
																	document.getElementById('View1').value = <?php echo $countoffset?>;
																	document.getElementById('View2').value = <?php echo $countoffset?>;
																	</script>
                                                                    <?php } ?>
																<div class="float_R" style="line-height:30px;position:relative;top:-5px">
																	<div style="margin-right:1px">
																		<div class="float_R">
																			<div class="pagingID" id="paginataionPlace2" style="position:relative; top:5px">
																			</div>
																		</div>
																		<div class="clear_L withClear">&nbsp;</div>
																	</div>           
																</div>
																<div class="clear_R withClear">&nbsp;</div>
															</div>
														</div>
														<div class="clear_B withClear">&nbsp;</div>
													</div>
												</div>
												<div style="clear:left;font-size:1px;height:1px;line-height:1px;overflow:hidden">&nbsp;</div>
											</div>
											<div style="clear:both;font-size:1px;height:1px;line-height:1px;overflow:hidden">&nbsp;</div>
											
											
											
											
											
											
											
											
											


									
									</div>
									
								</div>
							</div>
						</div>
					</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
		</div>
    </div>
    <!--Start_OuterBorder-->
                        <div class="lineSpace_10">&nbsp;</div>
</div>

											<?php if(!empty($countArray)) {if($countval > 30) { ?>
														<script>
															setStartOffset(<?php echo $pagenumber?>,'nsstartoffset','nscountoffset');
															doPagination(<?php echo $countval ?>,'nsstartoffset','nscountoffset','paginataionPlace1','paginataionPlace2','methodName',3);	
														</script>
											<?php }
                                            } ?>

<script>
document.getElementById('mainInstitutesDiv').style.height = document.getElementById('institutesDiv').offsetHeight > document.getElementById('rightPanelNaukri').offsetHeight ? (parseInt(document.getElementById('institutesDiv').offsetHeight + 100) + 'px') : (parseInt(document.getElementById('rightPanelNaukri').offsetHeight + 100) + 'px');
<?php if(!empty($countArray) && $selectall == 0) { ?>
document.getElementById('selectalldiv').style.display = 'none';
<?php } ?>
function selectcoursetype(courselevel,courselevel1,coursetype,tabname,subtabname,upperlevelid,selectedcount)
{
	document.getElementById('nscourselevel').value = courselevel;
	document.getElementById('nscourselevel1').value = courselevel1;
	document.getElementById('nscoursetype').value = coursetype;
	document.getElementById('nsstartoffset').value = 0;
	var flag = 1;
	var i = 0;
	var tab = "subtab" + upperlevelid + i;
	var subtab = "subtabatag" + upperlevelid + i;
	while(flag != 0)
	{
		if(document.getElementById(tab))
		{
			document.getElementById(tab).className = '';
			document.getElementById(subtab).className = '';
			i++;
			tab = "subtab" + upperlevelid + i;
			subtab = "subtabatag" + upperlevelid + i;
		}
		else
			flag = 0;
	}
	document.getElementById(tabname).className = 'selected';
	document.getElementById(subtabname).className = 'selected';
	document.getElementById('selectedcount').value = selectedcount;
    if(trim(document.getElementById('locationselect').options[document.getElementById('locationselect').selectedIndex].title) == trim(document.getElementById('selectedlocation').innerHTML))
    {
    sendCourseRequest();
    }
	return false;
}


function showCourseFiltration(coursetype,courselevel,courselevel_1,tabname,obj,coursecount,val)
{
	showTabs(tabname,val);
	document.getElementById('nscourselevel').value = courselevel;
	document.getElementById('nscourselevel1').value = courselevel_1;
	document.getElementById('nscoursetype').value = '';
	document.getElementById('nsstartoffset').value = 0;
	document.getElementById('selectedcount').value = coursecount;
    if(trim(document.getElementById('locationselect').options[document.getElementById('locationselect').selectedIndex].title) == trim(document.getElementById('selectedlocation').innerHTML))
    {
    sendCourseRequest();
    }

	return false;
}
function obtainPostitionX(element) {
    var x=0;
    while(element)	{
        x += element.offsetLeft;
        element=element.offsetParent;
    }
    return x;
}

function obtainPostitionY(element) {
    var y=0;
    while(element) {
        y += element.offsetTop;
        element=element.offsetParent;
    }
    return y;
}

function sendReqForNaukriShiksha()
{
    var count = document.getElementById('nscountoffset').value;
    var start = document.getElementById('nsstartoffset').value;
    var category = document.getElementById('nscategoryid').value;
		if(trim(category) == '')
        {
        document.getElementById('catselect_error').innerHTML = 'Please select the category';
		document.getElementById('catselect_error').parentNode.className = '';
        return false;
        }
    var subcategory = document.getElementById('nssubcategoryid').value;
    var country = 2;
    var city = document.getElementById('nscity').value;
			if(trim(city) == '')
            {
            document.getElementById('locationselect_error').innerHTML = 'Please select the city';
			document.getElementById('locationselect_error').parentNode.className = '';
            return false;
            }
    
    var courselevel = trim(document.getElementById('nscourselevel').value);
    courselevel = courselevel.replace('/','okok');
    var courselevel1 = document.getElementById('nscourselevel1').value;
    var coursetype = document.getElementById('nscoursetype').value;

    var url = BASE_URL + 'naukrishiksha/naukrishiksha/showList/' +  category + '/' + subcategory + '/' + country + '/' + city + '/' + start + '/' + count + '/' + courselevel + '/' + courselevel1  + '/' + coursetype;
    window.location = url;
}
/*function sendReqForNaukriShiksha(count,start,category,subcategory,country,city,courselevel,courselevel1,coursetype)
{
alert('qwqwe');
    if(typeof(count) == 'undefined')
        count = document.getElementById('nscountoffset').value;
    if(typeof(start) == 'undefined')
        startFrom = document.getElementById('nsstartoffset').value;
    if(typeof(category) == 'undefined')
        category = document.getElementById('nscategoryid').value;
    if(typeof(subcategory) == 'undefined')
        subcategory = document.getElementById('nssubcategoryid').value;
    if(typeof(country) == 'undefined')
        country = 2;
    if(typeof(city) == 'undefined')
        city = document.getElementById('nscity').value;
    if(typeof(courselevel) == 'undefined')
    {
        courselevel = trim(document.getElementById('nscourselevel').value);
        courselevel = courselevel.replace('/','okok');
    }
    if(typeof(courselevel1 == 'undefined')
            courselevel1 = document.getElementById('nscourselevel1').value;
            if(typeof(coursetype) == 'undefined')
            coursetype = document.getElementById('nscoursetype').value;

	window.location = BASE_URL + 'naukrishiksha/naukrishiksha/showList/' +  category + '/' + subcategory + '/' + country + '/' + city + '/' + start + '/' + count + '/' + courselevel + '/' + courselevel1  + '/' + coursetype;
}*/

</script>
<?php $this->load->view('naukrishiksha/footer'); ?>
