
<header id="page-header" class="clearfix">
    <div data-role="header" data-position="fixed" class="head-group ui-header-fixed">
        <a id="branchOverlayClose" href="javascript:void(0);" onclick="clearAutoSuggestorForBranch();" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>
        <h3>Select College</h3>
    </div>
</header>

<section class="layer-wrap fixed-wrap of-hide" style="height: 100%">
    <div class="search-option2" id="autoSuggestRefine">
            <div id="searchbox2">
                <span class="icon-search"></span>
                <input id="city-list-textbox" type="text" placeholder="Enter college name" onkeyup="cityStateAutoSuggest(this.value,'state');" autocomplete="off">
                <i class="icon-cl" onClick="clearAutoSuggestorForBranch();">&times;</i>
            </div>
    </div>

    <div class="content-child2 clearfix" style="padding: 0em;">
        <section id="loc-section">
   
           <div id="state-list">
               <ul class="location-list location-list2" style="margin-left: 0px !important;border-left: 0px">
              		<?php if(!empty($instituteGroupArr)):?> 
               		  <?php foreach($instituteGroupArr as $key=>$value ): ?>
                       <li id="sLI<?php echo strtolower($value['collegeGroupName']);?>">
                            <label id ="L_sLI<?php echo strtolower($value['collegeGroupName']);?>" style="padding: 15px 0 15px 8px; min-height: inherit;">
                                <input style="width: 20px; float: left" type="checkbox" id="<?php echo $value['collegeGroupName'];?>">
                                <span style="margin-left:25px; display: block" id="<?php echo 'sN'.strtolower($value['collegeGroupName']);?>">All <?php echo $value['collegeGroupName']; ?>s</span>
                            </label>
                       </li>
                       <?php endforeach;?>
                     <?php endif;?>  
               <?php foreach( $instituteArr as $key=>$value ){ ?>
                       <li id="sLI<?php echo $value['id'];?>">
                            <label id ="L_sLI<?php echo $value['id'];?>" style="padding: 15px 0 15px 8px; min-height: inherit;">
                                <input style="width: 20px; float: left" type="checkbox" id="<?php echo $value['id'];?>">
                                <span style="margin-left:25px; display: block"  id="<?php echo 'sN'.$value['id'];?>"><?php echo $value['collegeName'].', '.$value['cityName'];?></span>
                            </label>
                       </li>
               <?php } ?>
                        <li href="javascript:void(0);" id="not-found-state-list" style="display:none;">
                            <label><span>No result found for this institute.</span></label>
                        </li>
                        <li style="margin-bottom: 58px;">&nbsp;</li>
               </ul>
           </div>
      
       </section>
    </div>
</section>


<a id="lDButton" class="refine-btn" href="javascript:void(0);" onclick="collegeLayerDone();"><span class="icon-done"><i></i></span> Done</a>
<a id="clearlDButton" class="cancel-btn" onclick="clearFiltersOnLayer();" href="javascript:void(0);">Clear All</a>
<script>
jQuery(document).ready(function(){
/*window.onscroll = function() {
        jQuery('#clearlDButton').css({position: 'fixed', left: '0px', bottom: '0px', width:'100%'});
        jQuery('#lDButton').css({position: 'fixed', left: '0px', bottom: '37px', width:'100%'});
}*/
});

function collegeLayerDone(){
    //Calculate the checked checkboxes inside branch div
    locationIdsCount = 0;
    instituteList = '';
    $('#state-list input:checked').each(function() {
        locationIdsCount++;
        instituteList = (instituteList=='')?$(this).attr('id'):instituteList+','+$(this).attr('id');
    });
    var htmlShown = 'Selected ('+locationIdsCount+')';
    $('#instituteText').html(htmlShown);
    clearAutoSuggestorForBranch();
    $('#branchOverlayClose').click();
}

</script>
