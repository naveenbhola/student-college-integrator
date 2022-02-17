<?php $this->load->view('mIIMPredictor5/iimPercentile/iimPercentileWelcome'); ?>

    <div class="tab-form frame-padng iim-form">

        <h2 class="rnk-tabHdng">Enter your expected CAT <?php echo date('Y');?> score below to predict your CAT percentile.</h2>
        <div class="tabDet-form">
            <input type="text" id='cat_score' placeholder="Enter your CAT Score" class="frm-field">
            <div id="error_score" class="errorMsg hid"></div>
        </div>
        
        <div class="src-tac">
            <a href="javascript:void(0)" class="src-btn rank_page" id="searchButton">Predict Percentile</a>
        </div>
        <div class="rnkPre-launch">
        <p>
            CAT <?php echo date('Y');?> Percentile predictor prepared by Shiksha.com helps students to get their predicted CAT <?php echo date('Y');?> percentile on the basis of their expected CAT exam score. This CAT percentile predictor uses the actual percentile & exam score data of more than 35000 students who took CAT in <?php echo date('Y')-1;?>, to predict your percentile for year <?php echo date('Y');?>. By using Shiksha's CAT Percentile Predictor you can get a good idea of your chances at the IIMs based on the analysis of your expected score in CAT <?php echo date('Y');?> exam. This will in turn help you to prepare for the Personal Interview rounds.
           </br>
           </br>
           So what are you waiting for? Just predict your best possible CAT <?php echo date('Y');?> percentile by simply registering at Shiksha and providing your expected CAT <?php echo date('Y');?> exam score.
        </p>
        </div>
    </div>
