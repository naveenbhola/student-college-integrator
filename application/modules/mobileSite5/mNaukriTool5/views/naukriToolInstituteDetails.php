<?php
$exam = $courseObj->getEligibility(array('general'));
foreach ($exam['general'] as $result){
	$name = $result->getExamName();
	$marks = $result->getValue();
	if(empty($marks))
	   $examAndcutoff[] = $name;
	else
	   $examAndcutoff[] = $name."(".$marks.")"; 
}
$examAndcutoff = implode(", ",$examAndcutoff);
$courseFees 	 = $courseObj->getFees();
$courseFeesValue = is_object($courseFees) ? $courseFees->getFeesValue() :'';
$courseId = $courseObj->getId();  // used in myshortlist view
?>

    	<header>
		<div class="layer-header">
            <a style="width:10px;" class="back-box" id="" href="javascript:void(0);" onclick="NaukriToolComponent.closeInstituteDetailLayer();" data-rel="back"><i class="msprite back-icn"></i></a> 
            <p style="text-align:left; font-size:14px;">Search Results</p>
        </div>
       		
	</header>
  <section class="content-section" style="background:#e6e6dc">
        	<article class="institute-widget-sec">
                <div class="detail-sec">          
                    <div class="institute-detail">
                        <a href="<?php echo $courseObj->getURL();?>" class="institute-link"><?php echo $courseObj->getInstituteName(); ?></a>
			<span><?php echo $courseObj->getMainLocation()->getCityName();?>, <?php echo $total_alumni;?> Alumini</span>
                        <!---<span><?php echo $total_alumni;?> Alumini</span>-->
			<?php if(!empty($examAndcutoff)){ ?>
                        <p>Exams & Cut-Off: <strong><?php echo $examAndcutoff; ?></strong></p>
			<?php } ?>
			<?php if(!empty($courseFeesValue)) { ?>
                        <p>Total Fees (INR): <strong><?php echo getIndianDisplableAmt($courseFeesValue);//echo $courseFees; ?></strong></p>
			<?php } ?>
                        <p>Average Salary of all Alumni: <strong><?=$salary = number_format($instituteNaukriSalaryData['ctc50'], 1, '.', '')." L";?></strong></p>
                    </div>
			<!----shortlist-course---->
			<?php
				$data['courseId'] = $courseId;
				$data['pageType'] = 'MOB_CareerCompass_Shortlist';
				$data['animateStar'] = 'false';
				$data['listing_id'] = $courseObj->getInstituteId();
            	$data['listing_type'] = $courseObj->getInstituteType();
				$data['tracking_keyid'] = $shortlistTrackingPageKeyId;
				$this->load->view('widgets/shortlistStar',$data);
			?>
			<!-----end-shortlist------>
	</div>
          <ul class="rank-criteria clearfix">
          <?php 
          
          $index = 1;
           	foreach($courseObj->course_ranking as $source => $rank) { 
	           	if($index % 2 == 1){
	           		$lClass= 'flLt';
	           	}else{
	           		$lClass= 'flRt';
	           	}
	           	$index++;
	           	?>

	            <li>			
	            	<div class="<?php echo $lClass;?> rank-widget">
	                	<label><?php echo $source; ?> :</label> 
	                    <span class="rank-tag"> <?php echo $rank; ?> </span>
	                </div>
	            </li>
			<?php } ?>
        </ul>
    </article>
</section>
  
  
       <!--- <div class="filter-widget" style="padding-bottom:0px">
       		<p>Annual salary (INR) of alumni in different job functions</p>
        </div> -->
	<?php
	if(!empty($chart) && $chart != 'null') { ?>
	<section class="content-section" style="background:#e6e6dc">
	<article>
	<div class="filter-widget">
		<h2 style="font-weight:normal">Annual salary (INR) of alumni in different job functions</h2>
	</div>
	<div class="institute-widget-sec clearfix">
	<div class="graph-box" style="margin-left:0px; float:left; "> 
		<div id="salary-data-chart" class="alumni-graph" style="float:left;background:rgb(247, 245, 250);"></div>
	</div>
	</div>
	<div class="clearfix"></div>
	</article>
	</section>
	<?php } ?>

        <section class="content-section" style="background:#e6e6dc">
	
        	<article>
	
       <!--- <section class="content-section">
            <article class="institute-widget-sec">
       	    	<img src="public/mobile5/images/graph-image.jpg" width="271" height="158" alt="graph-image">
            </article>
        </section> -->
        <div class="filter-widget">
       		<h2 id="alumni-employment-data" style="font-weight:normal">Alumni Employment Data of this college
