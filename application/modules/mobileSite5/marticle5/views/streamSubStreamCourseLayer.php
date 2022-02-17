<header id="page-header" class="clearfix">
    <div data-role="header" data-position="fixed" class="head-group ui-header-fixed">
	<a id="categoryLocationOverlayClose" href="javascript:void(0);" onclick="clearAutoSuggestorForCategoryPage('category-layer-list-ul');" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>   	
        <h3>Choose a Stream</h3>
    </div>
</header>

<section class="layer-wrap fixed-wrap of-hide" style="height: 100%">
    <div class="search-option2" id="autoSuggestRefine">
    	
            <div id="searchbox2">
            	<span class="icon-search"></span>
                <input id="city-list-textbox" type="text" placeholder="Enter stream name" onkeyup="articleNewObj.StreamSubstreamAutoSuggest(this.value,'city');" autocomplete="off">
                <input id="state-list-textbox" type="text" placeholder="Enter course name" onkeyup="articleNewObj.StreamSubstreamAutoSuggest(this.value,'state');" autocomplete="off" style="display:none;">
                <i class="icon-cl" onClick="clearAutoSuggestorForLocation();">&times;</i>
            </div>
        
    </div>

 <div class="content-child2 clearfix" style="padding:0 0.7em;">
  <section id="loc-section">   
	<nav id="side-nav" class="loc-nav">
	    <ul style="margin-bottom: 45px;">
			<li style="cursor:pointer;" class="active" onClick="articleNewObj.showHideLocations('city-list');" id="city-list-Menu"><span>Streams</span></li>
			<li style="cursor:pointer;" onClick="articleNewObj.showHideLocations('state-list');" id="state-list-Menu"><span>Popular Courses</span></li>
	    </ul>
	</nav>
	<ul class="location-list location-list2" id="city-list">
   		<?php 
   		$liclass = 'activeLink';
   		foreach ($streams as $streamId => $subArr) {
   			foreach ($subArr as $subStreamId => $subStreamArr) {
   				if($subStreamId == 'all'){
   				?>
   				<li style="padding:8px 6px !important;font-size: 0.9em;background-color:#ebebeb;" id="cLIall<?php echo $streamId?>" class="subHeading_all_<?php echo $streamId; ?>"><span id="<?php echo 'cNall'.$streamId;?>">&nbsp;<?php echo $subStreamArr['name']?></span></li>

   				<li <?php if($entityId==$streamId && $entityType=='stream'){ ?> style="font-weight:bold;" <?php } ?> <?php if($entityId==$streamId && $entityType=='stream'){ ?> class="<?php echo $liclass;?>" <?php } ?> id="cAllTag<?php echo $streamId;?>"><label id ="L_cLI<?php echo $subStreamId;?>" onClick="window.location='<?php echo $subStreamArr['url']; ?>'">
					 <span id="<?php echo 'cN'.$subStreamId;?>"><?php echo 'All';?></span></label>
					 <?php if($entityId==$streamId && $entityType=='stream'){ ?><i class="icon-check" style="top:34% !important;"></i><?php } ?>
				</li>
   				<?php 
   				}else{
   				?>
   				<li streamId="<?php echo $streamId;?>" <?php if($entityId==$subStreamId && $entityType=='substream'){ ?> style="font-weight:bold;" <?php } ?> class="<?php if($entityId==$subStreamId && $entityType=='substream'){  echo $liclass; } ?> allSubStreams<?php echo $streamId;?>" id="cLI<?php echo $subStreamId;?>"><label id ="L_cLI<?php echo $subStreamId;?>" onClick="window.location='<?php echo $subStreamArr['url']; ?>'">
	 <span id="<?php echo 'cN'.$subStreamId;?>"><?php echo $subStreamArr['name'];?></span></label>
		<?php if($entityId==$subStreamId && $entityType=='substream'){ ?><i class="icon-check" style="top:34% !important;"></i><?php } ?>
		</li>
   				<?php 
   				}
   			}
   		}
   		?>
   		<li href="javascript:void(0);" id="not-found-city-list" style="display:none;">
			<label><span>No result found for this stream.</span></label>
		</li>
	</ul>
    <div id="state-list" style="display: none;">
	<ul class="location-list location-list2">
	<?php 
	foreach ($popularCourses as $pcId => $pcArr) {
	?>
	<li <?php if($entityId==$pcId && $entityType=='popularCourse'){ ?> style="font-weight:bold;" <?php } ?> id="sLI<?php echo $pcId;?>"><label id ="L_sLI<?php echo $pcId;?>" onClick="window.location='<?php echo $pcArr['url']; ?>'">
		<span id="<?php echo 'sN'.$pcId;?>"><?php echo $pcArr['name'];?></span></label>
		<?php if($entityId==$pcId && $entityType=='popularCourse'){ ?><i class="icon-check" style="top:34% !important;"></i><?php } ?>
		</li>
	<?php 
	}
	?>
	<li href="javascript:void(0);" id="not-found-state-list" style="display:none;">
		<label><span>No result found for this course.</span></label>
	</li>
	</ul>
    </div>
  </section>
</div>
</section>