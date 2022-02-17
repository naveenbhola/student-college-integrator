<?php		$topLeftSearchPanelFileData = array('infoWidgetData' => $infoWidgetData);
		$headerComponents = array(
								'css'	=>	array(
											'raised_all',
											'mainStyle',
											'events',
											'header',
											'modal-message'
										),
								'js'	=>	array(
											'common',
											'prototype',
											'multipleapply',
											'lazyload',
											'events',
											'cityList',
											'CalendarPopup',
											'alerts'
										),
								'title'	=>	'Important Dates – Results - Admissions - Application Forms - Scholarships - Education Events - Career Fairs',
								'tabName'	=>	'Important Dates',
								'bannerProperties' => array('pageId'=>'EVENTS_HOME', 'pageZone'=>'HEADER'),
								'taburl' =>  site_url('events/Events/index'),
								'metaDescription' => 'Important Dates, Results, Admissions, Application Forms, Scholarships, Education Events & Career Fairs. Find details on education events - Application Submission Deadlines, Course Commencement in different universities / colleges, Result Declaration, Examination Dates, Form Issuance, Admissions, entrance exams updates, exam schedules & new courses.',
								'metaKeywords'	=>'education events,  education events in India, deadlines, career fairs, Application Submission Deadlines, Application Deadlines, Course Commencement, Result Declaration, Examination Dates, Form Issuance, Admissions, entrance exams updates, exam schedules, new courses',
								'product' => 'events',
								'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
								'callShiksha'=>1
							);
		$this->load->view('common/header', $headerComponents);
		$dataForHeaderSearchPanel = array('topLeftSearchPanelFileData' => $topLeftSearchPanelFileData);
		$this->load->view('events/headerSearchPanelForEvents',$dataForHeaderSearchPanel);
                $this->load->view('common/calendardiv');
		$this->load->view('common/commonOverlay');
		$recentEventsArray = json_decode($recentEventsList, true);
		$eventCount = 0;
		if(isset($recentEventsArray[0]))
			$eventCount = $recentEventsArray[0]['total'];

		$selectedCountryName = ($selectedCountryName == 'All')?'All countries':$selectedCountryName;
		$selectedCategoryName = ($selectedCategoryName == 'All')?'All categories':$selectedCategoryName;
		$evntHeadingWithCount = ($eventCount > 0)?'Showing '.$eventCount.' Important Dates for':'No Important Dates added for';
?>
	<!--Start_Mid_Panel-->
       <?php
       		$this->load->view('events/eventYearListOverlay');
       ?>
       <script>
       var SITE_URL = '<?php echo base_url() ."/";?>';
       var completeCountryTree = eval(<?php echo $countryList; ?>);
       var completeCategoryTree = eval(<?php echo $categoryForLeftPanel; ?>);
       </script>
