<?php if(count($chpInterLinking['links'])){?>
<div class="chp-interlinking">
<div class="groupcard">
  <h2>Courses you may be interested in</h2>
    <div class="chplist">
        <?php foreach($chpInterLinking['links'] as $rows){?>
        <div class="chplinks"><a ga-optlabel="click" ga-page="<?php echo $chpInterLinking['gaPage'];?>"  ga-attr="CHP_Interlinking_Desktop" href="<?php echo SHIKSHA_HOME.$rows['url'];?>"><?php echo $rows['displayName'];?></a></div>
        <?php }?>
    </div>
  </div>
</div>
<?php }?>