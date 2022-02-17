<?php
    //Make the main tuple for the first course
    $flagshipCourse = reset($university->getSnapshotCourses());
    if($flagshipCourse){
    //print_r($flagshipCourse);
?>

<li class="clearwidth" id="<?=$flagshipCourse->getId()?>">
    <div class="tuple-box">
        <div class="tuple-image flLt">
            <?php
                $univPhotos = $university->getPhotos();
                if(count($univPhotos)){
                    $imgURL = reset($univPhotos);
                    $imgURL = $imgURL->getThumbURL('172x115');
                }
                else{
                    $imgURL = SHIKSHA_HOME."/public/images/defaultCatPage1.jpg";
                }
            ?>
            <a href="<?=$flagshipCourse->getSeoUrl()?>" target="_blank">
                <img src="<?=$imgURL?>" title="<?=$university->getName()?>" alt="<?=$university->getName().", ".$university->getLocation()->getCountry()->getName();?>" align="center">
            </a>
        </div>
        <div class="tuple-detail">
            <div class="tuple-title" style="margin-bottom:0;">
                <a href="<?=($university->getURL().'?refCourseId='.$flagshipCourse->getId())?>" target="_blank"><?=htmlentities($university->getName())?></a><span class="font-11">, <?=$university->getLocation()->getCity()->getName()?><?=($university->getLocation()->getCountry()->getName()=="")?"":", ".$university->getLocation()->getCountry()->getName()?>
                </span>
            </div>
            <div class="course-tuple clearwidth">
                <p class="tuple-sub-title mt5">
                    <a href="<?=$flagshipCourse->getSeoUrl()?>" target="_blank"><?=htmlentities($flagshipCourse->getName())?></a>
                </p>
                <div class="clearwidth">
                    <div class="uni-course-details flLt">
                        <div class="detail-col flLt">
                            <?php
                            if($university->getTypeOfInstitute()=='public'){
                                $mark = 'tick';
                                $symbol = "&#10004";
                                $ptag = '<p style="font-size:12px !important;">';
                                $endptag = '</p>';
                            }
                            else{
                                $mark = 'cross';
                                $symbol = "&times;";
                                $ptag = '<p class="non-available" style="font-size:12px !important;">';
                                $endptag = "</p>";
                            }
                            ?>
                            <?=$ptag?>
                                <span class="<?=$mark?>-mark"><?=$symbol?></span>
                                Public University
                            <?=$endptag?>
                        </div>
                        <div class="detail-col flLt" style="width:120px">
                            <?php
                            if($university->hasCampusAccommodation()){
                                $mark = 'tick';
                                $symbol = "&#10004";
                                $ptag = '<p style="font-size:12px !important;">';
                                $endptag = '</p>';
                            }
                            else{
                                $mark = 'cross';
                                $symbol = "&times;";
                                $ptag = '<p class="non-available" style="font-size:12px !important;">';
                                $endptag = "</p>";
                            }
                            ?>
                            <?=$ptag?>
                                <span class="<?=$mark?>-mark"><?=$symbol?></span>
                                Accommodation
                            <?=$endptag?>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <?php
                    $snapshotCoursesOfUniversity = $university->getSnapshotCourses();
                    $countToShow = count($snapshotCoursesOfUniversity);
                    if($countToShow > 1){
                        $msg = '';
                        if($countToShow == 2){
                            $msg = "1 similar course";
                        }
                        else{
                            $msg = ($countToShow-1)." similar courses";
                        }
                ?>
                        <a class="smlr-course-btn" style="margin-top:5px;" href="javascript:void(0);" onclick="showHideSnapshotSimilarCourses(this)">
                            <i class="cate-sprite plus-icon"></i>
                            <?=$msg?>
                        </a>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>
    <?php
    //Now for the similar courses tuples
    $similarCourses = $university->getSnapshotCourses();
    unset($similarCourses[0]);  //This is the main tuple, really
    ?>
    <div class="similarSnapshotCourseTuple">
        <?php
        foreach($similarCourses as $similarCourse){
            $this->load->view('categoryList/abroad/widget/similarSnapshotTuple',array('university'=>$university,'course'=>$similarCourse));
        }
        ?>
    </div>
</li>

<?php
    }
?>