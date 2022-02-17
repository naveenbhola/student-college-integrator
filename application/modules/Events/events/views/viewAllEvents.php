<?php		$topLeftSearchPanelFileData = array('infoWidgetData' => $infoWidgetData); 
		if($fromOthers==0){
                                                        $eventType="Application Submission Deadline";}
                                                        elseif($fromOthers==1){
                                                        $eventType="Course Commencement";}
                                                        elseif($fromOthers==2){
                                                        $eventType="Result Declaration";}
                                                        elseif($fromOthers==3){
                                                        $eventType="Examination Date";}
                                                        elseif($fromOthers==4){
                                                        $eventType="Form Issuance";}
                                                        elseif($fromOthers==5){
                                                        $eventType="General";}
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
											'multipleapply',
											'lazyload',
											'events',
											'cityList',
											'CalendarPopup',
											'alerts'		
										),
								'title'	=>	'Events in '.$category_name.' in '.$location_name.' - '.$eventType.' - Education Events - Important Dates',
								'tabName'	=>	'Event Calendar',
								'bannerProperties' => array('pageId'=>'EVENTS_VIEWALL', 'pageZone'=>'HEADER'),
								'taburl' =>  site_url('events/Events/index'),	
								'metaDescription' => 'Events in '.$category_name.' in '.$location_name.' - '.$eventType.' - Education Events, Important Dates. Find details on Results, Admissions, Application Forms, Scholarships, Career Fairs, Application Submission Deadlines, Course Commencement in different Universities / Colleges, Examination Dates, Form Issuance, Entrance Exams Updates, Exam Schedules & New Courses.',
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
			$eventCount = $recentEventsArrayi[0]['total'];
		
		$selectedCountryName = ($selectedCountryName == 'All')?'All countries':$selectedCountryName;
		$selectedCategoryName = ($selectedCategoryName == 'All')?'All categories':$selectedCategoryName;
		$evntHeadingWithCount = ($eventCount > 0)?'Showing '.$eventCount.' Important Dates for':'No Important Dates added for';
?>
	<!--Start_Mid_Panel-->
       <?php 
