<?php
$cookieVal[] = '';
$activeStatus = 0;
	$compareStickyCategoryPage = 'compare-global-categoryPage';
	if($_COOKIE[$compareStickyCategoryPage]){
        $cookieVal = explode('|||',$_COOKIE[$compareStickyCategoryPage]);
    }
	
	if($_COOKIE['compare-active-status'])
	{
	   $activeStatus =	$_COOKIE['compare-active-status'];
	}
?>
<script>var getcHostName = "<?=SHIKSHA_HOME?>/compare-colleges";</script>
<!--compare sticky widget-->
   <div class="compare-bot-sticky" id="main-compare-bot-sticky" style="display: none;">
      <div class="compare-sticky-items" id="sticky-compare-items" <?php if($activeStatus){?> style="display: none;" <?php }?>>
         <div class="clgs-added" id="addedCollegetoComapre">
<?php    
    $floatingWidget = 0;
    for($i = 0; $i <  4; $i++)
    {
        if($cookieVal[$i])
        {
            $floatingWidget = 1;
            $institute = explode('::',$cookieVal[$i]);
            $instituteId = $institute[0];
            $instituteName =  $institute[3];
            $courseId =  $institute[4];
            $courseFinalUrl = $institute[5];
            $urlImage = $institute[2];
            $institutecheck = "compare".$instituteId.'-'.$courseId;
            $closeid = 'close'.($i+1);
            $instTitle = $instituteName;
            if(strlen($instituteName) > 45){
                $instituteName = substr($instituteName,0,40).' ...';
            }
        ?>
              <div class="added-clgs">
                    <a class="ready-to-compare" href="<?php echo $courseFinalUrl;?>" title="<?php echo $instTitle;?>"><?php echo $instituteName;?></a>
                    
                    <a class="close-icon" href="javascript:void(0);" id="<?php echo $closeid; ?>" onclick="toggleCompareCheckbox('<?php echo $institutecheck;?>',false); updateAddCompareList('<?php echo $institutecheck?>','<?php echo base64_encode($cookieVal[$i]);?>'); return false;">&times;</a>
              </div>
            <?php }else{?>          
                <div class="added-clgs">
                    <div class="num-to-add"><?php echo ($i+1);?></div>
                </div>
            <?php }
        }?>  
        
        </div>
        
        <div class="added-clgs btn-col" id="compare-orng-btn">
            <div class="cmpre-col">
                <a href="javascript:void(0);" onclick="checkUserOnCmpPage();" class="cmpre-btn">Compare</a>
                <a href="javascript:void(0);" onclick="clearAllCompareTool();" class="link">Remove All</a>
            </div>
        </div> 
        
      </div>
       <a href="javascript:void(0);" class="show-hide-btn" id="cmp-tool-hide-btn" <?php if($activeStatus){?> style="display: none;" <?php }else{?>style="display:block;" <?php }?> onclick="toggleCompareBtmBox('cmp-tool-hide-btn','cmp-tool-show-btn','false')"><i class="common-sprite hide-arr"></i></a>
       <a href="javascript:void(0);" class="show-cmpre-btn" id="cmp-tool-show-btn" <?php if($activeStatus){?> style="display: block;" <?php }else{?>style="display:none;" <?php }?> onclick="toggleCompareBtmBox('cmp-tool-show-btn','cmp-tool-hide-btn','true')">Compare<i class="common-sprite show-arr"></i></a>
</div>