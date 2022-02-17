<?php if(count($chpInterLinking['links'])){
$pageType = ($chpInterLinking['pageType']) ? $chpInterLinking['pageType'] : 'MS'; // like MS, AMP
?>
<div class="chp-interlinking">
<div class="groupcard">
  <h2>Courses you may be interested in</h2>
  <input type="radio" name="chplinks" id="show-chplist">
    <div class="chplist">
        <?php foreach($chpInterLinking['links'] as $key =>$rows){?>
              <div class="chplinks <?php if($key >9){ echo ' hidechp';}?>">

                  <?php if($chpInterLinking['pageType'] == 'AMP'){?>
                      <a class="ga-analytic" data-vars-event-name="CHP_Interlinking_Click" href="<?php echo SHIKSHA_HOME.$rows['url'];?>"><?php echo $rows['displayName'];?></a>
                  <?php }else{?>        
                      <a ga-optlabel="click" ga-page="<?php echo $chpInterLinking['gaPage'];?>" ga-attr="CHP_Interlinking_<?php echo $pageType;?>" href="<?php echo SHIKSHA_HOME.$rows['url'];?>"><?php echo $rows['displayName'];?></a>
                  <?php }?>
                  
              </div>
        <?php }?>
    </div>
    <!-- hide after 10 -->
    <?php if(count($chpInterLinking['links']) > 10){?>
    	<label class="showchplinks" for="show-chplist">View All</label>
	<?php }?>
  </div>
</div>
<?php }?>