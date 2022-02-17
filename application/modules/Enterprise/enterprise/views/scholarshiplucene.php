<input type="hidden" id="luceneTotalResults" value="<?php echo $data['numOfRecords']; ?>" />
<?php $i=0;$total=count($data['results']);
foreach($data['results'] as $d) :
	$imgS = ($d['isSponsored']==1) ? "<img src='/public/images/sponsored.gif' alt='Sponsored' title='Sponsored' align='absmiddle'>" : "";
	$imgF = ($d['isFeatured']==1) ? "<img src='/public/images/featured.gif' alt='Featured' title='Featured' align='absmiddle'>" : "";
?>
<div id="scTable_<?php echo $i;?>" name="scTable[]" class="normaltxt_11p_blk" style="border-bottom:1px solid #999999;cursor:pointer;line-height:25px;" onClick="selectFieldsSchol(this.id,'<?php echo $d['typeId'];?>',<?php echo $total;?>);" >
    <div class="float_L" style="width:60%;">
        <div class="mar_full_10p">
	      <?php if ($usergroup=="cms") { ?>
	      <?php echo $imgS;?><?php echo $imgF;?>
	      <?php } ?>
            <span class="bld"><a href="#" onClick="scholTextFetch('<?php echo $d['typeId'];?>')"><?php echo $d['title']; ?></a></span>
        </div>
    </div>
    <div class="float_L" style="width:14%;">
        <div class="mar_full_10p"><?php echo $d['applicableTo'];?></div>
    </div>
    <div class="float_L" style="width:24%;">
        <div class="mar_full_10p">&nbsp;</div>
    </div>
    <div class="clear_L"></div>
</div>
<?php $i++;?>
<?php endforeach; ?>