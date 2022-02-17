
<header id="page-header" class="clearfix">
    <div data-role="header" data-position="fixed" class="head-group ui-header-fixed">
        <a id="branchOverlayClose" href="javascript:void(0);" onclick="clearAutoSuggestorForBranch();" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>
        <h3>Select Branch</h3>
    </div>
</header>

<section class="layer-wrap fixed-wrap of-hide" style="height: 100%">
    <div class="search-option2" id="autoSuggestRefine">
            <div id="searchbox2">
                <span class="icon-search"></span>
                <input id="city-list-textbox" type="text" placeholder="Enter branch name" onkeyup="cityStateAutoSuggest(this.value,'state');" autocomplete="off">
                <i class="icon-cl" onClick="clearAutoSuggestorForBranch();">&times;</i>
            </div>
    </div>

    <div class="content-child2 clearfix" style="padding: 0em;">
        <section id="loc-section">
   
           <div id="state-list">
               <ul class="location-list location-list2" style="margin-left: 0px !important;border-left: 0px">
               <?php foreach( $branchArr as $key=>$value ){ ?>
                       <li id="sLI<?php echo $key;?>">
                            <label id ="L_sLI<?php echo $key;?>">
                                <input type="checkbox" value = "<?php echo $value['branchAcronym'];?>" id="<?php echo preg_replace('/(\s)+/', '', strtolower($value['branchAcronym']));?>">&nbsp;
                                <span id="<?php echo 'sN'.$key;?>"><?php echo $value['branchAcronym'];?></span>
                            </label>
                       </li>
               <?php } ?>
                        <li href="javascript:void(0);" id="not-found-state-list" style="display:none;">
                            <label><span>No result found for this branch.</span></label>
                        </li>
                        <li style="margin-bottom: 58px;">&nbsp;</li>
               </ul>
           </div>
      
       </section>
    </div>
</section>


<a id="lDButton" class="refine-btn" href="javascript:void(0);" onclick="branchLayerDone();"><span class="icon-done"><i></i></span> Done</a>
<a id="clearlDButton" class="cancel-btn" onclick="clearFiltersOnLayer();" href="javascript:void(0);">Clear All</a>
<script>
jQuery(document).ready(function(){
/*window.onscroll = function() {
        jQuery('#clearlDButton').css({position: 'fixed', left: '0px', bottom: '0px', width:'100%'});
        jQuery('#lDButton').css({position: 'fixed', left: '0px', bottom: '37px', width:'100%'});
}*/
});

function branchLayerDone(){
    //Calculate the checked checkboxes inside branch div
    locationIdsCount = 0;
    branchList = '';
    $('#state-list input:checked').each(function() {
        locationIdsCount++;
        branchList = (branchList=='')?$(this).attr('value'):branchList+','+$(this).attr('value');
    });
    var htmlShown = 'Selected ('+locationIdsCount+')';
    $('#branchText').html(htmlShown);
    clearAutoSuggestorForBranch();
    $('#branchOverlayClose').click();
}
</script>