</h2>
        </div>

                <ul class="criteria-cards">
                  <li style="overflow: hidden;">
                    <div class="criteria-title" onclick="NaukriToolComponent.hideLayer('4','5');" id="criteria-title4">
                        <strong class="flLt" id="InstjobfuncHeading" style="font-weight:bold">Select Job Function</strong>
                        <p class="flRt" id="InsttotalJobFunctionCount">
			<?php
                        $totalJobFunctions = 0;
			
			//error_log("+++++".print_r($allData,true));
			//_p($allData);die;
			//$allData['jobFuncData'] = json_decode($allData['jobFuncData'],true);
                        foreach($allData['jobFuncData'] as $key=>$arr){
			//error_log("+++++".print_r($arr['totalEmployee'],true));
                        $totalJobFunctions += $arr['totalEmployee'];
                        }
                        echo $totalJobFunctions;
                        ?>
			</p>
                        <div class="clearfix"></div>
                    </div>
                    <div class="criteria-detail" id="section4">
                    	<strong class="select-job-title" style="font-weight:normal">Job functions of Alumni<span id="specializationChart4"></span></strong>
			<div id="piechart4"></div>
			<!--<img src="public/mobile5/images/criteria-image.jpg" width="271" height="169" alt="image">-->
                    </div>
                 </li>
                 <li style="overflow: hidden;">
                    <div class="criteria-title" onclick="NaukriToolComponent.hideLayer('5','4');" id="criteria-title5" >
                        <strong class="flLt" id="InstcompanyHeading" style="font-weight:bold">Select Company</strong>
                        <p class="flRt" id="InsttotalCompanyCount">
			<?php
			$totalCompanies = 0;
			foreach($allData['companiesData'] as $key=>$arr){
                        $totalCompanies += $arr['totalEmployee'];
			}
			echo $totalCompanies;
			?>
			</p>
		    <div class="clearfix"></div>
                    </div>
                    <div class="criteria-detail" id="section5">
                    	<p class="select-job-title">Companies <span id="specializationChart5">where alumni are working </span></p>
			<div id="piechart5"></div>
			<!--<img src="public/mobile5/images/criteria-image.jpg" width="271" height="169" alt="image">-->
                    </div>
                 </li>
                </ul>
            </article>
        </section>
	
		<?php $onclick = "$('#brochureForm".$courseObj->getId()."').submit();"; ?>
        <div class="filter-widget" style="padding-bottom:0; text-align:center; background:#e6e6dc">
       		<h2 style="font-weight:normal">To know more details about this College:</h2>
            <a href="javascript:void(0);" id="request_e_brochure<?=$courseObj->getId();?>" class="brouchure-btn" onclick="responseForm.showResponseForm('<?php echo $courseObj->getInstituteId();?>','MOB_CareerCompass_Ebrochure','institute',{'trackingKeyId': '<?php echo $trackingPageKeyId;?>','callbackObj':'','callbackFunction': 'showEbdownloadMsg','callbackFunctionParams': {'instituteId':'<?php echo $courseObj->getInstituteId(); ?>','url':'<?php echo base64_encode(SHIKSHA_HOME.'/mba/resources/mba-alumni-data');?>'}},{});"><i class="msprite brouchure-icon"></i>Request Brochure</a>
        </div>
        
		<?php 
				$style = '';
				$Shortlist = 'SHORTLIST';
				$shortlistClass = "shortlist-big-btn";
				
				$shortlistAndRegister = "listingShortlist('".$courseObj->getInstituteId()."','".$shortlistbottomTrackingPageKeyId."','".$courseObj->getInstituteType()."', {'pageType':'NM_CareerCompass','listing_type':'".$courseObj->getInstituteType()."','callbackFunctionParams':{'pageType':'NM_CareerCompass'}});";
				
		?>
		<section class="shortlist-btn-area">
        	<a href="javascript:void(0);" class="<?php echo $shortlistClass;?> _srtBtn<?php echo $courseId;?>" onclick="<?php echo $shortlistAndRegister;?>"><span><?php echo $Shortlist;?></span></a>
        </section>