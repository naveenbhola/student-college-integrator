<input type="hidden" autocomplete="off" id="myEventCountOffset" value="5"/>
<input type="hidden" autocomplete="off" id="myEventStartFrom" value="0"/>
<input type = "hidden" autocomplete="off" id = "showMyEvents" value = "showMyEvents"/>
<input type = "hidden" autocomplete="off" id = "showMySubscribedEvents" value = "showMySubscribedEvents"/>
<input type = "hidden" autocomplete="off" id = "myEventCount" value = ""/>
<!-- Event started -->
<div class="row hideComponent" id="myShikshaEventsHidden"></div>
<div id="myShikshaEventsShow">
	<div id="myShikshaEventsBox" style="width:100%;">
		<div class="raised_lgraynoBG"> 
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent_lgraynoBG">
				<div class="h22 raisedbg_sky normaltxt_11p_blk fontSize_13p OrgangeFont">
					<?php if(strcmp($editFlag,'true')==0): ?>
					<div class="float_R mar_right_10p  myProfile"><a href="<?php echo SHIKSHA_EVENTS_HOME_URL; ?>/events/Events/showAddEvent" title="Create event">Create event</a></div>
					<?php endif; ?>
					<div class="bld" style="margin-bottom:10px"><img src="/public/images/album.gif" width="28" height="15" align="absmiddle" />My Events</div>
				</div>
					<div id="blogNavigationTab" style="background:url(/public/images/ln.gif) left bottom repeat-x;height:27px;overflow:hidden;width:100%;margin:5px 0 0 0">
						<ul style="margin-left:10px">
							<li container="browseListing" tabName="browseListingCourses" class="selected" onClick=" selectListingTab('browseListing','Courses');showMyEvents(0,5);">
								<a href="javascript:void(0);">Events Added</a>
							</li> 
							<li container="browseListing" tabName="browseListingColleges" class="" onClick=" selectListingTab('browseListing','Colleges');showMySubscribedEvents(0,5);">
								<a href="javascript:void(0);">Events Subscribed</a>
							</li>
						</ul>
						<div class="clear_B">&nbsp;</div>
					</div>
					<div class="clear_B">&nbsp;</div>					
				
				 <div class="clear_B">&nbsp;</div>	
				<div style="line-height:10px;*line-height:1px;overflow:hidden">&nbsp;</div>				
					<!-- Events list will come here -->
					<div class="float_L" style="width:100%">
					<div class="mar_full_10p" style="line-height:24px;margin:10px 10px 0 10px;*margin:0 10px 0 10px">
						<div class="float_L">
							<div class="bld normaltxt_11p_blk lineSpace_20" id="myEventsCountShow">&nbsp;</div>
						</div>
						<div class="float_R">
							<div class="pagingID" align="right" id="myEventsPagination"></div>
						</div>
						<div class="clear_B">&nbsp;</div>
					</div>					
					</div>
					<div class="clear_B">&nbsp;</div>
					<div class="lineSpace_10">&nbsp;</div>
					<div id="myEvents" class="fontSize_12p" style="margin:0 10px"></div>
					<div id="myEvents_error_place"></div>
<!--					<div class="row">			
						<?php if(strcmp($editFlag,'true')==0): ?>	
						<div class="float_R myProfile mar_right_10p" id="eventsViewAllLink"><img src="/public/images/eventArrow.gif" width="6" height="6" /> <a href="<?php echo SHIKSHA_EVENTS_HOME; ?>" title="View all">View all</a></div>
						<?php endif; ?>
					<div class="clear_L">&nbsp;</div>
					</div>	-->
			</div>
		  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
	</div>
	<div class="lineSpace_10">&nbsp;</div>
</div>
<!-- Event finished -->
<script> 	
	myEventsShow('<?php echo $myEvents;?>',0,5);
</script>