<!--[if IE 7]>
<style>
	.searchTP{padding:0px 2px;color:#acacac;position:relative;top:-4px;height:18px;border:solid 1px #acacac;}
</style>
<![endif]-->
<!-- Main Mid Panel Starts -->
	<div class="lineSpace_10">&nbsp;</div>
	<div>
		<!--Start_MidPanel-->
        <div class="mlr10">
            <div>
                <div class="float_L" style="width:692px">
                	<div class="wdh100">
			<?php if(!empty($spotlightEventsList)){?>
                    	<div class="bdr">
                        	<div class="pt8">
                            	<h1><div class="mlr2010 fcOrg Fnt16 bld">Event Spotlight</div></h1>
                                <div>
                                    <ul class="impDts">
                                    	<!--Start_Repeating_Data_For_Spotlight-->
										<?php foreach($spotlightEventsList as $temp){
					                                        $eventId=$temp['event_id'];
					                                        $startEvent=$temp['start_date'];
					                                        $endEvent=$temp['end_date'];
					                                        $titleEvent=$temp['event_title'];
										$fromOthersSpot=$temp['fromOthers'];
										$country_name=$temp['country_name'];
										$city_name=$temp['city_name'];
										$paidEventId=$temp['paid_event_id'];
										$tillDate=$temp['till_date'];
										$paidImageURL=$temp['paid_image_url'];
										$currentDate=date("Y-m-d");
										if($eventId==$paidEventId && $tillDate>=$currentDate){
                                        ?>
                                        <li class="entSptBG">
											<div class="lineSpace_10">&nbsp;</div>
											<div class="mlr2010">
												<div class="rw1">
													<?php
													if(date("jS M,y",strtotime($startEvent))!=date("jS M,y",strtotime($endEvent))){
													$currentDate=date("Y-m-d");
													if($startEvent>=$currentDate){
													?>
														<div class="Fnt10">starts on</div>
														<div class="sdtBg">
															<div class="whiteColor"><?php echo date("M",strtotime($startEvent));?></div>
															<div><?php echo date("j",strtotime($startEvent));?></div>
														</div>
															<?php }else{  ?>
														<div class="Fnt10">upto</div>
														<div class="sdtBg">
															<div class="whiteColor"><?php echo date("M",strtotime($endEvent));?></div>
															<div><?php echo date("j",strtotime($endEvent));?></div>
														</div>
													<?php }
                                                                                }else{
                                                                                ?>
                                                                        <div class="Fnt10"></div>
                                                                        <div class="sdtBg">
                                                                                <div class="whiteColor"><?php echo date("M",strtotime($startEvent));?></div>
                                                                                <div><?php echo date("j",strtotime($startEvent));?></div>
                                                                        </div>
                                                                        <?php } ?>
												</div>
												<div class="rw2">
													<img src="<?php echo $paidImageURL; ?>" align="right" class="ml10" />
                                                    <div><a href="<?php
														$optionalArgs['fromOthers'] = $fromOthers;
														$optionalArgs['location'] = array($country_name,$city_name);
														echo getSeoUrl($eventId,'event',$titleEvent,$optionalArgs); ?>" class=""><?php echo $titleEvent; ?></a></div>
						     <?php
                                                if(!(is_array($validateuser) && $validateuser != "false")) {
                                $onRedirect = base64_encode("/events/Events/subscribeEvents/1/<?php echo $eventId; ?>/<?php echo $titleEvent; ?>");
                                $onClick = "calloverlayEvents('$eventId','".addslashes($titleEvent)."','EVENTS');return false;";
                                                }else {
                                                        if($validateuser['quicksignuser']==1) {
                                                        $base64url = base64_encode($_SERVER['REQUEST_URI']);
                                                        $onClick = 'javascript:location.replace(\'/user/Userregistration/index/<?php
                                                echo $base64url?>/1\');return false;';
                                                        } else {
                                                                $onClick = "calloverlayEvents('$eventId','".addslashes($titleEvent)."','EVENTS');return false;";
                                                        }
                                                }
                                        ?>
                                                    <div class="drkGry"><?php echo $city_name?>, <?php echo $country_name?><?php if($fromOthersSpot>3){?>, <?php echo date("jS M,y",strtotime($startEvent)) ?> - <?php echo date("jS M,y",strtotime($endEvent)) ?><?php } ?></div>
							<?php if($fromOthersSpot>3){?>
                                                    <div class="mt10"></div>					<?php } ?><input class="subsBtn" type="button" onClick="<?php echo $onClick?>;subscribeEvents('<?php echo $eventId; ?>','<?php echo addslashes($titleEvent); ?>');return false;" value="&nbsp;" />
                                                </div>
                                                <div class="clear_B"></div>
											</div>
                                        </li>
                                        <?php } } ?>
										<?php foreach($spotlightEventsList as $temp){
										$eventId=$temp['event_id'];
										$fromOthersSpot=$temp['fromOthers'];
										$startEvent=$temp['start_date'];
										$endEvent=$temp['end_date'];
										$titleEvent=$temp['event_title'];
										$country_name=$temp['country_name'];
										$city_name=$temp['city_name'];
										$paidEventId=$temp['paid_event_id'];
										if($eventId!=$paidEventId || $tillDate<$currentDate){
										?>
					<a name="important_deadlines"></a>
                                        <li>
											<div class="lineSpace_10">&nbsp;</div>
											<div class="mlr2010">
												<div class="rw1">
													<?php
													if(date("jS M,y",strtotime($startEvent))!=date("jS M,y",strtotime($endEvent))){
													$currentDate=date("Y-m-d");
													if($startEvent>=$currentDate){
													?>
													<div class="Fnt10">starts on</div>
													<div class="sdtBg">
															<div class="whiteColor"><?php echo date("M",strtotime($startEvent));?></div>
															<div><?php echo date("j",strtotime($startEvent));?></div>
													</div>
															<?php }else{  ?>
													<div class="Fnt10">upto</div>
													<div class="sdtBg">
															<div class="whiteColor"><?php echo date("M",strtotime($endEvent));?></div>
															<div><?php echo date("j",strtotime($endEvent));?></div>
													</div>
													<?php }
                                                                                }else{
                                                                                ?>
                                                                        <div class="Fnt10"></div>
                                                                        <div class="sdtBg">
                                                                                <div class="whiteColor"><?php echo date("M",strtotime($startEvent));?></div>
                                                                                <div><?php echo date("j",strtotime($startEvent));?></div>
                                                                        </div>
                                                                        <?php } ?>
                                                </div>
                                                <div class="rw2">
                                                    <div><a href="<?php $optionalArgs['fromOthers'] = $fromOthers;
														$optionalArgs['location'] = array($country_name,$city_name);
														echo getSeoUrl($eventId,'event',$titleEvent,$optionalArgs); ?>" class=""><?php echo $titleEvent; ?></a></div>
							<div class="drkGry"><?php echo $city_name?>, <?php echo $country_name?><?php if($fromOthersSpot>3){?>, <?php echo date("jS M,y",strtotime($startEvent)) ?> - <?php echo date("jS M,y",strtotime($endEvent)) ?><?php } ?></div>
						<?php
                                                if(!(is_array($validateuser) && $validateuser != "false")) {
                                $onRedirect = base64_encode("/events/Events/subscribeEvents/1/<?php echo $eventId; ?>/event/<?php echo $eventId; ?>/<?php echo $titleEvent; ?>");
                                $onClick = "calloverlayEvents('$eventId','".addslashes($titleEvent)."','EVENTS');return false;";
                                                }else {
                                                        if($validateuser['quicksignuser']==1) {
                                                        $base64url = base64_encode($_SERVER['REQUEST_URI']);
                                                        $onClick = 'javascript:location.replace(\'/user/Userregistration/index/<?php
                                                echo $base64url?>/1\');return false;';
                                                        } else {
                                                                $onClick = "calloverlayEvents('$eventId','".addslashes($titleEvent)."','EVENTS');return false;";
                                                        }
                                                }
                                        ?>
						<?php if($fromOthersSpot>3){?>
                                                    <div class="mt10"></div>					<?php } ?><a href="javascript:void(0);" onClick="<?php echo $onClick?>;subscribeEvents('<?php echo $eventId; ?>','<?php echo addslashes($titleEvent); ?>');return false;" class="vDLink_2">Subscribe</a>
                                                </div>
                                                <div class="clear_B"></div>
											</div>
                                        </li>
										<?php } } ?>
                                        <!--End_Repeating_Data_For_Spotlight-->
                                    </ul>
                                </div>
                            </div>
                        </div>
				<?php }?>
						<input type="hidden" id="category_id" autocomplete="off" value="<?php echo $category_id; ?>" />
						<input type="hidden" id="category_name" autocomplete="off" value="<?php echo $category_name; ?>" />
						<input type="hidden" id="location_id" autocomplete="off" value="<?php echo $location_id; ?>" />
						<input type="hidden" id="location_name" autocomplete="off" value="<?php echo $location_name; ?>" />
						<input type="hidden" id="countryName" autocomplete="off" value="<?php echo $countryName; ?>" />
						<input id = "catcountry" value = "<?php echo $countrySelected1;?>" type = "hidden"/>
						<input id = "catcityid" value = "<?php echo $selectedCity;?>" type = "hidden"/>
						<input id = "catcity" value = "<?php echo $cityNameSelected;?>" type = "hidden"/>
                        <div class="lineSpace_20">&nbsp;</div>
                        <div><h1 style="display:inline">
                        <span class="Fnt16 blackFont"><b>&nbsp;Events in <span class="fcOrg"><?php echo $category_name; ?></span></b></span></h1>&nbsp;<span class="fcGya fs13 bld" style="position:relative;top:-2px">[</span> <a href="javascript:void(0);" style="" tabindex="10" class = "btmArw" onClick="drpdwnOpen(this, 'impDeadlines')" onmouseout="MM_showHideLayers('impDeadlines','','hide');">Change Category</a> <span class="fcGya fs13 bld" style="position:relative;top:-2px">]</span> <b class="Fnt16">in <span class="fcOrg"><?php echo $location_name; ?></span></b> <span class="fcGya fs13 bld" style="position:relative;top:-2px">[</span> <span><a href="javascript:void(0);" onclick = "showlocationlayer(0,this,false);return false;" tabindex="10" class="btmArw" style="">Change Location</a></span> <span class="fcGya fs13 bld" style="position:relative;top:-2px">]</span> </div>
                        <div class="lineSpace_5">&nbsp;</div>
                        <div class="bdr bgDate">
                        	<div class="float_L Fnt14 bld">Refine by Date&nbsp; &nbsp; </div>
                            <div class="float_L"><input type="text" class="dtBox" required="true" name="refine_date" id="refine_date" value="yyyy-mm-dd" onChange="javascript:todaysEvents(this.value);" readonly maxlength="10" size="15" onClick="cal.select($('refine_date'),'rd','yyyy-mm-dd');" caption="Refine Date"/> &nbsp;</div>
                            <div class="float_L" style="margin-top:7px"><img src="/public/images/calDate.gif" style="cursor:pointer" align="" id="rd" onClick="cal.select($('refine_date'),'rd','yyyy-mm-dd');" border="0" /></a></div>
			    <div id="todaysSelection">
                            <div class="float_L fcGya pl36"><a href="javascript:todaysEvents('All');" style="background:#FF8200;padding:0 5px;color:#fff">All</a> | <a href="javascript:todaysEvents(0);">Today</a> |  <a href="javascript:todaysEvents(7);">Next Seven Days</a> |  <a href="javascript:todaysEvents(30);">Next One Month</a></div>
			    </div>
                            <div class="clear_L">&nbsp;</div>
						</div>
						<div id="eventListByType" class="mt10">
							<!--Start_Application_submission_deadline-->
							<?php if(!empty($eventListByType)){
								foreach($eventListByType as $temporary){
									$fromOthersMaster=$temporary['fromOthers'];
									$eventIdMaster=$temporary['event_id'];
									$eventTitleMaster=$temporary['event_title'];
									$countryNameMaster=$temporary['country_name'];
									if($fromOthersOld!=$fromOthersMaster){
							?>
							<div class="mlr10">
								<div class="Fnt16 bld mb5">
								<div class="float_L">
									<?php
									if($fromOthersMaster==0){
									echo "Application Submission Deadline";}
									elseif($fromOthersMaster==1){
									echo "Course Commencement";}
									elseif($fromOthersMaster==2){
									echo "Result Declaration";}
									elseif($fromOthersMaster==3){
									echo "Examination Date";}
									elseif($fromOthersMaster==4){
									echo "Form Issuance";}
									elseif($fromOthersMaster==5){
									echo "General";}
									?>
								</div>
									<?php
                                                if(!(is_array($validateuser) && $validateuser != "false")) {
                                $onRedirect = base64_encode("/events/Events/subscribeEvents/1/<?php echo $eventIdMaster; ?>/event/<?php echo $eventIdMaster; ?>/<?php echo $eventTitleMaster; ?>");
                                $onClick = "calloverlayEvents('$eventIdMaster','".addslashes($eventTitleMaster)."','EVENTS');return false;";
				$onClickAll = "calloverlayEvents('$eventIdMaster','".addslashes($eventTitleMaster)."','HPEVENTS','$countryName',$fromOthersMaster,'$location_id','$location_name',$category_id,'$category_name');return false;";
                                                }else {
                                                        if($validateuser['quicksignuser']==1) {
                                                        $base64url = base64_encode($_SERVER['REQUEST_URI']);
                                                        $onClick = 'javascript:location.replace(\'/user/Userregistration/index/<?php echo $base64url?>/1\');return false;';
                                                        } else {
                                                                $onClick = "calloverlayEvents('$eventIdMaster','".addslashes($eventTitleMaster)."','EVENTS');return false;";
				                                $onClickAll = "calloverlayEvents('$eventIdMaster','".addslashes($eventTitleMaster)."','HPEVENTS','$countryName',$fromOthersMaster,'$location_id','$location_name',$category_id,'$category_name');return false;";
                                                        }
                                                }
                                        ?>
									<div class="float_R"><input type="button" onClick="<?php echo $onClickAll?>;subscribeEvents('<?php echo $temporary['event_id']; ?>','<?php echo addslashes($temporary['event_title']); ?>');return false;" class="btn_scrib" value="Subscribe" /></div>
									<div class="clear_B">&nbsp;</div>
								</div>
								<div class="bdrBlu">&nbsp;</div>
								<div>
									<ul class="impDts">
										<!--Start_Repeat_Data-->
										<?php foreach($eventListByType as $tempo){
					                                        $event_id=$tempo['event_id'];
					                                        $titleEvent=$tempo['event_title'];
					                                        $startEvent=$tempo['start_date'];
					                                        $endEvent=$tempo['end_date'];
					                                        $fromOthers=$tempo['fromOthers'];
										$country_name=$tempo['country_name'];
										$city_name=$tempo['city_name'];
										if($fromOthers==$fromOthersMaster){
                                        ?>
										<li>
											<div class="lineSpace_10">&nbsp;</div>
											<div class="mlr10">
												<div class="rw1">
													<?php
													if(date("jS M,y",strtotime($startEvent))!=date("jS M,y",strtotime($endEvent))){
													$currentDate=date("Y-m-d");
													if($startEvent>=$currentDate){ ?>
														<div class="Fnt10">starts on</div>
														<div class="sdtBg">
															<div class="whiteColor"><?php echo date("M",strtotime($startEvent));?></div>
															<div><?php echo date("j",strtotime($startEvent));?></div>
														</div>
													<?php }else{  ?>
														<div class="Fnt10">upto</div>
														<div class="sdtBg">
															<div class="whiteColor"><?php echo date("M",strtotime($endEvent));?></div>
															<div><?php echo date("j",strtotime($endEvent));?></div>
														</div>
													<?php }
                                                                                }else{
                                                                                ?>
                                                                        <div class="Fnt10"></div>
                                                                        <div class="sdtBg">
                                                                                <div class="whiteColor"><?php echo date("M",strtotime($startEvent));?></div>
                                                                                <div><?php echo date("j",strtotime($startEvent));?></div>
                                                                        </div>
                                                                        <?php } ?>
												</div>
												<div class="rw2">
													<div><a href="<?php  $optionalArgs['fromOthers'] = $fromOthers;
														$optionalArgs['location'] = array($country_name,$city_name);
														echo getSeoUrl($event_id,'event',$titleEvent,$optionalArgs); ?>" class=""><?php echo $titleEvent; ?></a></div>
													 <?php
                                                if(!(is_array($validateuser) && $validateuser != "false")) {
                                $onRedirect = base64_encode("/events/Events/subscribeEvents/1/<?php echo $event_id; ?>/event/<?php echo $event_id; ?>/<?php echo $titleEvent; ?>");
                                $onClick = "calloverlayEvents('$event_id','".addslashes($titleEvent)."','EVENTS');return false;";
                                                }else {
                                                        if($validateuser['quicksignuser']==1) {
                                                        $base64url = base64_encode($_SERVER['REQUEST_URI']);
                                                        $onClick = 'javascript:location.replace(\'/user/Userregistration/index/<?php
                                                echo $base64url?>/1\');return false;';
                                                        } else {
                                                                $onClick = "calloverlayEvents('$event_id','".addslashes($titleEvent)."','EVENTS');return false;";
                                                        }
                                                }
                                        ?>
													<div class="drkGry"><?php echo $city_name?>, <?php echo $country_name?><?php if($fromOthers>3){?>, <?php echo date("jS M,y",strtotime($startEvent)) ?> - <?php echo date("jS M,y",strtotime($endEvent)) ?><?php } ?></div>
													<div><a href="javascript:void(0);" onClick="<?php echo $onClick?>;subscribeEvents('<?php echo $eventId; ?>','<?php echo addslashes($titleEvent); ?>');return false;" class="vDLink_2">Subscribe</a></div>
												</div>
												<div class="clear_B"></div>
											</div>
										</li>
										<?php } } ?>
										<li class="txt_align_r">
											<a href="/events/Events/viewAllEvents/<?php echo $countryName; ?>/<?php echo $fromOthersMaster; ?>/<?php echo $category_id; ?>/<?php echo $category_name; ?>/<?php echo $location_id?>/<?php echo $location_name; ?>/0/10/All" class="bld">View All</a>
										</li>
										<!--End_Repeat_Data-->
									</ul>
								</div>
							</div>
							<?php } ?>
							<?php $fromOthersOld=$fromOthersMaster; }
								} else {
									echo "No events matching your selection found.";
							} ?>
						</div>
						<!--End_Application_submission_deadline-->
					</div>
                </div>
		<input type="hidden" id="onClick" value="<?php echo $onClick ?>"/>
		<input type="hidden" id="onClickAll" value="<?php echo $onClickAll ?>"/>
                <div class="float_R" style="width:265px">
					<div class="wdh100">
                    	<div align="center"><?php
				if($countryName=='india'){
                                     $criteriaArray = array(
                                     'category' => $category_id,
                                     'country' => '2',
                                     'city' => $location_id,
                                     'keyword'=>'');
                                     }else{
                                     $criteriaArray = array(
                                     'category' => $category_id,
                                     'country' => $location_id,
                                     'city' => '',
                                     'keyword'=>'');
                                     }
                        $bannerProperties = array('pageId'=>'EVENTS_HOME','pageZone'=>'RIGHT','shikshaCriteria' => $criteriaArray);
                        $this->load->view('common/banner.php', $bannerProperties); ?><!--<img src="/public/images/eventbanner_1.gif" />--></div>
                        <div class="lineSpace_20">&nbsp;</div>
						<!--Start_AreaOfInterest-->
						<?php $this->load->view('listing_forms/widgetConnectInstitute'); ?>
                        <!--End_AreaOfInterest-->
                        <!--Start_Related_Articles-->
						<?php if(!empty($relatedArticlesList)){ ?>
                        <div class="raised_lgraynoBG">
                        	<div class="bgImpDt Fnt16 bld"><div class="bkIcon">Related Articles</div></div>
                            <div class="boxcontent_lgraynoBG">
                                <div class="mlr10">
                                    <div class="wdh100">
                                        <!--Start_Repeating_Data-->
										<?php $i=1; foreach($relatedArticlesList as $temp){
											if($i == count($relatedArticlesList))
												$brdBottom = '';
											else
											$brdBottom = "brdbottom";
										?>
                                        <div class="pb10 <?php echo $brdBottom; ?>">
                                            <div class="lineSpace_10">&nbsp;</div>
                                            <div class="float_L w85"><div align="center"><img src="<?php echo (!isset($temp['blogImageURL']) || ( $temp['blogImageURL'] == '')) ? '/public/images/faqSA.jpg' : $temp['blogImageURL'] ?>" /><!--<img src="/public/images/2.png" />--></div></div>
                                            <div class="ml95">
                                                <div class="fcGya fs11">
                                                	<div><a href="/getArticleDetail/<?php echo $temp['blogId']; ?>/<?php echo $temp['blogTitle']; ?>" class="Fnt11"><?php echo insertWbr($temp['blogTitle'],4); ?></a></div>
                                                <a href="/getArticleDetail/<?php echo $temp['blogId']; ?>/<?php echo $temp['blogTitle']; ?>#blogCommentSection" class="comnt comntA Fnt11"><?php if($temp['commentCount']=='0'){?>No <?php }else{?><?php echo $temp['commentCount']; ?><?php } ?>&nbsp;<?php if($temp['commentCount']=='1'){?>Comment<?php }else{?>Comments<?php }?></a>
						</div>
                                            </div>
                                            <div class="clear_B"></div>
                                        </div>
										<?php $i++;} ?>
                                        <!--End_Repeating_Data-->
                                    </div>
                                </div>
							</div>
                            <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
                        </div>
						<?php } ?>
                        <!--End_Related_Articles-->
                        <div class="lineSpace_10">&nbsp;</div>
                    </div>
				</div>
				<div class="clear_B"></div>
			</div>
		</div>
        <!--End_MidPanel-->
	</div>
	<?php
if($openOverlay == 1)
$displayflag = '';
else
$displayflag = 'none';
	?>
	<?php
if($openOverlay == 0)
{
$this->load->view('events/locationUselessLayer',array('displayflag' => $displayflag));
}
	?>
<div style="height:50px">&nbsp;</div>
<script>
	LazyLoad.loadOnce([
        '/public/js/<?php echo getJSWithVersion("tooltip"); ?>',
        '/public/js/<?php echo getJSWithVersion("ajax-api"); ?>'
        ],callbackfn);
	var cal = new CalendarPopup("calendardiv");

	function showlocationlayer(dimbck,objElement,defaultPositionFlag){
	var h = document.body.scrollTop;
	var  h1 = document.documentElement.scrollTop;
        h = h1 > h ? h1 : h;
	var divY =  h + 20;
	var divX = (parseInt(document.body.offsetWidth)/2) - (parseInt(obtainPostitionX(objElement))/2) -50;
        $('locationlayer').style.left = (divX) +  'px';
        $('locationlayer').style.top = (divY) + 'px';
        $('locationlayer').style.display = '';
	}
	/*
	function showsubscriptionlayer(dimbck,objElement,defaultPositionFlag){
        var h = document.body.scrollTop;
        var  h1 = document.documentElement.scrollTop;
        h = h1 > h ? h1 : h;
        var divY =  h + 200;
        var divX = (parseInt(document.body.offsetWidth)/2) - (parseInt(obtainPostitionX(objElement))/2) -90;
        $('subscriptionlayer').style.left = (divX) +  'px';
        $('subscriptionlayer').style.top = (divY) + 'px';
        $('subscriptionlayer').style.display = '';
	$('dim_bg').style.height = document.body.offsetHeight +  'px';
	$('dim_bg').style.width = document.body.offsetWidth +  'px';
	$('dim_bg').style.display = 'inline';
	}
	*/
	function subscribeEvents(eventId,eventTitle){
        <!--
	var url = '/events/Events/subscribeEvents/1/'+ eventId +'/event/'+ eventId +'/'+ eventTitle;
        var a  = new Ajax.Request (url,{ method:'post', parameters: (eventId), onSuccess: function (xmlHttp) {
	}});
        -->
	}
</script>
<?php
	$bannerProperties = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer', $bannerProperties);
?>

