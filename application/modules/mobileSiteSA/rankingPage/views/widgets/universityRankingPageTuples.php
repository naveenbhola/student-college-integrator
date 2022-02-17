<?php
$rankCounter = $startRank;
foreach($rankingPageData as $ranking){
$universityObj = $ranking['university'];

if(!($universityObj instanceof University) || ($universityObj instanceof University && $universityObj->getName()=="")){
    continue;
}    ?>
<li>
    <div class="rank-div">Ranked <br><strong>#<?php echo $rankCounter; ?></strong></div>
    <div class="rank-detail">
        <a class="univ-link" href="<?php echo $universityObj->getURL(); ?>" target="_blank"><strong><?php echo htmlentities($universityObj->getName()); ?></strong></a>
        <span class="univ-loc">
        <?php  if($universityObj->getEstablishedYear()){ echo "Estd. in ".$universityObj->getEstablishedYear().", "; }
                echo htmlentities($universityObj->getLocation()->getCity()->getName()).", ",htmlentities($universityObj->getLocation()->getCountry()->getName()); ?>
        </span>
        <a id= "downloadBrochure" class="btn btn-primary btn-full mb15 dnd-brchr" href="#responseForm" data-rel="dialog" data-transition="slide" onclick = "loadBrochureForm('<?=base64_encode(json_encode($brochureDataObjList[$universityObj->getId()]))?>',this);" ><span class="vam">Email Brochure</span></a>
    </div>
</li>
<?php $rankCounter++; }  ?>
<script>
var rankCounter ='<?php echo $rankCounter;?>';
</script>
