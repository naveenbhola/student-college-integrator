<div class="n-featColg">
    <a class="featTxt"><i class="icons ic_featdTxt"></i></a>
    <a class="featMoveArw"><i class="icons ic_right-gry"></i></a>
    <ul class="n-featBanrs">
<?php 
shuffle($featuredTextAds['paid']);
$finalArray = $featuredTextAds['paid'];
$finalArrayCount = count($finalArray);
if($finalArrayCount % 5 != 0) {
    $remainingFeaturedTextCount = 5 - ($finalArrayCount % 5);
    if(count($featuredTextAds['default']) >= $remainingFeaturedTextCount) {
        $remainingRandomArray = array_rand($featuredTextAds['default'], $remainingFeaturedTextCount);
        if(!is_array($remainingRandomArray)) {
            $finalArray[] = $featuredTextAds['default'][$remainingRandomArray];
        }
        else {
            foreach ($remainingRandomArray as $value) {
                $finalArray[] = $featuredTextAds['default'][$value];
            }
        }
    }
    else {
        shuffle($featuredTextAds['default']);
        foreach ($featuredTextAds['default'] as $value) {
            $finalArray[] = $value;
        }
    }
}
else if($finalArrayCount == 0){
    shuffle($featuredTextAds['default']);
    for($i = 0; $i < 5; $i++) {
        $finalArray[] = $featuredTextAds['default'][$i];
    }
}

foreach($finalArray as $featuredTextData) { ?>
        <li>
            <a href="<?php echo $featuredTextData['target_url'];?>" target="_blank" rel="nofollow">
                <div>
                <?php 
                        if(strpos($featuredTextData['display_text'], '<big>')) {
                            $cssClass = 'big'; 
                            $h1Class = '';
                        }
                        else if(strpos($featuredTextData['display_text'], '<small>')) {
                            $cssClass = 'small'; 
                            $h1Class = 'n-bnrSmllTxt';
                        }
                        $displayText = explode("<$cssClass>", $featuredTextData['display_text']); ?>
                    <h1 class="<?php echo $h1Class;?>">
                        <?php echo htmlentities($displayText[0]); ?>
                        <b>
                        <?php echo htmlentities($displayText[1]); ?>
                        </b>
                    </h1>
                    <p>
                        <?php echo htmlentities($featuredTextData['usp']); ?>
                    </p>
                </div>
            </a>
        </li>
<?php }
?>
        <p class="clr"></p>
    </ul>
</div>