<input type="hidden" id="luceneTotalResults" value="<?php echo $data['numOfRecords']; ?>" />
<?php $i=0;$total=count($data['results']);
foreach($data['results'] as $d) :
$imgS = ($d['isSponsored']==1) ? "<img src='/public/images/sponsored.gif' alt='Sponsored' title='Sponsored' align='absmiddle'>" : "";
$imgF = ($d['isFeatured']==1) ? "<img src='/public/images/featured.gif' alt='Featured' title='Featured' align='absmiddle'>" : "";
?>
<div onclick="selectFieldsCourse(this.id,<?php echo $d['typeId'];?>,<?php echo $total;?>)" style="border-bottom: 1px solid rgb(153, 153, 153); cursor: pointer;line-height:25px;" class="normaltxt_11p_blk" name="courseTable[]" id="courseTable_<?php echo $i;?>">
   <div style="width: 40%;" class="float_L">
      <div class="mar_full_10p">
      <?php if ($usergroup=="cms") { ?>
      <?php echo $imgS;?><?php echo $imgF;?>
      <?php } ?>
      <span class="bld"><a href="#"><?php echo $d['title'];?></a></span></div>
   </div>
   <div style="width: 40%;" class="float_L">
      <div class="mar_full_10p"><?php echo $d['college']; ?></div>
   </div>
   <div style="width: 18%;" class="float_L">
      <div class="mar_full_10p"><?php echo $userDetails[$d['userId']];?></div>
   </div>
   <div class="clear_L" ></div>
</div>
<?php $i++;?>
<?php endforeach; ?>
