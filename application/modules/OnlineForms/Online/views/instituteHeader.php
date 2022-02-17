  
	<?php if(isset($instituteInfo[0]['instituteInfo'][0]['institute_name'])){ ?>
        <div class="appsFormHeaderChild">
	    <div class="appsLogo"><img src="<?php echo $instituteInfo[0]['instituteInfo'][0]['logo_link']; ?>" alt="" /></div>
	    <div class="appsDetails">
        <?php
        $str = $instituteInfo[0]['instituteInfo'][0]['sessionYear'];
        if($courseId=='12873'){
            $str = 'PGDM (C) 2015-2017';
        }
        ?>
		<h3> <?php echo $instituteInfo[0]['instituteInfo'][0]['institute_name']; ?> Application Form - <?=$str?></h3>
		<div class="formNumb">
		    <div class="formNumbRt">
			<div class="formNumbMid">
			    Form No.:<br /><a href="javascript:void(0);" onmouseover="showTipOnline('This is a temporary Form number. Once, you complete the form, we will assign you a permanent form number.',this);" onmouseout="hidetip();">Temp <?php echo $onlineFormId;?></a>
			</div>
		    </div>
		</div>
	    </div>
        </div>
        
        <div id="instructions">
            <h3>INSTRUCTIONS</h3>
            <ul>
		<li>1) Please read the instructions carefully. All fields are mandatory. For fields not applicable to you, just enter NA.</li>
		<?php if($courseId=='27232') {?>
		<li>2) Application fee for this form is Rs <?php echo $instituteInfo[0]['instituteInfo'][0]['actualFees'];  ?>/- for General/OBC/EBC/WBC and Rs.200/- for SC/ST </li>
		<?php } elseif($courseId=='128457') {?>
		<li>2) Application fee for this form is Rs <?php echo $instituteInfo[0]['instituteInfo'][0]['actualFees'];  ?>/- till Feb 15 (Thereafter Rs.1500/-)
		<?php } elseif($courseId=='7544' || $courseId=='268490') {?>
	        <li>2) Application fee for this form is Rs 
                <?php 
                echo $instituteInfo[0]['instituteInfo'][0]['actualFees'];  ?>/- (Non-Refundable) 
        	<?php }else {?>
		          
                <li>2) Application fee for this form is Rs <?php echo $instituteInfo[0]['instituteInfo'][0]['actualFees']; ?>/-</li>

		<?php } ?>

        <?php if ($courseId == 7544 || $courseId==268490) { ?> <li>3) <strong>Eligibility Criteria: </strong>
                <?php  echo ($instituteInfo[0]['instituteInfo'][0]['min_qualification']); ?> </li> <li>4) Documents required are: <?php echo $instituteInfo[0]['instituteInfo'][0]['documentsRequired']; ?>.</li>
                <?php } ?>

               <?php if($courseId!='12873' && $courseId !='7544' && $courseId!='268490' && $courseId != '115590' && $courseId != '123964' && $courseId !='280191' && $courseId !='244277'){ ?> <li>3) Documents required are: <?php echo $instituteInfo[0]['instituteInfo'][0]['documentsRequired']; ?>.</li> <?php }?>
                <?php if(isset($instituteInfo[0]['instituteInfo'][0]['instituteDisplayText'])){ ?><li><?php echo $instituteInfo[0]['instituteInfo'][0]['instituteDisplayText']; ?></li><?php } ?>
            </ul>
            
            <div class="helpBox">
                <span>
                <a href="javascript:void(0);" onclick="showHelpLayer();"  class="helpIcon" title="Help">Help</a>
                <a href="javascript:void(0);" onclick="showFaqLayer();" class="faqsIcon" title="Faqs">Faqs</a>
                </span>
            </div>
        </div>
	<?php } ?>

