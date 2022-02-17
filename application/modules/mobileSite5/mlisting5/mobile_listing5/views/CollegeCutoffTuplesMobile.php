<?php
    $counter = 0;
    foreach ($branchInformation as $row) {
        $counter += 1;
        $courseId = $row->getShikshaCourseId();
        ?>
<div class="rslt-crd">
   <div class="grad-postn">
    <div class="crd-desc shrt">
        <p><a href="<?php echo $courseData[$courseId]['instituteUrl']; ?>" class="instut-lnk"><?php echo strlen($courseData[$courseId]['instituteName']) > 75 ? substr($courseData[$courseId]['instituteName'], 0, 72).'...' : $courseData[$courseId]['instituteName']; ?></a></p>
        <p class="crd-loc"><?php echo !empty($courseData[$courseId]['localityName']) ? $courseData[$courseId]['localityName'].", ":""; ?> <?php echo $courseData[$courseId]['cityName']; ?></p>
        <?php
        if(isset($shortlistedCoursesOfUser[$courseId])){
          $class = 'act';
        }else{
          $class = '';
        } 
    ?>
        <i class="shrtbtn <?php echo $class; ?>" id='<?php echo "shortlist_{$courseId}";?>' trackingKeyId="<?php echo $shortlistTrackingKeyId; ?>" clientCourseId="<?php echo $courseId; ?>"></i>
    </div>
     <div><a href="<?php echo $courseData[$courseId]['courseUrl']; ?>" class="instut-lnk"><?php echo strlen($row->getBranchName()) > 75 ? substr($row->getBranchName(), 0, 72).'...' : $row->getBranchName(); ?></a></div>
    <table >
        <tbody>
            <tr>
                <th width="70%" class="tal">Rounds</th>
                <th width="30%">Cut-off</th>
            </tr>
            <?php 
            $roundNum = 0;
            $roundInfo = $row->getRoundsInfo();
            foreach ($roundInfo as $roundData) {
                $roundNum++;
                $hidClass = $roundNum >4?'hid':'';
                ?>
                <tr class ='<?php echo $hidClass; ?>'>
                    <td class="tal">Round <?php echo $roundData['round']; ?></td>
                    <td><span><?php echo empty($roundData['closingRank']) ? '-' : $roundData['closingRank'].'%'; ?></span></td>
                </tr>
                <?php if($roundNum == 4 && count($roundInfo)>4){ ?>
                    <tr class ='showAll'>
                        <td class="tac" colspan="2"><a href="javascript:void(0)" class="btn-tertiary">View All</a></td>
                     </tr>
                <?php
                }
            }
            ?>
        </tbody>
    </table>
    </div>
    <?php 
    $remarks = $row->getRemarks();
    if(!empty($remarks)){
        ?>
        <p class="cutOff-txt"><?php echo $remarks; ?></p>
        <?php
    }
    ?>
    <div class="rslt-btn">
        <a href="javascript:void(0);" compareCourseId=<?php echo $courseId; ?> trackingKeyId="<?php echo $compareTrackingKeyId; ?>" id='compare_<?php echo $courseId ?>' class="blue-rnkBtn addToCmp">Compare</a>
        <?php 
        $brochureButtonDisable=false;
        if(checkIfBrochureDownloaded($courseId)){
            $brochureButtonDisable=true;
        }
        ?>
        <a href="javascript:void(0);" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>"  clientCourseId=<?php echo $courseId; ?> courseName="$courseData[$courseId]['courseName']" trackingKeyId="<?php echo $brochureTrackingKeyId; ?>" class="orng-rnkBtn <?php echo $brochureButtonDisable? "btn-mb-dis":"dnldBrchr";?>"><?php echo $brochureButtonDisable? "Brochure Mailed":"Apply Now";?></a>
    </div>
</div>
        <?php
        if($counter == 3 ){
            $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "C1_mobile"));

        }
        if($counter == 7 ){
            $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "C2_mobile"));
        }
        if($counter == 10 ){
            $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "C3_mobile"));
        }
        if($counter == 13 ){
            $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "C4_mobile"));
        }
    }
?>