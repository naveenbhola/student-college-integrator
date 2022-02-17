<?php
    $currencySymbol = $this->config->item("ENT_SA_CURRENCY_SYMBOL_MAPPING");
    if(!empty($currencySymbol[$courseObj->getJobProfile()->getCurrencyEntity()->getId()])) {
	$fromCurrencyUnit = $currencySymbol[$courseObj->getJobProfile()->getCurrencyEntity()->getId()];
    }else{
	$fromCurrencyUnit = $courseObj->getJobProfile()->getCurrencyEntity()->getCode();
    }
    $weightage = 0;
    $hiddenPlacementSectionClass = '';
    $shownPlacementSectionClass = 'shown-section-class'
?>
<section class="detail-widget navSection" data-enhance="false" id="placementSection">
<div class="detail-widegt-sec" >
<div class="detail-info-sec">
	<div>
	<strong class="flLt">Placement Info.</strong>
	<?php if($fromAverageSalary != '' && $toAverageSalary != ''){?>
	<div class="flRt custom-dropdown">
        <select id="placement-currency-select" class="universal-select" <?=($isIndianCurr)?'disabled':'onchange="changePlacementTabSalary(this)"'?> style="width:120px;">
			<option value="indian-currency">INR</option><?php if(!$isIndianCurr){?>
			<option value="foreign-currency"><?=$courseObj->getJobProfile()->getCurrencyEntity()->getCode()?></option><?php   }	?>
		</select>
	</div>
	<?php }?>
	<div class="clearfix"></div>
	</div>
	<ul class="fee-list salary-info-list clearfix">
		<li>
	    <?php    if($fromAverageSalary != '' && $toAverageSalary != ''){	$weightage += 1; ?>
		<strong>Avg. Annual Salary / Package</strong>
		<p class="placement-indian-currency fee-detail" style="margin-top:5px"><span style="font-size:12px !important;">INR</span> <?=substr($toAverageSalary,0,strpos($toAverageSalary,'.'));?> </p>
		<?php if(!$isIndianCurr){  ?><p class="placement-foreign-currency fee-detail" style="margin-top:5px;display: none"><span style="font-size:12px !important;"><?=$fromCurrencyUnit?></span> <?=$fromAverageSalary?> </p><?php   }  ?>		
		<?php   }
	        if($courseObj->getJobProfile()->getCareerServicesLink()){
	            $careerLink = '';
				if(0===strpos($courseObj->getJobProfile()->getCareerServicesLink(),'http')){
				    $careerLink = $courseObj->getJobProfile()->getCareerServicesLink();
				}else{
				    $careerLink = 'http://'.$courseObj->getJobProfile()->getCareerServicesLink();
			} ?>
	    <a target="_blank" style="display:block; margin-top:4px;" class="tac" href="<?=$careerLink?>" rel="nofollow">Placement services<i class="sprite arrow-icon"></i></a>
		</li><?php }?>		
		<?php    $recruitCompanies = $courseObj->getRecruitingCompanies();
			    if(count($recruitCompanies) > 0 && $recruitCompanies[0]->getName() != ''){?>
	    <li>
		<strong>Recruiting Companies</strong>
			<ol class="recruting-company-list">
			<?php
			$countOfCompanies = count($recruitCompanies);
			$i=-1;
			$hiddenPlacementSectionClass = '';
			$displayNone = '';
			while(TRUE){
			    if($weightage >= 3){
				$hiddenPlacementSectionClass = 'hidden-section-class';
				$displayNone = 'style ="display:block"';
			    }
			    $weightage += 2;
			?>
			    <li> <?php   if(++$i < $countOfCompanies){ ?>
					    <div class="flLt"><img src="<?=$recruitCompanies[$i]->getLogoURL()?>" width="120" height="40" alt="company-img"></div><?php   }else{ ?>    
				</li><?php       break;} if(++$i < $countOfCompanies){ ?>
			    <div class="flRt">
				<img src="<?=$recruitCompanies[$i]->getLogoURL()?>" width="120" height="40" alt="company-img">
			    </div>
			<?php   }else{ ?></li><?php break;}?></li><?php    }    ?>
			</ol>
		</li><?php }?>
		<?php if($courseObj->getJobProfile()->getPopularSectors()) {
			$hiddenPlacementSectionClass = '';
			$popularSectors = $courseObj->getJobProfile()->getPopularSectors();
			if($weightage <= 3 && strlen($popularSectors) >= 160){
			    $weightage += 4;
			    $shortPopularSectors = formatArticleTitle($courseObj->getJobProfile()->getPopularSectors(),150);
			    $hiddenPlacementSectionClass = 'hidden-section-class';
		?>
	    <li><strong>Popular Sectors</strong><div class="internship-txt dynamic-content"><?=$popularSectors?></div></li>
		<?php   }elseif ($weightage > 3) {
			    $hiddenPlacementSectionClass = 'hidden-section-class';
			    $weightage += 4;
		?>    
	    <li><strong>Popular Sectors</strong><div class="internship-txt dynamic-content"><?=$popularSectors?></div></li>
		<?php   }else{?>
		<li><strong>Popular Sectors</strong><div class="internship-txt dynamic-content"><?=$popularSectors?></div></li>
		<?php   }	    
		}if($courseObj->getJobProfile()->getInternships()){
			$hiddenPlacementSectionClass = '';
			$internshipSectionText = $courseObj->getJobProfile()->getInternships();
			if(($weightage <=3 && strlen($internshipSectionText) >= 160)){
			    $weightage += 4;
			    $shortInternshipText = formatArticleTitle($internshipSectionText, 150);
			    $hiddenPlacementSectionClass = 'hidden-section-class';
		?>
	    <li><strong>Internships</strong><div class="internship-txt dynamic-content"><?=$internshipSectionText?></div></li>
		<?php   }elseif($weightage > 3){
		$weightage += 4;
		$hiddenPlacementSectionClass = 'hidden-section-class';?>
	    <li><strong>Internships</strong><div class="internship-txt dynamic-content"><?=$internshipSectionText?></div></li>
		<?php   }else{	?>            
	    <li><strong>Internships</strong><div class="internship-txt dynamic-content"><?=$internshipSectionText?></div></li>
		<?php   }	    }		?>
	 </ul>
	</div>
    </div>
    <?php if(!$isIndianCurr){?>
	<script>
	    function changePlacementTabSalary(elem){
		if($j(elem).val() == 'indian-currency' && currencyChanger <= 1){
                    ++currencyChanger;
		    $j('.placement-foreign-currency').css('display','none');
		    $j('.placement-indian-currency').css('display','block');
            $j('#fees-currency-select').val('indian-currency').change();
		}else if($j(elem).val() == 'foreign-currency' && currencyChanger <= 1){
                    ++currencyChanger;
		    $j('.placement-indian-currency').css('display','none');
		    $j('.placement-foreign-currency').css('display','block');
            $j('#fees-currency-select').val('foreign-currency').change();
		}
                currencyChanger = 0;
	    }
	</script>
    <?php   }?>
</section>