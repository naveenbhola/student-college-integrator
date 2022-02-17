                                <!--Start_SubTab-->
                                <?php if(trim($show_level)=='yes'){?>
                                <?php if($showbar != 0) { ?>


                            	<div class="shik_roundCornerHeaderSpirit shik_roundCornerHeader_3">
                                	<div style="line-height:6px">&nbsp;</div>
									<div style="padding-left:13px">
                                        <div id="shik_catMainSubTab">
                                        <?php
                                        if(isset($countArray))
                                        {
                                        $i = 0;
                                        foreach($countArray as $key => $value)
                                        {
                                        $i++;
                                        $courselevel = '';
                                        $courselevel_1 = '';
														if(strpos($key,"UG") !== false)
														{

															$courselevel = substr($key,(strpos($key,"UG") + strlen("UG")));
															$courselevel_1 = "Under Graduate";
														}
														if(strpos($key,"PG") !== false)
														{
															$courselevel = substr($key,(strpos($key,"PG") + strlen("PG")));
															$courselevel_1 = "Post Graduate";
														}
														if($courselevel_1 == '' && $courselevel == '')
														{
															$courselevel = $key;
															if($courselevel == "Diploma")
															{
															$courselevel_1 = "Diploma";
															}
															else
															$courselevel_1 = 'NULL';
														}
                                        if(is_array($value))
                                       {
                                       if($key != 'mainids' && $key != 'categoryselector')
                                       {
                                      if($key == $countArray['selectedCluster'])
                                      {
                                      ?>

                                            <a href="#" id = "<?php echo 'clustab'.$key?>" onClick = "setparams('<?php echo trim($courselevel);?>','<?php echo $courselevel_1;?>','All',0);return false;" class="selectedMainSubTab" title="<?php echo $key;?>"><span><?php echo $key;?></span></a>
                                            <?php } else { ?>
                                            <a href="#" id = "<?php echo 'clustab'.$key?>" onClick = "setparams('<?php echo trim($courselevel)?>','<?php echo $courselevel_1?>','All',0);return false;" title="<?php echo $key?>"><span><?php echo $key?></span></a>
                                         <?php }}}}} ?>
                                        </div>
                                        <script>
                                        </script>
                                    </div>
                                </div>
                                <?php } else {?> <div class="shik_roundCornerHeaderSpirit shik_roundCornerHeader_3"><div class = "lineSpace_20 fontSize_14p" style="padding:5px 0"><b style="padding-left:10px">Top 20 Institutes of India</b></div></div><?php }?>
                                <?php } ?>
                                <!--End_SubTab-->

                                <!--Start_SubSubTab-->
                                <?php if (trim($show_mode) == 'yes') { ?>
                                <?php
                                if($showbar != 0) { ?>
                                <div style="height:30px;line-height:30px">
                                	<!--<div style="">--><div style="padding-left:17px">
                                    	<div class="float_L" style="width:100%">
                                            <div id="shik_catMainSubSubTab" style="width:100%">
                                            <div id = "clussubtab">
                                            <?php

                                        if(isset($countArray))
                                        {
                                            if($selectedcoursetype == '')
                                            $selectedcoursetype = 'All';
                                            if(!empty($countArray))
                                            {
                                            if('All' == $selectedcoursetype) $className = 'selectedMainSubSubTab';else $className = '';?>
                                            <a href="#" id = "subtab0" class="<?php echo $className; ?>" onclick = "document.getElementById('catcoursetype').value = 'All';document.getElementById('catstartoffset').value = 0;opencatpage();return false;" title="All">All</a>
                                            <?php } $i =0; foreach($countArray[$countArray['selectedCluster']] as $key => $value)
                                            {

                                            $i++;
                                            if(trim($key) == $selectedcoursetype) $className = 'selectedMainSubSubTab';else $className = '';?>
                                            <a href="#" id = "<?php echo 'subtab'.$i?>" onclick = "document.getElementById('catcoursetype').value = '<?php echo $key;?>';document.getElementById('catstartoffset').value = 0;opencatpage();return false;" class="<?php echo $className;?>" title="<?php echo $key; ?>"><?php echo $key; ?></a>
                                            <?php }} ?>
                                            </div>
                                            </div>
                                        </div>
<!--                                        <div class="float_L" style="width:29%;height:30px">
                                        	<div style="width:100%;padding:4px 0" class="txt_align_r"><select><option>Refine by Locality</option></select></div>
                                        </div>-->
                                        <div class="clear_L">&nbsp;</div>
									</div>
                                </div>
<?php } ?>
<?php } ?>

                                <!--End_SubSubTab-->
