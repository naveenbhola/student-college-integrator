<?php 
$rankCounter = $startRank;
    foreach($rankingPageData as $key=>$ranking)
    {
    $courseObj = $ranking['course'];
    $universityObj = $ranking['university'];
    if(!($courseObj instanceof AbroadCourse && $universityObj instanceof University))
    {
        if(!(($courseObj->getId() && $universityObj->getId())))
        {
            continue;
        }
    }   ?>
<li>
    <div class="rank-div">Ranked <br><strong>#<?php echo $rankCounter ?></strong></div>
    <div class="rank-detail">
        <p class="rnk-univ"><a href="<?php echo ($universityObj->getURL()); ?>"><?php echo htmlentities($universityObj->getName())?></a></p>
        <span><?php echo  htmlentities($universityObj->getLocation()->getCity()->getName()).", ", htmlentities($universityObj->getLocation()->getCountry()->getName()); ?></span>
        <a class="course-name" target="_blank" href="<?php echo $courseObj->getURL(); ?>"><strong><?php echo htmlentities($courseObj->getName())?></strong></a>
        <div class="crse-brief-desc">
            <ul>
                <li>
                <label>1st year fees:</label>
                <span><?php echo $rankingCoursesFeesData[$courseObj->getId()]; ?></span>
                </li>
                <li>
                <label>Eligibilty:</label>
                <span>
                <?php    $examCount = 0;
                         foreach($courseObj->getEligibilityExams() as $examObj)
                         {
                            if($examObj->getId() == -1){continue;}
                            if($examCount>1)break;
                            if($examObj->getCutoff() == "N/A")
                            {
                                $cutOffText = "Accepted";
                            }
                            else
                            {
                                $cutOffText = $examObj->getCutoff();
                            } 
                            if($examCount>0) echo '<br style="margin-bottom:3px;"/>';
                            echo htmlentities($examObj->getName()).": ".$cutOffText;
                            $examCount++;
                        }?>
                </span>
                <a id= "downloadBrochure" class="btn btn-primary btn-full mb15 dnd-brchr" href="#responseForm" data-rel="dialog" data-transition="slide" onclick = "loadBrochureForm('<?=base64_encode(json_encode($brochureDataObjList[$courseObj->getId()]))?>',this);" ><span class="vam">Email Brochure</span></a>
                </li>
            </ul>
        </div>
    </div>
</li>
<?php $rankCounter++; }?>
<script>
var rankCounter ='<?php echo $rankCounter;?>';
</script>
