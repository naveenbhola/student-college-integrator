<?php
$activeStatus = 0;
if($_COOKIE['compare-active-status'])
{
   $activeStatus =	$_COOKIE['compare-active-status'];
}
?>
<script>var getcHostName = "<?=SHIKSHA_HOME?>/resources/college-comparison";</script>
<div class="compare-bot-sticky <?php if($activeStatus == 1){?> noshadow <?php }?>" id="_cmpSticky" style="display: none;">
    <div class="compare-sticky-items" id="cmpItemList" <?php if($activeStatus){?> style="display: none;" <?php }?>>
        <div class="cmploader"><img></div>
        <div class="clgs-added" id="addItems">
            <div class="added-clgs"><div class="num-to-add">1</div></div>
            <div class="added-clgs"><div class="num-to-add">2</div></div>
            <div class="added-clgs"><div class="num-to-add">3</div></div>
            <div class="added-clgs"><div class="num-to-add">4</div></div>
        </div>
        
        <div class="added-clgs btn-col" id="compare-orng-btn">
            <div class="cmpre-col">
                <a href="javascript:void(0);" class="cmpre-btn">Compare</a>
                <a href="javascript:void(0);" class="link rmAll">Remove All</a>
            </div>
        </div> 
        
        </div>
    <a href="javascript:void(0);" class="show-hide-btn _cmpTgleBtn" id="_cmpBt1" <?php if($activeStatus){?> style="display: none;" <?php }else{?> style="display:block;" <?php }?> data-status="false"><i class="common-sprite hide-arr"></i></a>
    <a href="javascript:void(0);" class="show-cmpre-btn _cmpTgleBtn" id="_cmpBt2" <?php if($activeStatus){?> style="display: block;" <?php }else{?> style="display:none;" <?php }?> data-status="true">Compare<i class="common-sprite show-arr"></i></a>
</div>

<!-- open confirm layer befor go compare page -->
<div id="cmpLyr" class="cmp-show-layer" style="display: none;">
<div class="cmn-head">
 <div class="cmn-header">
 <p>College Comparison</p>
  </div>
  <div class="alert-div">
    <p id="err_lyr" style="position:relative;"></p>
    <div style="text-align:right;">
       <a id="cancelBtn" class="pop cb">Cancel</a>
       <a id="okBtn" class="pop">Ok</a>
    </div>
  </div>
  </div>
</div>