<script>
showTabs('<?php echo 'clustab' .$countArray['selectedCluster'];?>');
</script>
<?php

$COUNT = $countArray['totalcount'];
$LOWERLIMIT = $start + 1;
$UPPERLIMIT = ($start + $count) > $COUNT ? $COUNT : ($start + $count);?>



                                <!--Start_OutOfPages-->
                                <?php if($showbar != 0) { if(!empty($countArray) && $showselectall == 0) { ?>
                                <div style="background:#f1f1f3;height:37px;margin-top:10px;">
                                	<div class="float_L" style="width:69%">
                                    	<div style="width:100%;padding-top:5px">&nbsp;</div>
                                    </div>
                                    <div class="float_R" style="width:30%">
                                    	<div style="width:100%;line-height:37px"><div class="txt_align_r" style="padding-right:10px"><b><?php echo $LOWERLIMIT;?> -  <?php echo $UPPERLIMIT;?> of <?php echo $countArray['totalcount']?></b></div></div>
                                    </div>
                                    <div class="clear_B">&nbsp;</div>
                                </div>
                                <?php }

                                ?>

                                <?php if(!empty($countArray) && $showselectall == 1) { ?>
                                <span style="font-size:5px;display:block;height:10px;overflow:hidden;">&nbsp;</span>
                                <div style="background:#f1f1f3;height:37px">
                                	<div class="float_L" style="width:68%">
                                    	<div style="width:100%;padding-top:5px">
                              <!--          <span style="padding-left:5px"><input type="button" value="" class="applyBtn88x22 mr15"  onclick="var abc = checkformMultipleApply('HOMEPAGE_CATEGORY_MIDDLEPANEL_REQUESTINFO');return abc;return false;"/></span> -->
                                            <span style="position:relative;top:1px;padding-left:5px"><input type="checkbox" name="" id = "checkAll" onclick = "checkAllFields(1);" checked style = 'display:none'/></span> <a href="#" id = "hrefcheckall" checked style = 'display:none' onClick = "var chkAll = document.getElementById('checkAll'); if (chkAll.checked == false) { chkAll.checked = true ; checkAllFields(1);return false;}">Select All</a> &nbsp;<span style="color:#a2a2a2"></span>&nbsp;<!-- <a href="javascript:void(0);" onclick="var chkAll = document.getElementById('checkAll'); chkAll.checked = false ; checkAllFields(1);return false;" id="hrefcheckall">Clear All</a>&nbsp;&nbsp;&nbsp;-->
											<input type="button" value="Request E-brochure" class="getFreeBrochure"  onclick="naurkiLearningPageTracking(); var abc = multipleCourseApplyForCategoryPage('multiple','HOMEPAGE_CATEGORY_MIDDLEPANEL_REQUESTINFO');return false;"/>
                                        </div>
                                    </div>
                                    <div class="float_R" style="width:30%">
                                    	<div style="width:100%;line-height:37px"><div class="txt_align_r" style="padding-right:5px"><b><?php echo $LOWERLIMIT;?> - <?php echo $UPPERLIMIT;?> of <?php echo $countArray['totalcount']?></b></div></div>
                                    </div>
                                    <div class="clear_B">&nbsp;</div>
                                </div>
                                <?php }} ?>
								<?php if($categoryUrlName!="management") { ?>
								<div class="wdh100" style="padding:4px 0;">
									<div style="display:none" class="tShikExprt mlr10">
										<div class="Fnt14 bld mb5" style="padding-top:9px">Check out the new &amp; improved Institute pages below and get more info on...</div>
										<div class="wdh100 Fnt14">
											<div class="float_L"><div class="tDot">Course Detail</div></div>
											<div class="float_L"><div class="tDot">Photos &amp; Videos</div></div>
											<div class="float_L"><div class="tDot">Top Recruiting Companies</div></div>
											<div class="float_L"><div class="tDotn">Alumni reviews</div></div>
											<div class="clear_B">&nbsp;</div>
										</div>
									</div>
								</div>
								<?php } else { ?>
								<div class="wdh100" style="padding:4px 0;">
									<div style="display:none" class="tShikExprt mlr10">
										<div class="Fnt14 bld mb5" style="padding-top:9px">Check out the new &amp; improved Institute &amp; Course pages below and get more info on...</div>
										<div class="wdh100 Fnt14">
											<div class="float_L"><div class="tDot">Top Recruiting Companies</div></div>
											<div class="float_L"><div class="tDot">Salary Statistics</div></div>
											<div class="float_L"><div class="tDot">AIMA rankings</div></div>
											<div class="float_L"><div class="tDotn">Alumni reviews</div></div>
											<div class="clear_B">&nbsp;</div>
										</div>
									</div>
								</div>
								<?php } ?>
                                <!--End_OutOfPages-->
							<?php if(count($resultSet) == 0):?>
							<h2 class="Fnt14 orangeColor" style="margin:10px 0 0 30px">No Institutes Found!</h2>
                            <?php else: ?>
                              <?php $this->load->view('categoryList/category_listing'); ?>
                              
                                <!--Start_Pagination-->
                                	<div style="width:100%;padding:20px 0 10px 0">
										<?php if($showbar != 0) { ?>
                                		<div class="float_L" style="width:40%">
                                        	<div style="width:100%">
												<?php if($showbar != 0) { ?>
													<span class="homeShik_Icon shik_checkedIcon" style="padding-left:29px">&nbsp;</span>Sponsored Listing
                                                <?php } ?>
                                            </div>
                                        </div>
										<?php if($showbar != 0) { ?>
											<div class="float_L" style="width:60%">
												<div style="width:100%;height:30px;line-height:30px">
													<div class="txt_align_r pagingID" style="padding-right:10px" id="paginataionPlace1" >
												<?php echo $paginationHTML;?>
													</div>
												</div>
											</div>
										<?php } ?>
                                        <div class="clear_L">&nbsp;</div>
										<?php } else { ?>
											<div style="padding-left:10px">* This list has been compiled by following ratings provided by leading Indian publications</div>
											<div style="padding-left:10px">* The institutes have been listed in alphabetic order</div>
										<?php } ?>
                                    </div>
                                <!--End_Pagination-->
                               <?php
									//Amit Singhal : Multiple Course Apply
									$applyCoursesArray = array();
									foreach($resultSet as $value){
										$applyCoursesArray[$value['institute']['id']] = array();
										foreach($value['courses'] as $course){
											$applyCoursesArray[$value['institute']['id']][] = array(0 => $course['course_id'], 1 => $course['course_title']);
										}
									}
									//echo "<pre>";
									//print_r($applyCoursesArray);
									//echo "</pre>";
								?>
								
								<script>
									var applyCoursesArray = <?=json_encode($applyCoursesArray)?>;
									var categoryId = '<?=$categoryId?>';
									var subCategoryId = <?php echo $subCategoryId?$subCategoryId:"1";?>;
									var selectedCity = <?php echo $selectedCity?$selectedCity:"1"; ?>;
									var newcourseID = <?php echo $course_key_id?$course_key_id:"0"; ?>;
								</script>
								<?php
								endif;
								?>
