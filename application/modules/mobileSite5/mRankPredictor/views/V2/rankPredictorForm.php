<div class="clg-predctr rank-predctr">
    <div class="clg-bg" id="clg-bg2">
        <?php $this->load->view('CP/V2/breadcrumb');?>
        <div class="clg-txBx">
                <?php if($examName == 'jee-main') { ?>
                    <h1 class="main-hdng"><?php echo $displayExamName." ";?> Rank Predictor <?=date('Y');?></h1>
                <?php } else{?>
                    <h1 class="main-hdng"><?php echo $displayExamName;?> Rank Predictor</h1>
                <?php }?>
               
               <h2 class="sb-hdng"><?php 
                if($examName == "jee-main"){
                    echo 'Calculate your '.$displayExamName.' '.date('Y').' Rank using your '.$displayExamName.' Percentile or Score.';
                }
                else{
                    echo "Predict your ".$displayExamName." ".date('Y')."  all India rank based on your actual/expected score using our ".$displayExamName." rank predictor.";
                }
               ?> </h2>
        </div>
    </div>
    <div class="tab-form frame-padng">
        <?php if($examName == "jee-main") { ?>
            <strong>Enter any of the following – <?= $displayExamName;?> Percentile/Score - and get your predicted rank.</strong>
        <?php } else {?>
            <p class="rnk-tabHdng">Enter your <?= $displayExamName;?> score below and get your predicted <?php echo $displayExamName;

            echo " rank. This rank predictor tool will return your All India Rank for  your ".$displayExamName." results.";

         }?>
        </p>
        <div class="tabDet-form">
            <?php if($examName === "jee-main"){ ?>
                <div class="field-set">
                <p>
                    <strong>
                        Percentile 1 (Jan <?=date('Y');?>)
                    </strong>
                     
                </p>
                <input type="text" id='jee_percentile1' placeholder="Enter <?= $displayExamName;?> Percentile for January Exam" class="frm-field">
                <div id="error_percentile1" class="errorMsg hid"></div>
            </div>
            <div class="field-set">
                <p>
                    <strong>
                        Percentile 2 (Apr <?=date('Y');?>)
                    </strong>
                </p>
                <input type="text" id='jee_percentile2' placeholder="Enter <?= $displayExamName;?> Percentile for April Exam" class="frm-field">
                <div id="error_percentile2" class="errorMsg hid"></div>
            </div>
            <div class="field-set">
                <p>
                    <strong>
                        Score 2 (Apr <?=date('Y');?>)
                    </strong>
                </p>
                <input type="text" id='jee_score' placeholder="Enter <?= $displayExamName;?> Score here" class="frm-field">
                <div id="error_score" class="errorMsg hid"></div>
            </div>
            <?php }else { ?>
                <input type="text" id='jee_score' placeholder="Enter your <?= $displayExamName;?> Score" class="frm-field">
                <div id="error_score" class="errorMsg hid"></div>
            <?php } ?>
        </div>
        
        <div class="src-tac">
            <div id="error_predict" class="errorMsg hid"></div>
            <a href="javascript:void(0)" class="src-btn rank_page" id="searchButton">
            <?php
            if($examName != "jee-main"){
               echo "Predict Rank Now";
            }
            else{
                echo "Predict Now";
            }
            ?>
            </a>
        </div>
        <div class="rnkPre-launch">
        <?php if($examName != 'jee-main'){?>
            <p class='rd_more'>
             Our predictor tool will analyse your score &amp; use a model based on <?= $displayExamName;?> <?php echo (date('Y') - 1);?> and past <?= $displayExamName;?> exam results to provide your...<a href="javascript:void(0)" class='rd_more_btn'>Read More</a></p>
            <p class='hid'>
                Our predictor tool will analyse your score &amp; use a model based on <?= $displayExamName;?> <?php echo (date('Y') - 1);?> and past <?= $displayExamName;?> exam results to provide your expected rank for this year. After your rank prediction you can predict your college using our <?= $displayExamName;?> college predictor tool and prepare yourself for <?= $displayExamName;?> <?php echo date('Y');?> counselling.
            </p>
        <?php }else{?>
            <p>

                Shiksha's JEE Main 2019 Rank  Predictor will analyse your actual/expected score &amp; use a model based on past <a href="<?=SHIKSHA_HOME?>/b-tech/jee-main-exam-results">JEE Main exam results</a> to provide your expected rank for this year. To prepare for JEE Main 2019 JOSAA counselling, use Shiksha's <a href="<?=SHIKSHA_HOME?>/b-tech/resources/jee-mains-college-predictor">JEE Main College Predictor</a> and see the college, branch, specialization that you can get admission in.
            </p>
        <?php }?>    
        </div>
    </div>
</div>
<div class="rslt-dv">

    <div class="cutoff-lnch">Cut-offs for select colleges are below</div>

    <?php $this->load->view('mcollegepredictor5/V2/collegePredictorInner'); ?>
    
    <div class="contentRank-dv">
        <p class='rd_more'>
         The <?= $displayExamName;?> Rank Predictor tool developed by Shiksha helps the students get an idea...<a href="javascript:void(0)" class='rd_more_btn'>Read More</a></p>
        <?php if($examName != 'jee-main'){?>
        <p class='hid'>
            The <?= $displayExamName;?> Rank Predictor tool developed by Shiksha helps the students get an idea about the best possible rank they can achieve in <?= $displayExamName;?> exam <?php echo date('Y');?> before the declaration of the actual result. Students who attempted the <?= $displayExamName;?> exam this year can get in-depth career insights and predict their <?= $displayExamName;?> rank based on their performance in the exam by using Shiksha's <?= $displayExamName;?> rank predictor <?php echo date('Y');?>. You can predict your best possible <?= $displayExamName;?> rank for this year at Shiksha by simply registering and providing your expected <?= $displayExamName;?> exam score. Our <?= $displayExamName;?> predictor tool will analyse your score & use a model based on <?= $displayExamName;?> 2017 and past exam results to provide your expected rank for this year in <?= $displayExamName;?> exam.    
        </p>
        <?php }else{?>
        <p class='hid'>
            The <?= $displayExamName;?> Rank Predictor tool developed by Shiksha helps the students get an idea about the best possible rank they can achieve in <?= $displayExamName;?> exam <?php echo date('Y');?> before the declaration of the actual result. Students who attempted the <a href="<?=SHIKSHA_HOME?>/b-tech/jee-main-exam"><?= $displayExamName;?> exam</a> this year can get in-depth career insights and predict their <?= $displayExamName;?> rank based on their performance in the exam by using Shiksha's <?= $displayExamName;?> rank predictor <?php echo date('Y');?>. You can predict your best possible <?= $displayExamName;?> rank for this year at Shiksha by simply registering and providing your expected <?= $displayExamName;?> exam score. Our <?= $displayExamName;?> predictor tool will analyse your score & use a model based on <?= $displayExamName;?> 2018 and past exam results to provide your expected rank for this year in <?= $displayExamName;?> exam.    
        </p>
        <?php } ?>
    </div>
</div>