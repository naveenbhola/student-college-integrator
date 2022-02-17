<div id="content-wrapper" style="padding: 0px;">
	<div class="wrapperFxd" id="main-wrapper">
	<div class="spacer10 clearFix"></div>
	
	<div class="breadcrumb2">
    	<span itemscope="" itemtype="https://data-vocabulary.org/Breadcrumb">
        	<a href="<?php echo SHIKSHA_HOME;?>" itemprop="url">
        		<span itemprop="title">Home</span>
        	</a>
    	</span>
        <span class="breadcrumb-arrow">›</span>
        <span itemscope="" itemtype="https://data-vocabulary.org/Breadcrumb">
        	<span itemprop="title">B.Tech</span>
    	</span>
        <span class="breadcrumb-arrow">›</span>
       	<span itemscope="" itemtype="https://data-vocabulary.org/Breadcrumb">
        	<span itemprop="title">Resources</span>
    	</span>
        <span class="breadcrumb-arrow">›</span>
        <span>Rank and College Predictors </span>
    </div>
    
    <div class="rank-pred-wrap">
    <?php if(count($rankPredictor)>0){?>
    <div class="exam-name-disp">
    		<h1>B.Tech Rank and College Predictors</h1>
            <h2>Rank Predictors
            	<span class="get-dtls">Enter your actual/predicted score to find Rank </span>
            </h2>
    </div>
    <div class="get-exam-dtls">
        <ul class="top-curl">
        <?php foreach($rankPredictor as $row){ $rName = strtoupper(substr($row['name'],0,-4));?>
            <li>
                <div class="exam-card-dtls">
                    <a href="<?php echo RANK_PREDICTOR_BASE_URL.'/'.$row['url'];?>" class="exam-titl" title="<?php echo $rName;?> ">
                    <strong><?php echo $rName;?></strong>
                    </a>
	            </div>
           </li>
           <?php }?>
        </ul>
    </div>
    <?php } if(count($cPredictor)>0){?>
    <div class="exam-name-disp">
            <h2>College Predictors
            	<span class="get-dtls">Enter your rank to find list of colleges according to your location, category and preference </span>
            </h2>
    </div>
    <div class="get-exam-dtls" style="border-bottom: 0">
    <div class="clearFix rank-othr-sec" style="margin:0;">
    	<h3>Popular</h3>
        <div class="hline"></div>
        <ul class="top-curl">
            <?php foreach ($cPredictor as $key => $row) {
            		if(in_array($key, $popularCP)){
            			$cpName = strtoupper(substr($row['shortName'],0,-4));?>
            <li>
                <div class="exam-card-dtls">
                    <a href="<?php echo RANK_PREDICTOR_BASE_URL.'/'.$row['collegeUrl']?>" class="exam-titl" title="<?php echo $cpName;?>"><strong><?php echo $cpName;?></strong>
                    </a>
	            </div>
           </li>
          <?php }}?>
        </ul>
		</div>
        <div class="clearFix rank-othr-sec">
        <h3>Other</h3>
        <div class="hline"></div>
        <ul class="top-curl">
            <?php 
                $this->load->config('CP/CollegePredictorConfig',TRUE);
                $collegepredictorlibrary = $this->load->library('CP/CollegePredictorLibrary');
                foreach ($cPredictor as $key => $row) { 
            		if(!in_array($key, $popularCP) && $collegepredictorlibrary->isValidPredictorForStream($key, 'b-tech')){
            		$cpName = strtoupper(substr($row['shortName'],0,-4));?>
            <li>
                <div class="exam-card-dtls">
                    <a href="<?php echo RANK_PREDICTOR_BASE_URL.'/'.$row['collegeUrl']?>" class="exam-titl" title="<?php echo $cpName;?>"><strong><?php echo $cpName;?></strong>
                    </a>
	            </div>
           	</li>
           <?php }}?>
        </ul>
        </div>
    </div>
    <?php }?>
</div>
    </div>
</div>
