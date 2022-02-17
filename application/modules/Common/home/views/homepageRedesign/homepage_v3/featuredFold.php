<?php 
$freeCount  = count($featuredColleges['free']);
$paidCount  = count($featuredColleges['paid']);
$totalCount = $paidCount + $freeCount;
if($paidCount != 0 || $freeCount != 0)
{
?>
<section class="featureBanner">
    <div class="_cntr">
        <div class="heading3">
            <h2>FEATURED COLLEGES</h2>
        </div>
        <div class="featuredContainer">
            <div class="sliderContainer homepageFeaturedCollegeSlider">
                <?php 
                if($totalCount > 8)
                {
                ?>
                    <a class="leftArrow lftArwDsbl"><i></i></a>
                <?php 
                }
                ?>
                <div class="slidingArea ">
                    <ul class="featuredSlider" id="ftrSldUl">
                        <li class="clgLst">
                        <?php 
                        $iteration = 0;
                        $a=1;
                        $b=1;
                        $arr = array('paid'=>array(), 'free'=>array());
                        foreach ($featuredColleges['paid'] as $value) {
                            if($iteration == 8){
                                break;
                            }
                        ?>
                            <a id="paid_<?php echo $a; ?>" class="banner randomize_paid" target="_blank" href="<?php echo $value['target_url']; ?>">
                            <img class="lazy" data-original="<?php echo MEDIA_SERVER.$value['image_url']; ?>" alt="<?php echo $value['collegeName']; ?>" title="<?php echo $value['collegeName']; ?>" /></a>
                        <?php 
                            $iteration++;
                            $arr['paid'][] = $a;
                            $a++;
                        }
                        if($iteration < 8) {
                            for ($i=0; $i < (8-$iteration); $i++) { 
                            ?>
                            <a id="free_<?php echo $b; ?>" class="banner randomize_free" target="_blank" href="<?php echo $featuredColleges['free'][$i]['target_url']; ?>">
                            <img class="lazy" data-original="<?php echo MEDIA_SERVER.$featuredColleges['free'][$i]['image_url']; ?>" alt="<?php echo $featuredColleges['free'][$i]['collegeName']; ?>" title="<?php echo $featuredColleges['free'][$i]['collegeName']; ?>" /></a>
                            <?php 
                            $arr['free'][] = $b;
                            $b++;
                            }
                        }
                        ?>
                        </li>
                        <?php 
                        if($paidCount > 8)
                        {
                        ?>
                            <li class="clgLst">
                            <?php 
                            for ($i=$iteration; $i<$paidCount ; $i++) {
                            ?>
                                <a id="paid_<?php echo $a; ?>" class="banner randomize_paid" target="_blank" href="<?php echo $featuredColleges['paid'][$i]['target_url']; ?>">
                                <img class="lazy" data-original="<?php echo MEDIA_SERVER.$featuredColleges['paid'][$i]['image_url']; ?>" alt="<?php echo $featuredColleges['paid'][$i]['collegeName']; ?>" title="<?php echo $featuredColleges['paid'][$i]['collegeName']; ?>" /></a>
                            <?php 
                                $arr['paid'][] = $a;
                                $a++;
                            }
                            for ($i=(8-$iteration); $i < $freeCount; $i++) {
                            ?>
                                <a id="free_<?php echo $b; ?>" class="banner randomize_free" target="_blank" href="<?php echo $featuredColleges['free'][$i]['target_url']; ?>">
                                <img class="lazy" data-original="<?php echo MEDIA_SERVER.$featuredColleges['free'][$i]['image_url']; ?>" alt="<?php echo $featuredColleges['free'][$i]['collegeName']; ?>" title="<?php echo $featuredColleges['free'][$i]['collegeName']; ?>" /></a>
                            <?php 
                                $arr['free'][] = $b;
                                $b++;
                            }
                            ?>
                            </li>
                        <?php 
                        }
                        ?>
                    </ul>
                </div>
                <?php 
                if($totalCount > 8)
                {
                ?>
                    <a class="rightArrow"><i></i></a>
                <?php 
                }
                ?>
            </div>
        </div>
    </div>
</section>
<?php 
echo "<script> var randomize = '".json_encode($arr)."'; </script>";
}
?>