//      		$this->load->view('events/eventYearListOverlay');
       ?>
	<script>
       var SITE_URL = '<?php echo base_url() ."/";?>'; 
       var completeCountryTree = eval(<?php echo $countryList; ?>);     
       var completeCategoryTree = eval(<?php echo $categoryForLeftPanel; ?>);
       </script>
		<!-- Main Mid Panel Starts -->
		<div class="lineSpace_10">&nbsp;</div>
	<div class="wrapperFxd">
		<!--Start_MidPanel-->
        <div class="mlr10">
            <div>                
                <div class="float_L" style="width:657px">
                	<div class="wdh100">
						<input type="hidden" id="category_id" autocomplete="off" value="<?php echo $category_id; ?>" />              
						<input type="hidden" id="days" autocomplete="off" value="<?php echo $days?>"/>
						<input type="hidden" id="category_name" autocomplete="off" value="<?php echo $category_name; ?>" />
						<input type="hidden" id="location_id" autocomplete="off" value="<?php echo $location_id; ?>" />
						<input type="hidden" id="location_name" autocomplete="off" value="<?php echo $location_name; ?>" />
						<input type="hidden" id="countryName" autocomplete="off" value="<?php echo $countryName; ?>" />
						<input type="hidden" id="from_others" autocomplete="off" value="<?php echo $fromOthers; ?>" />
						<input id = "catcountry" value = "<?php echo $countrySelected1;?>" type = "hidden"/>
						<input id = "catcityid" value = "<?php echo $selectedCity;?>" type = "hidden"/>
						<input id = "catcity" value = "<?php echo $cityNameSelected;?>" type = "hidden"/>                        
                        <div><h2 style="display:inline"><b class="Fnt16 blackFont">Events in  <span class="fcOrg"><?php echo $category_name; ?></span></b></h2> <span class="fcGya fs13 bld">[</span> <a href="javascript:void(0);" tabindex="10" class = "btmArw" onClick="drpdwnOpen(this, 'eventCategories')" onmouseout="MM_showHideLayers('eventCategories','','hide');">Change Category</a> <span class="fcGya fs13 bld">]</span> <b class="Fnt16"> in <span class="fcOrg"><?php echo $location_name; ?></span></b> <span class="fcGya fs13 bld">[</span> <a href="javascript:void(0);" onclick = "showlocationlayer(0,this,false);return false;" tabindex="10" class="btmArw">Change Location</a> <span class="fcGya fs13 bld">]</span> </b></span></div>
                        <div class="lineSpace_8">&nbsp;</div>
                        <div class="bdr bgDate">
                        	<div class="float_L Fnt14 bld">Refine by Date&nbsp; &nbsp;</div>
			<div id="dateDisplay">
                            <div class="float_L" style="*margin-top:5px"><input type="text" class="dtBox" required="true" required="true" name="refine_date" id="refine_date" value="<?php echo strpos($days,"-")?$days:"yyyy-mm-dd" ?>" onChange="javascript:todaysViewAllEvents(this.value);" readonly maxlength="10" size="15" class="" onClick="cal.select($('refine_date'),'rd','yyyy-mm-dd');" caption="Refine Date"/> &nbsp;</div>
			</div>
                            <div class="float_L" style="margin-top:6px"><img src="/public/images/calDate.gif" style="cursor:pointer" align="absmiddle" id="rd" onClick="cal.select($('refine_date'),'rd','yyyy-mm-dd');" border="0" /></a></div>
			    <div id="todaysSelection">
			    <?php if($days=='0'){?>
                            <div class="float_L fcGya pl36"><a href="javascript:todaysViewAllEvents('All');">All</a> | <a href="javascript:todaysViewAllEvents(0);" style="background:#FF8200;padding:0 5px;color:#fff">Today</a> |  <a href="javascript:todaysViewAllEvents(7);">Next Seven Days</a> |  <a href="javascript:todaysViewAllEvents(30);">Next One Month</a></div>
			    <?php }else if($days=='7'){?>
			    <div class="float_L fcGya pl36"><a href="javascript:todaysViewAllEvents('All');">All</a> | <a href="javascript:todaysViewAllEvents(0);">Today</a> |  <a href="javascript:todaysViewAllEvents(7);" style="background:#FF8200;padding:0 5px;color:#fff">Next Seven Days</a> |  <a href="javascript:todaysViewAllEvents(30);">Next One Month</a></div>
			    <?php }else if($days=='30'){?>
			    <div class="float_L fcGya pl36"><a href="javascript:todaysViewAllEvents('All');">All</a> | <a href="javascript:todaysViewAllEvents(0);">Today</a> |  <a href="javascript:todaysViewAllEvents(7);">Next Seven Days</a> |  <a href="javascript:todaysViewAllEvents(30);" style="background:#FF8200;padding:0 5px;color:#fff">Next One Month</a></div>
			    <?php }else if($days=='All'){?>
			    <div class="float_L fcGya pl36"><a href="javascript:todaysViewAllEvents('All');" style="background:#FF8200;padding:0 5px;color:#fff">All</a> | <a href="javascript:todaysViewAllEvents(0);">Today</a> |  <a href="javascript:todaysViewAllEvents(7);">Next Seven Days</a> |  <a href="javascript:todaysViewAllEvents(30);">Next One Month</a></div>
			    <?php }else{ ?>
			    <div class="float_L fcGya pl36"><a href="javascript:todaysViewAllEvents('All');">All</a> | <a href="javascript:todaysViewAllEvents(0);">Today</a> |  <a href="javascript:todaysViewAllEvents(7);">Next Seven Days</a> |  <a href="javascript:todaysViewAllEvents(30);">Next One Month</a></div>
			    <?php } ?>
			    </div>
                            <div class="clear_L">&nbsp;</div>
						</div>
						<!--Start_ShowingPagination-->
						<div class="mt10">
							 <?php          
									foreach($eventListByType as $temporary){
				                                        $total_events=$temporary['total_events'];
			        	                                }
				                        ?>
						<div id="showingEventsList1">
						<?php if(!empty($total_events)){ ?>
							<div class="float_L">Showing <?php echo $start+1;?> - <?php $temp = (($start+$count) > $total_events)?$total_events:($start+$count); echo $temp;?> out of <?php echo $total_events?></div>
						<?php } ?>
						</div>
							<div class="float_R">
								<div id="pagingIDc" style="padding:3px">
									<!--Pagination Related hidden fields Starts-->
									<input type="hidden" id="startOffset" value="<?php echo $start ?>"/>
									<input type="hidden" id="countOffset" value="<?php echo $count; ?>"/>
									<input type="hidden" id="methodName" value="getPaginatedEvents"/>
									<!--Pagination Related hidden fields Ends  -->					
									<span>
										<span class="pagingID" id="paginataionPlace1"> <?php echo $paginationHTML; ?></span>
									</span>
								</div>
							</div>
							<div class="clear_B">&nbsp;</div>
						</div>
						<!--End_ShowingPagination-->						
						
                        <!--Start_Application_submission_deadline-->
			<div id="eventListByType">
                        <div class="lineSpace_10">&nbsp;</div>
                        <div class="mlr10">
                        	<div class="Fnt16 mb5"><div class="float_L"><?php
							if($fromOthers==0){
							echo "<h2><b>Application Submission Deadline</b></h2>";}
							elseif($fromOthers==1){
							echo "<h2><b>Course Commencement</b></h2>";}
							elseif($fromOthers==2){
							echo "<h2><b>Result Declaration</b></h2>";}
							elseif($fromOthers==3){
							echo "<h2><b>Examination Date</b></h2>";}
							elseif($fromOthers==4){
							echo "<h2><b>Form Issuance</b></h2>";}
							elseif($fromOthers==5){
							echo "<h2><b>General</b></h2>";}
							?>
							<?php
                                                        if(!(is_array($validateuser) && $validateuser != "false")) {
                                                        $onRedirect = base64_encode("/events/Events/subscribeEvents/1/''/event/''/<?php echo ".addslashes($titleEvent)."; ?>");
                                                        $onClickAll = "calloverlayEvents('1234','Events Home Page','VAEVENTS','$countryName',$fromOthers,'$location_id','$location_name',$category_id,'$category_name');return false;";
                                                        }else {
                                                        if($validateuser['quicksignuser']==1) {
                                                        $base64url = base64_encode($_SERVER['REQUEST_URI']);
                                                        $onClick = 'javascript:location.replace(\'/user/Userregistration/index/<?php
                                                        echo $base64url?>/1\');return false;';
                                                        } else {
                                                        $onClickAll = "calloverlayEvents('1234','Events Home Page','VAEVENTS','$countryName',$fromOthers,'$location_id','$location_name',$category_id,'$category_name');return false;";
                                                        }
                                                        }
                                                        ?>
				<span class="fcGya fs13"><b>[</b> <a href="javascript:void(0);"  tabindex="11" class="btmArw"  onClick="drpdwnOpen(this, 'eventTypes');" onmouseout="MM_showHideLayers('eventTypes','','hide');" >Change type of event</a> <b>]</b></span></div>
				<?php if($total_events!=0){?>
				<div class="float_R"><input type="button" onClick="<?php echo $onClickAll?>;subscribeEvents('<?php echo $event_id; ?>','<?php echo addslashes($titleEvent); ?>');return false;" class="btn_scrib" value="Subscribe" /></div>
				<?php } ?>
				<div class="clear_B">&nbsp;</div>
						</div>
						<div class="bdrBlu">&nbsp;</div>						
						<input type="hidden" id="total_events" value="<?php echo $total_events ?>"/>
                            <div>
                                <ul class="impDts">
                                    <!--Start_Repeat_Data-->
				<?php   if(!empty($eventListByType)){
					foreach($eventListByType as $tempo){
                                        $event_id=$tempo['event_id'];
                                        $titleEvent=$tempo['event_title'];
                                        $startEvent=$tempo['start_date'];
                                        $endEvent=$tempo['end_date'];
                                        $fromOthers=$tempo['fromOthers'];
					$country_name=$tempo['country_name'];
					$city_name=$tempo['city_name'];
                                        ?>
                                    <li>
                                        <div class="lineSpace_10">&nbsp;</div>
                                        <div class="mlr10">
                                            <div class="rw1">
                                                 <?php				if(date("jS M,y",strtotime($startEvent))!=date("jS M,y",strtotime($endEvent))){
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
					<div><a href="<?php echo getSeoUrl($event_id,'event',$titleEvent); ?>"class=""><?php echo $titleEvent; ?></a></div>
							<?php
	                                                if(!(is_array($validateuser) && $validateuser != "false")) {
                        			        $onRedirect = base64_encode("/events/Events/subscribeEvents/1/<?php echo $event_id; ?>/event/<?php echo $event_id; ?>/<?php echo $titleEvent; ?>");
			                                $onClick = "calloverlayEvents('$event_id','".addslashes($titleEvent)."','EVENTS');return false;";
							$onClickAll = "calloverlayEvents('$event_id','".addslashes($titleEvent)."','VAEVENTS','$countryName',$fromOthers,'$location_id','$location_name',$category_id,'$category_name');return false;";
                                               		}else {
                                                        if($validateuser['quicksignuser']==1) {
                                                        $base64url = base64_encode($_SERVER['REQUEST_URI']);
                                                        $onClick = 'javascript:location.replace(\'/user/Userregistration/index/<?php
                                        	        echo $base64url?>/1\');return false;';
                                                        } else {
                                                        $onClick = "calloverlayEvents('$event_id','".addslashes($titleEvent)."','EVENTS');return false;";
                                                        $onClickAll = "calloverlayEvents('$event_id','".addslashes($titleEvent)."','VAEVENTS','$countryName',$fromOthers,'$location_id','$location_name',$category_id,'$category_name');return false;";
                                                        }
        	                                        }
                		                        ?>	
                                                <div class="drkGry"><?php echo $city_name?>, <?php echo $country_name?><?php if($fromOthers>3){?>, <?php echo date("jS M,y",strtotime($startEvent)) ?> - <?php echo date("jS M,y",strtotime($endEvent)) ?><?php } ?></div>
						<div><a href="javascript:void(0);" onClick="<?php echo $onClick?>;subscribeEvents('<?php echo $event_id; ?>','<?php echo addslashes($titleEvent); ?>');return false;" class="vDLink_2">Subscribe</a></div>
                                            </div>
                                            <div class="clear_B"></div>
                                        </div>
                                    </li>
					<?php	}
					}else{
					echo "No events matching your selection found.";} ?>
                                    <!--End_Repeat_Data-->
                                </ul>
                            </div>                                                        
                        </div>
			</div>
			
			<div class="brdbottom">&nbsp;</div>
			<div id="showingEventsList2">
			<?php if(!empty($total_events)){ ?>
			<div class="float_L">Showing <?php echo $start+1;?> - <?php $temp = (($start+$count) > $total_events)?$total_events:($start+$count); echo $temp;?> out of <?php echo $total_events?></div>	
			<?php } ?>
			</div>
			<div class="txt_align_r mt10">
				<div id="pagingIDc" style="padding:3px">
					<span>
						<span class="pagingID" id="paginataionPlace2"><?php echo $paginationHTML;?></span>
					</span>
				</div>
			</div>
                        <!--End_Application_submission_deadline-->
                        <div class="lineSpace_10">&nbsp;</div>                                
                        </div>
                </div>
                <div class="float_R" style="Width:265px">
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
                        $bannerProperties = array('pageId'=>'EVENTS_VIEWALL','pageZone'=>'RIGHT','shikshaCriteria' => $criteriaArray);
                        $this->load->view('common/banner.php', $bannerProperties); ?><!--<img src="/public/images/eventbanner_1.gif" />--></div>
                        <div class="lineSpace_20">&nbsp;</div>
						<!--Start_AreaOfInterest-->                        
				<?php   $this->load->view('listing_forms/widgetConnectInstitute'); ?>
                        <!--End_AreaOfInterest-->                                                                                                              
                        <!--Start_Related_Articles-->
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
						<div class="float_L w85"><div align="center"><img src="<?php echo (!isset($temp['blogImageURL']) || ( $temp['blogImageURL'] == '')) ? '/public/images/faqSA.jpg' : $temp['blogImageURL'] ?>" /></div></div>
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
$this->load->view('events/locationlayer',array('displayflag' => $displayflag));
}
        ?>
