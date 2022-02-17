<?php 
$GA_Tap_On_View_More_Admission = 'VIEW_MORE_ADMISSION';
$GA_Tap_On_Exam = 'VIEW_EXAM';
$GA_Tap_On_View_Info = 'VIEW_ADMISSION_INFO';
?>

<div id="admissionsection-offer" class="listingTuple"> 
    <?php if((!empty($examList) && count($examList) > 0) || (!empty($admissionInfo))) {?>
        <div class="university-recat uni-rel">
    <?php if(!empty($admissionInfo)) { ?>
        <div style="position: relative;">
            <h2 class="head-L2 head-gap">Admissions</h2>
            <div class="lcard university-recat">
                <?php if(!empty($examList) && count($examList) > 0) {?>
                    <h3 class="head-L2 blk">Admission Process</h3>
                <?php } ?>
                <div class="rich-txt-container admission-div" id="admission-div">
                        <?php echo $admissionInfo;?>
                    <!-- <a class="link" id="viewMoreLink">View more</a> -->
                </div>
            </div>

                 <div class="gradient-col" id="viewMoreLink" style="display: block">
                        <a href="<?php echo $admissionPageUrl.'#admission';?>" class="btn-tertiary  mL10" ga-attr="<?=$GA_Tap_On_View_More_Admission;?>">View more</a>
                </div>
            </div>
            
    <?php } ?>
    <?php if(!empty($examList) && count($examList) > 0) { ?>
        <div class="gap university-recat">
            <?php if(empty($admissionInfo)) {?>
                <h2 class="head-L2 head-gap">Exams Offered by University</h2>
            <?php } ?>
            <div class="lcard">
                <?php if(!empty($admissionInfo)) {?>
                        <h3 class="head-L2 blk">Exams Offered by University</h3>
                <?php } ?>
                <ul class="vwd-clg-lst exam">
                <?php foreach($examList as $examKey => $examValue) { 
			$examYear = ($examValue['year']!='')?' '.$examValue['year']:'';
			?>
                    <li ga-attr="<?=$GA_Tap_On_Exam;?>" onclick="window.location.href='<?php echo $examValue['url'];?>'">
                        <p>
                            <strong><a href="<?php echo $examValue['url'];?>" style="color:#111"><?php echo $examValue['name'];?><?=$examYear?></a></strong>
                        <?php if(trim($examValue['description'])) { 
                            $examValue['description'] = trim($examValue['description']);
                            if(strlen($examValue['description']) > 75 )
                            {
                                $examValue['description'] = substr($examValue['description'], 0,72).'...';
                            }
                            echo $examValue['description'];
                        } ?>
                        </p>
                        <i class="forward-icon"></i>
                    </li>
                <?php } ?>
                </ul>
            </div>
        </div>
    <?php } ?>
    </div>
    <?php } ?>
        <?php if(!empty($viewAdmissionLink)) {?> 
            <div class="gap university-recat">
                <div class="lcard end-col">
                    <h2 class="head-L2">Want to know the eligibility, admission process and important dates of all <?php echo htmlentities($instituteName);?> courses?</h2>
                    <a href="<?php echo $admissionPageUrl;?>" class="btn-mob-blue" ga-attr="<?=$GA_Tap_On_View_Info;?>">View Admissions Info</a>
                </div>
            </div>
        <?php } ?>
</div>        
