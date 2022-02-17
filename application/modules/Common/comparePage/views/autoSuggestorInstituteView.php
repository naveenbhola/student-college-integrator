<?php 
$j = 4;
$instituteName = ($institute->getShortName() == '') ? $institute->getName() : $institute->getShortName();
if(strlen($instituteName) > 40){
        $instStr  = preg_replace('/\s+?(\S+)?$/', '',substr(htmlspecialchars($instituteName),0,37));
        $instStr .= "...";
}else{
        $instStr = htmlspecialchars($instituteName);
}
if(strlen($instituteName) > 100){
        $instStr1  = preg_replace('/\s+?(\S+)?$/', '',substr(htmlspecialchars($instituteName),0,97));
        $instStr1 .= "...";
}else{
        $instStr1 = htmlspecialchars($instituteName);
}
?>
<div class="cmpre-head"><!-- for normal div -->
    <a class="close-sec" href="javascript:;"><i class="cmpre-sprite ic-cls" onclick="removeCollege('<?=$j?>');"></i></a>
    <div class="cmpre-inst-title">
      <a href="<?=$institute->getURL()?>" target="_blank" title="<?=htmlspecialchars($instituteName)?>"><?=$instStr1?></a>
      <p class="color-of-year"><?php if($institute->getEstablishedYear()){ echo "Established in: ".$institute->getEstablishedYear();}?></p>
    </div>
    <p class="loc-of-clg"><i class="cmpre-sprite ic-gloc"></i><?php echo $institute->getMainLocation()->getCityName();?></p>
</div>
<?php echo "||"; ?>
<div class="cmpre-head"><!-- for sticky div -->
    <a class="close-sec" href="javascript:;"><i class="cmpre-sprite ic-cls" onclick="removeCollege('<?=$j?>');"></i></a>
    <div class="cmpre-inst-title">
      <a href="<?=$institute->getURL()?>" target="_blank" title="<?php echo htmlspecialchars($instituteName);?>"><?=$instStr?></a>
      <p class="loc-of-clg"><i class="cmpre-sprite ic-gloc"></i><?php echo $institute->getMainLocation()->getCityName();?></p>
    </div>
</div>
<?php echo "||"; ?>