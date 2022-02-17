<?php $examNameNew = strtoupper($examName); ?>
<div class="tab-form" id="searchFormSection">
    <?php if($examNameNew == 'JEE-MAINS'){ ?>
    <div class="table score-div">
        <div class="tabDet-form">
            <label>Percentile <?= $examNameNew == 'JEE-MAINS' ? "1 (Jan ".date('Y').")" : "" ;?></label>
            <input id="percentile" type="text" placeholder="<?=$examNameNew == 'JEE-MAINS' ? 'Enter JEE Main Percentile for January Exam' : 'Total %ile'?>" class="frm-field" inputType='Percentile' minlength="1" maxlength="7">
            <div class="errorMsg hid" id="error_percentile" ></div>
        </div>
        <?php if($examNameNew == 'JEE-MAINS') { ?>
            <div class="tabDet-form">
                <label>Percentile 2 (Apr <?=date('Y')?>)</label>
                <input id="percentile2" type="text" placeholder="Enter JEE Main Percentile for April Exam" class="frm-field" inputType='Percentile2' minlength="1" maxlength="7">
                <div class="errorMsg hid" id="error_percentile2" ></div>
            </div>
        <?php } else {?>
            <div class="tabDet-form table-cell  padl5">
                <label>Score </label>
                <input id="score" type="text" placeholder="Score in Numerals" class="frm-field" inputType='Score' minlength="1" maxlength="7">
                <div class="errorMsg hid" id="error_Score" ></div>
                <span class="link-colspan">
                    <a id="external-link" href="" target="_blank";></a>
                </span>
            </div>
        <?php } ?>
    </div>
    <?php } ?>
    <div class="tabDet-form">
        <?php if($examNameNew != 'JEE-MAINS'){ ?>
            <label>Enter <?php echo (!empty($invertLogic) && $invertLogic==1)?"Score":"Rank/Merit No.";?></label>
        <?php }else{ ?>
            <label>Rank <span class="optinl">(If Available)</span></label>
        <?php }?>
        <input id="userRank" type="text" placeholder="<?php echo (!empty($invertLogic) && $invertLogic==1)?"Score":"Rank"; ?> in Numerals" class="frm-field" inputType='<?php echo (!empty($invertLogic) && $invertLogic==1)?"Score":"Rank"; ?>' minlength="1" maxlength="7">
        <div class="errorMsg hid" id="error_userRank" ></div>
        <?php if($examNameNew != 'JEE-MAINS' && $showRankOverlay=='YES'){ ?>
            <span>Don’t know your rank? 
            <?php if($examNameNew == 'COMEDK'){ ?>
            <a id="external-link" href="<?php echo COLLEGE_PREDICTOR_BASE_URL.'/comedk-rank-predictor'?>" > Find your Rank </a>
            <?php } ?>
            </span>
         <?php }
         if( $examNameNew == 'JEE-MAINS' ) {
          ?>    
             <span class="link-colspan">
                     <a id="external-link" href="<?php echo COLLEGE_PREDICTOR_BASE_URL.'/jee-main-rank-predictor'?>" target="_blank";> Find your Rank </a>
             </span>
         <?php } ?>

    </div>
    <div class="tabDet-form <?php echo count($categoryData)>1 ? '':'hid'; ?>" id="category_dropdown">
        <label>Select Category</label>
        <p class="custm-Rnksrch"><span id='category_slt'><?=reset($categoryData);?></span><i class="pointrDown"></i></p>
    </div>
    <div class="tabDet-form <?php if($showRankType=='NO'){ echo 'hid';} ?>">
        <?php if($examNameNew != 'JEE-MAINS'){ ?>
        <label>Select State Quota Eligibility</label>
        <?php if($examNameNew =='KCET') { ?>
        <span class="tab-radio">
            <input type="radio" name='rank_type' id="quota_1" value="KCETGeneral" checked= "checked">
            <label for="quota_1">All India</label>
        </span>
        <span class="tab-radio">
            <input type="radio" name='rank_type' id="quota_2" value="HyderabadKarnatakaQuota">
            <label for="quota_2">Home State</label>
        </span>
        <?php } else if($examNameNew =='MHCET'){ ?>
        <span class="tab-radio">
            <input type="radio" name='rank_type' id="quota_1" value='Other' checked= "checked">
            <label for="quota_1">All India</label>
        </span>
        <span class="tab-radio">
            <input type="radio" name='rank_type' id="quota_2" value='HomeUniversity'>
            <label for="quota_2">Home State</label>
        </span>
        <?php } else { ?>
        <span class="tab-radio">
            <input type="radio" name='rank_type' id="quota_1" value='Other' <?php if($rankType == 'Other' || $rankType=='') echo "checked= checked";?>>
            <label for="quota_1">All India</label>
        </span>
        <span class="tab-radio">
            <input type="radio" name='rank_type' id="quota_2" value='Home' <?php if($rankType=='HOME' || $rankType=='Home') echo 'checked=checked';?>>
            <label for="quota_2"><?php echo $examNameNew == 'MAHCET'? 'Maharashtra':'Home State'; ?></label>
        </span>
        <?php } }?>
    </div>
    <?php if($examNameNew =='MHCET'){ ?>
    <div class="tabDet-form hid" id="state_cat_dropdown">
        <label>Select  state category</span></label>
        <p class="custm-Rnksrch"><span id='state_cat_slt'>Home University </span><i class="pointrDown"></i></p>
    </div>
    <?php } else{?>
    <div class="tabDet-form <?php  echo  ($examNameNew != 'JEE-MAINS')?" hid":"";?>" id="city_dropdown">
        <label>Select <?php echo $examNameNew == 'MAHCET'? 'City': ($examNameNew == 'JEE-MAINS')?' home state':'State'; ?> 
        <?php if($examNameNew == 'MAHCET'){ ?>
        <span class="optinl">(Optional)</span>
        <?php } ?>
        </label>
        <p class="custm-Rnksrch"><span id='city_slt'>Select </span><i class="pointrDown"></i></p>
        <?php if($examNameNew == 'MAHCET') { ?>
            <div class="sel-cutff">
                <strong>Select a City to know Maharashtra Cut-offs:</strong>
                <ul>
                    <li>The City where your Bachelor's degree granting university is</li>
                    <li>The City for which either of your parents have a domicile certificate</li>
                    <li>The City your parents are posted/retired to from Government service </li>
                </ul>
            </div>
        <?php } ?>
        <div class="errorMsg hid" id="error_stateSelect" >Please select State</div>
    </div>
    <?php } ?>
    <?php if($examNameNew != 'JEE-MAINS') { ?>
    <div class="tabDet-form">
        <label>Select Preferred Colleges <span class="optinl">(Optional)</span></label>
        <p class="custm-Rnksrch" id='clg_dropdown'><span id='slt_college'>Any College </span><i class="pointrDown"></i></p>
    </div>  
    <?php } ?>
    <div class="errorMsg hid" id="error_predict" ></div>
    <div class="src-tac flex flex--col">
	<a href="javascript:void(0)" trackingkeyid="<?=$trackingPageKeyId?>" class="src-btn" id='searchButton'>Predict Results</a>
	 <?php if(array_key_exists($examNameNew,$nameToIdMappingForAllCollegePredictorPage)){?>
        <label class="spacearnd">-OR-</label>
	<a href="<?php echo SHIKSHA_HOME?>/college-predictor?se[]=<?php echo $nameToIdMappingForAllCollegePredictorPage[$examNameNew] ?>" class="prd-btn">Predict for Other Exams</a>
	<?php } ?>
    </div>
    <div class="launch-predCont">

    <?php 

    $countsAndUrls = array();
    $countsAndUrls["KCET"] = array(212,"/b-tech/colleges/b-tech-colleges-accepting-kcet-india");
    $countsAndUrls["TS EAMCET"]=array(150,"/b-tech/colleges/b-tech-colleges-accepting-ts-eamcet-india");
    $countsAndUrls["AP EAMCET"]=array(198,"/b-tech/colleges/b-tech-colleges-accepting-ap-eamcet-india");
    $countsAndUrls["UPSEE"]=array(195,"/b-tech/colleges/b-tech-colleges-accepting-upsee-uttar-pradesh");
    $countsAndUrls["WBJEE"] = array(93,"/b-tech/colleges/b-tech-colleges-accepting-wbjee-india");
    $countsAndUrls["KEAM"] = array(75,"/b-tech/colleges/b-tech-colleges-accepting-keam-india");
    $countsAndUrls["TNEA"] = array(75,"/b-tech/colleges/b-tech-colleges-accepting-tnea-india");
    $countsAndUrls["GUJCET"] = array(35,"/b-tech/colleges/b-tech-colleges-accepting-gujcet-india");
    $countsAndUrls["CGPET"] = array(8,"/b-tech/colleges/b-tech-colleges-accepting-cg-pet-india");
    $countsAndUrls["GGSIPU"] = array(17,"/b-tech/colleges/b-tech-colleges-accepting-ipu-cet-india");
    $countsAndUrls["BITSAT"] = array(7,"/b-tech/colleges/b-tech-colleges-accepting-bitsat-india");
    ?>

    <?php if($examinationName == 'CLAT'){ ?>
    CLAT College Predictor helps students to get an idea about the chances of admission in the Law College of their choice based on CLAT 2018 result. You can predict a Law College based on your rank using Shiksha's CLAT College Predictor 2018, which uses an advanced algorithm and opening &amp; closing ranks of the colleges using last year's CLAT counselling data.<br>
    So what are you waiting for? Just predict your best possible law college by simply registering at Shiksha and providing your CLAT exam result/rank 2018.</span>    
    <?php } if($examNameNew == 'JEE-MAINS'){ ?>
    JEE Main Rank and College Predictor uses your<a id="external-link" href="<?php echo SHIKSHA_HOME.'/b-tech/jee-main-exam-results'?>" target="_blank";> JEE Main 2019 percentile </a>to predict your rank and chances of admission in the college of your choice. The Predictor combines opening and closing rank from 3500 courses, 800 colleges and 9 different counsellings including <a id="external-link" href="<?php echo SHIKSHA_HOME.'/exams/josaa'?>" target="_blank";> JOSAA </a>, JAC Delhi, <a id="external-link" href="<?php echo SHIKSHA_HOME.'/b-tech/cg-pet-exam'?>" target="_blank";> CGPET </a>,HSTES, <a id="external-link" href="<?php echo SHIKSHA_HOME.'/b-tech/mht-cet-exam'?>" target="_blank";> MHTCET </a>, MPPET, <a id="external-link" href="<?php echo SHIKSHA_HOME.'/mba/ojee-exam'?>" target="_blank";> OJEE </a> PTU, and <a id="external-link" href="<?php echo SHIKSHA_HOME.'/b-tech/wbjee-exam'?>" target="_blank";> WBJEE </a> Sign up to get Cut offs, Fees, Placement Reviews, Admission process and shortlist courses from IITs, NITs, IIITs, and 800 JEE Main colleges across India.
    <?php } else { ?>

<?php
if($countsAndUrls[$examNameNew] != null){?>

<a id="external-link" href="<?php echo SHIKSHA_HOME.$countsAndUrls[$examNameNew][1]?>" target="_blank";>  <?php echo $countsAndUrls[$examNameNew][0]."+"?> Colleges accept <?php echo $examNameNew." ".date('Y'); ?></a> score. 

<?php }?>

Which college will you get admission in? <?php echo $examNameNew; ?> College Predictor <?=date('Y');?> enables students to predict chances of getting admission in the college of their choice. Shiksha's <?php echo $examNameNew; ?> College Predictor <?=date('Y');?> uses an advanced algorithm and opening &amp; closing ranks of last year's <?php echo $examNameNew; ?> Exam counselling data to predict the best possible college for you to pursue Engineering So go ahead, simply register at Shiksha and enter your <?php echo $examNameNew; ?> exam result/rank/score <?=date('Y');?>.


<!--     <?php echo $examNameDisplay; ?> College Predictor helps students to get an idea about the chances of admission in the college of their choice based on <?php echo $examNameDisplay; ?> 2018 result. You can predict your college based on your rank using Shiksha's <?php echo $examNameDisplay; ?> College Predictor <?=date('Y');?>, which uses an advanced algorithm and opening &amp; closing ranks of the colleges using last year's <?php echo $examNameDisplay; ?> counselling data. So what are you waiting for? Just predict your best possible college for pursuing B.Tech, by simply registering at Shiksha and providing your <?php echo $examNameDisplay; ?> main exam result/rank <?=date('Y');?>.</span> -->
    <?php } ?>
    </div>
</div>  
<input type="hidden" name="noStateDropDown" id ="noStateDropDown" value= "<?php echo $noStateDropDown ?>" />
<?php $this->load->view("mcommon5/socialShareThis",array('className'=>'shadow')); ?>
<div class="rslt-dv">
    <?php if($examNameNew == 'JEE-MAINS'){ ?>
        <h2><div class="cutoff-lnch">JEE Main Cut-offs for top ranked engineering colleges are shown below</div></h2>
    <?php } else { ?>
        <div class="cutoff-lnch">Cut-offs for select colleges are below</div>
    <?php } $this->load->view('V2/collegePredictorInner');?>
</div>
<?php  if($examNameNew == 'JEE-MAINS'){ 
    $this->load->view('V2/JeeShowInTable');
} ?>
<div class="prd-toolDes">
    <p>Predictor tools work on the basis of past data. The output/results should be used purely for reference . Actual result of <?=date('Y');?> may vary. </p>
</div>