<div style="height:50px">&nbsp;</div>
<input type="hidden" id="total_events" value="<?php echo $total_events ?>"/>	
<input type="hidden" id="onClick" value="<?php echo $onClick ?>"/>
<input type="hidden" id="onClickAll" value="<?php echo $onClickAll ?>"/>
<script>
	LazyLoad.loadOnce([
        '/public/js/<?php echo getJSWithVersion("tooltip"); ?>',
        '/public/js/<?php echo getJSWithVersion("ajax-api"); ?>'
	],callbackfn);
	function getPaginatedEvents(){
        var startOffset = document.getElementById('startOffset').value;
        var countOffset = document.getElementById('countOffset').value;
	var location_id= document.getElementById('location_id').value;
        var category_id= document.getElementById('category_id').value;
        var category_name= document.getElementById('category_name').value;
        var location_name= document.getElementById('location_name').value;
	var countryName= document.getElementById('countryName').value;
        var from_others= document.getElementById('from_others').value;
	var days= document.getElementById('days').value;
        location.replace('/events/Events/viewAllEvents/'+countryName+'/'+from_others+'/'+category_id+'/'+category_name+'/'+location_id+'/'+location_name+'/'+startOffset+'/'+countOffset+'/'+days);
	}
	var cal = new CalendarPopup("calendardiv");
	function showlocationlayer(dimbck,objElement,defaultPositionFlag){
        $('locationlayer').style.display = '';
        var divX,divY;
        if(typeof(defaultPositionFlag)=='undefined'){
        divX = parseInt(screen.width/2 - $('locationlayer').offsetWidth/2);
        divY = parseInt(screen.height/2 - $('locationlayer').offsetHeight/2);
        }else{
        divX = parseInt(obtainPostitionX(objElement))-120;
        divY = parseInt(obtainPostitionY(objElement))-50;
        }
        $('locationlayer').style.left = (divX) +  'px';
        $('locationlayer').style.top = (divY) + 'px';
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

