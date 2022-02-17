<?php
function subval_sort($a,$subkey) {
	foreach($a as $k=>$v) {
		$b[$k] = strtolower($v[$subkey]);
	}
	asort($b);
	foreach($b as $key=>$val) {
		$c[] = $a[$key];
	}
	return $c;
}
$docListNew= subval_sort($docList,'name');
?>
<div style="overflow:hidden; width: 160" >
    <?php for($i=0; $i < $docLen; $i++){ if ($i%4==0){?>
    <div class="lineSpace_8">&nbsp;</div><?php }?>
    <div style="width:25%;" class="float_L">
        <a href="<?php echo $docListNew[$i][url];?>" style="background:url(/public/images/dwnlcon.png) left top no-repeat;padding-left:25px;display:block;font-size:11px;height:45px"><?php echo $docListNew[$i]['name'];?></a>
    </div>
    <?php }?>
</div>

