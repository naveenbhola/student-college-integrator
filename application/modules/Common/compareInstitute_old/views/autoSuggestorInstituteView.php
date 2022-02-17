<?php 
$j = 4;
if(strlen($institute->getName()) > 40){
        $instStr  = preg_replace('/\s+?(\S+)?$/', '',substr(htmlspecialchars($institute->getName()),0,37));
        $instStr .= "...";
}else{
        $instStr = htmlspecialchars($institute->getName());
}
if(strlen($institute->getName()) > 100){
        $instStr1  = preg_replace('/\s+?(\S+)?$/', '',substr(htmlspecialchars($institute->getName()),0,97));
        $instStr1 .= "...";
}else{
        $instStr1 = htmlspecialchars($institute->getName());
}
?>
<div class="cmpre-head"><!-- for normal div -->
    <a class="close-sec" href="javascript:;"><i class="cmpre-sprite ic-cls" onclick="removeCollege('<?=$j?>');"></i></a>
    <div class="cmpre-inst-title">
      <a href="<?=$institute->getURL()?>" target="_blank" title="<?=htmlspecialchars($institute->getName())?>"><?=$instStr1?></a>
      <p class="color-of-year"><?php if($institute->getEstablishedYear()){ echo "Established in: ".$institute->getEstablishedYear();}?></p>
    </div>
    <p class="loc-of-clg"><i class="cmpre-sprite ic-gloc"></i><?=$institute->getMainLocation()->getCity()->getName()?></p>
</div>
<?php echo "||::||"; ?>
<div class="cmpre-head"><!-- for sticky div -->
    <a class="close-sec" href="javascript:;"><i class="cmpre-sprite ic-cls" onclick="removeCollege('<?=$j?>');"></i></a>
    <div class="cmpre-inst-title">
      <a href="<?=$institute->getURL()?>" target="_blank" title="<?=htmlspecialchars($institute->getName())?>"><?=$instStr?></a>
      <p class="loc-of-clg"><i class="cmpre-sprite ic-gloc"></i><?=$institute->getMainLocation()->getCity()->getName()?></p>
    </div>
</div>
<?php echo "||::||"; ?>