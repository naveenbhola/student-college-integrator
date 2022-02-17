<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 6/6/18
 * Time: 3:39 PM
 */
if(isset($univData) && count($univData)>0)
{
    foreach ($univData as $univId => $univValues)
    {

        if(!(isset($universityRelatedData) && ($universityRelatedData[$univId] instanceof University)))
        {
            continue;
        }
        $brochureDataArray[$univId]['widget'] = 'search';
        $brochureDataArray[$univId]['trackingPageKeyId'] = 1637;
        $videoCount = $universityRelatedData[$univId]->getVideoCount();
        $photoCount = $universityRelatedData[$univId]->getPhotoCount();
        ?>
        <div class="newTupleDiv ulpDiv clearwidth">
            <div class="clearwidth">
                <div class="picDiv <?php echo ($videoCount>0 || $photoCount > 0)? '' :'noShadow';?>">
                    <a class="tl" loc="img" lid="<?php echo $univValues['univId'];?>" href="<?php echo $univValues['univSeoUrl'];?>"><img class="lazy" width="300" height="200" data-original="<?php echo $univValues['logoLink'];?>" alt="<?php echo htmlentities($univValues['univName']);?>"></a>
                    <?php
                    if($videoCount>0 || $photoCount > 0)
                    {
                        ?>
                        <p class="clickIcons">
                            <?php
                            if($photoCount > 0)
                            {
                                ?>
                                <a class="selfi_imgs"><i class="srpSprite icamera"></i><?php echo $photoCount;?></a>
                                <?php
                            }
                            if($videoCount>0)
                            {
                                ?>
                                <a class="selfi_imgs"><i class="srpSprite ivideo"></i><?php echo $videoCount;?></a>
                                <?php
                            }
                            ?>

                        </p>
                        <?php
                    }
                    ?>
                </div>
                <div class="selectedDtls">
                    <div class="nameDiv">
                        <h2 class="titleOfClg"><a class="tl" loc="utitle" lid="<?php echo $univValues['univId'];?>" href="<?php echo $univValues['univSeoUrl'];?>"><?php echo htmlentities($univValues['univName']);?></a></h2>
                        <p class="subPlace"><?php echo htmlentities($univValues['cityName']);?>, <?php echo htmlentities($univValues['countryName']);?></p>
                    </div>
                    <div class="clearwidth">
                        <div class="courseDiv">
                            <div class="flexBox">
                                <div>
                                    <p class="flexDtls">University Type</p>
                                    <p class="costDiv"><?php echo ucfirst($univValues['univType']);?></p>
                                </div>
                                <?php
                                if(isset($univValues['estYear']) && !is_null($univValues['estYear']))
                                {
                                    ?>
                                    <div>
                                        <p class="flexDtls">Established in</p>
                                        <p class="costDiv"><?php echo $univValues['estYear'];?></p>
                                    </div>
                                    <?php
                                }
                                if(isset($univValues['mealRawValue']) && !empty($univValues['mealRawValue']))
                                {
                                    ?>
                                    <div>
                                        <p class="flexDtls">Room & Meals</p>
                                        <p class="costDiv"><?php echo $univValues['mealFee'];?></p>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="univRanking"><?php echo isset($univValues['rank']) && !is_null($univValues['rank'])?'Ranked '.$univValues['rank'].' in <a href="'.$univValues['rankURL'].'" class="tl" loc="rutitle" lid="'.$univValues['univId'].'">'.htmlentities($univValues['rankName']).'</a>':''; ?> </div>
                        <?php
                        $today = date("Y-m-d");
                        if($univValues['announcementSection']['text'] && $today >= $univValues['announcementSection']['startdate'] && $today <= $univValues['announcementSection']['enddate']) {
                        ?>
                        <div class="Annoncement-Box">
                        <strong>Annoncement</strong>
                        <div class="Announcement-section">
                        <p> <?php echo $univValues['announcementSection']['text'] ?></p>
                        <p><?php echo $univValues['announcementSection']['actiontext']?></p>
                        </div>
                        </div>
                        <?php } ?>
                       
                        <div class="moreSelections clearwidth">
                            <div class="lineNone">
                                <a href="javascript:void('0');" class="dwnLdBtn tl" loc="univbroch" lid="<?php echo $univValues['univId']; ?>" onclick="loadBrochureDownloadForm('<?=base64_encode(json_encode($brochureDataArray[$univId]))?>');">Download Brochure</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php
            if(count($univValues['course'])>0)
            {
                $this->load->view('SASearch/moreCourseSlider',array('courseData'=>$univValues['course']));
            }
            ?>
        </div>
        <?php
        unset($universityRelatedData[$univId]);
    }
}
?>
