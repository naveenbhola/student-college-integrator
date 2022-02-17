<div class="clg-predctr rnk-predictr">
  <section id="clg-bg">
   <div class="base-frame">
       <div class="bc-wrapper"><?php $this->load->view('CP/V2/breadcrumb');?></div>
    <div class="clg-bg " >
    <div class="clg-txBx">
            <?php if($examName == 'jee-main') { ?>
                <h1><?= $displayExamName." ";?>  Rank Predictor <?=date('Y');?></h1>
            <?php } else{ ?>
                <h1><?= $displayExamName;?> Rank Predictor</h1>
            <?php } ?>
           <?php if($examName == 'jee-main') {?>
                <h2 class="rnk-para-hd">Calculate your <?php echo $displayExamName.' '.date('Y');?> Rank using your <?=$displayExamName;?> Percentile or Score.</h2>
           <?php } else {?>
               <p class='rnk-para-hd'>Predict your <?php echo $displayExamName.' '.date('Y');?> <?php echo $examName == 'jee-main' ? "percentile and " : "";?> all India rank based on your actual/expected score
                using our <?php echo $displayExamName; ?> <?php echo $examName == 'jee-main' ? "percentile and " : "";?> rank predictor.</p>
            <?php } ?>
    </div>
    </div>
    </div>
    </section>
    <div class="base-frame">
    <div class="tab-form frame-padng <?=$examName == 'jee-main' ? 'text-left' : ''?>">
        <?php if($examName == "jee-main") {?>
            <p class="field-p"><strong>Enter any of the following – <?= $displayExamName;?> Percentile/Score - and get your predicted rank.</strong></p>
        <?php } else{?>
            <p class="rnk-heading">Enter your <?= $displayExamName;?> score below and get your predicted <?= $displayExamName;?> rank.<br/> This rank predictor tool will return your All India Rank for your <?= $displayExamName;?> results.</p>
        <?php } ?>
        <?php if($examName == 'jee-main') {?>
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
        <?php }else {?>
            <div class="tabDet-form">
                <input type="text" id='jee_score' placeholder="Enter <?= $displayExamName;?> Score here" class="frm-field">
                <div id="error_score" class="errorMsg hid"></div>
            </div>
        <?php } ?>
        
        <div class="src-tac actl-div <?=$examName == 'jee-main' ? 'text-left' : ''?>">
            <div id="error_predict" class="errorMsg hid"></div>
            <a href="javascript:void(0);" id="src-btn" class="src-btn rank_page button button--orange">Predict <?php echo $examName == 'jee-main'? "": " Rank"?> Now</a>
        </div>
        <div class="rnkPre-launch">
        <?php if($examName != 'jee-main'){?>
            <p>Our predictor tool will analyse your score &amp; use a model based on <?= $displayExamName;?> <?php echo (date('Y') - 1);?> and past <?= $displayExamName;?> exam results to provide your expected rank for this year. After your rank prediction you can predict your college using our <?= $displayExamName;?> college predictor tool and prepare yourself for <?= $displayExamName;?> <?php echo date('Y');?> counselling.</p>
            <?php }else{ ?>
            <p>
                Shiksha's <?php echo $displayExamName.' '.date('Y');?> Rank Predictor will analyse your actual/expected percentile or score & use a model based on past <a target="_blank" href="<?=SHIKSHA_HOME?>/b-tech/jee-main-exam-results"><?php echo $displayExamName;?> exam results</a> to provide your expected rank for this year. To prepare for <?php echo $displayExamName.' '.date('Y');?> JOSAA counselling, use Shiksha's <a target="_blank" href="<?=SHIKSHA_HOME?>/b-tech/resources/jee-mains-college-predictor">JEE Main College Predictor</a> and see the college, branch, specialization that you can get admission in.
            </p>
            <?php } ?>

        </div>
        <p class="rnkCutoff-tle">Cut-offs for select colleges are below</p>
        <div class="rslt-contnr rnk-modify rnk-launch">
            <?php 
            $maxRoundNumbers = count($roundData);
            if($maxRoundNumbers > 1) { ?>
                <div class="rslt-head">
                    <p>Institute &amp; Branch (Course) </p>
                    <table>
                        <tbody><tr>
                            <th>Rounds</th>
                            <th>Closing Rank</th>
                        </tr>
                    </tbody></table>
                </div>
                <?php } 
            ?>
            <?php $this->load->view('CP/V2/collegePredictorInner'); ?>
        </div>
        <div class="contentRank-dv">
            <p>
                <?php if($examName != 'jee-main'){?>
                The <?= $displayExamName;?> Rank Predictor tool developed by Shiksha helps the students get an idea about the best possible rank they can achieve in <?= $displayExamName;?> exam <?php echo date('Y');?> before the declaration of the actual result. Students who attempted the <?= $displayExamName;?> exam this year can get in-depth career insights and predict their <?= $displayExamName;?> rank based on their performance in the exam by using Shiksha's <?= $displayExamName;?> rank predictor <?php echo date('Y');?>. You can predict your best possible <?= $displayExamName;?> rank for this year at Shiksha by simply registering and providing your expected <?= $displayExamName;?> exam score. Our <?= $displayExamName;?> predictor tool will analyse your score & use a model based on <?= $displayExamName;?> 2017 and past exam results to provide your expected rank for this year in <?= $displayExamName;?> exam.
                <?php }else{ ?>
                The <?= $displayExamName;?> Rank Predictor tool developed by Shiksha helps the students get an idea about the best possible rank they can achieve in <?= $displayExamName;?> exam <?php echo date('Y');?> before the declaration of the actual result. Students who attempted the <a target="_blank" href="<?=SHIKSHA_HOME?>/b-tech/jee-main-exam"><?= $displayExamName;?> exam</a> this year can get in-depth career insights and predict their <?= $displayExamName;?> rank based on their performance in the exam by using Shiksha's <?= $displayExamName;?> rank predictor <?php echo date('Y');?>. You can predict your best possible <?= $displayExamName;?> rank for this year at Shiksha by simply registering and providing your expected <?= $displayExamName;?> exam score. Our <?= $displayExamName;?> predictor tool will analyse your score & use a model based on <?= $displayExamName;?> 2018 and past exam results to provide your expected rank for this year in <?= $displayExamName;?> exam.
                <?php } ?>
            </p>
        </div>
    </div>
    </div>
</div>