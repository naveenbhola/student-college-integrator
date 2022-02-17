<?php 
$GA_Tap_On_View_More_Admission = 'VIEW_MORE_ADMISSION';
$GA_Tap_On_Exam = 'VIEW_EXAM';
$GA_Tap_On_View_Info = 'VIEW_ADMISSION_INFO';
 ?>
    <?php if((!empty($examList) && count($examList) > 0) || (!empty($admissionInfo))) {?>
    <?php if(!empty($admissionInfo)) { ?>
    <section>
        <div class="data-card m-5btm pos-rl">
        <h2 class="color-3 f16 heading-gap font-w6">Admissions & Cut-offs</h2>
        <div class="card-cmn color-w">
        <?php if(!empty($examList) && count($examList) > 0) {?>
            <h3 class="f14 color-3 font-w6">Admission Process</h3>
        <?php } ?>
        <div class="rich-txt-container admission-div" id="admission-div">
        <amp-iframe width="600" height="0"  class="frame-graph new-frame" layout="responsive" sandbox="allow-scripts allow-popups allow-top-navigation" scrolling="no" frameborder="0" src = '<?php echo SHIKSHA_HOME; ?>/mobile_listing5/InstituteMobile/getAdmissionInfo/<?php echo $listing_id; ?>'>
        </amp-iframe>
        </div>
        </div>
        <div class="gradient-col hide-class" id="viewMoreLink">
            <a href="<?php echo $admissionPageUrl;?>" class="color-b btn-tertiary f14 ga-analytic" data-vars-event-name="<?=$GA_Tap_On_View_More_Admission;?>">View more</a>
        </div>
       </div>
    </section>
<?php } ?>

    <?php if(!empty($examList) && count($examList) > 0) { ?>
        <section>
          <div class="data-card m-5btm">
                <?php if(empty($admissionInfo)) {?>
                    <h2 class="color-3 font-w6 f14 heading-gap">Exams Offered by University</h2>
                <?php } ?> 
                <div class="card-cmn color-w">
                <?php if(!empty($admissionInfo)) {?>
                    <h3 class="color-6 font-w6 f14 padb">Exams Offered by University</h3>
                <?php } ?>
                <ul class="cls-ul">
                <?php foreach($examList as $examKey => $examValue) {
			$examYear = ($examValue['year']!='')?' '.$examValue['year']:'';
		 ?>
                <li class="ga-analytic" data-vars-event-name="<?=$GA_Tap_On_Exam;?>">
                    <a href="<?php echo $examValue['url'];?>">
                    <p class="f14 color-6">
                      <strong class="block m-3btm"><?php echo $examValue['name'];?><?=$examYear?></strong>
                       <?php if(trim($examValue['description'])) { 
                        $examValue['description'] = trim($examValue['description']);
                        if(strlen($examValue['description']) > 75 )
                        {
                            $examValue['description'] = substr($examValue['description'], 0,72).'...';
                        }
                        echo $examValue['description'];
                    } ?>               
                    </p>
                    <i class="lft-frwd"></i>
                    </a>
                </li>
                <?php } ?>
                </ul>
            </div>
            <?php } ?>
            </div>
       </section>
    <?php } ?>


   <?php if(!empty($viewAdmissionLink)) {?> 
    <section>
       <div class="data-card m-5btm">
             <div class="card-cmn color-w">
                 <h2 class="f14 color-3 font-w6 m-btm">Want to know the eligibility, admission process and important dates of all <?php echo htmlentities($instituteName);?> courses?</h2>
                 <a href="<?php echo $admissionPageUrl;?>" class="btn btn-secondary color-w color-b f14 font-w6 ga-analytic" data-vars-event-name="<?=$GA_Tap_On_View_Info;?>">View Admissions & Cut-Offs Info</a>
             </div>
         </div>
     </section>
         <?php } ?>
